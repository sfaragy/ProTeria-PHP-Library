<?php
class ModelExtensionShippingProteria extends Model {
	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "bangloss_proteria_order` (
			  `proteria_order_id` int(11) NOT NULL AUTO_INCREMENT,
			  `order_id` int(11) NOT NULL,
			  `shipmentid` int(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `date_modified` DATETIME NOT NULL,
			  `status` ENUM('Complete','NotComplete') DEFAULT NULL,
			  `trackingnumber` TEXT NOT NULL,
			  `trackingurl` TEXT NOT NULL,
			  `label` TEXT NOT NULL,
			  `request` TEXT NOT NULL,
			  `response` TEXT NOT NULL,
			  PRIMARY KEY (`proteria_order_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci
		");

	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "bangloss_proteria_order`");
	}

    public function log($data, $title = null) {
		if ($this->config->get('shipping_prederia_debug')) {
			$this->log->write('Shipping Proteria debug (' . $title . '): ' . json_encode($data));
		}
	}


	public function call($data) {
		if ($this->config->get('payment_pp_express_test') == 1) {
			$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
			$user = $this->config->get('payment_pp_express_sandbox_username');
			$password = $this->config->get('payment_pp_express_sandbox_password');
			$signature = $this->config->get('payment_pp_express_sandbox_signature');
		} else {
			$api_endpoint = 'https://api-3t.paypal.com/nvp';
			$user = $this->config->get('payment_pp_express_username');
			$password = $this->config->get('payment_pp_express_password');
			$signature = $this->config->get('payment_pp_express_signature');
		}

		$settings = array(
			'USER' => $user,
			'PWD' => $password,
			'SIGNATURE' => $signature,
			'VERSION' => '84',
			'BUTTONSOURCE' => 'OpenCart_Cart_EC',
		);

		$this->log($data, 'Call data');

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $api_endpoint,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => http_build_query(array_merge($data, $settings), '', "&")
		);

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);

		if (!$result = curl_exec($ch)) {
			$log_data = array(
				'curl_error' => curl_error($ch),
				'curl_errno' => curl_errno($ch)
			);

			$this->log($log_data, 'CURL failed');
			return false;
		}

		$this->log($result, 'Result');

		curl_close($ch);

		return $this->cleanReturn($result);
	}

	private function curl($endpoint, $additional_opts = array()) {
		$default_opts = array(
			CURLOPT_PORT => 443,
			CURLOPT_HEADER => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_URL => $endpoint,
		);

		$ch = curl_init($endpoint);

		$opts = $default_opts + $additional_opts;

		curl_setopt_array($ch, $opts);

		$response = json_decode(curl_exec($ch));

		curl_close($ch);

		return $response;
	}
}
