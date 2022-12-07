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
			$this->child = $this->session->userdata['isLog']['child'];

		}else{
			redirect('auth');
		}	
	}
    function index(){
        if($this->role == 'hrd'){
            $cek = $this->model_app->getHRDWhere(array('hrd.id'=>$this->child));
            if($cek->num_rows() > 0){
                $data['title'] = 'PROFIL - '.title();
                $data['page'] = 'Profil';
                $data['right'] = '';
                $data['row'] = $cek->row();
                $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
              
                $data['breadcrumb'] .= '<li class="breadcrumb-item active">Profil</li>';
                $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                $this->template->load('template','hrd/profile',$data);
                
            }else{
                $this->session->set_flashdata('error','Sesi telah berakhir');
                redirect('logout');
            }
        }else{
            $cek = $this->model_app->getUserWhere(array('pegawai.id'=>$this->child));
            if($cek->num_rows() > 0){
                $data['title'] = 'PROFIL - '.title();
                $data['page'] = 'Profil';
                $data['right'] = '';
                $data['row'] = $cek->row();
                $data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
              
                $data['breadcrumb'] .= '<li class="breadcrumb-item active">Profil</li>';
                $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
                $data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
                $this->template->load('template','pegawai/profile',$data);
                
            }else{
                $this->session->set_flashdata('error','Sesi telah berakhir');
                redirect('logout');
            }
        }
        


    }
    function update(){
        if($this->input->method() == 'post'){
           if($this->role == 'hrd'){
                $cek = $this->model_app->getHRDWhere(array('hrd.id'=>$this->child));
                if($cek->num_rows() > 0){
                    $row = $cek->row();
                    $id = $row->id;
                
                    $this->form_validation->set_rules('name','Nama','required');
                    $this->form_validation->set_rules('pob','Tempat Lahir','required');
                    $this->form_validation->set_rules('phone','Telepon/Hp','required');
                
                    $this->form_validation->set_rules('dob','Tanggal lahir','required');
                    $this->form_validation->set_rules('address','Alamat','required');
                







                    if($this->form_validation->run() == false){
                        
                        $this->index();
                        
                    }else{
                        
                        $name = $this->input->post('name');
            
                        
                        $pwd = $this->input->post('password');
                        $telp = $this->input->post('phone');
                        $alamat = $this->input->post('address');
                        $pob = $this->input->post('pob');
                        $dob = $this->input->post('dob');
                        $tgl = date('Y-m-d',strtotime($dob));
                    
                        
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
                        $this->model_app->update('hrd',$dataPeg,array('id'=>$this->child));
                        $this->session->set_flashdata('success','Profil berhasil disimpan');
                        redirect('profile');
                    }
                    
                }else{
                    $this->session->set_flashdata('error','Sesi telah berakhir');
                    redirect('logout');
                }

           }else{
            $cek = $this->model_app->getUserWhere(array('pegawai.id'=>$this->child));
			if($cek->num_rows() > 0){
				$row = $cek->row();
				$id = $row->id;
			
				$this->form_validation->set_rules('name','Nama','required');
				$this->form_validation->set_rules('pob','Tempat Lahir','required');
				$this->form_validation->set_rules('phone','Telepon/Hp','required');
			
				$this->form_validation->set_rules('dob','Tanggal lahir','required');
				$this->form_validation->set_rules('address','Alamat','required');
			







				if($this->form_validation->run() == false){
					
					$this->index();
					
				}else{
					
					$name = $this->input->post('name');
		
					
					$pwd = $this->input->post('password');
					$telp = $this->input->post('phone');
					$alamat = $this->input->post('address');
					$pob = $this->input->post('pob');
					$dob = $this->input->post('dob');
					$tgl = date('Y-m-d',strtotime($dob));
				
					
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
					$this->model_app->update('pegawai',$dataPeg,array('id'=>$this->child));
					$this->session->set_flashdata('success','Profil berhasil disimpan');
					redirect('profile');
				}
				
			}else{
				$this->session->set_flashdata('error','Sesi telah berakhir');
				redirect('logout');
			}
           }
        }else{
            redirect('profile');
        }
    }
    public function updateLogin(){
        if($this->input->method() == 'post'){
            if($this->role == 'hrd'){
                $cek = $this->model_app->getHRDWhere(array('hrd.id'=>$this->child));
                if($cek->num_rows() > 0){
                    $row = $cek->row();
                    $id = $row->id;
                    $this->form_validation->set_rules('username','Username','required|edit_unique[users.username.id.'.$id.']');
                
        
                    if($this->form_validation->run() == false){
                        
                        $this->index();
                        
                    }else{
                       
                        $username = $this->input->post('username');
                        $pwd = $this->input->post('password');
                       
                        if(trim($pwd)){
                            $password = sha1($pwd);
                        }else{
                            $password = $row->password;
                        }
                        
                        $data = array('username'=>$username,'password'=>$password,'level'=>'hrd');
                         $this->model_app->update('users',$data,array('username'=>$row->username));
                       
                        $this->session->set_flashdata('success','Data login berhasil disimpan');
                        redirect('profile');
                    }
                    
                }else{
                    $this->session->set_flashdata('error','Sesi telah berakhir');
                    redirect('logout');
                }
            }else{
                $cek = $this->model_app->getUserWhere(array('pegawai.id'=>$this->child));
                if($cek->num_rows() > 0){
                    $row = $cek->row();
                    $id = $row->id;
                    $this->form_validation->set_rules('username','Username','required|edit_unique[users.username.id.'.$id.']');
                
        
                    if($this->form_validation->run() == false){
                        
                        $this->index();
                        
                    }else{
                       
                        $username = $this->input->post('username');
                        $pwd = $this->input->post('password');
                       
                        if(trim($pwd)){
                            $password = sha1($pwd);
                        }else{
                            $password = $row->password;
                        }
                        
                        $data = array('username'=>$username,'password'=>$password,'level'=>'pegawai');
                         $this->model_app->update('users',$data,array('username'=>$row->username));
                       
                        $this->session->set_flashdata('success','Data login berhasil disimpan');
                        redirect('profile');
                    }
                    
                }else{
                    $this->session->set_flashdata('error','Sesi telah berakhir');
                    redirect('logout');
                }
            }
        }else{
            redirect('profile');
        }
    }

}
