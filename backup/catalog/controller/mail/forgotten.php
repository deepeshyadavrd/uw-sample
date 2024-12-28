<?php
class ControllerMailForgotten extends Controller {
	public function index(&$route, &$args, &$output) {			            
		$this->load->language('mail/forgotten');

		$data['text_greeting'] = sprintf($this->language->get('text_greeting'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$data['text_change'] = $this->language->get('text_change');
		$data['text_ip'] = $this->language->get('text_ip');
		$data['title'] = 'Reset Password | Urbanwood';
		$data['heading'] = 'Reset Password here';
		$data['exp_text'] = sprintf('A password change has been requested for your account. If this was you, please use the link below to reset your password.');
		// $data['img'] = sprintf('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAAACXBIWXMAAAsTAAALEwEAmpwYAAADYUlEQVR4nO2ZzU4TURTHeYHSe2c6idG9r2BYu5GYYOnM9JMCYeXGxBWJaxcVsNAWKi2lgIDAyp1bwiPIA5jwYQyYGCRojBE95k5n2unMnZnOvQPpYk7y33T1+92cO+dMZ2AgrLDCCqsvKzK18zgyufs5MrkHg5O7enZgcMLIOz3bEB03smXKJkTzJG+1IC0bgMaMrOtZA5RrBeeanWRXtaBM4xRn68P+BSb2TiNtcJ/weX/w2AEeZxtaUHblxL9An8DjbAOEzAr4FggMfowfXmAX6A94gU3ACu4Ev+kBb4Cv65e1CTizAihVBaRWIKqUACllPRVAyaoFvq7Fv8BNwGcacC9fgxfrB3BweAxfvl3BxdUvLSdfL+Hw0zlE5QUbPKOAP3jUA/yD51twfH4JboUS8zZ4IV3jEQgAPteEu2PLcHT23RW+I9ANzyHgNqDM8Bsu8GuAMzWYXt2HXgolijZ4Ib3MIhAQfK6pXdj9j0c22LOLH/BwehuwPK+dPIHH2h3ohhdZBIKC1wTUCpxf/LQJPC1/ACFV7e75tB2eUSAYeJKoXILff65tAvenapZ+p8OLqTc8AnzwZDgRAVqJit4uXS1jgk+14NkF8sFslOTZ7nph0+7wYqrKIMCxlKHkknbqrSxANFGkC4zOARp9bcocCGrZBs8hwLbXOLWMV73cOgBRXbTBi0kmAfaljFVgvPAexOSSDZ78xiTgPl2t8KuePe9VQ8+aLWALfIxFgBWebJIsAtd//8EdtUiFjyUXGQR6hW+Dm14ByWqc6ExXcjlpheMzgOOz7QhykQrPIeAfnrbLEwm6wCz1wlrhYyqTgPeAssKb36DME5Y8It0Fqjr8EhU+plY4BDjhyXByF6h6wrML8MCb9hq3OyD2AB9TywwCAcGT1QC7CSQNcGd4ZoEg4Mleg+MOAk9e9QQvKQwCfv7y8NooSa/TSmgLGPAVKryklDgEOOHJU8ZdYNETnl0gAPiWwIyDQKEneElZYBBggO+8QXWvw44CI4We4DkEnKerM7wB3hlQQnxOu7CkZcipaxkpgEhayAZfssFLMpNAMPBOqwH95EtUeEmeZxe4DXhJcYdnEiBfRvoFXpKL/j9w4FR9GKfrpzcDX3aGl83gLXhptPjIt0BYYYUV1sBt1H9ja3+JK6NR4QAAAABJRU5ErkJggg==');
		// $data['img'] = 'https://www.urbanwood.in/' . 'image/' . $this->config->get('config_logo');
		// $data['url'] = 'www.urbanwood.in';
		
		$data['reset'] = str_replace('&amp;', '&', $this->url->link('account/reset', 'code=' . $args[1], true));
		$data['ip'] = $this->request->server['REMOTE_ADDR'];

		$mail = new Mail($this->config->get('config_mail_engine'));
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
		$mail->smtp_username = $this->config->get('config_mail_smtp_username');
		$mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
		$mail->smtp_port = $this->config->get('config_mail_smtp_port');
		$mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

		$mail->setTo($args[0]);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
		$mail->setSubject(html_entity_decode(sprintf($this->language->get('text_subject'), html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8')), ENT_QUOTES, 'UTF-8'));
		$mail->setHtml($this->load->view('mail/forgotten', $data));
		$mail->send();
	}
}
