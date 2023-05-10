<?php

if (!defined('MODX_BASE_PATH')) {
	die('HACK???');
}

if(!function_exists('reindex')) {
	function reindex($params) {
		$modx = evolutionCMS();
		include_once('search.class.php');
	    $search_plugin_params = array_merge($params, [
	    	'offset'=>0,
	    	'rowsperonce'=>1,
	    	'reindex'=>0,
	    	'includeTmpls'=>$modx->getConfig('client_search_includeTmpls'),
	    	'excludeTmpls'=>$modx->getConfig('client_search_excludeTmpls'),
	    	'excludeIDs'=>'',
	    	'TvNames'=>$modx->getConfig('client_search_TvNames'),
	    	'unpublished'=>0,
	    	'deleted'=>0,
	    	'dicts'=>'rus,eng',
	    	'prepare'=>''
	    ]);

	    $eSP = new evoSearchPlugin($modx, $search_plugin_params);
	    //проверяем наличие и создаем при необходимости нужные поля и индекса
	    $eSP->prepareRun();
	    //теперь начинаем работу
	    $sql = $eSP->makeSQLForSelectWords();
	    //echo $sql;die();
	    $q = $modx->db->query($sql);
	    $content_fields = explode(',', $eSP->cleanIn($eSP->search_fields));
	    while ($row = $modx->db->getRow($q)) {
	        $tmp = array();
	        foreach($content_fields as $k => $v) {
	            if (isset($row[$v]) && !empty($row[$v])) {
	                $tmp[] = $row[$v];
	            }
	        }
	        $content_original = implode(' ', $tmp);
	        $content = $modx->stripTags($content_original);
	        $content = $eSP->injectTVs($row['id'], $content);
	        $words = $eSP->Words2BaseForm(mb_strtoupper($content, 'UTF-8'));
	        $fields = array(
	            $eSP->ext_content_field => $modx->db->escape($content),
	            $eSP->ext_content_index_field => $modx->db->escape($words),
	            'docid' => $row['id'],
	            'pagetitle' => isset($row['pagetitle']) ? $modx->db->escape($row['pagetitle']) : '',
	            'table' => $eSP->content_table_name
	        );
	        $up = $eSP->updateSearchTable($fields);
	        //die();
	    }
	    //удалим строки индексов для исключенных шаблонов и ресурсов
	    $eSP->emptyExcluded();
	}
}


switch($eventName) {
	case 'OnWebPageInit': {
		$field_name = $modx->getConfig('client_search_field_name');
		if(isset($_GET[$field_name]) && !empty($_GET[$field_name])) {
			$params = [
				'search_field'=>$field_name,
				'debug'=>0,
				'action'=>'ids',
				'display'=>0
			];

			require_once('evoSearch/evoSearch.snippet.php');
			return '';
		}
		break;
	}


	case 'OnPageNotFound': {
		/* Поиск */
		if (strpos($_GET['q'], 'search') === 0) {
			$params = [
				'search_field'=>'search_query',
				'debug'=>0,
				'display'=>20,
				'tvList'=>$modx->getConfig('client_search_TvNames'),
				'tpl'=>$modx->getConfig('client_search_rowtpl'),
				'ownerTPL'=>'@CODE:<ul>[+dl.wrap+]</ul>',
				'noneTPL'=>'@CODE:',
				'extract'=>0,
				'noneWrapOuter'=>0,
				'show_stat'=>0,
				'orderBy'=>'parent ASC',
				'noResult'=>'Ничего не найдено'
			];

			$result = require_once('evoSearch/evoSearch.snippet.php');
			echo json_encode(['result'=>$result]);
			die();
		}

		/* Переиндексация поиска */
		if (strpos($_GET['q'], 'reindex_rearch') === 0) {
			if(!isset($_SESSION['mgrValidated'])){
			    die();
			}

			$_SESSION['reindex_process'] = isset($_SESSION['reindex_process']) ? $_SESSION['reindex_process'] : 0;
			$_SESSION['reindex_last'] = isset($_SESSION['reindex_last']) ? $_SESSION['reindex_last'] : 0;

			$where = [];
			if(!empty($modx->getConfig('client_search_includeTmpls'))) {
				$where[] = 'template IN ('.$modx->getConfig('client_search_includeTmpls').')';
			}
			if(!empty($modx->getConfig('client_search_excludeTmpls'))) {
				$where[] = 'template NOT IN ('.$modx->getConfig('client_search_excludeTmpls').')';
			}

			$where[] = 'searchable=1';
			$where[] = 'published=1';

			$where = implode(' AND ', $where);


			if(!isset($_SESSION['reindex_total'])) {
				$q = $modx->db->select('id', $modx->getFullTableName('site_content'), $where);
				$_SESSION['reindex_total'] = $modx->db->getRecordCount($q);
			}


			$_time = microtime(true);
			$where = !empty($where) ? $where.' AND id>'.$_SESSION['reindex_last'] : 'id>'.$_SESSION['reindex_last'];
	        $q = $modx->db->select('id', $modx->getFullTableName('site_content'), $where, 'id ASC', 500);
	        while ($id = $modx->db->getValue($q)) {
	            if ($id) {
	            	reindex(array('id' => $id, 'mode' => 'upd'));
	            	//$modx->invokeEvent('OnDocFormSave', array('id' => $id, 'mode' => 'upd'));
	            }
	            $_SESSION['reindex_last'] = $id;
	            $_SESSION['reindex_process']++;
	            $time = microtime(true) - $_time;
	            if ($time > 2) {
	                break;
	            }
	        }

	        $out = [];
	        $out['success'] = true;
	        $out['total'] = $_SESSION['reindex_total'];
	        if (!$_SESSION['reindex_process']) {
	            $out['progress'] = 0;
	        } else {
	        	if($_SESSION['reindex_process'] < $_SESSION['reindex_total']) {
	        		$out['progress'] = ceil($_SESSION['reindex_process']*100/$_SESSION['reindex_total']);
	        	} else {
	        		$out['progress'] = 100;
	        		unset($_SESSION['reindex_process']);
					unset($_SESSION['reindex_last']);
					unset($_SESSION['reindex_total']);
	        	}
	        }

	        echo json_encode($out, JSON_UNESCAPED_UNICODE);
	        die();
		}
		break;
	}

	case 'OnDocFormSave': {
		if($modx->getConfig('client_search_disabled') > 0) break;
	    reindex($params);
	    break;
	}
}