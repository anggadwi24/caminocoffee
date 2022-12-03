<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller 
{

	public function __construct()
	{
        parent::__construct();
    	if($this->session->userdata('isLog')){
			$this->id = $this->session->userdata['isLog']['id'];
			$this->role = $this->session->userdata['isLog']['role'];
		}else{
			redirect('auth');
		}	
	}
	public function index()
	{
		$data['style'] = ['assets/plugins/morris/morris.css'];
		$data['script'] = ['assets/plugins/raphael/raphael.min.js','assets/js/chart.js'];
		if($this->role == 'hrd'){
			$data['title'] = 'DASHBOARD - '.title();
			$this->template->load('template','hrd/dashboard',$data);
		}else if($this->role == 'pegawai'){

		}
		
	}
	
	function getCalendar(){
		if($this->input->method() == 'post'){
			$status = false;
			$msg = null;
			$arr = null;
			if($this->role == 'admin' OR $this->role == 'camat'){
				$data = $this->model_app->view_where('agenda',array('agenda_status !='=>'ditolak'));
				$status = true;
			}else if($this->role == 'pegawai'){
				$status = true;
				$data = $this->db->query("SELECT * FROM agenda WHERE agenda_validasi = 'y' AND agenda_validasi_by IS NOT NULL AND agenda_status != 'ditolak' ");
			}else{
				$status = false;
				$msg = 'Please re-login';
			}
			if($status == true){
				if($data->num_rows() > 0){
					foreach($data->result_array() as $row){
						if($row['agenda_status'] == 'done'){
							$className = 'bg-gradient2';
							$title = 'SELESAI - ';
						}else if($row['agenda_status'] == 'undone'){
							if($row['agenda_validasi'] == 'y' AND $row['agenda_validasi_by'] != NULL){
								$className = 'bg-gradient4';
								$title = 'VALIDASI - ';

							}else{
								$className = 'bg-gradient3';
								$title = 'BELUM VALIDASI - ';

							}
						}
						$arr[] = array('id'=>$row['agenda_id'],
                                
						'title'=>$title.$row['agenda_name'],
						'description'=>$row['agenda_name'].', '.$row['agenda_place'],
						'start'=>date_format( date_create($row['agenda_date_start']) ,"Y-m-d H:i:s"),
						'end'=>date_format( date_create($row['agenda_date_end']) ,"Y-m-d H:i:s"),
						'className'=>$className,
						'url'=>base_url('agenda/detail?id='.$row['agenda_id']),

						);
						
					}
				}
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'arr'=>$arr));
		}
	}
}
