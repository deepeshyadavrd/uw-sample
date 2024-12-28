<?php
class ControllerProductAllreview extends Controller {
	public function index() {
		$this->load->model('catalog/category');

		$category_total = $this->model_catalog_category->getCategories();
		// print_r($category_total);

		$data['categories'] = array();

		foreach($category_total as $category){
			$data['categories'][] = array(
				'category_id' => $category['category_id'],
				'category_name' => $category['name']
			);
		}
		$current_category = $this->request->get['category'];
		$current_city = $this->request->get['city'];
		$current_date = $this->request->get['date'];

		if (isset($this->request->get['category'])) {
			$category = $this->request->get['category'];
		} else {
			$category = '';
		}
		if (isset($this->request->get['date'])) {
			$date = $this->request->get['date'];
		} else {
			$date = '';
		}
		if (isset($this->request->get['city'])) {
			$city = $this->request->get['city'];
		} else {
			$city = '';
		}
        $this->load->model('catalog/review');

		$city_list = $this->model_catalog_review->getCity();
		$data['city_list'] = $city_list;
		
		$city_list = $this->model_catalog_review->getCity();
		$data['city_list'] = $city_list;

		$year_list = $this->model_catalog_review->getYears();
		$data['year_list'] = $year_list;

        if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		$data['reviews'] = array();
		$sorting_review = array(
			'category' => $category,
			'city'     => $city,
			'date'     => $date
		);
		$review_total = $this->model_catalog_review->getTotalReviews($sorting_review);

		$results = $this->model_catalog_review->getReviews($sorting_review,($page - 1) * 10, 10);
// print_r($review_total);
		foreach ($results as $result) {
			$data['reviews'][] = array(
				'author'     => $result['author'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
                'city'       => $result['city'],
				'category'	=> $result['main_category']
			);
		}

		$pagination = new Pagination();
		$pagination->total = $review_total;
		$pagination->page = $page;
		$pagination->limit = 10;
		if(isset($current_category) && isset($current_city) && isset($current_date)){
			$pagination->url = $this->url->link('product/allreview&category='.$current_category.'&city='.$current_city.'&date='.$current_date, 'page={page}', true);
		}elseif(isset($current_category) && isset($current_city)){
			$pagination->url = $this->url->link('product/allreview&category='.$current_category.'&city='.$current_city, 'page={page}', true);
		}elseif(isset($current_city) && isset($current_date)){
			$pagination->url = $this->url->link('product/allreview&city='.$current_city.'&date='.$current_date, 'page={page}', true);
		}elseif(isset($current_category) && isset($current_date)){
			$pagination->url = $this->url->link('product/allreview&category='.$current_category.'&date='.$current_date, 'page={page}', true);
		}elseif(isset($current_category)){
			$pagination->url = $this->url->link('product/allreview&category='.$current_category, 'page={page}', true);
		}elseif(isset($current_city)){
			$pagination->url = $this->url->link('product/allreview&city='.$current_city, 'page={page}', true);
		}elseif(isset($current_date)){
			$pagination->url = $this->url->link('product/allreview&date='.$current_date, 'page={page}', true);
		}else{
			$pagination->url = $this->url->link('product/allreview', 'page={page}', true);
		}
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($review_total) ? (($page - 1) * 10) + 1 : 0, ((($page - 1) * 10) > ($review_total - 10)) ? $review_total : ((($page - 1) * 10) + 10), $review_total, ceil($review_total / 10));
        $data['header'] = $this->load->controller('common/header');  
        $data['footer'] = $this->load->controller('common/footer');  
        $data['menu'] = $this->load->controller('common/menu');  
        
		$this->response->setOutput($this->load->view('product/allreview', $data));

    }
	public function filters(){
		// print_r($this->request->post['category']);
		$this->load->model('catalog/review');
		$category = $this->request->post['category'];

		$results = $this->model_catalog_review->getReviewsByCategoryId($category);
		//print_r($results);
		
		echo json_encode($results);
	}
}