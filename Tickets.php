<?php 

class Tickets extends WS_Controller{

	public function __Construct()
	{
		parent::__Construct();
		ob_start();
		$this->load->library('session');
		$this->load->helper('url');
		$this->load->library('pagination');
		$this->load->library('breadcrumbs');
		$this->load->model('order_model');
		ini_set('display_errors', 1);
		error_reporting(E_ALL);
	}
	public function index(){		
		
		$dd = $this->db->query("SELECT * FROM oc_tickets");

		$data['leads'] = $dd->result();
		// print_r($data);exit;
		$this->website_header();
		$this->load->view('ticket_list',$data);
		$this->website_footer();
		
	}
	public function addedit($param = '')
	{
		
		if(is_numeric($param)){
			$data['heading']='View Ticket'; 
			
		}else{
			$data['heading']='Add Ticket'; 
		}
		$id = $param;
		if($id){
		    
			$dds = $this->db->query("SELECT * FROM oc_tickets where id ='".$id."'");
			$user_role = $this->session->userdata('arole');
			$admin_id = $this->session->userdata('adminid');
			if($user_role == 4 && $array_records->created_by != $admin_id){
				redirect('admin/blog');
				exit;
			}
			
		}else{
			$array_records=array();
		}
		
		$data['row'] = $array_records;
	
	
		
		$this->website_header();
		$this->load->view('ticket_form',$data);
		$this->website_footer();
	}
	public function view($id = '')
	{
		
	
		if($id){
		    
			$dds = $this->db->query("SELECT * FROM oc_tickets where id ='".$id."'");
			$chats = $this->db->query("SELECT * FROM oc_ticket_chat where ticket_id ='".$id."'");
		
		
			
		}else{
			$array_records=array();
		}
	
	    $data['chats'] = $chats->result();
		$data['row'] = $dds->row();
	
	   // print_r($data);exit;
		
		$this->website_header();
		$this->load->view('ticket_view',$data);
		$this->website_footer();
	}
	public function checkmail(){
	    
	        $email =  $this->input->post('cemail');
	        $order_id =  $this->input->post('order_id');
	        $data = array();
	        if($email!=''){
	             $dds = $this->db->query("SELECT * FROM oc_order where email ='".$email."' and order_status_id>0");
	             $data['odata'] = $dds->result();
	        }
	        
	        if($order_id!=''){
	             $dds = $this->db->query("SELECT * FROM oc_order where order_id ='".$order_id."' and order_status_id>0");
	             $data['odata'] = $dds->result();
	        }
	      
	       //echo '<pre>';print_r($data);exit;
	        $this->website_header();
    		$this->load->view('ticket_form',$data);
    		$this->website_footer();
	        
	}
	
	public function save_ticket()
	{
	
		
			
		$name = $this->input->post('customer_name');
		$email = $this->input->post('email');
		$city = $this->input->post('city');
		$description = $this->input->post('description');
		$order_id = $this->input->post('order_id');
		$created_agent_name = $this->session->userdata('username');
		
		$created_agent_id = $this->session->userdata('user_id');
		
// 		print_r($s_data);exit;
		$data = array(
					
					'customer_name'=>$name,
					'email'=>$email,
					'city'=>$city,
					'description'=>$description,
					'created_agent_id'=>$created_agent_id,
					'created_agent_name'=>$created_agent_name,
					'order_id'=>$order_id,
					'status'=>1
		    );
		    $data['indate'] = date('Y-m-d h:i:s');
			
		   	$save = $this->db->insert('oc_tickets', $data);
		   	
		
			if($save){
				
				return redirect(base_url('tickets'));
			}else{
				echo"<script>alert(' not inserted')</script>";
			}
			
	}
	public function delete($id)
	{
		
		$res=$this->blog_model->delete_blog($id);
		if($res)
		{
			return redirect('/admin/blog');
		}
	}
}
