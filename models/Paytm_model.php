<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

class Paytm_model extends TI_Model {

	public function __construct() {
		parent::__construct();

		$this->load->library('cart');
		$this->load->library('currency');
		// $this->load->lib('paytm/config_paytm.php');
		// $this->load->lib('paytm/encdec_paytm.php');
	}

	public function createCharge($token, $order_data = array()) {
		if (empty($token) OR empty($order_data['order_id'])) {
			return FALSE;
		}

		$currency = $this->currency->getCurrencyCode();
		$order_total = $this->currency->format($this->cart->order_total());

		$data = array();


		$data['ORDER_ID'] = $order_data['order_id'];
		$data['CUST_ID'] = $order_data['email'];
		$data['TXN_AMOUNT'] = $order_total;
		$data['INDUSTRY_TYPE_ID'] = (isset($settings['industry_type'])) ? $settings['industry_type'] : '';
		$data['CHANNEL_ID'] = (isset($settings['channel_id'])) ? $settings['channel_id'] : '';
		$data['WEBSITE'] = (isset($settings['website_url'])) ? $settings['website_url'] : '';

		return $this->sendToPaytm('charges', $data, $order_data);
	}

	private function sendToPaytm($end_point, $data = array(), $order_data = array()) {
		$payment = $this->extension->getPayment('paytm');
		$settings = !empty($payment['ext_data']) ? $payment['ext_data'] : array();

		
		return $response;
	}
}

/* End of file Paytm_model.php */
/* Location: ./extensions/paytm/models/Paytm_model.php */