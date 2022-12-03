<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Hasil extends CI_Controller 
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
	public function index()
	{
		$data['title'] = 'HASIL KEGIATAN - '.title();
		$bulan = $this->input->get('bulan');
		$kategori = $this->input->get('kategori');

	
		if($bulan != 'all' AND $bulan != null){
			$where = 'AND MONTH(agenda_date_start) = "'.$bulan.'" ';
		}else{
			$where = '';
		}
		if($kategori != 'all' AND $kategori != null){
			$where .= 'AND agenda_kategori = "'.$kategori.'" ';
		}else{
			$where .= '';
		}

		
		$data['record'] = $this->db->query("SELECT * FROM `agenda` JOIN `hasil_kegiatan` ON `agenda`.`agenda_id`=`hasil_kegiatan`.`hk_agenda_id` WHERE agenda_status = 'done' $where ORDER BY `hk_id` DESC");



		$this->template->load('template','hasil',$data);
	}
	public function detail()
	{
		$id = $this->input->get('id');
		if($id == NULL OR $id == ''){
			redirect('hasil');
		}else{
			$cek  = $this->model_app->join_where2('agenda','hasil_kegiatan','agenda_id','hk_agenda_id',array('agenda_id'=>$id));
			if($cek->num_rows() > 0){
				$row = $cek->row_array();
				$data['row'] = $cek->row_array();
				$data['title'] = $row['agenda_name'].' - '.title();	 
				$data['validasi'] = $this->model_app->view_where('validasi',array('valid_agenda_id'=>$id))->row_array();
				$this->template->load('template','hasil_detail',$data);
			}else{
				$this->session->set_flashdata('error',json_encode('Hasil kegiatan tidak ditemukan'));
				redirect('hasil');
			}

		}
		
	}
	public function edit()
	{
		$id = $this->input->get('id');
		if($this->role == 'admin'){
			if($id == NULL OR $id == ''){
				redirect('hasil');
			}else{
				$cek  = $this->model_app->join_where2('agenda','hasil_kegiatan','agenda_id','hk_agenda_id',array('agenda_id'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row_array();
					$data['row'] = $cek->row_array();
					$data['title'] = $row['agenda_name'].' - '.title();	 
					$this->template->load('template','hasil_edit',$data);
				}else{
					$this->session->set_flashdata('error',json_encode('Hasil kegiatan tidak ditemukan'));
					redirect('hasil');
				}
	
			}
		}else{
			$this->session->set_flashdata('error',json_encode('Tidak bisa mengakses halaman tersebut'));
			redirect('hasil');
		}
		
		
	}
	function delete(){
		if($this->role == 'admin'){
			$id = $this->input->get('id');
			$cek  = $this->model_app->join_where2('agenda','hasil_kegiatan','agenda_id','hk_agenda_id',array('agenda_id'=>$id));
			if($cek->num_rows() > 0){
				$row = $cek->row_array();
				$this->model_app->update('agenda',array('agenda_status'=>'undone'),array('agenda_id'=>$id));
				$this->model_app->delete('hasil_kegiatan',array('hk_agenda_id'=>$id));
				$this->session->set_flashdata('success','Hasil kegiatan berhasil dihapus');
				redirect('agenda/detail?id='.$id);
			}else{
				$this->session->set_flashdata('error',json_encode('Hasil kegiatan tidak ditemukan'));
				redirect('hasil');
			
				
			}
		}else{
			$this->session->set_flashdata('error',json_encode('Tidak bisa mengakses halaman tersebut'));
			redirect('hasil');
		}
	}
	function update(){
		if($this->input->method() == 'post'){
			$id = $this->input->post('id');
			if($this->role == 'admin'){
				$cek = $this->model_app->view_where('hasil_kegiatan',array('hk_id'=>$id));
				if($cek->num_rows() > 0 ){
					$rows = $cek->row_array();
					$cekA = $this->model_app->view_where('agenda',array('agenda_id'=>$rows['hk_agenda_id']));
					if($cekA->num_rows() > 0){
						$row = $cekA->row_array();
						$notulen = $this->input->post('notulen');
						$this->model_app->update('hasil_kegiatan',array('hk_notulen'=>$notulen),array('hk_id'=>$id));
						$count = count($_FILES['files']['name']);
						if($count > 0){
							for($x=0;$x<$count;$x++){
								if(!empty($_FILES['files']['name'][$x])){
									$_FILES['file']['name'] = $_FILES['files']['name'][$x];
									$_FILES['file']['type'] = $_FILES['files']['type'][$x];
									$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$x];
									$_FILES['file']['error'] = $_FILES['files']['error'][$x];
									$_FILES['file']['size'] = $_FILES['files']['size'][$x];
							
									$config2['upload_path']          = './upload/agenda/';
									$config2['encrypt_name']         = TRUE;
									$config2['allowed_types']        = 'gif|jpg|png|jpeg';
									$config2['max_size']             = 5000;
										
											
									$this->load->library('upload', $config2,'gallery');
									$this->gallery->initialize($config2);
									$this->gallery->do_upload('file');
									
									
									$uploadData = $this->gallery->data();
									$images = $uploadData['file_name'];
									$dataP = array(
										'hki_hk_id'=>$id,
										'hki_url'=>$images,
										
										);
									$this->model_app->insert('hasil_kegiatan_image',$dataP);
								}
							}
						}
					
						// $nama = $this->input->post('name');
						// $tempat = $this->input->post('place');
						
						// $start = $this->input->post('start');
						// $end = $this->input->post('end');
						
						// $kategori = $this->input->post('kategori');
						// $perihal = $this->input->post('perihal');
						// // $dateStart = date('Y-m-d H:i:s',strtotime($start));
						// // $dateEnd = date('Y-m-d H:i:s',strtotime($end));
						// $staff = $this->input->post('pegawai');

						// $data = array('agenda_name'=>$nama,'agenda_place'=>$tempat,'agenda_perihal'=>$perihal,'agenda_kategori'=>$kategori);
						// $this->model_app->update('agenda',$data,array('agenda_id'=>$row['agenda_id']));
						// if(count($staff) > 0){
			
						// 	$this->model_app->delete('agenda_pegawai',array('ap_agenda_id'=>$row['agenda_id']));
						// 	for($a=0;$a<count($staff);$a++){
								
								
						// 			$dataS = array('ap_agenda_id'=>$row['agenda_id'],'ap_pegawai_id'=>$staff[$a]);
						// 			$this->model_app->insert('agenda_pegawai',$dataS);
								
								
						// 	}
							
						// }
						$status = true;
						$msg = 'Hasil kegiatan berhasil diupdate';
						// $this->session->set_flashdata('success','Hasil kegiatan berhasil diupdate');
						$redirect = base_url('hasil/detail?id='.$row['agenda_id']);

					}else{
						$status = true;
						$msg = 'Agenda tidak ditemukan';
						// $this->session->set_flashdata('success','Hasil kegiatan berhasil diupdate');
						$redirect = base_url('hasil/');
						// $this->session->set_flashdata('error',json_encode('Agenda tidak ditemukan'));
						// redirect('hasil');
					}
				}else{
					$status = true;
					$msg = 'Hasil kegiatan tidak ditemukan';
					// $this->session->set_flashdata('success','Hasil kegiatan berhasil diupdate');
					$redirect = base_url('hasil/');
					// $this->session->set_flashdata('error',json_encode('Hasil kegiatan tidak ditemukan'));
					// redirect('hasil');
				}
			}else{
				$status = true;
				$msg = 'Tidak bisa mengakses halaman tersebut';
				// $this->session->set_flashdata('success','Hasil kegiatan berhasil diupdate');
				$redirect = base_url('hasil/');
				// $this->session->set_flashdata('error',json_encode('Tidak bisa mengakses halaman tersebut'));
				// redirect('hasil');
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'redirect'=>$redirect));
		}
	}
	function storeCatatan(){
		if($this->input->method() == 'post'){
			if($this->role == 'admin'){
				$id = $this->input->post('id');
				$cek  = $this->model_app->join_where2('agenda','hasil_kegiatan','agenda_id','hk_agenda_id',array('hk_id'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row_array();
					$catatan = $this->input->post('catatan');
					$data = array(
						'hkc_catatan' => $catatan,
						'hkc_hk_id' => $id,
						'hkc_created_by'=>$this->id,
					);
				
					$this->model_app->insert('hasil_kegiatan_catatan',$data);
					$this->session->set_flashdata('success','Catatan berhasil ditambah');
					redirect('hasil/detail?id='.$row['agenda_id']);
				}else{
					$this->session->set_flashdata('error',json_encode('Hasil kegiatan tidak ditemukan'));
					redirect('hasil');
				}
			}else{
				$this->session->set_flashdata('error',json_encode('Hasil kegiatan tidak ditemukan'));
				redirect('hasil');
			}
			

			
		}
	}
	function deleteDokumentasi(){
		if($this->input->method() == 'get'){
			if($this->role == 'admin'){
				$id = $this->input->get('id');
			$this->model_app->delete('hasil_kegiatan_image',array('hki_id'=>$id));
			$this->session->set_flashdata('success','Dokumentasi berhasil dihapus');
			redirect('hasil/detail?id='.$this->input->get('agenda'));
			}
			
		}else{
			$this->session->set_flashdata('error',json_encode('Halaman tidak bisa diakses'));
			redirect('hasil');
		}
	}
	function storeDokumentasi(){
		if($this->input->method() == 'post'){
			if($this->role == 'admin'){
				$id = $this->input->post('id');
				$cek  = $this->model_app->join_where2('agenda','hasil_kegiatan','agenda_id','hk_agenda_id',array('hk_id'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row_array();
					$catatan = $this->input->post('catatan');
					$count = count($_FILES['files']['name']);
					if($count > 0){
						for($x=0;$x<$count;$x++){
							if(!empty($_FILES['files']['name'][$x])){
								$_FILES['file']['name'] = $_FILES['files']['name'][$x];
								$_FILES['file']['type'] = $_FILES['files']['type'][$x];
								$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$x];
								$_FILES['file']['error'] = $_FILES['files']['error'][$x];
								$_FILES['file']['size'] = $_FILES['files']['size'][$x];
						
								$config2['upload_path']          = './upload/agenda/';
								$config2['encrypt_name']         = TRUE;
								$config2['allowed_types']        = 'gif|jpg|png|jpeg';
								$config2['max_size']             = 5000;
									
										
								$this->load->library('upload', $config2,'gallery');
								$this->gallery->initialize($config2);
								$this->gallery->do_upload('file');
								
								
								$uploadData = $this->gallery->data();
								$images = $uploadData['file_name'];
								$dataP = array(
									'hki_hk_id'=>$id,
									'hki_url'=>$images,
									
									);
								$this->model_app->insert('hasil_kegiatan_image',$dataP);
							}
						}
					}
					$status = true;
					$msg = 'Dokumentasi berhasil ditambah';
					// $this->session->set_flashdata('success','Dokumentasi berhasil ditambah');
					$redirect = base_url('hasil/detail?id='.$row['agenda_id']);
				}else{
					$status = false;
					$msg = 'Hasil kegiatan tidak ditemukan';
					
					$redirect = base_url('hasil');
				}
			}else{
				$status = false;
				$msg = 'Tidak bisa mengakses aksi ini';
				
				$redirect = base_url('hasil');
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'redirect'=>$redirect));

			
		}
	}
	function store(){
		if($this->input->method() == 'post'){
			$id = $this->input->post('id');
			$cek = $this->model_app->view_where('agenda',array('agenda_id'=>$id));
			if($cek->num_rows() > 0){
				$row = $cek->row_array();
				if($row['agenda_validasi'] == 'y'){
					if($row['agenda_status'] == 'undone'){
						$this->model_app->update('agenda',array('agenda_status'=>'done'),array('agenda_id'=>$id));
						$hk_id = $this->model_app->insert_id('hasil_kegiatan',array('hk_agenda_id'=>$id,'hk_notulen'=>$this->input->post('notulen'),'hk_created_by'=>$this->id));
						$count = count($_FILES['files']['name']);
						if($count > 0){
						for($x=0;$x<$count;$x++){
								if(!empty($_FILES['files']['name'][$x])){
									$_FILES['file']['name'] = $_FILES['files']['name'][$x];
									$_FILES['file']['type'] = $_FILES['files']['type'][$x];
									$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$x];
									$_FILES['file']['error'] = $_FILES['files']['error'][$x];
									$_FILES['file']['size'] = $_FILES['files']['size'][$x];
							
									$config2['upload_path']          = './upload/agenda/';
									$config2['encrypt_name']         = TRUE;
									$config2['allowed_types']        = 'gif|jpg|png|jpeg';
									$config2['max_size']             = 5000;
										
											
									$this->load->library('upload', $config2,'gallery');
									$this->gallery->initialize($config2);
									$this->gallery->do_upload('file');
									
									
									$uploadData = $this->gallery->data();
									$images = $uploadData['file_name'];
									$dataP = array(
										'hki_hk_id'=>$hk_id,
										'hki_url'=>$images,
										
										);
									$this->model_app->insert('hasil_kegiatan_image',$dataP);
								}
							}
						}
						$status = true;
						$msg = 'Data berhasil disimpan';
						$redirect = base_url('hasil/detail?id='.$id);
						// $this->session->set_flashdata('success','Data berhasil disimpan');
						// redirect('hasil/detail?id='.$id);
					}else{
						// $this->session->set_flashdata('error',json_encode('Agenda sudah selesai'));
						// redirect('agenda');
						$status = false;
						$msg = 'Agenda sudah selesai';
						$redirect = base_url('agenda');
					}	
					
				
				}else{
					// $this->session->set_flashdata('error',json_encode('Agenda belum divalidasi'));
					// redirect('agenda');
					$status = false;
					$msg = 'Agenda belum divalidasi';
					$redirect = base_url('agenda');
				}
			}else{
				
				// $this->session->set_flashdata('error',json_encode('Agenda tidak ditemukan'));
				// redirect('hasil');
				$status = false;
				$msg = 'Agenda tidak ditemukan';
				$redirect = base_url('agenda');
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'redirect'=>$redirect));
		}
	}
	

	public function add_hasil()
	{
		$this->template->load('template','hasil_add');
	}

	

	
}
