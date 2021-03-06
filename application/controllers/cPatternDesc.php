<?php 
	/**
	* 
	*/
	class cPatternDesc extends CI_Controller{

		public function __construct()
		{
			parent::__construct();
			$this->load->library(array('session', 'form_validation'));
			$this->load->model(array('mLogin','mPattern', 'mPatternDesc'));
			$this->load->helper('file', 'url');
		}

		public function index($pat_id=NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Pattern Description List';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$desc = $this->mPatternDesc->get_pattern_description_by_pattern($pat_id);
				$detail['rows'] = (is_bool($desc))? array():$desc;
				$detail['pat_id'] = $pat_id;
				$pattern = $this->mPattern->get_pattern($pat_id);
				$detail['pattern'] =  (is_bool($pattern))? array(): $pattern;

				$this->load->view('templates/header', $data);
				$this->load->view('pattern_management/vDescriptionList', $detail);
				$this->load->view('templates/footer');	
			}		
		}

		public function desc_detail($pat_id=NULL,$id=NULL, $tab=NULL){
			if(!$this->session->userdata('logged')){
				echo "<script type='text/javascript'>window.location='".base_url()."cUser/index'</script>";
			}
			else{
				$data['title'] = 'Pattern Description Detail';
				$data['userObj'] = $this->mLogin->get_user(array('email' => $this->session->userdata('email')))[0];
				$detail['rows'] = $this->mPatternDesc->get_pattern_description($id);
				$detail['pat_id'] = $pat_id;
				if($id != NULL){
					$detail['file_list'] = get_dir_file_info("./images/PatternImg/".$detail['pat_id']."/".$id."/");
					$detail['design_file_list'] = get_dir_file_info("./images/DesignImg/".$detail['pat_id']."/".$id."/");
				}
				if(is_bool($detail['rows'])){
					$detail['rows'] = NULL;
				}
				$detail['tabs'] = ($tab == NULL)? 1:$tab;

				$this->load->view('templates/header', $data);
				$this->load->view('pattern_management/vDescriptionDetail', $detail);
				$this->load->view('templates/footer');	
			}
		}

		public function add_desc($pat_id){
			$this->form_validation->set_rules('desc_version', 'Pattern Version Number', 'required');
			if($this->form_validation->run() == FALSE){
				$this->session->set_flashdata('pattern_error', validation_errors());
				$this->index($pat_id);
			}
			else{
				$data = array(
					'desc_version' => (float)$this->input->post('desc_version'),
					'pattern_id' => $pat_id,
					'desc_assess_count' => 0,
					'desc_classification' => $this->input->post('desc_classification'),
					'desc_aka' => $this->input->post('desc_aka'),
					'desc_intent' => $this->input->post('desc_intent'),
					'desc_motivation' => $this->input->post('desc_motivation'),
					'desc_applicability' => $this->input->post('desc_applicability'),
					'desc_structure' => $this->input->post('desc_structure'),
					'desc_participants' => $this->input->post('desc_participants'),
					'desc_collaborations' => $this->input->post('desc_collaborations'),
					'desc_implementation' => $this->input->post('desc_implementation'),
					'desc_consequences' => $this->input->post('desc_consequences'),
					'desc_sample_code' => $this->input->post('desc_sample_code'),
					'desc_known_uses' => $this->input->post('desc_known_uses'),
					'desc_related_pattern' => $this->input->post('desc_related_pattern'),
					'is_UCD' => ($this->input->post('is_UCD') == 'Y')? true: false,
					'is_AD' => ($this->input->post('is_AD') == 'Y')? true: false,
					'is_CD' => ($this->input->post('is_CD') == 'Y')? true: false,
					'is_SD' => ($this->input->post('is_SD') == 'Y')? true: false,
					'is_BSM' => ($this->input->post('is_BSM') == 'Y')? true: false
				);
				$result = $this->mPatternDesc->create_pattern_description($data);

				if($result){
					$this->session->set_flashdata('desc_msg', 'Pattern description ( Version ID:'.$data['desc_version'].') has been added successfully!');
					$this->index($pat_id);
				}
				else{
					$this->session->set_flashdata('desc_detail_error', 'Pattern desciption ( Version ID:'.$data['desc_version'].') cannot be added!');
					$this->desc_detail($pat_id);
				}
			}
		}

		public function edit_desc($id, $pat_id){
			var_dump($this->input->post('is_UCD'));
			$data = array(
				'desc_version' => $this->input->post('desc_version'),
				'pattern_id' => $pat_id,
				'desc_assess_count' => 0,
				'desc_classification' => $this->input->post('desc_classification'),
				'desc_aka' => $this->input->post('desc_aka'),
				'desc_intent' => $this->input->post('desc_intent'),
				'desc_motivation' => $this->input->post('desc_motivation'),
				'desc_applicability' => $this->input->post('desc_applicability'),
				'desc_structure' => $this->input->post('desc_structure'),
				'desc_participants' => $this->input->post('desc_participants'),
				'desc_collaborations' => $this->input->post('desc_collaborations'),
				'desc_implementation' => $this->input->post('desc_implementation'),
				'desc_consequences' => $this->input->post('desc_consequences'),
				'desc_sample_code' => $this->input->post('desc_sample_code'),
				'desc_known_uses' => $this->input->post('desc_known_uses'),
				'desc_related_pattern' => $this->input->post('desc_related_pattern'),
				'is_UCD' => ($this->input->post('is_UCD') == 'Y')? true: false,
				'is_AD' => ($this->input->post('is_AD') == 'Y')? true: false,
				'is_CD' => ($this->input->post('is_CD') == 'Y')? true: false,
				'is_SD' => ($this->input->post('is_SD') == 'Y')? true: false,
				'is_BSM' => ($this->input->post('is_BSM') == 'Y')? true: false
			);
			$result = $this->mPatternDesc->update_pattern_description($data, $pat_id, $id);

			if($result){
				$this->session->set_flashdata('desc_msg', 'Pattern description ( Version ID:'.$data['desc_version'].') has been updated successfully!');
				$this->index($pat_id);
			}
			else{
				$this->session->set_flashdata('desc_detail_error', 'Pattern desciption ( Version ID:'.$data['desc_version'].') cannot be updated!');
				$this->desc_detail($pat_id, $id);
			}
		}

		public function delete_desc($pat_id, $id){
			if($this->mPatternDesc->delete_pattern_description($id)){
				$this->session->set_flashdata('desc_msg', 'Pattern description ( id:'.$id.') has been deleted successfully!');
				$path = "./images/PatternImg/$pat_id/$id";
				if(is_dir($path)){ 
					$this->delete_dir($path); 
				}
				$this->index($pat_id);
			}
			else{
				$this->session->set_flashdata('desc_error', 'Pattern desciption ( id:'.$id.') cannot be deleted!');
				$this->index($pat_id);
			}
		}

		public function upload_design($pat_id, $id){
			$path = "./images/DesignImg/$pat_id/$id/";
			if(!is_dir($path)){
				mkdir($path, 0777, true);
			}
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']     = '5000';
			$config['file_name'] = "Design_".$pat_id."_".$id."_".(count(get_dir_file_info($path))+1);

			$this->load->library('upload', $config);

			if(! $this->upload->do_upload('design_file')){
				$this->session->set_flashdata('design_upload_error', $this->upload->display_errors());
				$this->desc_detail($pat_id, $id, 2);
			}
			else{
				$this->session->set_flashdata('design_upload_msg', "file (".$this->upload->data()['file_name'].") is upload successfully!");
				$this->desc_detail($pat_id, $id, 2);
			}
		}
		public function delete_design($pat_id, $id, $name){
			$path = "./images/DesignImg/$pat_id/$id/$name";
			if(file_exists($path)){ 
				unlink($path);
			}
			$this->desc_detail($pat_id, $id, 2);
		}

		public function upload_img($pat_id, $id){
			$path = "./images/PatternImg/$pat_id/$id/";
			if(!is_dir($path)){
				mkdir($path, 0777, true);
			}
			$config['upload_path'] = $path;
			$config['allowed_types'] = 'gif|jpg|png';
			$config['max_size']     = '5000';
			$config['file_name'] = $pat_id."_".$id."_".(count(get_dir_file_info($path))+1);


			$this->load->library('upload', $config);

			if(! $this->upload->do_upload('img_file')){
				$this->session->set_flashdata('upload_error', $this->upload->display_errors());
				$this->desc_detail($pat_id, $id, 3);
			}
			else{
				$this->session->set_flashdata('upload_msg', "file (".$this->upload->data()['file_name'].") is upload successfully!");
				$this->desc_detail($pat_id, $id, 3);
			}
		}

		public function delete_img($pat_id, $id, $name){
			$path = "./images/PatternImg/$pat_id/$id/$name";
			if(file_exists($path)){
				unlink($path);
			}
			$this->desc_detail($pat_id, $id, 3);
		}

		function delete_dir($path){
			$dir = opendir($path);
			while(false !== ($file = readdir($dir))){
				if (( $file != '.' ) && ( $file != '..' )) {
		            $full = $path . '/' . $file;
		            if ( is_dir($full) ) {
		                rrmdir($full);
		            }
		            else {
		                unlink($full);
		            }
		        }
			}
			closedir($dir);
			rmdir($path);
		}
	}
?>