<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller 
{
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
	}
	public function index()
	{
		if($this->session->userdata('isLog')){
			redirect('');
		}else{	
			$this->load->view('login');
		}
		
	}
	function do(){
		if($this->input->method() == 'post'){
			$username = $this->input->post('username');
			$pwd = $this->input->post('password');
			$this->form_validation->set_rules('password','Password','required');
			$this->form_validation->set_rules('username','Username','required');
			if($this->form_validation->run() == false){
				$this->load->view('login');
			}else{
				$cek = $this->model_app->view_where('users',array('username'=>$username));
				if($cek->num_rows() > 0){
					$row = $cek->row_array();
					if($row['active'] == 'y'){
						$checkPass = $this->model_app->view_where('users',array('username'=>$username,'password'=>sha1($pwd)));
						if($checkPass->num_rows() > 0){
							if($row['level'] == 'pegawai'){
								$pegawai = $this->model_app->view_where('pegawai',array('users_id'=>$row['id']));
								if($pegawai->num_rows() > 0 ){
									$child = $pegawai->row_array();
									$data = array('id'=>$row['id'],'username'=>$row['username'],'child'=>$child['id'],'role'=>$row['level'],'name'=>$child['name'],'photo'=>$child['photo']);
									$this->session->set_userdata('isLog',$data);
									redirect('/');
								}else{
									$this->session->set_flashdata('erorr',json_encode('Identitas pegawai tidak ditemukan'));
									redirect('auth');	
								}
							}else if($row['level'] == 'hrd'){
								$hrd = $this->model_app->view_where('hrd',array('users_id'=>$row['id']));
								if($hrd->num_rows() > 0 ){
									$child = $hrd->row_array();
									$data = array('id'=>$row['id'],'username'=>$row['username'],'child'=>$child['id'],'role'=>$row['level'],'name'=>$child['name'],'photo'=>$child['photo']);
									$this->session->set_userdata('isLog',$data);
									redirect('/');
								}else{
									$this->session->set_flashdata('erorr',json_encode('Identitas hrd tidak ditemukan'));
									redirect('auth');	
								}
							}else{
								$this->session->set_flashdata('erorr',json_encode('Akun tidak memiliki identitas'));
								redirect('auth');	
							}
						}else{
							$this->session->set_flashdata('erorr',json_encode('Password salah'));
							redirect('auth');	
						}
					}else{
						$this->session->set_flashdata('erorr',json_encode('Akun anda ditangguhkan'));
						redirect('auth');	
					}
					
				}else{
					$this->session->set_flashdata('erorr',json_encode('Akun tidak ditemukan'));
					redirect('auth');
				}
			}
			
		}else{
			redirect('auth');
		}
	}
}

