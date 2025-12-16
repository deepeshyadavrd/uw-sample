<?php

class ControllerCommonHeader extends Controller {

	public function index() {

		if ($this->request->server['HTTPS']) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		if (is_file(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->document->addLink($server . 'image/' . $this->config->get('config_icon'), 'icon');
		}
		$parts = explode('_', (string)$this->request->get['path']);

			$category_id = (int)array_pop($parts);
		$data['robots'] = false;
		if ($category_id == 154){
			header("X-Robots-Tag: noindex, follow", true);
			$data['robots'] = '<meta name="robots" content="noindex, follow">';
		}

		$data['title'] = $this->document->getTitle();
		$data['base'] = $server;
		$data['description'] = $this->document->getDescription();

		$data['keywords'] = $this->document->getKeywords();

			$req_uri = end(explode('/',$_SERVER['REQUEST_URI']));
			$req_uri = strtok($req_uri, '?');
			$req_uri = strtok($req_uri, '&');
		$data['canonical'] = '';
		if($data['title']!='The page you requested cannot be found!'){
			$data['canonical'] = $server.strtolower($req_uri);
		}

		return $this->load->view('common/header', $data);

	}

}

