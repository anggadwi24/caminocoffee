<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Agenda extends CI_Controller 
{
	public function __construct()
	{
        parent::__construct();
    	if($this->session->userdata('isLog')){
		
			$row = $this->model_app->join_where2('users','pegawai','users_pegawai_id','pegawai_id',array('users_id'=>$this->session->userdata['isLog']['id']))->row_array();
			$this->id = $row['pegawai_id'];
			$this->role = $this->session->userdata['isLog']['role'];
		}else{
			redirect('auth');
		}	
	}
	function sendMessage() {
		$chatID = '@agendakutaselatan';
		$token = '5457890316:AAGHv_Tmr9UWH-Ke6QrHU4_PYYyvBxOmsb0';
		$messaggio = 'Haa';
		// echo "sending message to " . $chatID . "\n";
	
		$url = "https://api.telegram.org/bot" . $token . "/sendMessage?chat_id=" . $chatID;
		$url = $url . "&text=" . urlencode($messaggio);
		$ch = curl_init();
		$optArray = array(
				CURLOPT_URL => $url,
				CURLOPT_RETURNTRANSFER => true
		);
		curl_setopt_array($ch, $optArray);
		$result = curl_exec($ch);
		curl_close($ch);
		print_r($result);
	}
	public function index()
	{
		$data['title'] = 'AGENDA - '.title();
		$data['staff'] = $this->model_app->join_where_order2('pegawai','users','pegawai_id','users_pegawai_id',array('users_role !='=>'camat'),'pegawai_name','ASC');
		// $data['staff'] = $this->model_app->view_where('pegawai',array('pegawai_visible'=>'y'));
		$status = $this->input->get('status');
		$kategori = $this->input->get('kategori');
		$bulan = $this->input->get('bulan');
		$kehadiran = $this->input->get('kehadiran');

		
		if($this->role == 'admin' OR $this->role =='camat'){
			// $data['record'] = $this->model_app->view_order('agenda','agenda_id','DESC');
			if($status == 'sudah'){
				$where = 'WHERE agenda_validasi = "y" AND agenda_validasi_by IS NOT NULL AND agenda_status = "undone"';
			}else if($status == 'belum'){
				$where = 'WHERE agenda_validasi = "n" AND agenda_status = "undone" AND agenda_validasi_by IS NULL';
	
			}else if($status == 'ditolak'){
				$where = 'WHERE agenda_validasi = "y" AND agenda_status = "ditolak"';
			}else{
				$where =' WHERE  agenda_status != "done"';
			}

			if($bulan != 'all' AND $bulan != NULL){
				$where .= ' AND MONTH(agenda_date_start) = "'.$bulan.'"';
			}else{
				$where .= '';
				
			}
			if($kategori != 'all' AND $kategori != NULL){
				
				$where .= ' AND agenda_kategori = "'.$kategori.'"';
			}else{
				$where .= '';
			}
			if($this->role == 'admin'){
				if($kehadiran == 'ya'){
					$where .= ' AND ap_pegawai_id = "'.$this->id.'" ';
					$data['record'] = $this->db->query('select * from  agenda a JOIN agenda_pegawai b ON a.agenda_id = b.ap_agenda_id  '.$where.' ');
				
				}else{
					$data['record'] = $this->db->query("SELECT * FROM agenda $where ORDER BY agenda_id DESC ");

				}
			}else{
				if($kehadiran == 'ya'){
					$where .= 'AND agenda_kehadiran_camat = "y"';
					$data['record'] = $this->db->query("SELECT * FROM agenda  $where ORDER BY agenda_id DESC ");

				}else{
					$data['record'] = $this->db->query("SELECT * FROM agenda $where ORDER BY agenda_id DESC ");

				}
			}
		}else{
			if($bulan != 'all' AND $bulan != NULL){
				$where = ' AND MONTH(agenda_date_start) = "'.$bulan.'"';
			}else{
				$where = '';
				
			}
			if($kategori != 'all' AND $kategori != NULL){
				
				$where .= ' AND agenda_kategori = "'.$kategori.'"';
			}else{
				$where .= '';
			}
			if($kehadiran == 'ya'){
				$where .= ' AND ap_pegawai_id = "'.$this->id.'" ';
				$data['record'] = $this->db->query('select * from  agenda a JOIN agenda_pegawai b ON a.agenda_id = b.ap_agenda_id WHERE agenda_validasi = "y" AND agenda_validasi_by IS NOT NULL AND agenda_status != "done" AND agenda_status != "ditolak"  '.$where.' ');
			}else{
				$data['record'] = $this->db->query("SELECT * FROM agenda WHERE agenda_validasi = 'y' AND agenda_validasi_by IS NOT NULL AND agenda_status != 'done' AND agenda_status != 'ditolak'  $where ORDER BY agenda_id DESC");

			}
			// $data['record'] = $this->model_app->view_where_ordering('agenda',array('agenda_validasi'=>'y','agenda_validasi_by != '=>NULL,'agenda_status !='=>'done'),'agenda_id','DESC');
		}
		
		$this->template->load('template','agenda',$data);
	}
	public function detail()
	{
		$id = $this->input->get('id');
		$cek = $this->model_app->view_where('agenda',array('agenda_id'=>$id));
		if($cek->num_rows() > 0){
			$row = $cek->row_array();
			$data['title'] = strtoupper($row['agenda_name']).' - '.title();
			$data['row'] = $row;
			$this->template->load('template','agenda_detail',$data);
		}else{
			$this->session->set_flashdata('error',json_encode('Agenda tidak ditemukan'));
			redirect('agenda');
		}
		
	}
	function laporan(){
		$id = $this->input->get('id');
		if($id != ''){
			$cek = $this->model_app->view_where('agenda',array('agenda_id'=>$id));
			if($cek->num_rows() > 0){
				$row = $cek->row_array();
				if($row['agenda_status'] == 'done'){
					$data['row'] = $row;
					$data['valid'] = $this->model_app->view_where('validasi',array('valid_agenda_id'=>$row['agenda_id']))->row_array();
					$data['pj'] = $this->model_app->view_where('pegawai',array('pegawai_id'=>$row['agenda_penanggung_jawab']))->row_array();
					$data['hasil'] = $this->model_app->view_where('hasil_kegiatan',array('hk_agenda_id'=>$row['agenda_id']))->row_array();

					$title = 'LAPORAN AGENDA - '.$row['agenda_name'];
					// $this->load->view('laporan_agenda',$data);
					$html = $this->load->view('laporan_agenda',$data,true);
					pdf_create($html,$title,'A4','potrait');
				}else{
					$this->session->set_flashdata('error',json_encode('Agenda dalam status '.$row['agenda_status']));
					redirect('agenda');
				}
			}else{
				$this->session->set_flashdata('error',json_encode('Agenda tidak ditemukan'));
				redirect('agenda');
			}
		}else{
			$this->session->set_flashdata('error',json_encode('Format Salah'));
			redirect('agenda');
		}
	}
	public function edit()
	{
		if($this->role == 'admin'){
			$id = $this->input->get('id');
			$cek = $this->model_app->view_where('agenda',array('agenda_id'=>$id));
			if($cek->num_rows() > 0){
				$row = $cek->row_array();
				if($row['agenda_status'] == 'undone' OR $row['agenda_status'] == 'ditolak'){
					$data['title'] = strtoupper($row['agenda_name']).' - '.title();
					// $data['staff'] = $this->model_app->view_where('pegawai',array('pegawai_visible'=>'y'));
					// $data['staff'] = $this->model_app->join_where_order2('pegawai','users','pegawai_id','users_pegawai_id',array('users_role'=>'pegawai'),'pegawai_name','ASC');
					$data['staff'] = $this->model_app->join_where_order2('pegawai','users','pegawai_id','users_pegawai_id',array('users_role !='=>'camat'),'pegawai_name','ASC');

					$data['row'] = $row;
					$this->template->load('template','agenda_edit',$data);
				}else{
					$this->session->set_flashdata('error',json_encode('Agenda sudah selesai'));
					redirect('agenda/detail?id='.$id);
				}
				
			}else{
				$this->session->set_flashdata('error',json_encode('Agenda tidak ditemukan'));
				redirect('agenda');
			}
		}else{
			$this->session->set_flashdata('error',json_encode('Anda tidak bisa mengakses halaman ini'));
			redirect('agenda');
		}
		
		
	}
	function update(){
		
		if($this->input->method() == 'post'){
			if($this->role == 'admin'){
				$id = $this->input->post('id');
				$cek = $this->model_app->view_where('agenda',array('agenda_id'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row_array();
					if($row['agenda_status'] == 'undone' OR $row['agenda_status'] == 'ditolak'){
						$config['upload_path']          = './upload/agenda/';
						$config['encrypt_name'] = TRUE;
						$config['allowed_types']        = 'pdf';
						$config['max_size']             = 20000;
							
								
						$this->load->library('upload', $config);

						if ($this->upload->do_upload('surat')){
							$upload_data = $this->upload->data();
							$surat = $upload_data['file_name'];
						}else{
							$surat = $row['agenda_surat'];
						}
						// $kode = $this->input->post('code');

						
						$nama = $this->input->post('name');
						$tempat = $this->input->post('place');
						$penyelenggara = $this->input->post('penyelenggara');
						$start = $this->input->post('start');
						$end = $this->input->post('end');
						$pj = $this->input->post('pj');
						$kategori = $this->input->post('kategori');
						$perihal = $this->input->post('perihal');
						$dateStart = date('Y-m-d H:i:s',strtotime($start));
						$dateEnd = date('Y-m-d H:i:s',strtotime($end));
						$staff = $this->input->post('pegawai');
						$kehadiran = $this->input->post('kehadiran');
						if($kehadiran == 'hadir'){
							$kehadiran = 'y';
						}else{
							$kehadiran = 'n';
						}
						// $status = true;
						if($row['agenda_status'] == 'ditolak'){
						$data = array('agenda_name'=>$nama,'agenda_place'=>$tempat,'agenda_status'=>'undone','agenda_validasi'=>'n','agenda_validasi_by'=>NULL,'agenda_surat'=>$surat,'agenda_penyelenggara'=>$penyelenggara,'agenda_date_start'=>$dateStart,'agenda_date_end'=>$dateEnd,'agenda_penanggung_jawab'=>$pj,'agenda_perihal'=>$perihal,'agenda_kategori'=>$kategori,'agenda_kehadiran_camat'=>$kehadiran);
						$this->model_app->delete('validasi',array('valid_agenda_id'=>$id));
						}else{
						$data = array('agenda_name'=>$nama,'agenda_place'=>$tempat,'agenda_surat'=>$surat,'agenda_penyelenggara'=>$penyelenggara,'agenda_date_start'=>$dateStart,'agenda_date_end'=>$dateEnd,'agenda_penanggung_jawab'=>$pj,'agenda_perihal'=>$perihal,'agenda_kategori'=>$kategori);

						}

						

					
						if(count($staff) > 0){
							$this->model_app->update('agenda',$data,array('agenda_id'=>$id));
							$this->model_app->delete('agenda_pegawai',array('ap_agenda_id'=>$id));
							for($a=0;$a<count($staff);$a++){
								
							
									$dataS = array('ap_agenda_id'=>$id,'ap_pegawai_id'=>$staff[$a]);
									$this->model_app->insert('agenda_pegawai',$dataS);
								
								
							}
							if($row['agenda_status'] == 'ditolak'){
								$msg = 'Agenda berhasil diperbaiki';
								// $text = 'Selamat pagi dan selamat datang di<br>Sistem Informasi Penjadwalan Agenda Kegiatan<br><br>Kepada Yth. Bpk camat kuta selatan,<br>Diinformasikan dan dimohon untuk memvalidasi data jadwal<br>agenda kegiatan yang baru saja direvisi dengan kode '.$row['agenda_code'].'<br>Link terlampir : '.base_url('agenda/detail?id='.$id).'<br><br>Kami ucapkan terima kasih dan selamat beraktifitas.<br><br>Salam hormat, <br>Sekretariat Kecamatan Kuta Selatan.';
								$text = 'Selamat pagi dan selamat datang di<br>Sistem Informasi Penjadwalan Agenda Kegiatan<br><br>Kepada Yth. Bpk camat kuta selatan,<br>Diinformasikan telah ditambahkan kembali data jadwal agenda kegiatan dengan kode '.$row['agenda_code'].' yang telah ditolak sebelumya.<br>Dimohon untuk memeriksa dan memberi tindakan terhadap informasi agenda kegiatan tersebut. <br>Link terlampir : '.base_url('agenda/detail?id='.$id).'<br><br>Kami ucapkan terima kasih dan selamat beraktifitas.<br><br>Salam hormat, <br>Sekretariat Kecamatan Kuta Selatan.';
								
								pushTelegram($text);
							}else{
								$msg = 'Agenda berhasil diupdate';
							}
							
							$status = true;
							$redirect = base_url('agenda/detail?id='.$id);
							
						}else{
						
							$msg = 'Tidak ada staff yang dipilih';
							$status = false;
							$redirect = base_url('agenda/detail?id='.$id);

						}
					
					
					}else{
						$msg = 'Agenda sudah selesai';
						$status = false;
						$redirect = base_url('agenda/detail?id='.$id);
					}
					
				}else{
					$msg = 'Anda tidak bisa mengakses halaman ini';
							$status = false;
							$redirect = base_url('agenda/');
					// $this->session->set_flashdata('error',json_encode('Anda tidak bisa mengakses halaman ini'));
					// redirect('agenda');
				}
			}else{
				$msg = 'Anda tidak bisa mengakses halaman ini';
							$status = false;
							$redirect = base_url('agenda/');
			}
			echo json_encode(array('msg'=>$msg,'status'=>$status,'redirect'=>$redirect));
		}
	}
	function intruksi(){
		if($this->input->method() == 'post'){
			if($this->role == 'camat'){
				$id = $this->input->post('id');
				$cek = $this->model_app->view_where('agenda',array('agenda_id'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row_array();
					$cekV = $this->model_app->view_where('validasi',array('valid_agenda_id'=>$id));
					if($cekV->num_rows() > 0){
						$rowV=  $cekV->row_array();
						$this->model_app->update('validasi',array('valid_intruksi'=>$this->input->post('intruksi')),array('valid_id'=>$rowV['valid_id']));
						$status = $this->input->post('status');
						if($status == 'validasi'){
							$data = array('agenda_validasi'=>'y','agenda_status'=>'undone','agenda_validasi_by'=>$this->id);
							$this->model_app->update('agenda',$data,array('agenda_id'=>$id));
							$msg = 'Agenda berhasil divalidasi';
						}else{
							$data = array('agenda_validasi'=>'y','agenda_status'=>'ditolak','agenda_validasi_by'=>$this->id);
							$this->model_app->update('agenda',$data,array('agenda_id'=>$id));
							$msg = 'Agenda berhasil ditolak';
						}
						$msg = 'Intruksi berhasil diubah';
						$redirect = base_url('agenda/detail?id='.$id);
						$status = true;
					}else{
						$status = $this->input->post('status');
						if($status == 'validasi'){
							$data = array('agenda_validasi'=>'y','agenda_status'=>'undone','agenda_validasi_by'=>$this->id);
							$this->model_app->update('agenda',$data,array('agenda_id'=>$id));
							$msg = 'Agenda berhasil divalidasi';
						}else{
							$data = array('agenda_validasi'=>'y','agenda_status'=>'ditolak','agenda_validasi_by'=>$this->id);
							$this->model_app->update('agenda',$data,array('agenda_id'=>$id));
							$msg = 'Agenda berhasil ditolak';
						}
						

						$dataV = array('valid_agenda_id'=>$id,'valid_by'=>$this->id,'valid_date'=>date('Y-m-d H:i:s'),'valid_intruksi'=>$this->input->post('intruksi'));
						$this->model_app->insert('validasi',$dataV);
						// $this->session->set_flashdata('success','Agenda berhasil divalidasi');
						// redirect('agenda/detail?id='.$id);
						
						$redirect = base_url('agenda/detail?id='.$id);
						$status = true;
					}
				}else{
					$msg = 'Agenda tidak ditemukan';
					$status = false;
					$redirect = base_url('agenda');
					// $this->session->set_flashdata('error',json_encode('Agenda tidak ditemukan'));
					// redirect('agenda');
				}
			}else{
					$msg = 'Halaman ini tidak bisa diakses';
					$status = false;
					$redirect = base_url('agenda');
			}
			echo json_encode(array('status'=>$status,'msg'=>$msg,'redirect'=>$redirect));
		}
	}
	function store(){
		if($this->input->method() == 'post'){
			if($this->role == 'admin'){
				$redirect= null;
				$this->form_validation->set_rules('kode_kegiatan','Kode Kegiatan','required|is_unique[agenda.agenda_code]|max_length[30]');
				$this->form_validation->set_rules('nama_kegiatan','Nama Kegiatan','required|max_length[255]');
				$this->form_validation->set_rules('tempat_kegiatan','Tempat Kegiatan','required|max_length[255]');
				$this->form_validation->set_rules('penyelenggara','Penyelenggara','required|max_length[255]');
				$this->form_validation->set_rules('start','Tanggal/Jam Mulai','required');
				$this->form_validation->set_rules('end','Tanggal/Jam Selesai','required');
				$this->form_validation->set_rules('pj','Penanggung Jawab','required');
				$this->form_validation->set_rules('kategori','Kategori','required');
				$this->form_validation->set_rules('perihal','Perihal','required');





				if($this->form_validation->run() == false){
						$msg = str_replace(array('<p>','</p>'),'',validation_errors());
						$status = false;
						
					
				}else{
					$config['upload_path']          = './upload/agenda/';
					$config['encrypt_name'] = TRUE;
					$config['allowed_types']        = 'pdf';
					$config['max_size']             = 20000;
						
							
					$this->load->library('upload', $config);

					if ($this->upload->do_upload('surat')){
						$upload_data = $this->upload->data();
						$surat = $upload_data['file_name'];
						$kode = $this->input->post('kode_kegiatan');
						$kehadiran = $this->input->post('kehadiran');
						$nama = $this->input->post('nama_kegiatan');
						$tempat = $this->input->post('tempat_kegiatan');
						$penyelenggara = $this->input->post('penyelenggara');
						$start = $this->input->post('start');
						$end = $this->input->post('end');
						$pj = $this->input->post('pj');
						$kategori = $this->input->post('kategori');
						$perihal = $this->input->post('perihal');
						$dateStart = date('Y-m-d H:i:s',strtotime($start));
						$dateEnd = date('Y-m-d H:i:s',strtotime($end));
						
						$by = $this->id;
						$staff = $this->input->post('pegawai');
						if($kehadiran == 'hadir'){
							$kehadiran = 'y';
						}else{
							$kehadiran = 'n';
						}
						$data = array('agenda_code'=>$kode,'agenda_name'=>$nama,'agenda_place'=>$tempat,'agenda_date_start'=>$dateStart,'agenda_date_end'=>$dateEnd,'agenda_penyelenggara'=>$penyelenggara,'agenda_penanggung_jawab'=>$pj,'agenda_perihal'=>$perihal,'agenda_by'=>$by,'agenda_kategori'=>$kategori,'agenda_validasi'=>'n','agenda_status'=>'undone','agenda_surat'=>$surat,'agenda_kehadiran_camat'=>$kehadiran);
						if(count($staff) > 0){
							$id = $this->model_app->insert_id('agenda',$data);
							for($a=0;$a<count($staff);$a++){
								$dataS = array('ap_agenda_id'=>$id,'ap_pegawai_id'=>$staff[$a]);
								$this->model_app->insert('agenda_pegawai',$dataS);
							}
							$status = true;
							$text = 'Selamat pagi dan selamat datang di<br>Sistem Informasi Penjadwalan Agenda Kegiatan<br><br>Kepada Yth. Bpk camat kuta selatan,<br>Diinformasikan telah ditambahkan data jadwal agenda kegiatan dengan kode kegiatan '.$kode.'<br>Dimohon untuk memeriksa dan memberi tindakan terhadap informasi agenda kegiatan tersebut. <br>Link terlampir : '.base_url('agenda/detail?id='.$id).'<br><br>Kami ucapkan terima kasih dan selamat beraktifitas.<br><br>Salam hormat, <br>Sekretariat Kecamatan Kuta Selatan.';
							$msg = 'Agenda berhasil ditambah';
							pushTelegram($text);
							$redirect = base_url('agenda/detail?id='.$id);
						}else{
							$status = false;
							$msg = 'Tidak ada staff yang dipilih';
					}
					}else{
						$status = false;
						$msg = $this->upload->display_errors();
					}
					
					

				}
				echo json_encode(array('status'=>$status,'msg'=>$msg,'redirect'=>$redirect));
			}else{
				$this->session->set_flashdata('error',json_encode('Anda tidak bisa mengakses halaman ini'));
				redirect('agenda');
			}
			
		}else{
			$this->session->set_flashdata('error',json_encode('Wrong method'));
			redirect('agenda');
		}
	}

	function delete(){
		if($this->role == 'admin'){
			$id = $this->input->get('id');
			if($id == '' OR $id == NULL){
				$this->session->set_flashdata('error',json_encode('Format salah'));
				redirect('agenda');
			}else{
				$cek = $this->model_app->view_where('agenda',array('agenda_id'=>$id));
				if($cek->num_rows() > 0){
					$row = $cek->row_array();
					if($row['agenda_status'] == 'undone'){
						$this->model_app->delete('agenda',array('agenda_id'=>$id));
						$this->model_app->delete('agenda_pegawai',array('ap_agenda_id'=>$id));
						// $this->model_app->delete('agenda_pegawai',array('ap_agenda_id'=>$id));
						$this->session->set_flashdata('success','Agenda berhasil dihapus');
						redirect('agenda');
					}else{
						$this->session->set_flashdata('error',json_encode('Agenda sudah selesai'));
						redirect('agenda');
					}
					

				}else{
					$this->session->set_flashdata('error',json_encode('Agenda tidak ditemukan'));
					redirect('agenda');
				}
			}
		}else{
			$this->session->set_flashdata('error',json_encode('Anda tidak bisa mengakses halaman ini'));
			redirect('agenda');
		}
	}



	

	
}
