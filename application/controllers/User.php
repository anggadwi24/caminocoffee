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
			if($this->role =='admin' OR $this->role == 'camat'){

			}else{
				redirect('');
			}
		}else{
			redirect('auth');
		}	
	}
	public function index()
	{
		$data['title'] = 'USER - '.title();
		$data['pegawai'] = $this->model_app->view_order('pegawai','pegawai_name','ASC');
		$data['record'] = $this->model_app->view_order('users','users_id','DESC');
		$this->template->load('template','user',$data);
	}

	function store(){
		if($this->input->method() == 'post'){
			$this->form_validation->set_rules('username','Username','required|is_unique[users.users_username]');
			$this->form_validation->set_rules('pegawai','Pegawai','required');
			$this->form_validation->set_rules('role','Role','required');
			$this->form_validation->set_rules('password','Password','required|min_length[6]');


			$redirect = base_url('user');
			if($this->form_validation->run() == false){
					$msg = str_replace(array('<p>','</p>'),'',validation_errors());
					$status = false;
					$redirect = base_url('user');
					
				
			}else{
				$pegawai = $this->input->post('pegawai');
				$username = $this->input->post('username');
				$role = $this->input->post('role');
				$pwd = $this->input->post('password');

				$cek = $this->model_app->view_where('users',array('users_pegawai_id'=>$pegawai));
				if($cek->num_rows() > 0){
					$msg = 'Pegawai sudah memiliki akun';
					$status = false;
				}else{
					$password = sha1($pwd);
					$data = array('users_pegawai_id'=>$pegawai,'users_username'=>$username,'users_password'=>$password,'users_role'=>$role);
					$this->model_app->insert('users',$data);
					// $this->session->set_flashdata('success','Berhasil tambah user');
					// redirect('user');
					$msg = 'Berhasil tambah user';
					$status = true;
				}	
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'redirect'=>$redirect));
			
		}else{
			$this->session->set_flashdata('error','Wrong Method');
			redirect('user');
		}
	}

	function update(){
		if($this->input->method() == 'post'){
			if($this->session->userdata['isLog']['role'] == 'camat'){
				// $this->form_validation->set_rules('username','Username','required');
				$this->form_validation->set_rules('pegawai','Pegawai','required');
				$this->form_validation->set_rules('role','Role','required');
			
				$redirect = base_url('user');
				if($this->form_validation->run() == false){
						$msg = str_replace(array('<p>','</p>'),'',validation_errors());
						$status = false;
					
				}else{
					$id = $this->input->post('id');
					$pegawai = $this->input->post('pegawai');
					// $username = $this->input->post('username');
					$role = $this->input->post('role');
					// $pwd = $this->input->post('password');

					$cek = $this->model_app->view_where('users',array('users_id'=>$id));
					if($cek->num_rows() > 0){
						$row = $cek->row_array();
						if($row['users_pegawai_id'] != $pegawai){
							$peg = $this->model_app->view_where('users',array('users_pegawai_id'=>$pegawai));
							if($peg->num_rows() > 0){
								$msg = 'Pegawai sudah memiliki akun';
								$status = false;
								
							}else{
								$pgw = $pegawai;
								$status = true;
							}
						}else{
							$status = true;
							$pgw = $row['users_pegawai_id'];
						}

				

						if($status == true){
							
							$data = array('users_pegawai_id'=>$pgw,'users_role'=>$role);

							
							
							$this->model_app->update('users',$data,array('users_id'=>$id));
							$msg =  'Berhasil update user';
							$status = true;
						}else{
							$status = false;
						}
						

					}else{
						$status = false;
						$msg = 'User tidak ditemukan';
					
					}
				}
			}else{
				$this->form_validation->set_rules('username','Username','required');
				// $this->form_validation->set_rules('pegawai','Pegawai','required');
				// $this->form_validation->set_rules('role','Role','required');
			
				$redirect = base_url('user');
				if($this->form_validation->run() == false){
						$msg = str_replace(array('<p>','</p>'),'',validation_errors());
						$status = false;
					
				}else{
					$id = $this->input->post('id');
					// $pegawai = $this->input->post('pegawai');
					$username = $this->input->post('username');
					// $role = $this->input->post('role');
					$pwd = $this->input->post('password');

					$cek = $this->model_app->view_where('users',array('users_id'=>$id));
					if($cek->num_rows() > 0){
						$row = $cek->row_array();
						

						
							if($pwd != ''){
								$password = sha1($pwd);
								
							}else{
								$password = $row['users_password'];
							}
							
							if($row['users_username'] != $username){
								$cekUs = $this->db->query("SELECT * FROM users WHERE users_username = '$username' AND users_id != $id ");
								if($cekUs->num_rows() > 0){
									$status = false;
									$msg =  'Username sudah digunakan';
								
								}else{
									$username = $username;
									$status = true;
								}
							}else{
								$status = true;
								$username = $row['users_username'];

								
							}

							if($status == true){
							
								$data = array('users_username'=>$username,'users_password'=>$password);

								
								$this->model_app->update('users',$data,array('users_id'=>$id));
								$msg =  'Berhasil update user';
								$status = true;
							}else{
								$status = false;
							}
						
					}else{
						$status = false;
						$msg = 'User tidak ditemukan';
					
					}
				}
			}
			
			echo json_encode(array('status'=>$status,'msg'=>$msg,'redirect'=>$redirect));
		}
	}
	
	function detail(){
		if($this->input->method() == 'post'){
			$id = $this->input->post('id');
			$cek = $this->model_app->view_where('users',array('users_id'=>$id));
			$arr = null;
			if($cek->num_rows() > 0 ){
				$row = $cek->row_array();
				$peg = $this->model_app->view_where('pegawai',array('pegawai_id'=>$row['users_pegawai_id']));
				if($peg->num_rows() > 0 ){
					$status = true;
					$msg = null;
					$rows = $peg->row_array();
					$arr = array('id'=>$row['users_id'],'username'=>$row['users_username'],'pegawai'=>$rows['pegawai_name'],'role'=>$row['users_role'],'peg_id'=>$row['users_pegawai_id']);
				}else{
					$status  =false;
					$msg = 'Pegawai tidak ditemukan';
				}
			}else{
				$status = false;
				$msg = 'User tidak ditemukan';
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'data'=>$arr));
		}
	}
	function delete(){
		if($this->input->method() == 'get'){
			$id = $this->input->get('id');
			$cek = $this->model_app->view_where('users',array('users_id'=>$id));
			$arr = null;
			if($cek->num_rows() > 0 ){
				$this->model_app->delete('users',array('users_id'=>$id));
				$this->session->set_flashdata('success','Berhasil hapus user');
			}else{
				$this->session->set_flashdata('error',json_encode('User tidak ditemukan'));
			}
			redirect('user');
		}
	}
}

