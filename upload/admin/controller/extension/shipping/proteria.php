<?php
class ControllerExtensionShippingProteria extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/shipping/proteria');

		$this->document->setTitle($this->language->get('heading_title1'));

		$this->load->model('setting/setting');

		//$this->getShippingMethodsForCustomer();

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('shipping_proteria', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true));
		}

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['key'])) {
			$data['error_key'] = $this->error['key'];
		} else {
			$data['error_key'] = '';
		}

		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}

		if (isset($this->error['post_code'])) {
			$data['error_post_code'] = $this->error['post_code'];
		} else {
			$data['error_post_code'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['contact_person_name'])) {
			$data['error_contact_person_name'] = $this->error['contact_person_name'];
		} else {
			$data['error_contact_person_name'] = '';
		}        
        
		if (isset($this->error['contact_person_email'])) {
			$data['error_contact_person_email'] = $this->error['contact_person_email'];
		} else {
			$data['error_contact_person_email'] = '';
		}        
        
		if (isset($this->error['contact_person_phone'])) {
			$data['error_contact_person_phone'] = $this->error['contact_person_phone'];
		} else {
			$data['error_contact_person_phone'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/shipping/proteria', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/shipping/proteria', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=shipping', true);

		if (isset($this->request->post['shipping_proteria_key'])) {
			$data['shipping_proteria_key'] = $this->request->post['shipping_proteria_key'];
		} else {
			$data['shipping_proteria_key'] = $this->config->get('shipping_proteria_key');
		}

		if (isset($this->request->post['shipping_proteria_city'])) {
			$data['shipping_proteria_city'] = $this->request->post['shipping_proteria_city'];
		} else {
			$data['shipping_proteria_city'] = $this->config->get('shipping_proteria_city');
		}

		if (isset($this->request->post['shipping_proteria_post_code'])) {
			$data['shipping_proteria_post_code'] = $this->request->post['shipping_proteria_post_code'];
		} else {
			$data['shipping_proteria_post_code'] = $this->config->get('shipping_proteria_post_code');
		}

		if (isset($this->request->post['shipping_proteria_country'])) {
			$data['shipping_proteria_country'] = $this->request->post['shipping_proteria_country'];
		} else {
			$data['shipping_proteria_country'] = $this->config->get('shipping_proteria_country');
		}

		// if (isset($this->request->post['shipping_proteria_pickup'])) {
		// 	$data['shipping_proteria_pickup'] = $this->request->post['shipping_proteria_pickup'];
		// } else {
		// 	$data['shipping_proteria_pickup'] = $this->config->get('shipping_proteria_pickup');
		// }

		// $data['pickups'] = array();

		// $data['pickups'][] = array(
		// 	'value' => '01',
		// 	'text'  => $this->language->get('text_daily_pickup')
		// );

		// $data['pickups'][] = array(
		// 	'value' => '03',
		// 	'text'  => $this->language->get('text_customer_counter')
		// );

		// $data['pickups'][] = array(
		// 	'value' => '06',
		// 	'text'  => $this->language->get('text_one_time_pickup')
		// );

		// $data['pickups'][] = array(
		// 	'value' => '07',
		// 	'text'  => $this->language->get('text_on_call_air_pickup')
		// );

		// $data['pickups'][] = array(
		// 	'value' => '19',
		// 	'text'  => $this->language->get('text_letter_center')
		// );

		// $data['pickups'][] = array(
		// 	'value' => '20',
		// 	'text'  => $this->language->get('text_air_service_center')
		// );

		// $data['pickups'][] = array(
		// 	'value' => '11',
		// 	'text'  => $this->language->get('text_suggested_retail_rates')
		// );

		// if (isset($this->request->post['shipping_proteria_packaging'])) {
		// 	$data['shipping_proteria_packaging'] = $this->request->post['shipping_proteria_packaging'];
		// } else {
		// 	$data['shipping_proteria_packaging'] = $this->config->get('shipping_proteria_packaging');
		// }

		// $data['packages'] = array();

		// $data['packages'][] = array(
		// 	'value' => '02',
		// 	'text'  => $this->language->get('text_package')
		// );

		// $data['packages'][] = array(
		// 	'value' => '01',
		// 	'text'  => $this->language->get('text_ups_letter')
		// );

		// $data['packages'][] = array(
		// 	'value' => '03',
		// 	'text'  => $this->language->get('text_ups_tube')
		// );

		// $data['packages'][] = array(
		// 	'value' => '04',
		// 	'text'  => $this->language->get('text_ups_pak')
		// );

		// $data['packages'][] = array(
		// 	'value' => '21',
		// 	'text'  => $this->language->get('text_ups_express_box')
		// );

		// $data['packages'][] = array(
		// 	'value' => '24',
		// 	'text'  => $this->language->get('text_ups_25kg_box')
		// );

		// $data['packages'][] = array(
		// 	'value' => '25',
		// 	'text'  => $this->language->get('text_ups_10kg_box')
		// );

		// if (isset($this->request->post['shipping_proteria_classification'])) {
		// 	$data['shipping_proteria_classification'] = $this->request->post['shipping_proteria_classification'];
		// } else {
		// 	$data['shipping_proteria_classification'] = $this->config->get('shipping_proteria_classification');
		// }

		// $data['classifications'][] = array(
		// 	'value' => '01',
		// 	'text'  => '01'
		// );

		// $data['classifications'][] = array(
		// 	'value' => '03',
		// 	'text'  => '03'
		// );

		// $data['classifications'][] = array(
		// 	'value' => '04',
		// 	'text'  => '04'
		// );

		if (isset($this->request->post['shipping_proteria_origin'])) {
			$data['shipping_proteria_origin'] = $this->request->post['shipping_proteria_origin'];
		} else {
			$data['shipping_proteria_origin'] = $this->config->get('shipping_proteria_origin');
		}

		$data['origins'] = array();

		$data['origins'][] = array(
			'value' => 'US',
			'text'  => $this->language->get('text_us')
		);

		$data['origins'][] = array(
			'value' => 'CA',
			'text'  => $this->language->get('text_ca')
		);

		$data['origins'][] = array(
			'value' => 'EU',
			'text'  => $this->language->get('text_eu')
		);

		$data['origins'][] = array(
			'value' => 'PR',
			'text'  => $this->language->get('text_pr')
		);

		$data['origins'][] = array(
			'value' => 'MX',
			'text'  => $this->language->get('text_mx')
		);

		$data['origins'][] = array(
			'value' => 'other',
			'text'  => $this->language->get('text_other')
		);

		// if (isset($this->request->post['shipping_proteria_contact_person_name'])) {
		// 	$data['shipping_proteria_contact_person_name'] = $this->request->post['shipping_proteria_contact_person_name'];
		// } else {
		// 	$data['shipping_proteria_contact_person_name'] = $this->config->get('shipping_proteria_contact_person_name');
		// }

		// if (isset($this->request->post['shipping_proteria_contact_person_email'])) {
		// 	$data['shipping_proteria_contact_person_email'] = $this->request->post['shipping_proteria_contact_person_email'];
		// } else {
		// 	$data['shipping_proteria_contact_person_email'] = $this->config->get('shipping_proteria_contact_person_email');
		// }

		// if (isset($this->request->post['shipping_proteria_contact_person_phone'])) {
		// 	$data['shipping_proteria_contact_person_phone'] = $this->request->post['shipping_proteria_contact_person_phone'];
		// } else {
		// 	$data['shipping_proteria_contact_person_phone'] = $this->config->get('shipping_proteria_contact_person_phone');
		// }

		if (isset($this->request->post['shipping_proteria_weight_class_id'])) {
			$data['shipping_proteria_weight_class_id'] = $this->request->post['shipping_proteria_weight_class_id'];
		} else {
			$data['shipping_proteria_weight_class_id'] = $this->config->get('shipping_proteria_weight_class_id');
		}

		$this->load->model('localisation/weight_class');

		$data['weight_classes'] = $this->model_localisation_weight_class->getWeightClasses();

		if (isset($this->request->post['shipping_proteria_length_class_id'])) {
			$data['shipping_proteria_length_class_id'] = $this->request->post['shipping_proteria_length_class_id'];
		} else {
			$data['shipping_proteria_length_class_id'] = $this->config->get('shipping_proteria_length_class_id');
		}

		$this->load->model('localisation/length_class');

		$data['length_classes'] = $this->model_localisation_length_class->getLengthClasses();

		if (isset($this->request->post['shipping_proteria_tax_class_id'])) {
			$data['shipping_proteria_tax_class_id'] = $this->request->post['shipping_proteria_tax_class_id'];
		} else {
			$data['shipping_proteria_tax_class_id'] = $this->config->get('shipping_proteria_tax_class_id');
		}

		$this->load->model('localisation/tax_class');

		$data['tax_classes'] = $this->model_localisation_tax_class->getTaxClasses();

		if (isset($this->request->post['shipping_proteria_geo_zone_id'])) {
			$data['shipping_proteria_geo_zone_id'] = $this->request->post['shipping_proteria_geo_zone_id'];
		} else {
			$data['shipping_proteria_geo_zone_id'] = $this->config->get('shipping_proteria_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['shipping_proteria_status'])) {
			$data['shipping_proteria_status'] = $this->request->post['shipping_proteria_status'];
		} else {
			$data['shipping_proteria_status'] = $this->config->get('shipping_proteria_status');
		}

		if (isset($this->request->post['shipping_proteria_sort_order'])) {
			$data['shipping_proteria_sort_order'] = $this->request->post['shipping_proteria_sort_order'];
		} else {
			$data['shipping_proteria_sort_order'] = $this->config->get('shipping_proteria_sort_order');
		}

		if (isset($this->request->post['shipping_proteria_debug'])) {
			$data['shipping_proteria_debug'] = $this->request->post['shipping_proteria_debug'];
		} else {
			$data['shipping_proteria_debug'] = $this->config->get('shipping_proteria_debug');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/shipping/proteria', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/shipping/proteria')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['shipping_proteria_key']) {
			$this->error['key'] = $this->language->get('error_key');
		}

		if (!$this->request->post['shipping_proteria_city']) {
			$this->error['city'] = $this->language->get('error_city');
		}

		if (!$this->request->post['shipping_proteria_country']) {
			$this->error['country'] = $this->language->get('error_country');
		}

		if (!$this->request->post['shipping_proteria_post_code']) {
			$this->error['post_code'] = $this->language->get('error_post_code');
		}

		// if (!$this->request->post['shipping_proteria_contact_person_name']) {
		// 	$this->error['contact_person_name'] = $this->language->get('error_contact_person_name');
		// }

		// if (!$this->request->post['shipping_proteria_contact_person_email']) {
		// 	$this->error['contact_person_email'] = $this->language->get('error_contact_person_email');
		// }

		// if (!$this->request->post['shipping_proteria_contact_person_phone']) {
		// 	$this->error['contact_person_phone'] = $this->language->get('error_contact_person_phone');
		// }

		return !$this->error;
	}

    
	public function install() {
		$this->load->model('extension/shipping/proteria');

		$this->model_extension_shipping_proteria->install();
	}

	public function uninstall() {
		$this->load->model('extension/shipping/proteria');

		$this->model_extension_shipping_proteria->uninstall();
	}
    
    public function getShippingMethodsForCustomer(){
		$all_shipping_methods = array();

		//GET /ShippingMethods/GetAllShippingMethods
		//https://cloudconnect.proteria.com/ShippingMethods/GetShippingMethodsForCustomer
		//Base URL https://cloudconnect.proteria.com/

		
        $this->load->library('proteria');
		$this->proteria->echoX();
		$this->proteria->getShippingMethodsForCustomer();
    }
}