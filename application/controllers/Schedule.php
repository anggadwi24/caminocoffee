<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller 
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
    public function index(){
        $data['title'] = 'SCHEDULE - '.title();
		$data['page'] = 'Schedule';
        if($this->role == 'hrd'){
            $data['right'] = ' <a href="'.base_url('schedule/add').'" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah</a>
							';
        }else{
            $data['right'] = '';
        }
        $data['bulan'] = [
            ['bulan'=>'Januari','code'=>'01'],
            ['bulan'=>'Februari','code'=>'02'],
            ['bulan'=>'Maret','code'=>'03'],
            ['bulan'=>'April','code'=>'04'],
            ['bulan'=>'Mei','code'=>'05'],
            ['bulan'=>'Juni','code'=>'06'],
            ['bulan'=>'Juli','code'=>'07'],
            ['bulan'=>'Agustus','code'=>'08'],
            ['bulan'=>'September','code'=>'09'],
            ['bulan'=>'Oktober','code'=>'10'],
            ['bulan'=>'November','code'=>'11'],
            ['bulan'=>'Desember','code'=>'12'],


        ];
        $years = $this->input->get('year');
        $months = $this->input->get('month');
        if($years == null){
            $years = date('Y');
        }
        if($months == null){
            $months = date('m');
        }
        $start = $years.'-'.$months.'-01';
        $end = $years.'-'.$months.'-d';

        $start = date($start);
        $end = date($end);
        $end = date('Y-m-t',strtotime($end));
        $endSch = date('Y-m-t',strtotime($end));

        $begin = new DateTime($start);
        $end   = new DateTime($end);
        $data['begin'] = $begin;
        $data['end'] = $end;
        $data['start_date'] = $begin;
        $data['end_date'] = $end;
        $data['months'] = $months;
        $data['years'] = $years;
        $data['employee'] =$this->model_app->join_order2('pegawai','users','users_id','id','pegawai.id','desc');
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Schedule</li>';
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
        $data['record'] = $this->model_app->view_order('shift','id','desc');
        $this->template->load('template','hrd/schedule',$data);
    }

    public function add(){
        $data['title'] = 'SCHEDULE - '.title();
		$data['page'] = 'Schedule';
       
        $data['right'] = '';
       
        $data['bulan'] = [
                        ['bulan'=>'Januari','code'=>'01'],
                        ['bulan'=>'Februari','code'=>'02'],
                        ['bulan'=>'Maret','code'=>'03'],
                        ['bulan'=>'April','code'=>'04'],
                        ['bulan'=>'Mei','code'=>'05'],
                        ['bulan'=>'Juni','code'=>'06'],
                        ['bulan'=>'Juli','code'=>'07'],
                        ['bulan'=>'Agustus','code'=>'08'],
                        ['bulan'=>'September','code'=>'09'],
                        ['bulan'=>'Oktober','code'=>'10'],
                        ['bulan'=>'November','code'=>'11'],
                        ['bulan'=>'Desember','code'=>'12'],


        ];
        
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item "><a href="'.base_url('schedule').'">Schedule</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Add</li>';
        $data['ajax'] = ['assets/ajax/schedule/add.js'];
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
        $data['record'] = $this->model_app->join_order2('pegawai','users','users_id','id','pegawai.id','desc');
        $this->template->load('template','hrd/schedule-add',$data);
        
    }
    public function store(){
        if($this->input->method() == 'post'){
            $this->form_validation->set_rules('pegawai','Pegawai','required');
            $this->form_validation->set_rules('months','Bulan','required');
            $this->form_validation->set_rules('years','Tahun','required');
            $this->form_validation->set_rules('dates[]','Tanggal','required');
            $this->form_validation->set_rules('shift[]','Shift','required');
            if($this->form_validation->run() == false){
                $status = 201;
                $msg = validation_errors();
           
                
            }else{
                $username = $this->input->post('pegawai');
                $months = $this->input->post('months');
                $years = $this->input->post('years');
                $dates = $this->input->post('dates');
                $shift = $this->input->post('shift');

                $row = $this->model_app->getUserWhere(array('username'=>$username));
                if($row->num_rows() > 0){
                    $row = $row->row();
                    for($a=0;$a<count($shift);$a++){
                        $check = $this->model_app->view_where('schedule',array('pegawai_id'=>$row->pegawai_id,'dates'=>$dates[$a],'months'=>$months,'years'=>$years));
                        if($check->num_rows() > 0){
                            $rows = $check->row();
                            if($shift[$a] == 'off' OR $shift[$a] == 'dc'){
                                $shift_id = null;
                                $status = $shift[$a];
                            }else{
                                $status = 'on';
                                $shift_id = $shift[$a];
                            }
                            $data = array('shift_id'=>$shift_id,'status'=>$status);
                            $this->model_app->update('schedule',$data,array('id'=>$rows->id));
                        }else{
                            if($shift[$a] == 'off' OR $shift[$a] == 'dc'){
                                $shift_id = null;
                                $status = $shift[$a];
                            }else{
                                $status = 'on';
                                $shift_id = $shift[$a];
                            }
                            $data = array('shift_id'=>$shift_id,'status'=>$status,'pegawai_id'=>$row->pegawai_id,'months'=>$months,'years'=>$years,'dates'=>$dates[$a],'created_by'=>$this->id);
                            $this->model_app->insert('schedule',$data);
                        }
                        
                    }
                    $status = 200;
                    $msg = 'Jadwal berhasil disimpan';
                }else{
                    $status = 201;
                    $msg = 'Pegawai tidak ditemukan';
                }


            }
            echo json_encode(['status'=>$status,'msg'=>$msg]);

        }else{
            redirect('schedule');
        }
    }
    public function getEmployee(){
        if($this->input->method() == 'post'){
            $output = null;
            $username = $this->input->post('pegawai');
            $bulan = $this->input->post('month');
            $year =$this->input->post('year');
            $this->form_validation->set_rules('pegawai','Pegawai','required');
            $this->form_validation->set_rules('month','Bulan','required');
            $this->form_validation->set_rules('year','Tahun','required');
        
        







            if($this->form_validation->run() == false){
                $status = 201;
                $msg = validation_errors();
           
                
            }else{
                $row = $this->model_app->getUserWhere(array('username'=>$username));
                if($row->num_rows() > 0){
                    $row = $row->row();
                    $start = $year.'-'.$bulan.'-01';
                    $end = $year.'-'.$bulan.'-d';

                    $start = date($start);
                    $end = date($end);
                    $end = date('Y-m-t',strtotime($end));
                    $begin = new DateTime($start);
                    $end   = new DateTime($end);
                    $status = 200;
                    $msg = null;
                  
                    
                  
                    
            
                    $output .= '<form id="formStore"><div class="table-responsive">
                                <table class="table table-striped custom-table table-nowrap mb-0">
                                    <tbody>';
                    for($i = $begin; $i <= $end; $i->modify('+1 day')){
                        $shift = $this->model_app->view_order('shift','schedule_in','asc');
                        $shiftOption = null;
                        if($shift->num_rows() > 0){
                            foreach($shift->result() as $shf){
                                
                                $checkHave = $this->model_app->view_where('schedule',array('pegawai_id'=>$row->id,'dates'=>$i->format('Y-m-d'),'months'=>$bulan,'years'=>$year,'shift_id'=>$shf->id));
                                if($checkHave->num_rows() > 0){
                                    $shiftOption .= '<option selected value="'.$shf->id.'">'.$shf->name.' '.date('H:i',strtotime($shf->schedule_in)).' - '.date('H:i',strtotime($shf->schedule_out)).'</option>';

                                }else{
                                     $shiftOption .= '<option value="'.$shf->id.'">'.$shf->name.' '.date('H:i',strtotime($shf->schedule_in)).' - '.date('H:i',strtotime($shf->schedule_out)).'</option>';

                                }
                            }
                        }
                        $shiftOption .= '<option value="off">Off</option><option value="dc">Cuti/DC</option>';
                        $output .= '<tr>
                                        <th>'.hari($i->format('Y-m-d')).', '.fulldate($i->format("Y-m-d")).' </th>
                                        <td><select class="form-control" name="shift[]">'.$shiftOption.'</select></td>
                                       
                                      
                                        <input type="hidden" name="dates[]" value="'.$i->format('Y-m-d').'">



                                    </tr>';
                    }
                   
                    $output .= '</tbody>';
                  
                    $output .= '</table></div>  <input type="hidden" name="months" value="'.$bulan.'">
                    <input type="hidden" name="years" value="'.$year.'"> <input type="hidden" name="pegawai" value="'.$username.'"><button class="btn btn-primary float-right mt-5">Update</button></form>';
                    
                }else{
                    $status = 201;
                    $msg = 'Pegawai tidak ditemukan';
                }
              
            }
            echo json_encode(['status'=>$status,'msg'=>$msg,'output'=>$output]);
        }else{
            redirect('schedule');
        }
    }
}