<?php
if (!defined('MODX_BASE_PATH')) {
	die('HACK???');
}

use Commerce\Carts\DbCartStore;
use Commerce\Carts\ProductsCart;
use Commerce\Currency;

switch($eventName) {
	case 'OnInitializeCommerce':{	
		if ($modx->event->name === 'OnInitializeCommerce') {
			ci()->commerce->currency = new Currency($modx);
			$carts = ci()->carts;
			$carts->registerStore('db', DbCartStore::class);
			$cart = new ProductsCart($modx, 'products', 'db');
			$carts->addCart('products', $cart);
		}
		break;
	}
		
	case 'OnRegisterDelivery': {
		$deliveries = json_decode($modx->getConfig('client_deliveries'), true);
		if($deliveries) {
			foreach($deliveries as $item) {
				$params['rows'][$item['code']] = [
					'title' => $item['title'],
					'price' => $item['price']
				];
			}
		}
		break;
	}
		
	case 'OnRegisterPayments': {
		if(!empty($modx->commerce)) {
			$class = new \Commerce\Payments\Payment($modx, $params);
			$payments = json_decode($modx->getConfig('client_payments'), true);
			if($payments) {
				foreach($payments as $item) {
					$modx->commerce->registerPayment($item['code'], $item['title'], $class);
				}    
			}
		}
		break;
	}
		
	case 'OnCollectSubtotals': {
		$processor = $modx->commerce->loadProcessor();
		$deliveries = json_decode($modx->getConfig('client_deliveries'), true);
		if ($deliveries && $processor->isOrderStarted()) {
			foreach($deliveries as $item) {
				if($item['code'] == $processor->getCurrentDelivery()) {
					$params['total'] += $item['price'];
					$params['rows'][$item['code']] = [
						'title' => $item['title'],
						'price' => $item['price']
					];
				}
			}
		}
		break;
	}
		
		
	case 'OnManagerBeforeOrdersListRender': {
		// добавляем столбец в таблицу заказов
		$params['columns']['address'] = [
			'title' => 'Адрес',
			'content' => function($data, $DL, $eDL) {
				return !empty($data['fields']['address']) ? $data['fields']['address'] : '';
			},
			'sort' => 50,
		];
		$params['columns']['comment'] = [
			'title' => 'Комментарий',
			'content' => function($data, $DL, $eDL) {
				return !empty($data['fields']['comment']) ? $data['fields']['comment'] : '';
			},
			'sort' => 50,
		];
		break;
	}
		
	case 'OnManagerBeforeOrderRender': {
		// добавляем поле на страницу просмотра заказа
		$params['groups']['payment_delivery']['fields']['address'] = [
			'title' => 'Адрес',
			'content' => function($data) {
				return !empty($data['fields']['address']) ? $data['fields']['address'] : '';
			},
			'sort' => 20,
		];
		$params['groups']['payment_delivery']['fields']['comment'] = [
			'title' => 'Комментарий',
			'content' => function($data) {
				return !empty($data['fields']['comment']) ? $data['fields']['comment'] : '';
			},
			'sort' => 20,
		];
		break;
	}
		
	case 'OnManagerBeforeOrderEditRender': {
		$params['fields']['address'] = [
			'title' => 'Адрес',
			'content' => function($data) {
				$value = !empty($data['fields']['address']) ? $data['fields']['address'] : '';
				return '<input type="text" class="form-control" name="order[fields][address]" value="' . htmlentities($value) . '">';
			},
			'sort' => 40,
		];
		break;
	}
}