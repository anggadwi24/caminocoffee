<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Schedule extends CI_Controller 
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
    public function index(){
        $data['title'] = 'JADWAL - '.title();
		$data['page'] = 'Jadwal';
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
        $data['difference'] = daysDifference($endSch,$start)+1;
      
        $data['months'] = $months;
        $data['years'] = $years;
        $data['employee'] =$this->model_app->getUser();
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Jadwal</li>';
        $data['ajax'] = ['assets/ajax/schedule/index.js'];
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
        $data['record'] = $this->model_app->view_order('shift','id','desc');
        $this->template->load('template','hrd/schedule',$data);
    }

    public function add(){
        $data['title'] = 'JADWAL - '.title();
		$data['page'] = 'Jadwal';
       
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
		$data['breadcrumb'] .= '<li class="breadcrumb-item "><a href="'.base_url('schedule').'">Jadwal</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Tambah</li>';
        $data['ajax'] = ['assets/ajax/schedule/add.js'];
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
        $data['record'] = $this->model_app->join_where_order2('pegawai','users','users_id','id',array('active'=>'y'),'pegawai.id','desc');
        $this->template->load('template','hrd/schedule-add',$data);
        
    }
    public function update(){
        if($this->input->method() == 'post'){
            if($this->role == 'hrd'){
                $id = decode($this->input->post('id'));
                $row = $this->model_app->view_where('schedule',array('id'=>$id));
                if($row->num_rows() > 0){
                    $row = $row->row();
                    $abs = $this->model_app->view_where('absensi',array('pegawai_id'=>$row->pegawai_id,'schedule_id'=>$row->id));
                    if($abs->num_rows() > 0){
                        $this->session->set_flashdata('error','Pegawai sudah melakukan absensi pada jadwal ini');
                        redirect('schedule');
                    }else{
                        $pegawai = $this->model_app->join_where_order2('pegawai','users','users_id','id',array('pegawai.id'=>$row->pegawai_id),'pegawai.id','desc');
                        if($pegawai->num_rows() > 0){
                           $shift = $this->input->post('shift');
                           if($shift == 'off'){
                                $data = array('status'=>'off','shift_id'=>null);
                                $this->model_app->update('schedule',$data,array('id'=>$id));
                                $this->session->set_flashdata('success','Jadwal berhasil diubah');
                                redirect('schedule');
                           }elseif($shift == 'dc'){
                                $data = array('status'=>'dc','shift_id'=>null);
                                $this->model_app->update('schedule',$data,array('id'=>$id));
                                $this->session->set_flashdata('success','Jadwal berhasil diubah');
                                redirect('schedule');
                           }else{
                                $check = $this->model_app->view_where('shift',array('id'=>$shift));
                                if($check->num_rows() > 0){
                                    $data = array('status'=>'on','shift_id'=>$shift);
                                    $this->model_app->update('schedule',$data,array('id'=>$id));
                                    $this->session->set_flashdata('success','Jadwal berhasil diubah');
                                    redirect('schedule');
                                }else{
                                    $this->session->set_flashdata('error','Shift tidak ditemukan');
                                    redirect('schedule/edit?sch='.encode($id));
                                }
                           }
                          
                        }else{
                            $this->session->set_flashdata('error','Pegawai tidak ditemukan');
                            redirect('schedule');
                        }
                    }
                   
                }else{
                    $this->session->set_flashdata('error','Jadwal tidak ditemukan');
                    redirect('schedule');
                }
            }else{
                $this->session->set_flashdata('error','Anda tidak bisa mengakses halaman ini');
                redirect('schedule');
            }
        }else{
            redirect('schedule');
        }
    }
    public function edit(){
        if($this->role == 'hrd'){
            $id = decode($this->input->get('sch'));
            $row = $this->model_app->view_where('schedule',array('id'=>$id));
            if($row->num_rows() > 0){
                $row = $row->row();
                $abs = $this->model_app->view_where('absensi',array('pegawai_id'=>$row->pegawai_id,'schedule_id'=>$row->id));
                if($abs->num_rows() > 0){
                    $this->session->set_flashdata('error','Pegawai sudah melakukan absensi pada jadwal ini');
                    redirect('schedule');
                }else{
                    $pegawai = $this->model_app->join_where_order2('pegawai','users','users_id','id',array('pegawai.id'=>$row->pegawai_id),'pegawai.id','desc');
                    if($pegawai->num_rows() > 0){
                        $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
                        $data['breadcrumb'] .= '<li class="breadcrumb-item "><a href="'.base_url('schedule').'">Jadwal</a></li>';
                        $data['breadcrumb'] .= '<li class="breadcrumb-item active">Edit</li>';
                        $data['page'] = 'Jadwal';
                        $data['title'] = 'JADWAL - '.title();
                        $data['right'] = '';
                        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                        $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                        $data['row'] = $row;
                        $data['shift'] = $this->model_app->view_order('shift','schedule_in','asc');
                        $data['rows'] = $pegawai->row();
                        $this->template->load('template','hrd/schedule-edit',$data);
                    }else{
                        $this->session->set_flashdata('error','Pegawai tidak ditemukan');
                        redirect('schedule');
                    }
                }
               
            }else{
                $this->session->set_flashdata('error','Jadwal tidak ditemukan');
                redirect('schedule');
            }
        }else{
            $this->session->set_flashdata('error','Anda tidak bisa mengakses halaman ini');
            redirect('schedule');
        }
        
                             
    }
    public function detail(){
        if($this->input->method() == 'post'){
            $id = decode($this->input->post('id'));
            $username = $this->input->post('username');
            $date = $this->input->post('date');

            $output = null;
            $row = $this->model_app->getUserWhere(array('username'=>$username));
            if($row->num_rows() > 0){   
                $row = $row->row();
                if( $row->photo != ''){
                    if(file_exists('upload/user/'.$row->photo) ){
                        $img= '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';

                    }else{
                        $img= '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                    }
                    
                }else{
                    $img= '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                }
                $profil = '<div class="col-md-6">
                                <div class="profile-widget">
                                    <div class="'.base_url('pegawai/detail/'.$row->username).'">
                                        <a href="'.base_url('pegawai/detail/'.$row->username).'" class="avatar">'.$img.'</a>
                                    </div>
                                    
                                <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="'.base_url('pegawai/detail/'.$row->username).'">'.$row->name.'</a></h4>
                             
                                <div class="small text-muted">'.ucfirst($row->position).'</div>

                               

                            </div>
                            
                        </div>';
                $status = 200;
                $msg= null;
                if($id != 'not'){
                    $schedule = $this->model_app->view_where('schedule',array('id'=>$id,'pegawai_id'=>$row->pegawai_id));
                    if($schedule->num_rows() > 0 ){
                        $sch = $schedule->row();
                        if($sch->status == 'on' AND $sch->shift_id != null){
                            $shift = $this->model_app->view_where('shift',array('id'=>$sch->shift_id));
                            if($shift->num_rows() > 0){
                                $shf = $shift->row();
                                $absensi = $this->model_app->view_where('absensi',array('schedule_id'=>$sch->id,'pegawai_id'=>$row->pegawai_id));
                                if($absensi->num_rows() > 0){
                                    $abs = $absensi->row();
                                    if($abs->absen_out == null){
                                        $out = 'Belum absen out';
                                        $absen_out = date('H:i');
                                    }else{
                                        $out = tanggalwaktu($abs->absen_out);
                                        $absen_out = date('H:i',strtotime($abs->absen_out));

                                    }
                                    $absen_in = date('H:i',strtotime($abs->absen_in));
                                    $duration = selisihJam($absen_in,$absen_out);
                                    $overtime = $this->model_app->view_where('overtime',array('absensi_id'=>$abs->id,'pegawai_id'=>$row->pegawai_id));
                                    if($overtime->num_rows() > 0){
                                        $ovt = $overtime->row();
                                        $overtimeText =  '<div class="stats-box">
                                                            <p>Overtime</p>
                                                            <h6> '.$ovt->overtime.' jam</h6>
                                                        </div>';
                                    }else{
                                        $overtimeText =  '';
                                    }
                                    if($this->role == 'hrd'){
                                        $act = '<a class="float-right text-dark" href="'.base_url('absensi/detail?absensi='.encode($abs->id)).'"><i class="fa fa-eye m-r-5"></i></a>';
                                    }else{
                                        $act = '';
                                    }
                                    $output .= $profil.'
                                    <div class="col-md-6">
                                        <div class="card recent-activity">
                                            <div class="card-body">
                                                <h5 class="card-title">Absensi <small class="text-muted">'.fulldate($date).'</small>'.$act.'</h5>
                                                <div class="punch-det">
                                                    <h6>Shift</h6>
                                                    <p>'.$shf->name.' '.date('H:i',strtotime($shf->schedule_in)).' - '.date('H:i',strtotime($shf->schedule_out)).'</p>
                                                </div>
                                                <div class="punch-det">
                                                    <h6>Absen In</h6>
                                                    <p>'.tanggalwaktu($abs->absen_in).'</p>
                                                </div>
                                                <div class="punch-info">
													<div class="punch-hours">
														<span>'.$duration.' jam</span>
													</div>
												</div>
                                                <div class="punch-det">
                                                    <h6>Absen Out</h6>
                                                    <p>'.$out.'</p>
                                                </div>
                                                '.$overtimeText.'
                                            </div>
                                        </div>
                                    </div>';
                                }else{
                                    if($sch->dates >= date('Y-m-d') AND $this->role == 'hrd'){
                                        $title = 'Belum ada absensi';
                                        $act = '<a class="float-right text-dark" href="'.base_url('schedule/edit?sch='.encode($sch->id)).'"><i class="fa fa-pencil m-r-5"></i></a>';
                                    }else{
                                        $title = 'Tidak absen';
                                        $act = '';
                                    }
                                    $output .= $profil.'
                                    <div class="col-md-6">
                                        <div class="card recent-activity">
                                            <div class="card-body">
                                                <h5 class="card-title">Absensi <small class="text-muted">'.fulldate($date).'</small>'.$act.'</h5>
                                                <div class="punch-det">
                                                
                                                    <p>'.$title.'</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>';
                                }
                              
                            }else{
                                $output .= $profil.'
                                <div class="col-md-6">
                                    <div class="card recent-activity">
                                        <div class="card-body">
                                            <h5 class="card-title">Shift tidak ditemukan <small class="text-muted">'.fulldate($date).'</small></h5>
                                        
                                        </div>
                                    </div>
                                </div>';
                            }
                        }else if($sch->status == 'off' AND $sch->shift_id == null){
                            if($sch->dates >= date('Y-m-d') AND $this->role == 'hrd'){
                                $act = '<a class="float-right text-dark" href="'.base_url('schedule/edit?sch='.encode($sch->id)).'"><i class="fa fa-edit m-r-5"></i></a>';
                            }else{
                                $act = '';
                            }
                            $output .= $profil.'
                                    <div class="col-md-6">
                                        <div class="card recent-activity">
                                            <div class="card-body">
                                                <h5 class="card-title">Libur <small class="text-muted">'.fulldate($date).'</small>'.$act.'</h5>
                                            
                                            </div>
                                        </div>
                                    </div>';
                        
                        }else{
                            if($sch->dates >= date('Y-m-d') AND $this->role == 'hrd'){
                                $act = '<a class="float-right text-dark" href="'.base_url('schedule/edit?sch='.encode($sch->id)).'"><i class="fa fa-edit m-r-5"></i></a>';
                            }else{
                                $act = '';
                            }
                            $pengajuan = $this->db->query("SELECT * FROM pengajuan WHERE pegawai_id = '".$row->pegawai_id."' AND '".$sch->dates."' BETWEEN start AND end");
                            if($pengajuan->num_rows() > 0){
                                $peng = $pengajuan->row();
                               
                               
                                $output .= $profil.'
                                <div class="col-md-6">
                                    <div class="card recent-activity">
                                        <div class="card-body">
                                            <h5 class="card-title">Cuti/DC <small class="text-muted">'.fulldate($date).'</small>'.$act.'</h5>
                                            <div class="punch-det">
                                                
                                                <p>'.$peng->perihal.'</p>
                                            </div>
                                            <div class="statistics">
                                            <div class="row">
                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box">
                                                        <p>Dari</p>
                                                        <h6>'.fulldate($peng->start).'</h6>
                                                    </div>
                                                </div>
                                                <div class="col-md-6 col-6 text-center">
                                                    <div class="stats-box">
                                                        <p>Sampai</p>
                                                        <h6>'.fulldate($peng->end).'</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                </div>';
                            }else{
                                $output .= $profil.'
                                            <div class="col-md-6">
                                                <div class="card recent-activity">
                                                    <div class="card-body">
                                                        <h5 class="card-title">Cuti/DC <small class="text-muted">'.fulldate($date).'</small>'.$act.'</h5>
                                                        <div class="punch-det">
                                                            
                                                            <p>Tidak ada keterangan</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>';
                            }
                        }
                    }else{
                        $output .= $profil.'
                                    <div class="col-md-6">
                                        <div class="card recent-activity">
                                            <div class="card-body">
                                                <h5 class="card-title">Jadwal tidak ditemukan <small class="text-muted">'.fulldate($date).'</small></h5>
                                            
                                            </div>
                                        </div>
                                    </div>';
                    }
                }else{
                  
                    $output .= $profil.'
                            <div class="col-md-6">
                                <div class="card recent-activity">
                                    <div class="card-body">
                                        <h5 class="card-title">Tidak ada jadwal <small class="text-muted">'.fulldate($date).'</small></h5>
                                    
                                    </div>
                                </div>
                            </div>';
                }
            }else{
                $status = 201;
                $msg = 'Pegawai tidak ditemukan';
            }
            echo json_encode(['status'=>$status,'msg'=>$msg,'output'=>$output]);
        }else{
            redirect('schedule');
        }
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