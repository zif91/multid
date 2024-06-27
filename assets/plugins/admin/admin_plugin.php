<?php

if (!defined('MODX_BASE_PATH')) {
	die('HACK???');
}

$DLTemplate = MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLTemplate.class.php';
if (file_exists($DLTemplate)) {
    include_once $DLTemplate;
}

$eventName = $modx->event->name;

require ('client_settings.php');

if($modx->getConfig('client_commerce_disabled')==0) {
	require ('commerce.php');
}

if($modx->getConfig('client_search_disabled')==0) {
	require ('search.php');
}



switch($eventName) {

	case 'OnWebPageInit': {
		if(in_array($modx->documentIdentifier, [11,12])) {
			if(!$modx->getLoginUserID('mgr')) {
				$modx->sendErrorPage();
			}
		}
		if(in_array($modx->documentIdentifier, [9,10])) {
			if($modx->getConfig('client_commerce_disabled')==1) {
				$modx->sendErrorPage();
			}
		}
		break;
	}

	case 'OnDocFormPrerender': {
		if(!isset($_REQUEST['id'])) {
			global $content;
			$content['hidemenu'] = 1;
		}
		break;
	}

	case 'OnPageNotFound': {
		/* Получение чанков */
		if (strpos($_GET['q'], 'getModal') === 0) {
			if(!isset($_POST['chunkname'])) {
				echo 'Чанк '.$_POST['chunkname'].' не найден';
				die();
			}

			echo DLTemplate::getInstance($modx)->parseChunk(trim($_POST['chunkname']), $_POST['data'], true);
			die();
		}

		if (strpos($_GET['q'], 'forms') === 0) {



			$formid = isset($_POST['formid'])
				&& is_scalar($_POST['formid'])
				&& isset($_SERVER['HTTP_X_REQUESTED_WITH'])
				&& (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
				? $_POST['formid']
				: '';

			$extension = isset($_POST['extension']) ? $_POST['extension'] : 'tpl';
			$path = isset($_POST['path']) ? $_POST['path'] : 'site/tpl/forms/';

			if(!empty($_POST['modal'])) {
				echo $modx->runSnippet('Form', $_POST);
				die();
			}


			$to = !empty($modx->getConfig('client_email_to')) ? $modx->getConfig('client_email_to') : $modx->getConfig('client_company_email');
			$from = !empty($modx->getConfig('client_smtp_username')) ? $modx->getConfig('client_smtp_username') : $modx->getConfig('client_company_email');
			$subject = 'Форма с сайта ' . $modx->getConfig('site_name');


			if(file_exists(MODX_BASE_PATH . $path.$formid.'.'.$extension)) {
				$formTpl = file_get_contents(MODX_BASE_PATH . $path.$formid.'.'.$extension);
			} else {
				echo 'Шаблон формы '.$path.$formid.'.'.$extension.' не найден';
				die();
			}

			$_params = [
				'api'=>4,
				'formid' => $formid,
				'subject' => $subject,
				'to' => $to,
				'from' => $from,
				'formTpl'=> $extension == 'twig' ? '@T_CODE:'.$formTpl : '@CODE:'.$formTpl,
				'messagesOuterTpl'=>'@CODE:<input type="hidden" name="formid" value="'.$formid.'"><div class="mt-3"><div class="alert alert-danger text-center" style="color:red;font-size:.85rem;">[+messages+]</div></div>',
				'messagesTpl'=>'@CODE:[+messages+]',
				'errorTpl'=>'@CODE:[+message+]',
				'errorClass'=>'is-invalid',
				'requiredClass'=>'is-invalid',
                'noemail' => 1,
			];


			if(file_exists(MODX_BASE_PATH . $path.$formid.'.json')) {
				$config_json = file_get_contents(MODX_BASE_PATH . $path.$formid.'.json');
				$config = json_decode($config_json, true);
				$_params = array_merge($_params, $config);

				if($formid=='order/order') {
					unset($_POST['url']);
					unset($_POST['pagetitle']);
					$_params['formid'] = 'order';
					$_params['reportTpl'] = '@CODE:'.file_get_contents(MODX_BASE_PATH . $path.$formid.'_report.'.$extension);
					$_params['ccSenderTpl'] = '@CODE:'.file_get_contents(MODX_BASE_PATH . $path.$formid.'_reportback.'.$extension);
					$_POST['formid'] = 'order';

					$result = $modx->runSnippet('Order', $_params);
				} else {
					if(file_exists(MODX_BASE_PATH . $path.$formid .'.success.'.$extension)) {
						$successTpl = file_get_contents(MODX_BASE_PATH . $path.$formid .'.success.'.$extension);
					} else {
						echo 'Шаблон формы '.$path.$formid.'.success.'.$extension.' не найден';
						die();
					}

					if(file_exists(MODX_BASE_PATH . $path.$formid .'.report.'.$extension)) {
						$reportTpl = file_get_contents(MODX_BASE_PATH . $path.$formid .'.report.'.$extension);
					} else {
						echo 'Шаблон формы '.$path.$formid.'.report.'.$extension.' не найден';
						die();
					}

					$_params['controller'] = 'Form';
					$_params['successTpl'] = $extension == 'twig' ? '@T_CODE:'.$successTpl : '@CODE:'.$successTpl;
					$_params['reportTpl'] = $extension == 'twig' ? '@T_CODE:'.$reportTpl : '@CODE:'.$reportTpl;

                    if(file_exists(MODX_BASE_PATH . $path.$formid .'._reportback.'.$extension)) {
                        $reportTpl = file_get_contents(MODX_BASE_PATH . $path.$formid .'._reportback.'.$extension);
                        $_params['bcc'] = 'pipzipper@yandex.ru';
                        $_params['ccSenderTpl'] = $extension == 'twig' ? '@T_CODE:'.$reportTpl : '@CODE:'.$reportTpl;
                        $_params['ccSubject'] = 'Заявка для обработки';
                        $_params['ccSender'] = 1;
                    }

                    $result = $modx->runSnippet('FormLister', $_params);
				}

				$result = json_decode($result, true);

				$result['errorClass'] = $_params['errorClass'];

				echo json_encode($result, JSON_UNESCAPED_UNICODE);
				die();
			} else {
				echo 'check formid';
				die();
			}
		}
		break;
	}

}
