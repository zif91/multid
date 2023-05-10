<?php
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);



if (!defined('MODX_API_MODE')) {
    $rootPath = dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR;
    define('MODX_API_MODE', true);
    define('MODX_BASE_PATH', $rootPath);
    define('MODX_BASE_URL', '/');
    define('MODX_SITE_URL', 'http://atlant.baspromode.beget.tech/');
    
    include_once $rootPath . 'index.php';
}


include_once(MODX_BASE_PATH."assets/lib/MODxAPI/modResource.php");


class usedParser {
	private $xml;
	private $folderId;
	private $output;
	private $lcv;
	private $tvs = [
		'vin' => 'description',
		'mark_id' => 'mark',
		'color' => 'color',
		'body_type' => 'body_type',
		'wheel' => 'wheel',
		'state' => 'car_state',
		'run' => 'run',
		'year' => 'year',
		'price' => 'car-price',
		'volume' => 'engine_volume',
		'power' => 'engine_power',
		'engine' => 'fuel',
		'gearbox' => 'gear',
		'wd' => 'drive',
		'description' => 'content',
		'images' => 'car_photos',
		'group_id' => 'model',
		'gearbox' => 'gear',
		'extras' => 'used_cars_extras',
		'folder_id' => 'model',
		'modification' => 'modification',
		'metallic' => 'metallic',
		'vin' => 'vin'
	];
	

	
	public function __construct() {
		$this->evo = EvolutionCMS();
	}

	public function start($city=27) {
		$this->folderId = $city['id'];
		$url_string = "https://probeg-centr.ru/local/xml/";
		$url_arr = explode(',',$url_string);
		$url_arr = array_map('trim', $url_arr);

		$this->evo->db->update(['deleted'=>1], $this->evo->getFullTableName('site_content'), 'parent = '.$this->folderId);
		foreach($url_arr as $url) {
			$this->lcv = preg_match("#autoru-format-lcv#", $url);
			$xml_content = $this->getUrl($url);
			
			if($xml_content) {
				$xml = simplexml_load_string($xml_content, null, LIBXML_NOCDATA);
				$xml = $xml->cars->children();

				$json = json_encode($xml);
				$this->xml = json_decode($json, true);
				$this->parseXml();
			}
		}	
		$this->evo->db->update(['longtitle'=>time()], $this->evo->getFullTableName('site_content'), 'id = '.$this->folderId);
		$this->evo->clearCache('full');
		return $this->output;
	}

	public function getUrl($url='') {
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HEADER, false);
		$content = curl_exec($ch);
		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		if($http_code !== 200) return false;
		return $content;
	}


	public function saveImages($path_image='', $images_data=[]) {
		if(!is_dir(MODX_BASE_PATH . $path_image)) {
			mkdir(MODX_BASE_PATH . $path_image, 0777, true);
		}

		$output = ['fieldValue'=>[], 'autoincrement'=>1];

		foreach($images_data as $item) {
			$image_name = $this->filenamehash($item).'.jpg';
			if(!file_exists(MODX_BASE_PATH . $path_image . '/' . $image_name)) {
				$image_file = file_get_contents($item);
				if(empty($image_file)) continue;

				file_put_contents(MODX_BASE_PATH . $path_image . '/' . $image_name, $image_file);
				
			}
			//$thumb_big = $this->evo->runSnippet('phpthumb', ['input'=>$path_image . '/' . $image_name, 'options'=>'w=605,h=450,far=C,bg=FFFFFF,f=jpg']);
			$thumb_small = $this->evo->runSnippet('phpthumb', ['input'=>$path_image . '/' . $image_name, 'options'=>'w=280,h=220,zc=1,bg=FFFFFF,f=jpg']);
			$output['fieldValue'][] = ['image' => $path_image . '/' . $image_name];
		}
		return json_encode($output, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
	}
	

	public function parseXml() {
		if(isset($this->xml)) {
			if(isset($this->xml['car'][0])) {
				foreach($this->xml['car'] as $car) {
					$this->parseCar($car);
				}
			} else {
				foreach($this->xml as $car) {
					$this->parseCar($car);
				}
			}
			
		}
		
		$this->output = $this->output .'<pre>'.print_r($this->xml, true);
	}

	public function parseCar($car = []) {
		if(!isset($car['vin'])) return;

		$insert_arr = [
			'template' => 8,
			'published' => 1,
			'deleted' => 0,
			'parent' => 27,
			'pagetitle' => $car['mark_id'].' '.(isset($car['group_id']) ? $car['group_id'] : $car['folder_id']).' '.$car['color'],
		];

		foreach($car as $key=>$value) {
			if($key=='images') {
				$images = $value['url'];
				/* Фотографии */
				$value = $this->saveImages('assets/images/cars', $images);
			}

			if($key=='description') {
				$value = nl2br($value);
			}

			if(isset($this->tvs[$key])) {
				$insert_arr[$this->tvs[$key]] = $value;
			}
		}

		$q = $this->evo->db->select('id', $this->evo->getFullTableName('site_content'), 'description = "'. $car['vin'] .'" AND parent = '.$this->folderId, '', 1);
		
		$doc = new modResource($this->evo);
		if($this->evo->db->getRecordCount($q)) {
			$doc->edit($this->evo->db->getValue($q));
			$doc->fromArray($insert_arr);
		} else {
			$doc->create($insert_arr);
		}

		$doc->save(true, true);
		return true;
	}

	public function filenamehash($filename='') {
		return mb_substr(md5($filename), 0, 8);
	}

}

$parser = new usedParser();

//$mainFolderId = 27114;
$cronTime = time() - 14400;

$q = $modx->db->select('id,description', $modx->getFullTableName('site_content'), 'template=27 AND (longtitle<'.$cronTime.' OR longtitle="")', '', 1);
if(!$modx->db->getRecordCount($q)) {
	echo 'нет подходящих';
	return;
}

echo $parser->start($modx->db->getRow($q));


