<?php
if (!defined('MODX_BASE_PATH')) {
    die('HACK???');
}

include_once(MODX_BASE_PATH."assets/lib/MODxAPI/modResource.php");

class configParser {
	private $folderId = 20770;
	private $domain = 'https://catalog.azgaz.ru';
	private $models = [
		'gazel-biznes',
		'gazel-city',
		'gazel-next',
		'gazel-nn',
		'gazon-next',
		'sadko-next',
		'sobol-4h2',
		'sobol-4h4',
		'valdaj-next'
	];
	private $storeFile = __DIR__ . '/configParserStore';
	
	public function __construct() {
		$this->evo = EvolutionCMS();
		$this->store = $this->getStore();
		$this->buildId = $this->getBuildId();
	}

	public function log($log='') {
		$this->evo->logEvent(1, 1, $log, 'configParser: '.$log);
		return true;
	}

	public function error($error='') {
		$this->evo->logEvent(1, 3, $error, 'configParser: '.$error);
		mail('d@iliukhin.com', 'autoretail error', 'configParser: '.$error);
		return true;
	}

	public function getStore() {
		if(!file_exists($this->storeFile)) {
			return ['buildId'=>false, 'nextAction'=>false, 'data'=>['slugs'=>[], 'modifications'=>[]]];
		}

		$storeContent = file_get_contents($this->storeFile);
		return json_decode($storeContent, true);
	}

	public function saveStore() {
		$store_json = json_encode($this->store, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT);
		file_put_contents($this->storeFile, $store_json);
		die('<script>setTimeout(function(){window.location.reload();}, 500);</script>waiting for next action '.$this->store['nextAction']);
	}

	public function destroyStore(){
		unlink(__DIR__ . '/configParserStore');
	}

	public function getUrl($url='') {
		$ch = curl_init($this->domain.$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$content = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($http_code !== 200) return false;
		return $content;
	}

	public function getBuildId() {
		/* Проверка актуальности buildId */
		if($this->store['buildId'] && $this->getUrl('/_next/static/'.$this->store['buildId'].'/_ssgManifest.js')) {
			return $this->store['buildId'];
		}

		/* Если buildId неактуален */
		$html = $this->getUrl();
		if(preg_match('/"buildId":"(.*)",/', $html, $matches)) {
			$this->store = ['buildId'=>false, 'nextAction'=>false, 'data'=>['slugs'=>[], 'modifications'=>[]]];
			$this->store['buildId'] = $matches[1];
			$this->saveStore();
		} else {
			$this->error('buildId not found in page source');
			die();
		}
	}

	public function next() {
		if($nextAction = $this->store['nextAction']) {
			return $this->$nextAction();
		}
		if(filectime($this->storeFile) > time()-14800 && !isset($_GET['force'])) {
			die('<script>setTimeout(function(){window.location.reload();}, 5000);</script>Waiting ' . date("d.m.Y H:i:s", filectime($this->storeFile)+43200));
		}
		return $this->getSlugs();
	}

	public function getSlugs() {

		foreach($this->models as $model) {
			$modelJson = $this->getUrl('/_next/data/'.$this->buildId.'/'.$model.'.json');
			$modelArray = json_decode($modelJson, true);
			$modelsData = $modelArray['pageProps']['pageData']['modelsData'];

			foreach($modelsData as $body) {
				$this->store['data']['slugs'][] = '/_next/data/'.$this->buildId.'/'.$model.'/'.$body['slug'].'.json';
			}
		}

		$this->store['nextAction'] = 'getModifications';
		$this->saveStore();
	}

	public function getModifications() {
		if(!count($this->store['data']['slugs'])) {
			$this->store['nextAction'] = false;
			$this->saveStore();
		}

		/*if(filectime($this->storeFile) > time()-60) {
			die('<script>setTimeout(function(){window.location.reload();}, 5000);</script>Waiting ' . date("d.m.Y H:i:s", filectime($this->storeFile)+60));
		}*/


		$url = array_shift($this->store['data']['slugs']);
		$modification_json = $this->getUrl($url);
		$modification_array = json_decode($modification_json, true);
		$modifications = $modification_array['pageProps']['initialReduxState']['modifications']['modifications'];

		if(!isset($modifications['ids'])) {
			die(''.print_r($modifications, true));
		}
		foreach($modifications['ids'] as $mod_id) {
			$mod_entity = $modifications['entities'][$mod_id];

			$model = $mod_entity['model'];
			$modification = $this->translate($mod_entity['modification']);

			$name = $mod_entity['name'];
			$category = $mod_entity['category'];

			/* Создаём папку модели */
			$q = $this->evo->db->select('id', $this->evo->getFullTableName('site_content'), 'parent='.$this->folderId.' AND pagetitle = "'.$model.'"', '', 1);
			if($this->evo->db->getRecordCount($q)) {
				$modelFolderId = $this->evo->db->getValue($q);
			} else {
				$doc = new modResource($this->evo);
				$doc->create(['pagetitle'=>$model, 'hidemenu'=>1, 'template'=>0, 'parent'=>$this->folderId]);
				$modelFolderId = $doc->save(false, false);
			}

			/* Создаём папку кузова */
			$q = $this->evo->db->select('id', $this->evo->getFullTableName('site_content'), 'parent='.$modelFolderId.' AND pagetitle = "'.$category.'"', '', 1);
			if($this->evo->db->getRecordCount($q)) {
				$bodyFolderId = $this->evo->db->getValue($q);
			} else {
				$doc = new modResource($this->evo);
				$doc->create(['pagetitle'=>$category, 'hidemenu'=>1, 'template'=>0, 'parent'=>$modelFolderId]);
				$bodyFolderId = $doc->save(false, false);
			}

			/* Создаём модификацию (поиск по description) */
			$q = $this->evo->db->select('id', $this->evo->getFullTableName('site_content'), 'parent='.$bodyFolderId.' AND description = "'.$modification.'"', '', 1);
			if($this->evo->db->getRecordCount($q)) {
				$modId = $this->evo->db->getValue($q);
			} else {
				$doc = new modResource($this->evo);
				$doc->create(['pagetitle'=>$modification, 'hidemenu'=>1, 'description'=>$modification, 'template'=>0, 'parent'=>$bodyFolderId]);
				$modId = $doc->save(false, false);
			}

			$aliasListing = $this->evo->getAliasListing($modId);
			$path = $aliasListing['path'].'/'.$aliasListing['alias'];


			$mod_images = [];
			if(isset($mod_entity['_original']['images']) && count($mod_entity['_original']['images'])) {
				$mod_images = $mod_entity['_original']['images'];
			} else if(isset($mod_entity['images']) && count($mod_entity['images'])) {
				foreach($mod_entity['images'] as $mod_img) {
					if(isset($mod_img['target'][0]) && $mod_img['target'][0] == $category) {
						$mod_images[] = $mod_img;
					}
				}
			}


			$this->store['data']['modifications'][] = [
				'doc_id'=>$modId,
				'path'=>$path,
				'data'=>[
					'option_packets' => $mod_entity['option_packets'], // Пакеты опций
					'engine' => $mod_entity['engine'],
					'images' => $mod_images,
					'specification' => $mod_entity['specification'],
					'colors' => $mod_entity['colors'],
					'options' => $mod_entity['options'],
					'option_prices' => $mod_entity['option_prices'],
					'available_options' => $mod_entity['available_options']	
				]
			];
		}

		$this->store['nextAction'] = 'parseModification';
		$this->saveStore();
	}


	public function parseModification() {
		if(!count($this->store['data']['modifications'])) {
			$this->store['nextAction'] = 'getModifications';
			$this->saveStore();
		}

		$mod_array = array_shift($this->store['data']['modifications']);
		$doc_id = $mod_array['doc_id'];
		$images_path = $this->getImagesPath($mod_array['path']);
		$doc_data = $mod_array['data'];


		$transform_array = [];

		/* Фотографии */
		$transform_array['images'] = $this->saveImages($images_path, $doc_data['images']);

		/* Цвета */
		$transform_array['colors'] = [];
		foreach($doc_data['colors'] as $color) {
			if(isset($color['name']) && isset($color['hex']) && isset($color['type'])) {
				$transform_array['colors'][$color['type']][$color['hex']] = [
					'name' => $color['name'],
					'type' => $color['type'],
					'image' => isset($color['image']) ? $this->saveColorImage($images_path, $color['id'], $color['image']) : '',
					'hex' => $color['hex'],
				];
			}
		}

		/* Спецификация */
		$transform_array['specification'] = [];
		foreach($doc_data['specification'][0]['items'] as $spec) {
			$transform_array['specification'][] = [
				'id' => $spec['id'],
				'title' => isset($spec['title']) ? $spec['title'] : '',
				'value' => isset($spec['value']) ? $spec['value'] : '',
				'unit' => isset($spec['measure']) ? $spec['measure'] : ''
			];
		}

		/* Двигатель */
		$transform_array['engine'] = isset($doc_data['engine']['model_code']) ? $doc_data['engine']['model_code'] : '';


		/* Опции (с ценами) */
		$transform_array['options'] = [];
		foreach($doc_data['options'] as $option_group) {
			if(isset($option_group['items']) && count($option_group['items'])) {
				
				foreach($option_group['items'] as $option) {
					if($option['hidden']) continue;
					$transform_array['options'][$option_group['name']][] = [
						'id' => $option['id'],
						'name' => isset($option['name']) ? $option['name'] : '',
						'price' => $this->getValueById($option['id'], 'price', $doc_data['option_prices'])
					];
				}
			}
		}

		/* Пакеты опций */
		$transform_array['option_packets'] = $doc_data['option_packets'];

		/* Базовые опции */
		$transform_array['available_options'] = $doc_data['available_options'];

		$doc = new modResource($this->evo);
		$doc->edit($doc_id);
		$doc->set('content', json_encode($transform_array, JSON_HEX_QUOT | JSON_UNESCAPED_UNICODE | JSON_FORCE_OBJECT));
		$doc->set('richtext', 0);
		$doc->set('contentType', 'application/json');
		$doc->set('searchable', 0);
		$doc->save(false, false);

		$this->saveStore();
	}

	public function getValueById($id='', $key='', $array=[]) {
		foreach($array as $item) {
			if(mb_strtolower($item['id']) == mb_strtolower($id)) return $item[$key];
		}
		return 0;
	}

	public function getImagesPath($path='') {
		$path = explode('/', $path);
		$path_slice = array_slice($path, 2);
		return 'assets/images/modifications/' . implode('/', $path_slice);
	}

	public function saveImages($path_image='', $images_data=[]) {
		if(!is_dir(MODX_BASE_PATH . $path_image)) {
			mkdir(MODX_BASE_PATH . $path_image, 0777, true);
		}

		$output = [];

		foreach($images_data as $item) {
			$image_name = '2022-'.$this->filenamehash($item['id']).'.png';
			if(!file_exists(MODX_BASE_PATH . $path_image . '/' . $image_name)) {
				$image_url = $item['url'];
				$image_file = file_get_contents($image_url);
				file_put_contents(MODX_BASE_PATH . $path_image . '/' . $image_name, $image_file);
			}
			$output[] = $path_image . '/' . $image_name;
		}
		return $output;
	}

	public function saveColorImage($path_image='', $filename='', $image_url='') {
		$colors_path = $path_image. '/colors';
		$color_name = $this->filenamehash($filename).'.png';

		if(!is_dir(MODX_BASE_PATH . $colors_path)) {
			mkdir(MODX_BASE_PATH . $colors_path, 0777, true);
		}

		if(!file_exists(MODX_BASE_PATH . $colors_path . '/' . $color_name)) {
			$image_file = file_get_contents($image_url);
			file_put_contents(MODX_BASE_PATH . $colors_path . '/' . $color_name, $image_file);
		}

		return $colors_path . '/' . $color_name;
	}

	public function filenamehash($filename='') {
		return mb_substr(md5($filename), 0, 8);
	}

	public function translate($model) {
        $model = str_replace('ГАЗ', '', $model);
        $model = trim($model, '- ');
        $model = str_replace(['А', 'С', 'В'], ['A', 'C', 'B'], $model);
        return trim($model);
    }

}

$parser = new configParser();
echo $parser->next();


