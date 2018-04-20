<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

class Admin_paytm extends Admin_Controller {

	public function index($module = array()) {
		$this->lang->load('paytm/paytm');

		$this->user->restrict('Payment.Paytm');

		$this->load->model('Statuses_model');

		$title = (isset($module['title'])) ? $module['title'] : $this->lang->line('_text_title');

		$this->template->setTitle('Payment: ' . $title);
		$this->template->setHeading('Payment: ' . $title);
		$this->template->setButton($this->lang->line('button_save'), array('class' => 'btn btn-primary', 'onclick' => '$(\'#edit-form\').submit();'));
		$this->template->setButton($this->lang->line('button_save_close'), array('class' => 'btn btn-default', 'onclick' => 'saveClose();'));
		$this->template->setButton($this->lang->line('button_icon_back'), array('class' => 'btn btn-default', 'href' => site_url('extensions')));

		$ext_data = array();
		if ( ! empty($module['ext_data']) AND is_array($module['ext_data'])) {
			$ext_data = $module['ext_data'];
		}

		if (isset($this->input->post['title'])) {
			$data['title'] = $this->input->post('title');
		} else if (isset($ext_data['title'])) {
			$data['title'] = $ext_data['title'];
		} else {
			$data['title'] = $title;
		}

		if (isset($this->input->post['merchant_id'])) {
			$data['merchant_id'] = $this->input->post('merchant_id');
		} else if (isset($ext_data['merchant_id'])) {
			$data['merchant_id'] = $ext_data['merchant_id'];
		} else {
			$data['merchant_id'] = '';
		}

		if (isset($this->input->post['merchant_key'])) {
			$data['merchant_key'] = $this->input->post('merchant_key');
		} else if (isset($ext_data['merchant_key'])) {
			$data['merchant_key'] = $ext_data['merchant_key'];
		} else {
			$data['merchant_key'] = '';
		}

		if (isset($this->input->post['website_url'])) {
			$data['website_url'] = $this->input->post('website_url');
		} else if (isset($ext_data['website_url'])) {
			$data['website_url'] = $ext_data['website_url'];
		} else {
			$data['website_url'] = '';
		}

		if (isset($this->input->post['channel_id'])) {
			$data['channel_id'] = $this->input->post('channel_id');
		} else if (isset($ext_data['channel_id'])) {
			$data['channel_id'] = $ext_data['channel_id'];
		} else {
			$data['channel_id'] = '';
		}

		if (isset($this->input->post['industry_type'])) {
			$data['industry_type'] = $this->input->post('industry_type');
		} else if (isset($ext_data['industry_type'])) {
			$data['industry_type'] = $ext_data['industry_type'];
		} else {
			$data['industry_type'] = '';
		}

		if (isset($this->input->post['transaction_mode'])) {
			$data['transaction_mode'] = $this->input->post('transaction_mode');
		} else if (isset($ext_data['transaction_mode'])) {
			$data['transaction_mode'] = $ext_data['transaction_mode'];
		} else {
			$data['transaction_mode'] = '';
		}

		if (isset($ext_data['order_total'])) {
			$data['order_total'] = $ext_data['order_total'];
		} else {
			$data['order_total'] = '';
		}

		if (isset($this->input->post['order_status'])) {
			$data['order_status'] = $this->input->post('order_status');
		} else if (isset($ext_data['order_status'])) {
			$data['order_status'] = $ext_data['order_status'];
		} else {
			$data['order_status'] = '';
		}

		if (isset($this->input->post['priority'])) {
			$data['priority'] = $this->input->post('priority');
		} else if (isset($ext_data['priority'])) {
			$data['priority'] = $ext_data['priority'];
		} else {
			$data['priority'] = '';
		}

		if (isset($this->input->post['status'])) {
			$data['status'] = $this->input->post('status');
		} else if (isset($ext_data['status'])) {
			$data['status'] = $ext_data['status'];
		} else {
			$data['status'] = '';
		}

		$data['statuses'] = array();
		$results = $this->Statuses_model->getStatuses('order');
		foreach ($results as $result) {
			$data['statuses'][] = array(
				'status_id'   => $result['status_id'],
				'status_name' => $result['status_name'],
			);
		}

		if ($this->input->post() AND $this->_updatePaytm() === TRUE) {
			if ($this->input->post('save_close') === '1') {
				redirect('extensions');
			}

			redirect('extensions/edit/payment/paytm');
		}

		return $this->load->view('paytm/admin_paytm', $data, TRUE);
	}

	private function _updatePaytm() {
		$this->user->restrict('Payment.Paytm.Manage');

		if ($this->input->post() AND $this->validateForm() === TRUE) {

			if ($this->Extensions_model->updateExtension('payment', 'paytm', $this->input->post())) {
				$this->alert->set('success', sprintf($this->lang->line('alert_success'), $this->lang->line('_text_title') . ' payment ' . $this->lang->line('text_updated')));
			} else {
				$this->alert->set('warning', sprintf($this->lang->line('alert_error_nothing'), $this->lang->line('text_updated')));
			}

			return TRUE;
		}
	}

	private function validateForm() {
		$this->form_validation->set_rules('title', 'lang:label_title', 'xss_clean|trim|required|min_length[2]|max_length[128]');
		$this->form_validation->set_rules('merchant_id', 'lang:label_merchant_id', 'xss_clean|trim|required');
		$this->form_validation->set_rules('merchant_key', 'lang:label_merchant_key', 'xss_clean|trim|required');
		$this->form_validation->set_rules('website_url', 'lang:label_website_url', 'xss_clean|trim|required');
		$this->form_validation->set_rules('channel_id', 'lang:label_channel_id', 'xss_clean|trim|required');
		$this->form_validation->set_rules('industry_type', 'lang:label_industry_type', 'xss_clean|trim|required');
		$this->form_validation->set_rules('transaction_mode', 'lang:label_transaction_mode', 'xss_clean|trim|required');
		$this->form_validation->set_rules('order_total', 'lang:label_order_total', 'xss_clean|trim|required|numeric');
		$this->form_validation->set_rules('order_status', 'lang:label_order_status', 'xss_clean|trim|required|integer');
		$this->form_validation->set_rules('status', 'lang:label_status', 'xss_clean|trim|required|integer');

		if ($this->form_validation->run() === TRUE) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}

/* End of file paytm.php */
/* Location: ./extensions/paytm/controllers/paytm.php */