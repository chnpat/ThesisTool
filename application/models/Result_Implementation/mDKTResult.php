<?php
	require_once "iAssessResult.php";
	/**
	* 
	*/
	class mDKTResult extends CI_Model implements iAssessResult
	{
		
		public function __construct(){
			parent::__construct();
			$this->load->model('mDBConnection', 'd');
		}

		public function create_result($pat_id, $ver, $ass_id, $data){
			$result = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 1,
				'score' => $this->calculate_result($data),
				'assessor_id' => $ass_id
				);
			$cond = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 1 AND assessor_id = ".$ass_id;
			$this->d->create('*', $cond, 'assess_result',$result);
			$result_id = $this->d->select('result_id', $cond, 'assess_result', 1)[0]['result_id'];
			foreach ($data as $key => $value) {
				$var_cond = "result_id = ".$result_id." AND variable_id = ".$key;
				$var = array(
					'result_id' => $result_id,
					'variable_id' => $key,
					'variable_score' => $value
					);
				$this->d->create('*', $var_cond, 'assess_result_detail', $var);
			}
		}

		public function get_metric(){
			$condition = "id = 1";
			$detail_cond = "metric_id = 1";
			$metr = $this->d->select('*', $condition, 'metric', 1)[0];
			$metr_detail = $this->d->select('id, variable_name, variable_description, variable_diagram', $detail_cond, 'metric_variable');
			$metr['detail'] = $metr_detail;
			return (is_bool($metr))?array():$metr;
		}

		public function get_result_all($pat_id, $ver=NULL){
			$condition = "pattern_id ='".$pat_id."' AND metric_id = 1";
			$condition = $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");
			$result = $this->d->select('*', $condition, 'assess_result');
			if($result != NULL){
				foreach ($result as $key => $value) {
					$detail_cond = "result_id = ".$value['result_id'];
					$detail = $this->d->select('*', $detail_cond, 'assess_result_detail');
					$result[$key]['detail'] = $detail;
				}
			}
			return $result;
		}

		public function get_result_no_detail($pat_id, $ver=NULL){
			$condition = "pattern_id ='".$pat_id."' AND metric_id = 1";
			$condition = $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");
			$result = $this->d->select('*', $condition, 'assess_result');
			return $result;
		}

		public function get_joined_detail_result($pat_id, $ver = NULL){
			$condition = "metric_id = 1";
			$condition = $condition.(($ver != NULL AND $ver != "")? " AND desc_version = ".(float)$ver:"");
			$result = $this->d->select_joined(	'metric_variable.id, metric_variable.metric_id, metric_variable.variable_name, metric_variable.variable_description, assess_result_detail.*', 
												$condition,
												'assess_result_detail',
												'metric_variable',
												'assess_result_detail.variable_id = metric_variable.id',
												'left' );
			return $result;
		}

		public function get_result($pat_id, $ver, $ass_id){
			$condition = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND assessor_id = ".$ass_id." AND metric_id = 1";
			$result = $this->d->select('result_id, score', $condition, 'assess_result', 1);
			return (is_bool($result))? NULL: $result[0];
		}

		public function get_result_detail($result_id){
			$condition = "$result_id = ".$result_id;
			$result = $this->d->select('variable_id, variable_score', $condition, 'assess_result_detail');
			return (is_bool($result))? NULL: $result;
		}

		public function update_result($pat_id, $ver, $ass_id, $data){
			$result = array(
				'pattern_id' => $pat_id,
				'desc_version' => $ver,
				'metric_id' => 1,
				'score' => $this->calculate_result($data),
				'assessor_id' => $ass_id
				);
			$cond = "pattern_id = '".$pat_id."' AND desc_version = ".(float)$ver." AND metric_id = 1 AND assessor_id = ".$ass_id;
			$this->d->update($cond, 'assess_result',$result);
			$result_id = $this->d->select('result_id', $cond, 'assess_result', 1)[0]['result_id'];
			foreach ($data as $key => $value) {
				$var_cond = "result_id = ".$result_id." AND variable_id = ".$key;
				$var = array(
					'result_id' => $result_id,
					'variable_id' => $key,
					'variable_score' => $value
					);
				$this->d->update($var_cond, 'assess_result_detail', $var);
			}
		}

		function calculate_result($data){
			$sum = 0;
			foreach ($data as $key => $value) {
				$sum = $sum + $value;
			}
			$Ca = 33;
			return ($sum/$Ca)*100;
		}
	}
?>