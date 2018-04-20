<?php if ( ! defined('BASEPATH')) exit('No direct access allowed');

class Paytm extends Main_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Orders_model');
        $this->load->model('Paytm_model');
        
    }

    public function index() {
        $this->lang->load('paytm');
        if ( ! file_exists(EXTPATH .'paytm/views/paytm.php')) { 								//check if file exists in views folder
            show_404(); 																		// Whoops, show 404 error page!
        }

        $payment = $this->extension->getPayment('paytm');

        // START of retrieving lines from language file to pass to view.
        $data['code'] 			= $payment['name'];
        $data['title'] 			= !empty($payment['ext_data']['title']) ? $payment['ext_data']['title'] : $payment['title'];
        // END of retrieving lines from language file to send to view.

        $order_data = $this->session->userdata('order_data');                           // retrieve order details from session userdata
        $data['payment'] = !empty($order_data['payment']) ? $order_data['payment'] : '';
        $data['minimum_order_total'] = is_numeric($payment['ext_data']['order_total']) ? $payment['ext_data']['order_total'] : 0;
        $data['order_total'] = $this->cart->total();

        // pass array $data and load view files
        return $this->load->view('paytm/paytm', $data, TRUE);
    }

    public function confirm() {
        $order_data = $this->session->userdata('order_data'); 						// retrieve order details from session userdata
        $cart_contents = $this->session->userdata('cart_contents'); 												// retrieve cart contents

        if (empty($order_data) OR empty($cart_contents)) {
            return FALSE;
        }

        if (!empty($order_data['payment']) AND $order_data['payment'] == 'paytm') { 	// check if payment method is equal to paytm

            $ext_payment_data = !empty($order_data['ext_payment']['ext_data']) ? $order_data['ext_payment']['ext_data'] : array();

            if (!empty($ext_payment_data['order_total']) AND $cart_contents['order_total'] < $ext_payment_data['order_total']) {
                $this->alert->set('danger', $this->lang->line('alert_min_total'));
                return FALSE;
            }

            $this->load->model('paytm/Paytm_model');
            $response = $this->Paypal_model->createCharge($order_data, $this->cart->contents());

            if (isset($response->error->message)) {
                if ($response->error->type === 'card_error') $this->alert->set('danger', $response->error->message);
            } else if (isset($response->status)) {

                if ($response->status !== 'succeeded') {
                    $order_data['status_id'] = $ext_payment_data['order_status'];
                } else if (isset($ext_payment_data['order_status']) AND is_numeric($ext_payment_data['order_status'])) {
                    $order_data['status_id'] = $ext_payment_data['order_status'];
                } else {
                    $order_data['status_id'] = $this->config->item('default_order_status');
                }

                if (!empty($response->paid)) {
                    $comment = sprintf($this->lang->line('text_payment_status'), $response->status, $response->id);
                } else {
                    $comment = "{$response->failure_message} {$response->id}";
                }

                $order_history = array(
                    'object_id'  => $order_data['order_id'],
                    'status_id'  => $order_data['status_id'],
                    'notify'     => '0',
                    'comment'    => $comment,
                    'date_added' => mdate('%Y-%m-%d %H:%i:%s', time()),
                );

                $this->load->model('Statuses_model');
                $this->Statuses_model->addStatusHistory('order', $order_history);

                $this->load->model('Orders_model');
                if ($this->Orders_model->completeOrder($order_data['order_id'], $order_data, $cart_contents)) {
                    redirect('checkout/success');                                   // redirect to checkout success page with returned order id
                }
            }

            return FALSE;
        }
    }

    
    public function cancel() {
        $order_data = $this->session->userdata('order_data'); 							// retrieve order details from session userdata

        if (!empty($order_data) AND $this->input->get('token')) { 						// check if token and PayerID is in $_GET data

            $this->load->model('Statuses_model');
            $status = $this->Statuses_model->getStatus($this->config->item('canceled_order_status'));

            $order_history = array(
                'object_id'  => $order_data['order_id'],
                'status_id'  => $status['status_id'],
                'notify'     => '0',
                'comment'    => $status['comment'],
                'date_added' => mdate('%Y-%m-%d %H:%i:%s', time()),
            );

            $this->Statuses_model->addStatusHistory('order', $order_history);

            $token = $this->input->get('token'); 												// retrieve token from $_GET data

            $this->alert->set('alert', $this->lang->line('alert_error_server'));
            redirect('checkout');
        }
    }
}

/* End of file patm.php */
/* Location: ./extensions/paypal_express/controllers/paytm.php */