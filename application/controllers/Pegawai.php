<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pegawai extends CI_Controller 
{
	public function __construct()
	{
        parent::__construct();
    	if($this->session->userdata('isLog')){
			$this->id = $this->session->userdata['isLog']['id'];
			$this->role = $this->session->userdata['isLog']['role'];
			if($this->role != 'hrd'){
				$this->session->set_flashdata('error','Anda tidak bisa mengakses halaman ini');
				redirect('/');
			}
		}else{
			redirect('auth');
		}	
	}
	public function index()
	{	
		$type = $this->input->get('type');
		$data['title'] = 'PEGAWAI - '.title();
		$data['page'] = 'Pegawai';
		if($type == null OR $type =='grid'){
			$gridActive = 'active';
			$listActive = '';
			$data['type'] = 'grid';

		}else{
			$gridActive = '';
			$listActive = 'active';
			$data['type'] = 'list';
		}
		$keyword = $this->input->get('keyword');
		if(isset($keyword)){
			$url = base_url('pegawai/download?keyword='.$keyword);
		}else{
			$url = base_url('pegawai/download');
		}
		$data['right'] = ' <a href="'.base_url('pegawai/add').'" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah Pegawai</a>
							<div class="view-icons">
								<a href="'.base_url('pegawai?type=grid').'" class="grid-view btn btn-link  '.$gridActive.'"><i class="fa fa-th"></i></a>
								<a href="'.base_url('pegawai?type=list').'" class="list-view btn btn-link '.$listActive.'"><i class="fa fa-bars"></i></a>
								<a href="'.$url.'" class="list-view btn btn-link"  title="Download PDF"><i class="fa fa-file-pdf-o"></i></a>	
							</div>';
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Pegawai</li>';
		$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
		
		if(isset($keyword)){
			$data['record'] = $this->model_app->join_like_order2('pegawai','users','users_id','id',array('name'=>$keyword),'pegawai.id','desc');
			$data['keyword'] = $keyword;
		}else{
			$data['record'] = $this->model_app->join_order2('pegawai','users','users_id','id','pegawai.id','desc');
			$data['keyword'] = '';

		}
		if($type == 'grid' OR $type == null){
			$this->template->load('template','hrd/pegawai',$data);
		}else{
			$this->template->load('template','hrd/pegawai-list',$data);
		}
	
		

	}
	public function download(){
		$keyword = $this->input->get('keyword');
		if(isset($keyword)){
			$data['record'] = $this->model_app->join_like_order2('pegawai','users','users_id','id',array('name'=>$keyword),'pegawai.id','desc');
			$data['keyword'] = $keyword;
		}else{
			$data['record'] = $this->model_app->join_order2('pegawai','users','users_id','id','pegawai.id','desc');
			$data['keyword'] = '';

		}
		
		$html = $this->load->view('hrd/pegawai-pdf',$data,true);
		$filename = 'PEGAWAI-CAMINO-COFFEE-AND-EATERY';
		$paper = 'A4';
		$orientation = 'landscape';

		$attach = pdf_create($html, $filename, $paper, $orientation,true);
	}
	public function add(){
		$data['title'] = 'PEGAWAI - '.title();
		$data['page'] = 'Pegawai';
		$data['right'] = '';
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('pegawai').'">Pegawai</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Tambah</li>';
		$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
		$this->template->load('template','hrd/pegawai-add',$data);
	}
	function status(){
		if($this->input->method() == 'get'){
			$username = $this->uri->segment(3);
			$cek = $this->model_app->view_where('users',array('username'=>$username));
			if($cek->num_rows() > 0){
				$row = $cek->row();
				if($row->active == 'y'){
					$act ='n';
					$this->session->set_flashdata('success','Pegawai berhasil disuspend');

				}else if($row->active == 'n'){
					$act = 'y';
					$this->session->set_flashdata('success','Pegawai berhasil diaktifkan');
				}

				$this->model_app->update('users',array('active'=>$act),array('username'=>$username));
				
				redirect('pegawai');
			}else{
				$this->session->set_flashdata('error','Pegawai tidak ditemukan');
				redirect('pegawai');
			}
			
		}else{
			$this->session->set_flashdata('error','Wrong method');
			redirect('pegawai');
		}

		
	}
	function detail($username){
		$cek = $this->model_app->join_where2('pegawai','users','users_id','id',array('username'=>$username));
		if($cek->num_rows() > 0){
			$data['title'] = 'PEGAWAI - '.title();
			$data['page'] = 'Pegawai';
			$data['right'] = '';
			$data['row'] = $cek->row();
			$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
			$data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('pegawai').'">Pegawai</a></li>';
			$data['breadcrumb'] .= '<li class="breadcrumb-item active">Detail</li>';
			$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
			$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
			$this->template->load('template','hrd/pegawai-detail',$data);
			
		}else{
			$this->session->set_flashdata('error','Pegawai tidak ditemukan');
			redirect('pegawai');
		}
	}
	
	function edit($username){
		
			
			$cek = $this->model_app->join_where2('pegawai','users','users_id','id',array('username'=>$username));
			if($cek->num_rows() > 0){
				$data['title'] = 'PEGAWAI - '.title();
				$data['page'] = 'Pegawai';
				$data['right'] = '';
				$data['row'] = $cek->row();
				$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
				$data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('pegawai').'">Pegawai</a></li>';
				$data['breadcrumb'] .= '<li class="breadcrumb-item active">Edit</li>';
				$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
				$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
				$this->template->load('template','hrd/pegawai-edit',$data);
				
			}else{
				$this->session->set_flashdata('error','Pegawai tidak ditemukan');
				redirect('pegawai');
			}
			
		

		
	}
	function update($username){
		if($this->input->method() == 'post'){
			$cek = $this->model_app->getUserWhere(array('username'=>$username));
			if($cek->num_rows() > 0){
				$row = $cek->row();
				$id = $row->id;
				$this->form_validation->set_rules('username','Username','required|edit_unique[users.username.'.$id.']');
				$this->form_validation->set_rules('name','Nama','required');
				$this->form_validation->set_rules('pob','Tempat Lahir','required');
				$this->form_validation->set_rules('phone','Telepon/Hp','required');
			
				$this->form_validation->set_rules('dob','Tanggal lahir','required');
				$this->form_validation->set_rules('address','Alamat','required');
				$this->form_validation->set_rules('position','Jabatan','required');

			







				if($this->form_validation->run() == false){
					
					$this->edit($username);
					
				}else{
					
					$name = $this->input->post('name');
		
					$username = $this->input->post('username');
					$pwd = $this->input->post('password');
					$telp = $this->input->post('phone');
					$alamat = $this->input->post('address');
					$pob = $this->input->post('pob');
					$dob = $this->input->post('dob');
					$position = $this->input->post('position');

					$tgl = date('Y-m-d',strtotime($dob));
					if(trim($pwd)){
						$password = sha1($pwd);
					}else{
						$password = $row->password;
					}
					
					$data = array('username'=>$username,'password'=>$password,'level'=>'pegawai');
					 $this->model_app->update('users',$data,array('username'=>$row->username));
					$config['upload_path']          = './upload/user/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types']        = 'gif|jpg|png|jpeg';
					$config['max_size']             = 5000;
						
							
					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')){
						$upload_data = $this->upload->data();
						$foto = $upload_data['file_name'];
					}else{
						$foto = $row->photo;
					}
					$dataPeg = array('name'=>$name,'address'=>$alamat,'phone'=>$telp,
								 'pob'=>$pob,'dob'=>$tgl,'photo'=>$foto,'position'=>$position);
					$this->model_app->update('pegawai',$dataPeg,array('id'=>$row->pegawai_id));
					$this->session->set_flashdata('success','Pegawai berhasil ditambah');
					redirect('pegawai');
				}
				
			}else{
				$this->session->set_flashdata('error','Pegawai tidak ditemukan');
				redirect('pegawai');
			}
		}else{
			$this->session->set_flashdata('error','Wrong method');
			redirect('pegawai');
		
		}
	}
	function delete(){
		if($this->input->method() == 'post'){
			
				$id = $this->input->post('username');

				$cek = $this->model_app->view_where('users',array('username'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row();
					$this->model_app->delete('pegawai',array('users_id'=>$row->id));
					$this->model_app->delete('users',array('username'=>$id));
					$this->session->set_flashdata('success','Pegawai berhasil dihapus');
					redirect('pegawai');
				}else{
					$this->session->set_flashdata('error',json_encode('Pegawai tidak ditemukan'));
					redirect('pegawai');
				}
			
		}else{
			$this->session->set_flashdata('error',json_encode('Wrong Method'));
			redirect('pegawai');
		}
	}
	function store(){
		if($this->input->method() == 'post'){
			
				$this->form_validation->set_rules('username','Username','required|is_unique[users.username]');
				$this->form_validation->set_rules('name','Nama','required');
				$this->form_validation->set_rules('pob','Tempat Lahir','required');
				$this->form_validation->set_rules('phone','Telepon/Hp','required');
				$this->form_validation->set_rules('password','Password','required');
				$this->form_validation->set_rules('dob','Tanggal lahir','required');
				$this->form_validation->set_rules('address','Alamat','required');
				$this->form_validation->set_rules('position','Jabatan','required');

			







				if($this->form_validation->run() == false){
					
					$this->add();
					
				}else{
					
					$name = $this->input->post('name');
					$nip = $this->input->post('nip');
					$username = $this->input->post('username');
					$pwd = $this->input->post('password');
					$telp = $this->input->post('phone');
					$alamat = $this->input->post('address');
					$pob = $this->input->post('pob');
					$dob = $this->input->post('dob');
					$position = $this->input->post('position');

				
					$tgl = date('Y-m-d',strtotime($dob));
				
					$data = array('username'=>$username,'password'=>sha1($pwd),'active'=>'y','level'=>'pegawai');
					$users_id = $this->model_app->insert_id('users',$data);
					$config['upload_path']          = './upload/user/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types']        = 'gif|jpg|png|jpeg';
					$config['max_size']             = 5000;
						
							
					$this->load->library('upload', $config);

					if ($this->upload->do_upload('file')){
						$upload_data = $this->upload->data();
						$foto = $upload_data['file_name'];
					}else{
						$foto = 'default.png';
					}
					$dataPeg = array('name'=>$name,'address'=>$alamat,'phone'=>$telp,
								 'pob'=>$pob,'dob'=>$tgl,'users_id'=>$users_id,'photo'=>$foto,'position'=>$position);
					$this->model_app->insert('pegawai',$dataPeg);
					$this->session->set_flashdata('success','Pegawai berhasil ditambah');
					redirect('pegawai');
				}
			
		
		}
	}

}

