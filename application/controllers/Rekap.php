<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap extends CI_Controller 
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
    public function index(){
        $data['title'] = 'REKAP ABSENSI - '.title();
		$data['page'] = 'Rekap Absensi';
      
        $data['bulan'] = [
            ['bulan'=>'Januari','code'=>'01'],
            ['bulan'=>'Februari','code'=>'02'],
            ['bulan'=>'Maret','code'=>'03'],
            ['bulan'=>'April','code'=>'04'],
            ['bulan'=>'Mei','code'=>'05'],
            ['bulan'=>'Juni','code'=>'06'],
            ['bulan'=>'Juli','code'=>'07'],
            ['bulan'=>'Agustus','code'=>'08'],
            ['bulan'=>'September','code'=>'09'],
            ['bulan'=>'Oktober','code'=>'10'],
            ['bulan'=>'November','code'=>'11'],
            ['bulan'=>'Desember','code'=>'12'],


        ];
        $years = $this->input->get('year');
        $months = $this->input->get('month');
        if($years == null){
            $years = date('Y');
        }
        if($months == null){
            $months = date('m');
        }
        $start = $years.'-'.$months.'-01';
        $end = $years.'-'.$months.'-d';

        $start = date($start);
        $end = date($end);
        $end = date('Y-m-t',strtotime($end));
        $endSch = date('Y-m-t',strtotime($end));

        $begin = new DateTime($start);
        $end   = new DateTime($end);
        $data['begin'] = $begin;
        $data['end'] = $end;
        $data['difference'] = daysDifference($endSch,$start)+1;
        $data['right'] = '<div class="view-icons">
       
                                <a href="'.base_url('rekap/download?month='.$months.'&year='.$years).'" class="list-view btn btn-link"  title="Download Excel"><i class="fa fa-file-excel-o"></i></a>	
                            </div>';
        $data['months'] = $months;
        $data['years'] = $years;
        $data['employee'] =$this->model_app->getScheduleAll(array('months'=>$months,'years'=>$years));
		$data['breadcrumb'] = '<li class="breadcrumb-item"><a href="'.base_url('/').'">Dashboard</a></li>';
		$data['breadcrumb'] .= '<li class="breadcrumb-item active">Rekap Absensi</li>';
        $data['ajax'] = ['assets/ajax/rekap/index.js'];
        $data['style'] = ['assets/css/bootstrap-datetimepicker.min.css','assets/css/dataTables.bootstrap4.min.css','assets/css/select2.min.css'];
		$data['script'] = ['assets/js/moment.min.js','assets/js/bootstrap-datetimepicker.min.js','assets/js/jquery.dataTables.min.js','assets/js/dataTables.bootstrap4.min.js','assets/js/select2.min.js'];
        $data['record'] = $this->model_app->view_order('shift','id','desc');
        $this->template->load('template','hrd/rekap',$data);
    }
    public function download(){
        $years = $this->input->get('year');
        $months = $this->input->get('month');
        if($years == null){
            $years = date('Y');
        }
        if($months == null){
            $months = date('m');
        }
        $start = $years.'-'.$months.'-01';
        $end = $years.'-'.$months.'-d';

        $start = date($start);
        $end = date($end);
        $end = date('Y-m-t',strtotime($end));
        $endSch = date('Y-m-t',strtotime($end));

        $begin = new DateTime($start);
        $end   = new DateTime($end);
        $begin = $begin;
        $end = $end;
        $difference = daysDifference($endSch,$start)+1;
      
   
        $employee =$this->model_app->getScheduleAll(array('months'=>$months,'years'=>$years));
		
		include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        
    
        $excel = new PHPExcel();
      
        $excel->getProperties()->setCreator('My Notes Code')
                     ->setLastModifiedBy('My Notes Code')
                     ->setTitle("Data Siswa")
                     ->setSubject("Siswa")
                     ->setDescription("Laporan Semua Data Siswa")
                     ->setKeywords("Data Siswa");

        $style_col = array(
          'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF'),),
          'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '1857fa')
            ), 
          'alignment' => array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER 
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),  
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) 
          )
        );

        $style_row = array(
        'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => '55ce63')
         ), 
          'alignment' => array(
            'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
             'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER 
          ),
          'borders' => array(
            'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
            'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
            'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
            'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) 
          )
        );
        $style_row_danger = array(
            'fill' => array(
                    'type' => PHPExcel_Style_Fill::FILL_SOLID,
                    'color' => array('rgb' => 'f62d51')
             ), 
              'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER 
              ),
              'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) 
              )
            );
		$style_row_off= array(
            'alignment' => array(
                'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                 'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER 
              ),
              'borders' => array(
                'top' => array('style'  => PHPExcel_Style_Border::BORDER_THIN),
                'right' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                'bottom' => array('style'  => PHPExcel_Style_Border::BORDER_THIN), 
                'left' => array('style'  => PHPExcel_Style_Border::BORDER_THIN) 
              )
			
		  );
		

		$excel->setActiveSheetIndex(0)->setCellValue('A1', "REKAP ABSENSI CAMINO COFFEE AND EATERY"); 
        $excel->getActiveSheet()->mergeCells('A1:AG1'); 
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(TRUE); 
        $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15); 
        $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER); 
		
		$title = strtoupper(bulan($months).' '.$years);

        $excel->setActiveSheetIndex(0)->setCellValue('A2', $title); // Set kolom A1 dengan tulisan "DATA SISWA"
        $excel->getActiveSheet()->mergeCells('A2:AG2'); // Set Merge Cell pada kolom A1 sampai E1
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setBold(TRUE); // Set bold kolom A1
        $excel->getActiveSheet()->getStyle('A2')->getFont()->setSize(15); // Set font size 15 untuk kolom A1
        $excel->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
	
        // Buat header tabel nya pada baris ke 3
        $excel->setActiveSheetIndex(0)->setCellValue('A4', "PEGAWAI"); // Set kolom A3 dengan tulisan "NO"
        $excel->getActiveSheet()->getStyle('A4')->applyFromArray($style_col);
        $excel->setActiveSheetIndex(0)->setCellValue('B4', "JABATAN"); // Set kolom A3 dengan tulisan "NO"
        $excel->getActiveSheet()->getStyle('B4')->applyFromArray($style_col);
 
        for($i=1,$j='C';$i<=$difference;$i++,$j++) {
            $excel->setActiveSheetIndex(0)
          ->setCellValue($j.'4', numberString($i));
            $excel->getActiveSheet()->getStyle($j.'4')->applyFromArray($style_col);

        }
       
       
        $numrow = 5;
        foreach($employee->result() as $row){
            $excel->setActiveSheetIndex(0)->setCellValue('A'.$numrow, strtoupper($row->name));
            $excel->getActiveSheet()->getStyle('A'.$numrow)->applyFromArray($style_row_off);
            $excel->setActiveSheetIndex(0)->setCellValue('B'.$numrow, strtoupper($row->position));
            $excel->getActiveSheet()->getStyle('B'.$numrow)->applyFromArray($style_row_off);
            for($a = 1,$c='C';$a <= $difference;$a++,$c++){
                $date = $years.'-'.$months.'-'.numberString($a);
                $date = date('Y-m-d',strtotime($date));
                $check = $this->model_app->view_where('schedule',array('pegawai_id'=>$row->pegawai_id,'dates'=>$date,'months'=>$months,'years'=>$years));
                if($check->num_rows() > 0){
                    $sch = $check->row();
                    if($sch->status == 'on'){
                        $absen = $this->model_app->view_where('absensi',array('pegawai_id'=>$row->pegawai_id,'schedule_id'=>$sch->id));
                        if($absen->num_rows() > 0){
                            $abs = $absen->row();
                           
                            if($abs->absen_in == null){
                                $out = '--:--';
                            }else{
                                $out = date('H:i',strtotime($abs->absen_out));
                            }
                            $title = date('H:i',$abs->absen_in).' - '.$out;
                            $overtime = $this->model_app->view_where('overtime',array('absensi_id'=>$abs->id));
                            if($overtime->num_rows() > 0){
                                $ovt = $overtime->row();
                                $durasi = '+'.$ovt->overtime;
                                $title = $title.' ('.$durasi.')';
                                $excel->setActiveSheetIndex(0)->setCellValue($c.$numrow,$title);
                                
                            }else{
                               
                                $excel->setActiveSheetIndex(0)->setCellValue($c.$numrow, $title);
                            }
                           
                            $excel->getActiveSheet()->getStyle($c.$numrow)->applyFromArray($style_row);

                        }else{
                            $excel->setActiveSheetIndex(0)->setCellValue($c.$numrow, 'ALPA');
                            $excel->getActiveSheet()->getStyle($c.$numrow)->applyFromArray($style_row_danger);


                        }

                    }else{
                        $excel->setActiveSheetIndex(0)->setCellValue($c.$numrow, strtoupper($sch->status));
                        $excel->getActiveSheet()->getStyle($c.$numrow)->applyFromArray($style_row_off);

                    }
                          

                }else{
                    $excel->setActiveSheetIndex(0)->setCellValue($c.$numrow, '-');
                    $excel->getActiveSheet()->getStyle($c.$numrow)->applyFromArray($style_row_off);

                }
                $excel->getActiveSheet()->getColumnDimension($c)->setWidth(15);
                


              }
              
            $numrow++;
        }
        
      
        
    
        $excel->getActiveSheet()->getColumnDimension('A')->setWidth(30);
        $excel->getActiveSheet()->getColumnDimension('B')->setWidth(10);

    
      
       

	
		$excel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(-1);
      
        $excel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);
        $name =  'REKAP-ABSENSI-'.strtoupper(bulan($months)).'-'.$years;
        $excel->getActiveSheet(0)->setTitle(bulan($months).' '.$years);
        $excel->setActiveSheetIndex(0);
            
       
		
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$name.'".xlsx"'); // Set nama file excel nya
        header('Cache-Control: max-age=0');
        $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
        
        ob_end_clean();
        $write->save('php://output');
	}
    public function detail(){
        if($this->input->method() == 'post'){
           
            $id = decode($this->input->post('absen'));

            $output = null;
            $absensi = $this->model_app->view_where('absensi',array('id'=>$id));
            if($absensi->num_rows() > 0){
                $abs = $absensi->row();
                $row = $this->model_app->getUserWhere(array('pegawai.id'=>$abs->pegawai_id));
                if($row->num_rows() > 0){
                    $row = $row->row();
                    $getSch = $this->model_app->join_where2('schedule','shift','shift_id','id',array('schedule.id'=>$abs->schedule_id),);
                    if($getSch->num_rows() > 0){
                        $shf = $getSch->row();
                        if( $row->photo != ''){
                            if(file_exists('upload/user/'.$row->photo) ){
                                $img= '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';
        
                            }else{
                                $img= '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                            }
                            
                        }else{
                            $img= '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                        }
                        $profil = '<div class="col-md-6">
                                        <div class="profile-widget">
                                            <div class="'.base_url('pegawai/detail/'.$row->username).'">
                                                <a href="'.base_url('pegawai/detail/'.$row->username).'" class="avatar">'.$img.'</a>
                                            </div>
                                            
                                        <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="'.base_url('pegawai/detail/'.$row->username).'">'.$row->name.'</a></h4>
                                        <div class="small text-muted">'.$row->username.'</div>
                                        <div class="small text-muted">Pegawai</div>
        
                                       
        
                                    </div>
                                    
                                </div>';
                                if($abs->absen_out == null){
                                    $out = 'Belum absen out';
                                    $absen_out = date('H:i');
                                }else{
                                    $out = tanggalwaktu($abs->absen_out);
                                    $absen_out = date('H:i',strtotime($abs->absen_out));
    
                                }
                                $absen_in = date('H:i',strtotime($abs->absen_in));
                                $duration = selisihJam($absen_in,$absen_out);
                                $overtime = $this->model_app->view_where('overtime',array('absensi_id'=>$abs->id,'pegawai_id'=>$row->pegawai_id));
                                if($overtime->num_rows() > 0){
                                    $ovt = $overtime->row();
                                    $overtimeText =  '<div class="stats-box">
                                                        <p>Overtime</p>
                                                        <h6> '.$ovt->overtime.' jam</h6>
                                                    </div>';
                                }else{
                                    $overtimeText =  '';
                                }
                                if($this->role == 'hrd'){
                                    $act = '<a class="float-right text-dark" href="'.base_url('absensi/detail?absensi='.encode($abs->id)).'"><i class="fa fa-eye m-r-5"></i></a>';
                                }else{
                                    $act = '';
                                }
                                $date = $abs->date;
                                $output .= $profil.'
                                <div class="col-md-6">
                                    <div class="card recent-activity">
                                        <div class="card-body">
                                            <h5 class="card-title">Absensi <small class="text-muted">'.fulldate($date).'</small>'.$act.'</h5>
                                            <div class="punch-det">
                                                <h6>Shift</h6>
                                                <p>'.$shf->name.' '.date('H:i',strtotime($shf->schedule_in)).' - '.date('H:i',strtotime($shf->schedule_out)).'</p>
                                            </div>
                                            <div class="punch-det">
                                                <h6>Absen In</h6>
                                                <p>'.tanggalwaktu($abs->absen_in).'</p>
                                            </div>
                                            <div class="punch-info">
                                                <div class="punch-hours">
                                                    <span>'.$duration.' jam</span>
                                                </div>
                                            </div>
                                            <div class="punch-det">
                                                <h6>Absen Out</h6>
                                                <p>'.$out.'</p>
                                            </div>
                                            '.$overtimeText.'
                                        </div>
                                    </div>
                                </div>';
                        $status = 200;
                        $msg= null;
                    }else{
                        $status = 201;
                        $msg = 'Jadwal tidak ditemukan';
                    }
                   
                }else{
                    $status = 201;
                    $msg = 'Pegawai tidak ditemukan';
                }
            }else{
                $status = 201;
                $msg = 'Absensi tidak ditemukan';
            }
           
            echo json_encode(['status'=>$status,'msg'=>$msg,'output'=>$output]);
        }else{
            redirect('schedule');
        }
    }
}