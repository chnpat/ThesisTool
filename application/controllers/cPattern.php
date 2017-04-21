<?php 
	class cPattern extends CI_Controller{
		public function __construct(){
			parent::__construct();
			$this->load->library('session');
			$this->load->model('mLogin');
		}

		public function index(){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Pattern List';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$this->load->view('templates/header', $data);
				$this->load->view('vPattern');
				$this->load->view('templates/footer');	
			}		
		}
	}