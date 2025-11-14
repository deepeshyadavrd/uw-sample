<?php

class ModelExtensionTotalSubTotal extends Model {

	public function getTotal($total) {

		$this->load->language('extension/total/sub_total');



		$sub_total = $this->cart->getSubTotal();

		// $to_inc_price = $sub_total*(20/100);
		// $sub_total = $sub_total+$to_inc_price;

		if (!empty($this->session->data['vouchers'])) {

			foreach ($this->session->data['vouchers'] as $voucher) {

				$sub_total += $voucher['amount'];

			}

		}



		$total['totals'][] = array(

			'code'       => 'sub_total',

			'title'      => $this->language->get('text_sub_total'),

			'value'      => $sub_total,

			'sort_order' => $this->config->get('sub_total_sort_order')

		);



		$total['total'] += $sub_total;

	}

}

