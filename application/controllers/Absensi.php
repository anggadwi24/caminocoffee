<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Absensi extends CI_Controller 
{
	public function __construct()
	{
        parent::__construct();
        date_default_timezone_set('Asia/Makassar');
    	if($this->session->userdata('isLog')){
			$this->id = $this->session->userdata['isLog']['id'];
			$this->child = $this->session->userdata['isLog']['child'];

			$this->role = $this->session->userdata['isLog']['role'];
		}else{
			redirect('auth');
		}	
	}
    public function index(){
        $data['title'] = 'Absensi - '.title();
		$data['page'] = 'Absensi';
        $data['right'] = ' 
							';
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Absensi</li>';
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];

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
            $employee = $this->input->get('pegawai');
            $month = $this->input->get('month');
            $year = $this->input->get('year');
            if($employee == null){
                $employee == 'all';
              
            }else{
                $employee == $employee;
            }
            if($month == null){
                $month = date('m');
            }
            if($year == null){
                $year = date('Y');
            }
    
            $data['pegawai'] = $this->model_app->join_where_order2('pegawai','users','users_id','id',array('active'=>'y'),'pegawai.id','desc');
            $data['employee'] = $employee;
            $data['month'] = $month;
            $data['year'] = $year;
            $data['record'] = $this->model_app->getAbsensi($employee,$month,$year);
            $this->template->load('template','hrd/absensi',$data);
        }else{
          
            $month = $this->input->get('month');
            $year = $this->input->get('year');
          
            if($month == null){
                $month = date('m');
            }
            if($year == null){
                $year = date('Y');
            }
    
            $schedule = $this->model_app->view_where('schedule',array('pegawai_id'=>$this->child,'dates'=>date('Y-m-d'),'months'=>date('m'),'years'=>date('Y')));
            if($schedule->num_rows() > 0)   {
                $sch = $schedule->row();
                $data['schedule'] = $sch;
                $shift = $this->model_app->view_where('shift',array('id'=>$sch->shift_id));
                if($shift->num_rows() > 0){
                    $data['shift'] = $shift->row();
                    $data['absensi'] = $this->model_app->view_where('absensi',array('pegawai_id'=>$this->child,'schedule_id'=>$sch->id));
                }else{
                    $data['shift'] = null;
                    $data['absensi'] = null;
                }
            
            }else{
                $data['schedule'] = null;
                $data['shift'] = null;
                $data['absensi'] = null;
            }
          
            $data['month'] = $month;
            $data['year'] = $year;
            $data['record'] = $this->model_app->getAbsensi($this->child,$month,$year);
            $this->template->load('template','pegawai/absensi',$data);
        }
       
    }
    public function in(){
        if($this->input->method() == 'post'){
            if($this->role == 'pegawai'){
                $schedule = $this->model_app->view_where('schedule',array('pegawai_id'=>$this->child,'dates'=>date('Y-m-d'),'months'=>date('m'),'years'=>date('Y')));
                if($schedule->num_rows() > 0)   {
                    $sch = $schedule->row();
                    if($sch->status == 'on'){
                        $shift = $this->model_app->view_where('shift',array('id'=>$sch->shift_id));
                        if($shift->num_rows() > 0){
                            $shf = $shift->row();
                            if(date('H:i',strtotime($shf->schedule_in)) <= date('H:i')){
                                $early_in = 'n';
                            }else{
                                $early_in = 'y';
                            }
                            $data = array('date'=>date('Y-m-d'),'schedule_id'=>$sch->id,'pegawai_id'=>$this->child,'absen_out'=>null,'absen_in'=>date('Y-m-d H:i:s'),'early_in'=>$early_in,'early_out'=>null,'created_by'=>$this->child);
                            $this->model_app->insert('absensi',$data);
                          
                            redirect('absensi');
                        }else{
                            $this->session->set_flashdata('error','Shift tidak ditemukan');
                            redirect('absensi');
                        }
                    }else{
                        $this->session->set_flashdata('error','Anda tidak dalam status onduty');
                        redirect('absensi');
                    }
                
                }else{
                    $this->session->set_flashdata('error','Tidak ada jadwal absensi hari ini');
                    redirect('absensi');
                }
            }else{
                redirect('absensi');
            }
        }else{
            redirect('absensi');
        }
    }
    public function detail(){
        if($this->role == 'hrd'){
            $id = decode($this->input->get('absensi'));
            $cek = $this->model_app->view_where('absensi',array('id'=>$id));
            if($cek->num_rows() > 0){
                $row = $cek->row();
                $pegawai = $this->model_app->join_where_order2('pegawai','users','users_id','id',array('pegawai.id'=>$row->pegawai_id),'pegawai.id','desc');
                if($pegawai->num_rows() > 0){
                    $record = $this->model_app->getAbsensiWhere(array('absensi.id'=>$row->id));
                    if($record->num_rows() > 0){
                        $data['page'] = 'Absensi';
                        $data['title'] = 'DETAIL ABSENSI - '.title();
                        $data['right'] = ' ';
                        $data['row'] = $record->row();
                        $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
                        $data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('absensi').'">Absensi</a></li>';
                        $data['breadcrumb'] .= '<li class="breadcrumb-item active">Detail</li>';
                        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                        $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                        $this->template->load('template','hrd/absensi-detail',$data);
                    }else{
                        $this->session->set_flashdata('error','Terjadi kesalahan');
                        redirect('absensi');
                    }
                }else{
                    $this->session->set_flashdata('error','Pegawai tidak ditemukan');
                    redirect('absensi');
                }
            }else{
                $this->session->set_flashdata('error','Absensi tidak ditemukan');
                redirect('absensi');
            }
        }else{
            $this->session->set_flashdata('error','Tidak bisa mengakses halaman');
            redirect('absensi');
        }
        

    }
}