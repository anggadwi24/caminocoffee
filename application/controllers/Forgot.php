<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Forgot extends CI_Controller 
{
	public function index()
	{
		if($this->session->userdata('isLog')){
			redirect('');
		}else{	
			$this->load->view('forgot');
		}
		
	}
	function do(){
		if($this->input->method() == 'post'){
			$email = $this->input->post('email');
			// $pwd = $this->input->post('password');
			$cek = $this->model_app->join_where_order2('pegawai','users','pegawai_id','users_pegawai_id',array('pegawai_email'=>$email),'pegawai_name','ASC');
			if($cek->num_rows() > 0){
				$row = $cek->row_array();
				$title = '[SISTEM PENJADWALAN AGENDA] - LUPA PASSWORD';
                $html = '<h3>Halo, '.$row['pegawai_name'].'</h3>';
                $html .= 'Anda menggunakan fitur lupa password, jika ini anda silahkan mengunjungi link dibawah <br>';
                $html .= base_url('forgot/reset?account=').encode($row['users_id'].'|'.date('YmdHis',strtotime('+15 Minutes')));
                pushEmail($email,$title,$html);
                $this->session->set_flashdata('success','Silahkan cek email anda');
				redirect('forgot');
                
			}else{
				$this->session->set_flashdata('erorr',json_encode('Akun tidak ditemukan'));
				redirect('forgot');
			}
		}else{
			redirect('forgot');
		}
	}
    function reset(){
        $acc = decode($this->input->get('account'));
        $ex = explode('|',$acc);
        $id = $ex[0];
        $time = $ex[1];
        // echo date('Y-m-d H:i:s',strtotime($time));

        if($acc != ''){
			$cek = $this->model_app->join_where_order2('pegawai','users','pegawai_id','users_pegawai_id',array('users_id'=>$id),'pegawai_name','ASC');
            if($cek->num_rows() > 0){
                if(date('Y-m-d H:i:s') < date('Y-m-d H:i:s',strtotime($time))){
                    $data['row'] = $cek->row_array();
                    $this->load->view('reset',$data);
                }else{
                    $this->session->set_flashdata('erorr',json_encode('Link sudah kadarluwasa'));
                    redirect('forgot');
                }
            }else{
                $this->session->set_flashdata('erorr',json_encode('Akun tidak ditemukan, silahkan ulangi masukan email'));
                redirect('forgot');
            }
            
        }else{
            $this->session->set_flashdata('erorr',json_encode('Format salah'));
            redirect('forgot');
        }
       
    }
    function doReset(){
        if($this->input->method() == 'post'){
            $id = decode($this->input->post('id'));
            $pwd = trim($this->input->post('password'));
            $repwd = trim($this->input->post('repassword'));
            if($pwd == $repwd){
			    $cek = $this->model_app->join_where_order2('pegawai','users','pegawai_id','users_pegawai_id',array('users_id'=>$id),'pegawai_name','ASC');
                if($cek->num_rows() > 0){
                    $password = sha1($pwd);
                    $this->model_app->update('users',array('users_password'=>$password),array('users_id'=>$id));
                    $this->session->set_flashdata('success','Password berhasil diubah, silahkan login');
                    redirect('auth');
                }else{
                    $this->session->set_flashdata('erorr',json_encode('Akun tidak ditemukan'));
                
                    redirect($_SERVER['HTTP_REFERER']);
                }
            
            }else{
                $this->session->set_flashdata('erorr',json_encode('Password tidak sama'));
                
                redirect($_SERVER['HTTP_REFERER']);
            }

        }else{
            redirect('auth');
        }
    }
}