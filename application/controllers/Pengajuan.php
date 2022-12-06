<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengajuan extends CI_Controller 
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
        $data['title'] = 'PENGAJUAN - '.title();
		$data['page'] = 'Pengajuan';
        if($this->role == 'hrd'){
            $data['right'] = '';
        }else{
            
            $data['right'] = ' <a href="'.base_url('pengajuan/add').'" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah</a>
            ';
        }

        $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Pengajuan</li>';
    
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
        if($this->role == 'pegawai'){
            $data['record'] = $this->model_app->view_where_ordering('pengajuan',array('pegawai_id'=>$this->child),'id','desc');
            $this->template->load('template','pegawai/pengajuan',$data);
        }else{
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
            $bulan = $this->input->get('month');
            $tahun = $this->input->get('year');
            $employee = $this->input->get('pegawai');
            if($bulan == null){
                $bulan = date('m');
            }
            if($tahun == null){
                $tahun = date('Y');
            }
            if($employee == null){
                $employee == 'all';
              
            }else{
                $employee == $employee;
            }
            $data['pegawai'] = $this->model_app->join_where_order2('pegawai','users','users_id','id',array('active'=>'y'),'pegawai.id','desc');
            $data['month'] = $bulan;
            $data['year'] = $tahun;
            $data['employee'] = $employee;
            $data['record'] = $this->model_app->view_pengajuan($employee,$bulan,$tahun);
            $this->template->load('template','hrd/pengajuan',$data);
        }
      
    }
    public function add(){
        if($this->role == 'pegawai'){
            $data['title'] = 'PENGAJUAN - '.title();
            $data['page'] = 'Pengajuan';
           
            $data['right'] = '';
            
    
            $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
            $data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('pengajuan').'">Pengajuan</a></li>';
            $data['breadcrumb'] .= '<li class="breadcrumb-item active">Tambah</li>';
        
            $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
            $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
            $this->template->load('template','pegawai/pengajuan-add',$data);
        }else{
            redirect('pengajuan');
        }
    }
    public function store(){
        if($this->role == 'pegawai'){
            if($this->input->method() == 'post'){
               
                
              
        
               
                    $this->load->helper('file');
                    $this->form_validation->set_rules('perihal','Perihal Pengajaun','required');
                    $this->form_validation->set_rules('start','Tanggal Mulai','required');
                    $this->form_validation->set_rules('end','Tanggal Sampai','required');
                    $this->form_validation->set_rules('alasan','Alasan Pengajuan','required');
                    $this->form_validation->set_rules('file', '', 'callback_file_check');

                    if($this->form_validation->run() == false){
					
                        $this->add();
                        
                    }else{
                       
                        $config['upload_path']          = './upload/pengajuan/';
                        $config['encrypt_name'] = TRUE;
                        $config['allowed_types']        = 'gif|jpg|png|jpeg';
                        $config['max_size']             = 5000;
                            
                                
                        $this->load->library('upload', $config);
    
                        if ($this->upload->do_upload('file')){
                            $perihal = $this->input->post('perihal');
                            $start = $this->input->post('start');
                            $end = $this->input->post('end');
                            $startDate = date('Y-m-d',strtotime($start));
                            $endDate = date('Y-m-d',strtotime($end));
                            if($endDate < $startDate){
                                $this->session->set_flashdata('error','Format tanggal salah!');
                                $this->add();
                            }else{
                                $reason = $this->input->post('alasan');
                        
                                $upload_data = $this->upload->data();
                               
                                $file_name = $_FILES['file']['name']; 
                                $file = $upload_data['file_name'];
                                $data = array('perihal'=>$perihal,'start'=>$startDate,'end'=>$endDate,'pegawai_id'=>$this->child,
                                             'reason'=>$reason,'file'=>$file,'file_name'=>$file_name,'approve'=>'p');
                                $this->model_app->insert('pengajuan',$data);
                               
                                $this->session->set_flashdata('success','Pengajuan Cuti/DC berhasil diajuakn');
                                redirect('pengajuan');
                            }
                           
                        }else{
                           $this->add();
                        }
                        
                    }

            }else{
                redirect('pengajuan/add');
            }
        }else{
            redirect('pengajuan');
        }
    }
    public function detail(){
        if($this->role == 'pegawai'){
            $id =decode($this->input->get('no'));
            $row = $this->model_app->join_where2_select('*,pengajuan.created_at as tanggal','pengajuan','pegawai','pegawai_id','id',array('pengajuan.id'=>$id,'pegawai_id'=>$this->child));
         
            if($row->num_rows() > 0){
                $data['title'] = 'PENGAJUAN - '.title();
                $data['page'] = 'Pengajuan';
               
                $data['right'] = '';
                $data['row'] = $row->row();
        
                $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
                $data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('pengajuan').'">Pengajuan</a></li>';
                $data['breadcrumb'] .= '<li class="breadcrumb-item active">Detail</li>';
            
                $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                $this->template->load('template','pegawai/pengajuan-detail',$data);
            }else{
                $this->session->set_flashdata('error','Pengajuan tidak ditemukan');
                redirect('pengajuan');
            }
        }else{
            $id =decode($this->input->get('no'));
            $row = $this->model_app->join_where2_select('*,pengajuan.created_at as tanggal,pengajuan.id as pengajuan_id','pengajuan','pegawai','pegawai_id','id',array('pengajuan.id'=>$id));
         
            if($row->num_rows() > 0){
                $data['title'] = 'PENGAJUAN - '.title();
                $data['page'] = 'Pengajuan';
               
                $data['right'] = '';
                $data['row'] = $row->row();
        
                $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
                $data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('pengajuan').'">Pengajuan</a></li>';
                $data['breadcrumb'] .= '<li class="breadcrumb-item active">Detail</li>';
            
                $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                $this->template->load('template','hrd/pengajuan-detail',$data);
            }else{
                $this->session->set_flashdata('error','Pengajuan tidak ditemukan');
                redirect('pengajuan');
            }
        }
    }
    public function disapprove(){
        if($this->role == 'hrd'){
            if($this->input->method() == 'post'){
                $id =decode($this->input->post('id'));
                $row = $this->model_app->join_where2_select('*,pengajuan.created_at as tanggal,pengajuan.id as pengajuan_id','pengajuan','pegawai','pegawai_id','id',array('pengajuan.id'=>$id));
                if($row->num_rows() > 0){
                   $row = $row->row();
                   if($row->approve == 'p'){
                        $data = array('approve'=>'n','approve_by'=>$this->child,'approve_at'=>date('Y-m-d H:i:s'));
                        $result = $this->model_app->update('pengajuan',$data,array('id'=>$id));
                        if($result){
                           
                            $this->session->set_flashdata('success','Pengajuan berhasil ditolak');
                            redirect('pengajuan/detail?no='.$this->input->post('id'));
                        }else{
                            $this->session->set_flashdata('error','Terjadi kesalahan');
                            redirect('pengajuan/detail?no='.$this->input->post('id'));
                        }
                   }else{
                        $this->session->set_flashdata('error','Pengajuan tidak dalam status proses');
                        redirect('pengajuan/detail?no='.$this->input->post('id'));
                   }
                }else{
                    $this->session->set_flashdata('error','Pengajuan tidak ditemukan');
                    redirect('pengajuan');
                }
            }else{
                redirect('pengajuan');
            }
        }else{
            redirect('pengajuan');
        }
    }
    public function approve(){
        if($this->role == 'hrd'){
            if($this->input->method() == 'post'){
                $id =decode($this->input->post('id'));
                $row = $this->model_app->join_where2_select('*,pengajuan.created_at as tanggal,pengajuan.id as pengajuan_id','pengajuan','pegawai','pegawai_id','id',array('pengajuan.id'=>$id));
                if($row->num_rows() > 0){
                   $row = $row->row();
                   if($row->approve == 'p'){
                        $data = array('approve'=>'y','approve_by'=>$this->child,'approve_at'=>date('Y-m-d H:i:s'));
                        $result = $this->model_app->update('pengajuan',$data,array('id'=>$id));
                        if($result){
                            $begin = new DateTime($row->start);
                            $end   = new DateTime($row->end);
                            for($i = $begin; $i <= $end; $i->modify('+1 day')){
                                $day = $i->format('Y-m-d');
                                $month = $i->format('m');
                                $year = $i->format('Y');
                                $check = $this->model_app->view_where('schedule',array('pegawai_id'=>$row->pegawai_id,'months'=>$month,'years'=>$year,'dates'=>$day));
                                if($check->num_rows() > 0){
                                    $sch = $check->row();
                                    $dataSch = ['shift_id'=>null,'status'=>'dc'];
                                    $this->model_app->update('schedule',$dataSch,array('id'=>$sch->id));
                                }else{
                                    $dataSch = ['pegawai_id'=>$row->pegawai_id,'shift_id'=>null,'months'=>$month,'years'=>$year,'dates'=>$day,'status'=>'dc','created_by'=>$this->child];
                                    $this->model_app->insert('schedule',$dataSch);
                                }
                                

                            }
                            $this->session->set_flashdata('success','Pengajuan berhasil disetujui');
                            redirect('pengajuan/detail?no='.$this->input->post('id'));
                        }else{
                            $this->session->set_flashdata('error','Terjadi kesalahan');
                            redirect('pengajuan/detail?no='.$this->input->post('id'));
                        }
                   }else{
                        $this->session->set_flashdata('error','Pengajuan tidak dalam status proses');
                        redirect('pengajuan/detail?no='.$this->input->post('id'));
                   }
                }else{
                    $this->session->set_flashdata('error','Pengajuan tidak ditemukan');
                    redirect('pengajuan');
                }
            }else{
                redirect('pengajuan');
            }
        }else{
            redirect('pengajuan');
        }
    }
    public function file_check($str){
        $allowed_mime_type_arr = array('image/gif','image/jpeg','image/pjpeg','image/png','image/x-png');
        $mime = get_mime_by_extension($_FILES['file']['name']);
        if(isset($_FILES['file']['name']) && $_FILES['file']['name']!=""){
            if(in_array($mime, $allowed_mime_type_arr)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'File wajib format gif/jpg/png ');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'File harus diisi');
            return false;
        }
    }
}