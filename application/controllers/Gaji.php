<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gaji extends CI_Controller 
{
	public function __construct()
	{
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
    	if($this->session->userdata('isLog')){
			$this->id = $this->session->userdata['isLog']['id'];
			$this->child = $this->session->userdata['isLog']['child'];
			$this->username = $this->session->userdata['isLog']['username'];


			$this->role = $this->session->userdata['isLog']['role'];
		}else{
			redirect('auth');
		}	
	}
    public function index(){
        $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
        $data['breadcrumb'] .= '<li class="breadcrumb-item active">Gaji</li>';
        $data['ajax'] = ['assets/ajax/schedule/index.js'];
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
        $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
        $data['title'] = 'GAJI - '.title();
        $data['page'] = 'Gaji';
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
        if($this->role == 'hrd'){
          
           
            $data['right'] = ' <a href="'.base_url('gaji/add').'" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah</a>
                                ';
           
          
            $years = $this->input->get('year');
            $months = $this->input->get('month');
            $employee = $this->input->get('pegawai');
            if($years == null){
                $years = date('Y');
            }
            if($months == null){
                $months = date('m');
            }
            if($employee == null){
                $employee == 'all';
              
            }else{
                $employee == $employee;
            }
          
            $data['month'] = $months;
            $data['year'] = $years;
            $data['pegawai'] =$this->model_app->getUser();
           
            $data['record'] = $this->model_app->getGajiPegawai($employee,$months,$years);
            $this->template->load('template','hrd/gaji',$data);
        }else{
            $years = $this->input->get('year');
            $months = $this->input->get('month');
            $data['right'] = '';
          
            if($years == null){
                $years = date('Y');
            }
            if($months == null){
                $months = date('m');
            }
            $data['month'] = $months;
            $data['year'] = $years;
            $data['row'] = $this->model_app->getGajiPegawai($this->username,$months,$years);
            $this->template->load('template','pegawai/gaji',$data);
        }
    }
    public function add(){
        if($this->role == 'hrd'){
            $data['title'] = 'GAJI - '.title();
            $data['page'] = 'Gaji';
           
            $data['right'] = '';
            
    
            $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
            $data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('gaji').'">Gaji</a></li>';
            $data['breadcrumb'] .= '<li class="breadcrumb-item active">Tambah</li>';
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
            $data['year'] = date('Y');
            $data['pegawai'] =$this->model_app->getUser();
            $data['ajax'] = ['assets/ajax/gaji/add.js'];
            $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
            $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/plugins/input-mask/jquery.inputmask.bundle.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
            $this->template->load('template','hrd/gaji-add',$data);
        }else{
            redirect('gaji');
        }
    }
    public function store(){
        if($this->input->method() == 'post'){
            $redirect = null;
            if($this->role == 'hrd'){
                $this->form_validation->set_rules('username','Pegawai','required');
                $this->form_validation->set_rules('month','Bulan','required');
                $this->form_validation->set_rules('year','Tahun','required');
                $this->form_validation->set_rules('pokok','Gaji Pokok','required');
                $this->form_validation->set_rules('meal','Tunjangan Makan','required');
                $this->form_validation->set_rules('overtime','Tunjangan Overtime','required');
                $this->form_validation->set_rules('insentif','Insentif','required');
                $this->form_validation->set_rules('bpjs','Tunjangan Bpjs','required');
                $this->form_validation->set_rules('potongan','Potongan','required');
                 
                if($this->form_validation->run() == false){
                    $status = 201;
                    $msg = validation_errors();
               
                    
                }else{
                    $username = $this->input->post('username');
                    $month = $this->input->post('month');
                    $year = $this->input->post('year');
                    $pokok = numberReplace($this->input->post('pokok'));
                    $meal = numberReplace($this->input->post('meal'));
                    $overtime = numberReplace($this->input->post('overtime'));
                    $insentif = numberReplace($this->input->post('insentif'));
                    $bpjs = numberReplace($this->input->post('bpjs'));
                    $potongan = numberReplace($this->input->post('potongan'));

                    $row = $this->model_app->getUserWhere(array('username'=>$username));
                    if($row->num_rows() > 0){
                        $row = $row->row();
                        $gaji = $this->model_app->view_where('slip',array('pegawai_id'=>$row->pegawai_id,'months'=>$month,'years'=>$year));
                        if($gaji->num_rows() > 0){
                            $status = 201;
                            $msg = 'Gaji sudah diinput pada '.bulan($month).' '.date('Y');
                        }else{
                            $total = ($pokok+$meal+$overtime+$insentif+$bpjs)-$potongan;
                            $data = ['pegawai_id'=>$row->pegawai_id,'years'=>$year,'months'=>$month,'basic_salary'=>$pokok,'meal_salary'=>$meal,'overtime_pay'=>$overtime,'incentive'=>$insentif,'bpjs'=>$bpjs,'cut_salary'=>$potongan,'total_salary'=>$total];
                            $id =  $this->model_app->insert_id('slip',$data);
                            $redirect = base_url('gaji/detail?slip='.encode($id));
                            $status = 200;
                            $msg = 'Gaji pada '.bulan($month).' '.date('Y').' berhasil diinput';
                            
                        }
                    }else{
                        $status = 200;
                        $msg = 'Pegawai tidak ditemukan';
                    }
                }
                echo json_encode(['status'=>$status,'msg'=>$msg,'redirect'=>$redirect]);
            }else{
               $status = 201;
               $msg  = 'Unauthorize access';
            }
        }else{
            redirect('gaji/add');
        }
    }
    public function getEmployee(){
        if($this->input->method() == 'post'){
            $output = null;
            if($this->role == 'hrd'){
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
                        $rows = $this->model_app->getGajiPegawai($username,$bulan,$year);
                        if($rows->num_rows() > 0){
                            $status = 201;
                            $msg = 'Gaji pegawai sudah diinput';
                        }else{
                            $date = '01-'.$bulan.'-'.$year;
                           

                            $dateTime = new DateTime($date);

                            $month = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('m');
                            $years = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('Y');
                            

                            // echo $lastMonth; // 'December 2013'
                            // $dates1 = date('m-Y',strtotime($monthY));
                            // $dates = date("d-m-Y", strtotime("-1 month"));
                        
                            // $month = date('m',strtotime($dates));
                            
                            // $years = date('Y',strtotime($dates));
                            // $month = date('m',strtotime($bulan,strtotime('-1 Months')));
                            // if($month == '01'){
                            //     $month = 12;
                            // }else{
                            //     $month = $month;
                            // }
                            // $years = date('Y',strtotime($year,strtotime('-1 Months')));

                            $schedule = $this->model_app->view_where('schedule',array('months'=>$month,'years'=>$years,'pegawai_id'=>$row->pegawai_id));
                            if($schedule->num_rows() > 0){
                                $lastMonth = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' ")->row();
                                $lastMonthResult = $this->db->query("SELECT coalesce(SUM(b.duration),0) as durasi, COUNT(b.id) as totalAbsen FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on'")->row();
                                $percentHours = round($lastMonthResult->durasi/($lastMonth->total*8)*100,1);
                                $hours = $lastMonth->total*8;
                                $terlambat = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyIn FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_in ='n' ")->row();
                                if($terlambat->totalEarlyIn > 0){
                                    $percentTerlambat = round($terlambat->totalEarlyIn/$lastMonthResult->totalAbsen*100,0);
                                
                                }else{
                                    $percentTerlambat= 0;
                                }
                                $pulang = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyOut FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_out ='y' ")->row();
                                if($pulang->totalEarlyOut > 0){
                                    $percentPulang = round($pulang->totalEarlyOut/$lastMonthResult->totalAbsen*100,0);
                                
                                }else{
                                    $percentPulang =0;
                                
                                }
                                $percentHari = round($lastMonthResult->totalAbsen/$lastMonth->total*100,2);
                                $off = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='off' ")->row();
                                $cuti = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='dc' ")->row();

                                $overtime = $this->db->query("SELECT COALESCE(SUM(overtime),0) as ovt FROM absensi a JOIN overtime b ON a.id = b.absensi_id WHERE a.pegawai_id = $row->pegawai_id AND MONTH(a.date) = '".$month."' AND YEAR(a.date) = '".$years."' ")->row();
                                if( $row->photo != ''){
                                    if(file_exists('upload/user/'.$row->photo) ){
                                        $img =  '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';
    
                                    }else{
                                        $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                                    }
                                    
                                }else{
                                    $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                                }
                                $output .= '<div class="col-md-6">
                                               
                                                <div class="card att-statistics">
                                                    <div class="card-body pb-5">
                                                        <h5 class="card-title">Statistik '.bulan($month).'</h5>
                                                        <div class="row justify-content-center">
                                                            <div class="profile-widget-one">
                                                                <div class="profile-img">
                                                                    <a href="'.base_url('pegawai/detail/'.$row->username).'" class="avatar">
                                                                    '.$img.'
                                                                    </a>
                                                                </div>
                                                               
                                                                <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="'. base_url('pegawai/edit/'.$row->username).'" >'. ucwords($row->name).'</a></h4>
                                                            
                                                              
                                            
                                                            </div>
                                                        </div>
                                                        <div class="stats-list mb-2 " style="height:100% !important">
                                                            <div class="stats-info">
                                                                <p>Hari Kerja <strong>'.$lastMonthResult->totalAbsen.' / '.$lastMonth->total.' hari</small></strong></p>
                                                        
                                                                <div class="progress">
                                                                    <div class="progress-bar '.progressColor($percentHari).'" role="progressbar" style="width: '.$percentHari.'%" aria-valuenow="'.$percentHari.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="stats-info">
                                                                <p>Jam Kerja <strong>'.round($lastMonthResult->durasi,2).' / '.$hours.' jam</small></strong></p>
                                                           
                                                                <div class="progress">
                                                                    <div class="progress-bar '.progressColor($percentHours).'" role="progressbar" style="width: '.$percentHours.'%" aria-valuenow="'.$percentHours.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="stats-info">
                                                                <p>Total Terlambat <strong>'.$terlambat->totalEarlyIn.' / '.$lastMonthResult->totalAbsen.' hari</small></strong></p>
                                                           
                                                                <div class="progress">
                                                                    <div class="progress-bar '.progressColor($percentTerlambat).'" role="progressbar" style="width: '.$percentTerlambat.'%" aria-valuenow="'.$percentTerlambat.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="stats-info">
                                                                <p>Total Mendahului Shift Pulang <strong>'.$pulang->totalEarlyOut.' / '.$lastMonthResult->totalAbsen.' hari</small></strong></p>
                                                           
                                                                <div class="progress">
                                                                    <div class="progress-bar '.progressColor($percentPulang).'" role="progressbar" style="width: '.$percentPulang.'%" aria-valuenow="'.$percentPulang.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="stats-info">
                                                                <p>Overtime<strong>'.$overtime->ovt.' jam</small></strong></p>
                                                        
                                                                <div class="progress">
                                                                    <div class="progress-bar " role="progressbar" style="width: 100%" aria-valuenow="'.$overtime->ovt.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="stats-info">
                                                                <p>Cuti/DC<strong>'.$cuti->total.' hari</small></strong></p>
                                                        
                                                                <div class="progress">
                                                                    <div class="progress-bar " role="progressbar" style="width: 100%" aria-valuenow="'.$cuti->total.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                            <div class="stats-info">
                                                                <p>Libur<strong>'.$off->total.' hari</small></strong></p>
                                                        
                                                                <div class="progress">
                                                                    <div class="progress-bar " role="progressbar" style="width: 100%" aria-valuenow="'.$off->total.'" aria-valuemin="0" aria-valuemax="100"></div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                
                                            </div>';
                                $output .= '<div class="col-md-6">
                                                <div class="card">
                                                    <div class="card-header">
                                                        <h5 class="card-title mb-0">Gaji '.bulan($bulan).'</h5>
                                                    </div>
                                                    <div class="card-body">
                                                       
                                                        <form id="formAct">
                                                            <input type="hidden" name="username" value="'.$row->username.'">
                                                            <input type="hidden" name="month" value="'.$bulan.'">
                                                            <input type="hidden" name="year" value="'.$year.'">

                                                            <div class="row">
                                                                <div class="col-12 form-group">
                                                                    <label>Gaji Pokok</label>
                                                                    <input type="text" name="pokok"  class="form-control  rupiah">
                                                                </div>
                                                                <div class="col-12 form-group">
                                                                    <label>Tunjangan Makan</label>
                                                                    <input type="text" name="meal"  class="form-control  rupiah">
                                                                </div>
                                                                <div class="col-12 form-group">
                                                                    <label>Overtime</label>
                                                                    <input type="text" name="overtime" class="form-control rupiah">
                                                                </div>
                                                                <div class="col-12 form-group">
                                                                    <label>Insentif</label>
                                                                    <input type="text" name="insentif"  class="form-control  rupiah">
                                                                </div>
                                                                <div class="col-12 form-group">
                                                                    <label>BPJS</label>
                                                                    <input type="text" name="bpjs" class="form-control  rupiah">
                                                                </div>
                                                                <div class="col-12 form-group">
                                                                    <label>Potongan</label>
                                                                    <input type="text" name="potongan" class="form-control  rupiah">
                                                                </div>
                                                                <div class="col-12 form-group text-right mt-4">
                                                                    <button class="btn btn-primary">Simpan</button>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>';
                                $status = 200;
                                $msg=  null;
                            }else{
                                $status = 201;
                                $msg = 'Bulan lalu pegawai tidak memiliki schedule';
                            }
                        }
                    }else{
                        $status = 201;
                        $msg = 'Pegawai tidak ditemukan';
                    }
                }

            }else{
                $status = 501;
                $msg = 'Unauthorize access';
            }
            echo json_encode(['status'=>$status,'msg'=>$msg,'output'=>$output]);
        }else{
            redirect('gaji');
        }
    }
    public function edit(){
        $id = decode($this->input->get('slip'));
        if($this->role == 'hrd'){
            $row = $this->model_app->getGajiPegawaiWhere($id);
            if($row->num_rows() > 0){
                $row = $row->row();
                $date = '01-'.$row->months.'-'.$row->years;
                           

                $dateTime = new DateTime($date);

                $month = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('m');
                $years = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('Y');

                $data['month']= $month;
                $data['years'] = $years;
                $data['bulan'] =$row->months;
                
                // echo $lastMonth; // 'December 2013'
                // $dates1 = date('m-Y',strtotime($monthY));
                // $dates = date("d-m-Y", strtotime("-1 month"));
            
                // $month = date('m',strtotime($dates));
                
                // $years = date('Y',strtotime($dates));
                // $month = date('m',strtotime($bulan,strtotime('-1 Months')));
                // if($month == '01'){
                //     $month = 12;
                // }else{
                //     $month = $month;
                // }
                // $years = date('Y',strtotime($year,strtotime('-1 Months')));

                $schedule = $this->model_app->view_where('schedule',array('months'=>$month,'years'=>$years,'pegawai_id'=>$row->pegawai_id));
                if($schedule->num_rows() > 0){
                    $lastMonth = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' ")->row();
                    $lastMonthResult = $this->db->query("SELECT coalesce(SUM(b.duration),0) as durasi, COUNT(b.id) as totalAbsen FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on'")->row();
                    $percentHours = round($lastMonthResult->durasi/($lastMonth->total*8)*100,1);
                    $hours = $lastMonth->total*8;
                    $terlambat = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyIn FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_in ='n' ")->row();
                    if($terlambat->totalEarlyIn > 0){
                        $percentTerlambat = round($terlambat->totalEarlyIn/$lastMonthResult->totalAbsen*100,0);
                    
                    }else{
                        $percentTerlambat= 0;
                    }
                    $pulang = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyOut FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_out ='y' ")->row();
                    if($pulang->totalEarlyOut > 0){
                        $percentPulang = round($pulang->totalEarlyOut/$lastMonthResult->totalAbsen*100,0);
                    
                    }else{
                        $percentPulang =0;
                    
                    }
                    $percentHari = round($lastMonthResult->totalAbsen/$lastMonth->total*100,2);
                    $off = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='off' ")->row();
                    $cuti = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='dc' ")->row();

                    $overtime = $this->db->query("SELECT COALESCE(SUM(overtime),0) as ovt FROM absensi a JOIN overtime b ON a.id = b.absensi_id WHERE a.pegawai_id = $row->pegawai_id AND MONTH(a.date) = '".$month."' AND YEAR(a.date) = '".$years."' ")->row();
                    
                    $data['title'] = 'GAJI - '.title();
                    $data['page'] = 'Gaji';
                
                    $data['right'] = '';
                    $data['row'] = $row;
                    
                    
            
                    $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
                    $data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('gaji').'">Gaji</a></li>';
                    $data['breadcrumb'] .= '<li class="breadcrumb-item active">Edit</li>';
                    $data['ajax'] = ['assets/ajax/gaji/edit.js'];
                    $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                    $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/plugins/input-mask/jquery.inputmask.bundle.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                    $data['lastMonth'] = $lastMonth;
                    $data['lastMonthResult'] = $lastMonthResult;
                    $data['percentHours'] = $percentHours;
                    $data['hours'] = $hours;
                    $data['terlambat'] = $terlambat;
                    $data['percentTerlambat'] = $percentTerlambat;
                    $data['pulang'] = $pulang;
                    $data['percentPulang'] = $percentPulang;
                    $data['percentHari'] = $percentHari;
                    $data['off'] = $off;
                    $data['cuti'] = $cuti;
                    $data['overtime'] = $overtime;
                    $this->template->load('template','hrd/gaji-edit',$data);

                }else{
                    $this->session->set_flashdata('error','Schedule gaji tidak ditemukan');
                    redirect('gaji');
                }
                

            }else{
                $this->session->set_flashdata('error','Gaji tidak ditemukan');
                redirect('gaji');
            }
        }else{
            redirect('gaji');
        }
        
    }
    public function detail(){
        $id = decode($this->input->get('slip'));
        if($this->role == 'hrd'){
            $row = $this->model_app->getGajiPegawaiWhere($id);
            if($row->num_rows() > 0){
                $row = $row->row();
                $date = '01-'.$row->months.'-'.$row->years;
                           

                $dateTime = new DateTime($date);

                $month = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('m');
                $years = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('Y');

                $data['month']= $month;
                $data['years'] = $years;
                $data['bulan'] =$row->months;
                
                // echo $lastMonth; // 'December 2013'
                // $dates1 = date('m-Y',strtotime($monthY));
                // $dates = date("d-m-Y", strtotime("-1 month"));
            
                // $month = date('m',strtotime($dates));
                
                // $years = date('Y',strtotime($dates));
                // $month = date('m',strtotime($bulan,strtotime('-1 Months')));
                // if($month == '01'){
                //     $month = 12;
                // }else{
                //     $month = $month;
                // }
                // $years = date('Y',strtotime($year,strtotime('-1 Months')));

                $schedule = $this->model_app->view_where('schedule',array('months'=>$month,'years'=>$years,'pegawai_id'=>$row->pegawai_id));
                if($schedule->num_rows() > 0){
                    $lastMonth = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' ")->row();
                    $lastMonthResult = $this->db->query("SELECT coalesce(SUM(b.duration),0) as durasi, COUNT(b.id) as totalAbsen FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on'")->row();
                    $percentHours = round($lastMonthResult->durasi/($lastMonth->total*8)*100,1);
                    $hours = $lastMonth->total*8;
                    $terlambat = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyIn FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_in ='n' ")->row();
                    if($terlambat->totalEarlyIn > 0){
                        $percentTerlambat = round($terlambat->totalEarlyIn/$lastMonthResult->totalAbsen*100,0);
                    
                    }else{
                        $percentTerlambat= 0;
                    }
                    $pulang = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyOut FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_out ='y' ")->row();
                    if($pulang->totalEarlyOut > 0){
                        $percentPulang = round($pulang->totalEarlyOut/$lastMonthResult->totalAbsen*100,0);
                    
                    }else{
                        $percentPulang =0;
                    
                    }
                    $percentHari = round($lastMonthResult->totalAbsen/$lastMonth->total*100,2);
                    $off = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='off' ")->row();
                    $cuti = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='dc' ")->row();

                    $overtime = $this->db->query("SELECT COALESCE(SUM(overtime),0) as ovt FROM absensi a JOIN overtime b ON a.id = b.absensi_id WHERE a.pegawai_id = $row->pegawai_id AND MONTH(a.date) = '".$month."' AND YEAR(a.date) = '".$years."' ")->row();
                    $data['title'] = 'GAJI - '.title();
                    $data['page'] = 'Gaji';
                
                    $data['right'] = '';
                    $data['row'] = $row;
                    
                    
            
                    $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
                    $data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('gaji').'">Gaji</a></li>';
                    $data['breadcrumb'] .= '<li class="breadcrumb-item active">Edit</li>';
                    $data['ajax'] = ['assets/ajax/gaji/edit.js'];
                    $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                    $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/plugins/input-mask/jquery.inputmask.bundle.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                    $data['lastMonth'] = $lastMonth;
                    $data['lastMonthResult'] = $lastMonthResult;
                    $data['percentHours'] = $percentHours;
                    $data['hours'] = $hours;
                    $data['terlambat'] = $terlambat;
                    $data['percentTerlambat'] = $percentTerlambat;
                    $data['pulang'] = $pulang;
                    $data['percentPulang'] = $percentPulang;
                    $data['percentHari'] = $percentHari;
                    $data['off'] = $off;
                    $data['cuti'] = $cuti;
                    $data['overtime'] = $overtime;
                    $this->template->load('template','hrd/gaji-detail',$data);

                }else{
                    $this->session->set_flashdata('error','Schedule gaji tidak ditemukan');
                    redirect('gaji');
                }
                

            }else{
                $this->session->set_flashdata('error','Gaji tidak ditemukan');
                redirect('gaji');
            }
        }else{
            redirect('gaji');
        }
        
    }
    public function update(){
        if($this->role == 'hrd'){
            if($this->input->method() == 'post'){
                $id = decode($this->input->post('id'));
                $row = $this->model_app->getGajiPegawaiWhere($id);
                if($row->num_rows() > 0){
                  
                    $this->form_validation->set_rules('pokok','Gaji Pokok','required');
                    $this->form_validation->set_rules('meal','Tunjangan Makan','required');
                    $this->form_validation->set_rules('overtime','Tunjangan Overtime','required');
                    $this->form_validation->set_rules('insentif','Insentif','required');
                    $this->form_validation->set_rules('bpjs','Tunjangan Bpjs','required');
                    $this->form_validation->set_rules('potongan','Potongan','required');
                     
                    if($this->form_validation->run() == false){
                        $status = 201;
                        $msg = validation_errors();
                   
                        
                    }else{
                       
                        $pokok = numberReplace($this->input->post('pokok'));
                        $meal = numberReplace($this->input->post('meal'));
                        $overtime = numberReplace($this->input->post('overtime'));
                        $insentif = numberReplace($this->input->post('insentif'));
                        $bpjs = numberReplace($this->input->post('bpjs'));
                        $potongan = numberReplace($this->input->post('potongan'));

                        
                        $total = ($pokok+$meal+$overtime+$insentif+$bpjs)-$potongan;
                        $data = ['basic_salary'=>$pokok,'meal_salary'=>$meal,'overtime_pay'=>$overtime,'incentive'=>$insentif,'bpjs'=>$bpjs,'cut_salary'=>$potongan,'total_salary'=>$total];
                        $id =  $this->model_app->update('slip',$data,array('id'=>$id));


                        $msg = 'Gaji '.$row->name.' pada '.bulan($row->months).' berhasil diubah';
                        $this->session->set_flashdata('success',$msg);
                        redirect('gaji');

                     
                        
                                
                            
                        
                    }
                }else{
                    $this->session->set_flashdata('error','Gaji tidak ditemukan');
                    redirect('gaji');
                }
            }else{
                redirect('gaji');
            }
        }else{
            redirect('gaji');
        }
    }
}
