<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RekapAbsensi extends CI_Controller 
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
	public function index()
	{
        $data['title'] = 'Rekap Absensi';
        $data['record'] = $this->model_app->view('absensi');
        $this->template->load('template','hrd/contoh',$data);
    }
}