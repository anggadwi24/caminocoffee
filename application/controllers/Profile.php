<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends CI_Controller 
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
    function index(){
        $data['row'] = $this->model_app->join_where2('users','pegawai','users_pegawai_id','pegawai_id',array('users_id'=>$this->id))->row_array();
        $data['title'] = 'PROFIL - '.title();
		$this->template->load('template','profile',$data);


    }
    function update(){
        if($this->input->method() == 'post'){
            $row = $this->model_app->join_where2('users','pegawai','users_pegawai_id','pegawai_id',array('users_id'=>$this->id))->row_array();
            // $golongan = $this->input->post('golongan');
            $pob = $this->input->post('pob');
            $dob = $this->input->post('dob');
         
            $config['upload_path']          = './upload/user/';
            $config['encrypt_name'] = TRUE;
            $config['allowed_types']        = 'gif|jpg|png|jpeg';
            $config['max_size']             = 5000;
                
                    
            $this->load->library('upload', $config);

            if ($this->upload->do_upload('file')){
                $upload_data = $this->upload->data();
                $foto = $upload_data['file_name'];
            }else{
                $foto = $row['pegawai_photo'];
            }
            $data = array('pegawai_email'=>$this->input->post('email'),'pegawai_religion'=>$this->input->post('agama'),'pegawai_phone'=>$this->input->post('hp'),'pegawai_address'=>$this->input->post('address'),'pegawai_gender'=>$this->input->post('jk'),'pegawai_pob'=>$pob,'pegawai_dob'=>$dob,'pegawai_photo'=>$foto);
            $this->model_app->update('pegawai',$data,array('pegawai_id'=>$row['users_pegawai_id']));
            $this->session->set_flashdata('success','Biodata berhasil disimpan');
            redirect('profile');
        }else{
            redirect('profile');
        }
    }
    function users(){
        if($this->input->method() == 'post'){
            $username = $this->input->post('username');
            $pwd = $this->input->post('pwd');
           
            $cek =$this->model_app->join_where2('users','pegawai','users_pegawai_id','pegawai_id',array('users_id'=>$this->id));
            if($cek->num_rows() > 0){
                $row = $cek->row_array();
                if(trim($pwd)){
                    $password = sha1($pwd);
                                
                }else{
                    $password = $row['users_password'];
                }
                if($row['users_username'] != $username){
                    $check = $this->db->query("SELECT * FROM users WHERE users_username = '".$username."' AND users_id != ".$this->id." ");
                    if($check->num_rows() > 0){
                        $this->session->set_flashdata('error',json_encode('username sudah digunakan'));
                        redirect('profile');
                    }else{
                        $data = array('users_username'=>$username,'users_password'=>$password);
                        $this->model_app->update('users',$data,array('users_id'=>$row['users_id']));
                        $this->session->set_flashdata('success','Data pengguna berhasil diubah');
                        redirect('profile');
                    }
                }else{
                    $data = array('users_username'=>$username,'users_password'=>$password);
                    $this->model_app->update('users',$data,array('users_id'=>$row['users_id']));
                    $this->session->set_flashdata('success','Data pengguna berhasil diubah');
                    redirect('profile');
                }
            }else{
                $this->session->set_flashdata('error',json_encode('Pengguna tidak ditemukan'));
                redirect('profile');
            }
        }else{
            redirect('profile');
        }
    }
}
