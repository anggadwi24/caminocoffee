<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller 
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
		$data['title'] = 'HRD - '.title();
		$data['page'] = 'HRD';
		if($type == null OR $type =='grid'){
			$gridActive = 'active';
			$listActive = '';
			$data['type'] = 'grid';

		}else{
			$gridActive = '';
			$listActive = 'active';
			$data['type'] = 'list';
		}
		$data['right'] = ' <a href="'.base_url('user/add').'" class="btn add-btn" ><i class="fa fa-plus"></i> Tambah HRD</a>
							<div class="view-icons">
								<a href="'.base_url('user?type=grid').'" class="grid-view btn btn-link  '.$gridActive.'"><i class="fa fa-th"></i></a>
								<a href="'.base_url('user?type=list').'" class="list-view btn btn-link '.$listActive.'"><i class="fa fa-bars"></i></a>
							</div>';
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">HRD</li>';
		$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
		$keyword = $this->input->get('keyword');
		if(isset($keyword)){
			$data['record'] = $this->model_app->join_like_order2('hrd','users','users_id','id',array('name'=>$keyword),'hrd.id','desc');
			$data['keyword'] = $keyword;
		}else{
			$data['record'] = $this->model_app->join_order2('hrd','users','users_id','id','hrd.id','desc');
			$data['keyword'] = '';

		}
		if($type == 'grid' OR $type == null){
			$this->template->load('template','hrd/user',$data);
		}else{
			$this->template->load('template','hrd/user-list',$data);
		}
	
		

	}
	public function add(){
		$data['title'] = 'HRD - '.title();
		$data['page'] = 'HRD';
		$data['right'] = '';
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('user').'">HRD</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Tambah</li>';
		$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
		$this->template->load('template','hrd/user-add',$data);
	}
	function status(){
		if($this->input->method() == 'get'){
			$username = $this->uri->segment(3);
			$cek = $this->model_app->view_where('users',array('username'=>$username));
			if($cek->num_rows() > 0){
				$row = $cek->row();
				if($row->active == 'y'){
					$act ='n';
					$this->session->set_flashdata('success','HRD berhasil disuspend');

				}else if($row->active == 'n'){
					$act = 'y';
					$this->session->set_flashdata('success','HRD berhasil diaktifkan');
				}

				$this->model_app->update('users',array('active'=>$act),array('username'=>$username));
				
				redirect('user');
			}else{
				$this->session->set_flashdata('error','HRD tidak ditemukan');
				redirect('user');
			}
			
		}else{
			$this->session->set_flashdata('error','Wrong method');
			redirect('pegawai');
		}

		
	}
	function detail($username){
		$cek = $this->model_app->getHRDWhere(array('username'=>$username));
		if($cek->num_rows() > 0){
			$data['title'] = 'HRD - '.title();
			$data['page'] = 'HRD';
			$data['right'] = '';
			$data['row'] = $cek->row();
			$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
			$data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('user').'">HRD</a></li>';
			$data['breadcrumb'] .= '<li class="breadcrumb-item active">Detail</li>';
			$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
			$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
			$this->template->load('template','hrd/user-detail',$data);
			
		}else{
			$this->session->set_flashdata('error','HRD tidak ditemukan');
			redirect('user');
		}
	}
	function edit($username){
		
			
			$cek = $this->model_app->join_where2('hrd','users','users_id','id',array('username'=>$username));
			if($cek->num_rows() > 0){
				$data['title'] = 'HRD - '.title();
				$data['page'] = 'HRD';
				$data['right'] = '';
				$data['row'] = $cek->row();
				$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
				$data['breadcrumb'] .= '<li class="breadcrumb-item"><a href="'.base_url('user').'">HRD</a></li>';
				$data['breadcrumb'] .= '<li class="breadcrumb-item active">Edit</li>';
				$data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
				$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
				$this->template->load('template','hrd/user-edit',$data);
				
			}else{
				$this->session->set_flashdata('error','HRD tidak ditemukan');
				redirect('user');
			}
			
		

		
	}
	function update($username){
		if($this->input->method() == 'post'){
			$cek = $this->model_app->getHRDWhere(array('username'=>$username));
			if($cek->num_rows() > 0){
				$row = $cek->row();
				$id = $row->id;
				$this->form_validation->set_rules('username','Username','required|edit_unique[users.username.id.'.$id.']');
				$this->form_validation->set_rules('name','Nama','required');
				$this->form_validation->set_rules('pob','Tempat Lahir','required');
				$this->form_validation->set_rules('phone','Telepon/Hp','required');
			
				$this->form_validation->set_rules('dob','Tanggal lahir','required');
				$this->form_validation->set_rules('address','Alamat','required');
			







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
					$tgl = date('Y-m-d',strtotime($dob));
					if(trim($pwd)){
						$password = sha1($pwd);
					}else{
						$password = $row->password;
					}
					
					$data = array('username'=>$username,'password'=>$password,'level'=>'hrd');
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
								 'pob'=>$pob,'dob'=>$tgl,'photo'=>$foto);
					$this->model_app->update('hrd',$dataPeg,array('id'=>$row->hrd_id));
					$this->session->set_flashdata('success','HRD berhasil ditambah');
					redirect('user');
				}
				
			}else{
				$this->session->set_flashdata('error','HRD tidak ditemukan');
				redirect('user');
			}
		}else{
			$this->session->set_flashdata('error','Wrong method');
			redirect('user');
		
		}
	}
	function delete(){
		if($this->input->method() == 'post'){
			
				$id = $this->input->post('username');

				$cek = $this->model_app->view_where('users',array('username'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row();
					$this->model_app->delete('hrd',array('users_id'=>$row->id));
					$this->model_app->delete('users',array('username'=>$id));
					$this->session->set_flashdata('success','HRD berhasil dihapus');
					redirect('user');
				}else{
					$this->session->set_flashdata('error',json_encode('HRD tidak ditemukan'));
					redirect('user');
				}
			
		}else{
			$this->session->set_flashdata('error',json_encode('Wrong Method'));
			redirect('user');
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
				
					$tgl = date('Y-m-d',strtotime($dob));
				
					$data = array('username'=>$username,'password'=>sha1($pwd),'active'=>'y','level'=>'hrd');
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
								 'pob'=>$pob,'dob'=>$tgl,'users_id'=>$users_id,'photo'=>$foto);
					$this->model_app->insert('hrd',$dataPeg);
					$this->session->set_flashdata('success','HRD berhasil ditambah');
					redirect('user');
				}
			
		
		}
	}

}

