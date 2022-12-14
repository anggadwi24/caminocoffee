<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller 
{

	public function __construct()
	{
        parent::__construct();
		date_default_timezone_set('Asia/Makassar');
    	if($this->session->userdata('isLog')){
			$this->id = $this->session->userdata['isLog']['id'];
			$this->role = $this->session->userdata['isLog']['role'];
			$this->child = $this->session->userdata['isLog']['child'];
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
			$data['row'] = $this->model_app->getHRDWhere(array('hrd.id'=>$this->child))->row();
			$data['pegawai'] = $this->model_app->getUser();
			$data['absensiOn'] = $this->model_app->getAbsensiWhere(array('date'=>date('Y-m-d')));
			$data['scheduleToday'] = $this->model_app->getScheduleWhere(array('dates'=>date('Y-m-d')));
			$data['scheduleEmployee'] = $this->model_app->getScheduleWhere(array('dates'=>date('Y-m-d'),'status'=>'on'));
			$data['scheduleOn'] = $this->model_app->view_where('schedule',array('dates'=>date('Y-m-d'),'status'=>'on'));
			$data['scheduleOff'] = $this->model_app->view_where('schedule',array('dates'=>date('Y-m-d'),'status'=>'off'));
			$data['scheduleDC'] = $this->model_app->view_where('schedule',array('dates'=>date('Y-m-d'),'status'=>'dc'));
			$data['pegawaiDC'] = $this->db->query("SELECT * FROM pengajuan WHERE '".date('Y-m-d')."' BETWEEN start AND end AND approve = 'y'");
			
			$data['style'] = ['assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/css/fullcalendar.min.css'];
			$data['script'] = ['assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery-ui.min.js','assets/js/fullcalendar.min.js','assets/js/jquery.fullcalendar.js'];
			$data['ajax'] = ['assets/ajax/dashboard/hrd.js'];
			$data['pengajuan'] = $this->model_app->getPengajuanWhere(array('approve'=>'p'));

			$this->template->load('template','hrd/dashboard',$data);
		}else if($this->role == 'pegawai'){
			$data['title'] = 'DASHBOARD - '.title();
			$data['row'] = $this->model_app->getUserWhere(array('pegawai.id'=>$this->child))->row();
			$data['schedule'] = $this->model_app->view_where('schedule',array('dates'=>date('Y-m-d'),'pegawai_id'=>$this->child));
			$data['scheduleOn'] = $this->model_app->view_where('schedule',array('months'=>date('m'),'years'=>date('Y'),'pegawai_id'=>$this->child,'status'=>'on','shift_id !='=>null));
			$data['att'] = $this->db->query("SELECT COUNT(id) as total, COALESCE(SUM(duration),0) as durasi  FROM absensi WHERE pegawai_id = '".$this->child."' AND MONTH(date) = '".date('m')."' AND YEAR(date) = '".date('Y')."' ")->row();
			$data['scheduleOff'] = $this->model_app->view_where_ordering('schedule',array('months'=>date('m'),'years'=>date('Y'),'pegawai_id'=>$this->child,'status'=>'off','dates >'=>date('Y-m-d')),'dates','asc');

			$data['peganjuanAll'] = $this->model_app->view_where_ordering('pengajuan',array('pegawai_id'=>$this->child,'MONTH(start)'=>date('m'),'YEAR(start)'=>date('Y')),'start','desc');
			$data['absensiToday'] = $this->model_app->join_where2('absensi','schedule','schedule_id','id',array('dates'=>date('Y-m-d'),'absensi.pegawai_id'=>$this->child));
			$data['style'] = ['assets/css/select2.min.css','assets/css/bootstrap-datetimepicker.min.css','assets/css/fullcalendar.min.css'];
			$data['script'] = ['assets/js/select2.min.js','assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery-ui.min.js','assets/js/fullcalendar.min.js','assets/js/jquery.fullcalendar.js'];
			$data['ajax'] = ['assets/ajax/dashboard/pegawai.js'];
			$data['scheduleEmployee'] = $this->model_app->getScheduleWhere(array('dates'=>date('Y-m-d'),'schedule.pegawai_id !='=>$this->child,'status'=>'on'));
			$data['scheduleTommorow'] = $this->model_app->view_where('schedule',array('dates'=>date('Y-m-d',strtotime('+1 Day')),'pegawai_id'=>$this->child));
			$data['scheduleEmployeeTommorow'] = $this->model_app->getScheduleWhere(array('dates'=>date('Y-m-d',strtotime('+1 Day')),'schedule.pegawai_id !='=>$this->child,'status'=>'on'));

			
			$this->template->load('template','pegawai/dashboard',$data);
		}
		
	}
	
	function getCalendarPegawai(){
		if($this->input->method() == 'post'){
			$status = 200;
			$msg = null;
			$arr = null;
			$data = $this->model_app->view_where('schedule',array('pegawai_id'=>$this->child));
			if($data->num_rows() > 0 ){
				foreach($data->result() as $row){
					if($row->status == 'on' AND $row->shift_id != null){
						$shift = $this->model_app->view_where('shift',array('id'=>$row->shift_id));
						if($shift->num_rows() > 0){
							$shf = $shift->row();
							$title = $shf->name;
							$startTime = $row->dates.' '.$shf->schedule_in;
							$endTime = $row->dates.' '.$shf->schedule_out;
							
							$class = 'bg-info';
							$allDay = true;
							$condition = 'on';

						}else{
							$title = 'OFF';
							$startTime = $row->dates;
							$endTime = null;
						
							$class = 'bg-danger';
							$allDay = true;
							$condition = 'off';
						}
					}else if($row->status == 'off'){
						$title = 'OFF';
						$startTime = $row->dates;
						$endTime =null;
						$class = 'bg-danger';
						$allDay = true;
						$condition = 'off';
						
					}else{
						$pengajuan = $this->db->query("SELECT * FROM pengajuan WHERE pegawai_id = '".$this->child."' AND  '".$row->dates."' BETWEEN start AND end  AND approve ='y' ");
						if($pengajuan ->num_rows() >0){
							$peng = $pengajuan->row();
							$title = $peng->perihal;
							$startTime = $row->dates;
							$endTime = null;
							$class = 'bg-warning';
							$allDay = true;
							$condition = 'dc';
						}else{
							$title = 'DC/Cuti';
							$startTime = $row->dates;
							$endTime = null;
							$class = 'bg-warning';
							$allDay = true;
							$condition = 'dc';
						}
						

					}
					$arr[] = [
						'id'=>$row->id,
						'title'=>$title,
						'start'=>$startTime,
						'end'=>$endTime,
						'allDay'=>$allDay,
						'className'=>$class,
						'condition'=>$condition,
						
					];
				}
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'arr'=>$arr));
		}
	}
	
	function getCalendarHRD(){
		if($this->input->method() == 'post'){
			$status = 200;
			$msg = null;
			$arr = null;
			$data = $this->model_app->view('schedule');
			if($data->num_rows() > 0 ){
				foreach($data->result() as $row){
					$peg = $this->model_app->view_where('pegawai',array('id'=>$row->pegawai_id));
					if($peg->num_rows() > 0 ){
						$rows = $peg->row();
						if($row->status == 'on' AND $row->shift_id != null){
							$shift = $this->model_app->view_where('shift',array('id'=>$row->shift_id));
							if($shift->num_rows() > 0){
								$shf = $shift->row();
								$title = $rows->name.' - '.$shf->name;
								$startTime = $row->dates.' '.$shf->schedule_in;
								$endTime = $row->dates.' '.$shf->schedule_out;
								
								$class = 'bg-info';
								$allDay = true;
								$condition = 'on';
	
							}else{
								$title = $rows->name.' - '.'OFF';
								$startTime = $row->dates;
								$endTime = null;
							
								$class = 'bg-danger';
								$allDay = true;
								$condition = 'off';
							}
						}else if($row->status == 'off'){
							$title = $rows->name.' - '.'OFF';
							$startTime = $row->dates;
							$endTime =null;
							$class = 'bg-danger';
							$allDay = true;
							$condition = 'off';
							
						}else{
							$pengajuan = $this->db->query("SELECT * FROM pengajuan WHERE pegawai_id = '".$row->pegawai_id."' AND  '".$row->dates."' BETWEEN start AND end  AND approve ='y' ");
							if($pengajuan ->num_rows() >0){
								$peng = $pengajuan->row();
								$title = $rows->name.' - '.$peng->perihal;
								$startTime = $row->dates;
								$endTime = null;
								$class = 'bg-warning';
								$allDay = true;
								$condition = 'dc';
							}else{
								$title = 'DC/Cuti';
								$startTime = $row->dates;
								$endTime = null;
								$class = 'bg-warning';
								$allDay = true;
								$condition = 'dc';
							}
							
	
						}
						$arr[] = [
							'id'=>$row->id,
							'title'=>$title,
							'start'=>$startTime,
							'end'=>$endTime,
							'allDay'=>$allDay,
							'className'=>$class,
							'condition'=>$condition,
							
						];
					}
					
				}
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'arr'=>$arr));
		}
	}
}
