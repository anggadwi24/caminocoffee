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
        if($this->role == 'hrd'){
          
           
            $data['right'] = ' <a href="'.base_url('gaji/add').'" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah</a>
                                ';
           
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
            $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
            $this->template->load('template','hrd/gaji-add',$data);
        }else{
            redirect('gaji');
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
                            $month = date('m',strtotime($bulan,strtotime('-1 Month')));
                            if($month == '01'){
                                $month = 12;
                            }
                            $years = date('Y',strtotime($year,strtotime('-1 Month')));

                            $schedule = $this->model_app->view_where('schedule',array('months'=>$month,'years'=>$years,'pegawai_id'=>$row->pegawai_id));
                            if($schedule->num_rows() > 0){
                                $lastMonth = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $this->child AND months = '".date('m')."' AND years = '".date('Y')."' AND status ='on'")->row();
                                $lastMonthResult = $this->db->query("SELECT coalesce(SUM(b.duration),0) as durasi, COUNT(b.id) as totalAbsen FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $this->child AND months = '".date('m')."' AND years = '".date('Y')."' AND status ='on'")->row();
                                $percentHours = round($lastMonthResult->durasi/($lastMonth->total*8)*100,1);
                                $hours = $lastMonth->total*8;
                                $terlambat = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyIn FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_in ='n' ")->row();
                                $percentTerlambat = round($terlambat->totalEarlyIn/$lastMonthResult->totalAbsen*100,0);
                                $output .= '<div class="col-md-6">
                                               
                                                <div class="card att-statistics">
                                                    <div class="card-body pb-5">
                                                        <h5 class="card-title">Statistik '.bulan($month).'</h5>
                                                        <div class="stats-list mb-2 ">
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
                                                        </div>
                                                        
                                                    </div>
                                                </div>
                                                
                                            </div>';
                                $output .= '<div class="col-md-6">

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
}
