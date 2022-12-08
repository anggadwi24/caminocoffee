<div class="page-header">
    <div class="row align-items-center">
        <div class="col">
            <h3 class="page-title"><?= $page ?></h3>
            <ul class="breadcrumb">
                <?= $breadcrumb ?>
               
            </ul>
        </div>
        <div class="col-auto float-right ml-auto">
            <?= $right ?>
           
        </div>
    </div>
</div>
<form action="<?= base_url('gaji') ?>" method="GET">
    <div class="row filter-row">
    
    
        <div class="col-sm-6 col-md-4"> 
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="month"> 
                   <?php 
                        for($a=0;$a<12;$a++){
                            if($bulan[$a]['code'] == $month){
                                echo "<option value='".$bulan[$a]['code']."' selected>".$bulan[$a]['bulan']."</option>";

                            }else{
                                echo "<option value='".$bulan[$a]['code']."'>".$bulan[$a]['bulan']."</option>";

                            }
                        }
                    ?>
                </select>
                <label class="focus-label">Pilih Bulan</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-4"> 
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="year"> 
                    <option value="<?= date('Y',strtotime('-2 Year'))?>" <?php if(date('Y',strtotime('-2 Year')) == $year){echo "selected";}?> ><?= date('Y',strtotime('-2 Year'))?></option>
                    <option value="<?= date('Y',strtotime('-1 Year'))?>" <?php if(date('Y',strtotime('-1 Year')) == $year){echo "selected";}?> ><?= date('Y',strtotime('-1 Year'))?></option>
                    <option value="<?= date('Y')?>" <?php if(date('Y') == $year){echo "selected";}?> ><?= date('Y')?></option>
                    <option value="<?= date('Y',strtotime('+1 Year'))?>" <?php if(date('Y',strtotime('+1 Year')) == $year){echo "selected";}?> ><?= date('Y',strtotime('+1 Year'))?></option>
                    <option value="<?= date('Y',strtotime('+2 Year'))?>" <?php if(date('Y',strtotime('+2 Year')) == $year){echo "selected";}?> ><?= date('Y',strtotime('+2 Year'))?></option>
                </select>
                <label class="focus-label">Pilih Tahun</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-4 ">  
            <button type="submit" class="btn btn-success btn-block"> Search </button>  
        </div>
    </div>
</form>
<?php if($row->num_rows() > 0){?>
<?php 
  $row = $row->row();
  $date = '01-'.$row->months.'-'.$row->years;
                           

  $dateTime = new DateTime($date);

  $month = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('m');
  $years = $dateTime->modify('-' . $dateTime->format('d') . ' days')->format('Y');
  $lastMonth = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' ")->row();
  $lastMonthResult = $this->db->query("SELECT coalesce(SUM(b.duration),0) as durasi, COUNT(b.id) as totalAbsen FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on'")->row();
  $percentHours = round($lastMonthResult->durasi/($lastMonth->total*8)*100,1);
  $hours = $lastMonth->total*8;
  $terlambat = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyIn FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_in ='n' ")->row();
  if($terlambat->totalEarlyIn > 0){
      $percentTerlambat = round($terlambat->totalEarlyIn/$lastMonthResult->totalAbsen*100,0);
  
  }else{
      $percentTerlambat= 0;
  }
  $pulang = $this->db->query("SELECT coalesce(COUNT(b.id),0) as totalEarlyOut FROM schedule a JOIN absensi b ON a.id = b.schedule_id  WHERE b.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='on' AND early_out ='y' ")->row();
  if($pulang->totalEarlyOut > 0){
      $percentPulang = round($pulang->totalEarlyOut/$lastMonthResult->totalAbsen*100,0);
  
  }else{
      $percentPulang =0;
  
  }
  $percentHari = round($lastMonthResult->totalAbsen/$lastMonth->total*100,2);
  $off = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='off' ")->row();
  $cuti = $this->db->query("SELECT coalesce(COUNT(a.id),0) as total  FROM schedule a  WHERE a.pegawai_id = $row->pegawai_id AND months = '".$month."' AND years = '".$years."' AND status ='dc' ")->row();

  $overtime = $this->db->query("SELECT COALESCE(SUM(overtime),0) as ovt FROM absensi a JOIN overtime b ON a.id = b.absensi_id WHERE a.pegawai_id = $row->pegawai_id AND MONTH(a.date) = '".$month."' AND YEAR(a.date) = '".$years."' ")->row();
  
?>
<div class="row justify-content-center" id="data">
   <?php 
    if( $row->photo != ''){
        if(file_exists('upload/user/'.$row->photo) ){
            $img =  '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';

        }else{
            $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
        }
        
    }else{
        $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
    }
    $output = '<div class="col-md-6">
                   
                    <div class="card att-statistics">
                        <div class="card-body pb-5">
                            <h5 class="card-title">Statistik anda di bulan '.bulan($month).'</h5>
                            
                            <div class="stats-list mb-2 " style="height:100% !important">
                                <div class="stats-info">
                                    <p>Hari Kerja <strong>'.$lastMonthResult->totalAbsen.' / '.$lastMonth->total.' hari</small></strong></p>
                            
                                    <div class="progress">
                                        <div class="progress-bar '.progressColor($percentHari).'" role="progressbar" style="width: '.$percentHari.'%" aria-valuenow="'.$percentHari.'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Jam Kerja <strong>'.round($lastMonthResult->durasi,2).' / '.$hours.' jam</small></strong></p>
                               
                                    <div class="progress">
                                        <div class="progress-bar '.progressColor($percentHours).'" role="progressbar" style="width: '.$percentHours.'%" aria-valuenow="'.$percentHours.'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Total Terlambat <strong>'.$terlambat->totalEarlyIn.' / '.$lastMonthResult->totalAbsen.' hari</small></strong></p>
                               
                                    <div class="progress">
                                        <div class="progress-bar '.progressColor($percentTerlambat).'" role="progressbar" style="width: '.$percentTerlambat.'%" aria-valuenow="'.$percentTerlambat.'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Total Mendahului Shift Pulang <strong>'.$pulang->totalEarlyOut.' / '.$lastMonthResult->totalAbsen.' hari</small></strong></p>
                               
                                    <div class="progress">
                                        <div class="progress-bar '.progressColor($percentPulang).'" role="progressbar" style="width: '.$percentPulang.'%" aria-valuenow="'.$percentPulang.'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Overtime<strong>'.$overtime->ovt.' jam</small></strong></p>
                            
                                    <div class="progress">
                                        <div class="progress-bar " role="progressbar" style="width: 100%" aria-valuenow="'.$overtime->ovt.'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Cuti/DC<strong>'.$cuti->total.' hari</small></strong></p>
                            
                                    <div class="progress">
                                        <div class="progress-bar " role="progressbar" style="width: 100%" aria-valuenow="'.$cuti->total.'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <div class="stats-info">
                                    <p>Libur<strong>'.$off->total.' hari</small></strong></p>
                            
                                    <div class="progress">
                                        <div class="progress-bar " role="progressbar" style="width: 100%" aria-valuenow="'.$off->total.'" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                    
                </div>';
    $output .= '<div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex justify-content-between">
                                 <h5 class="card-title mb-0">Gaji '.bulan($bulan).'</h5>
                                 <a href="'.base_url('gaji/pdf?slip='.encode($row->id)).'" class="list-view btn btn-link"  title="Download PDF"><i class="fa fa-file-pdf-o"></i></a>
                            </div>
                            
                        </div>
                        <div class="card-body">
                           
                          

                        <div class="row">
                            <div class="col-12 form-group">
                                <label>Gaji Pokok</label>
                                <h6>'.rp($row->basic_salary).'</h6>
                            </div>
                            <div class="col-12 form-group">
                                <label>Tunjangan Makan</label>
                                <h6>'.rp($row->meal_salary).'</h6>
                               
                            </div>
                            <div class="col-12 form-group">
                                <label>Overtime</label>
                                <h6>'.rp($row->overtime_pay).'</h6>
                            
                            </div>
                            <div class="col-12 form-group">
                                <label>Insentif</label>
                                <h6>'.rp($row->incentive).'</h6>
                               
                            </div>
                            <div class="col-12 form-group">
                                <label>BPJS</label>
                                <h6>'.rp($row->bpjs).'</h6>
                              
                            </div>
                            <div class="col-12 form-group">
                                <label>Potongan</label>
                                <h6>'.rp($row->cut_salary).'</h6>
                              
                            </div>
                            <div class="col-12 form-group ">
                                <label>Total Gaji</label>
                                <h6>'.rp($row->total_salary).'</h6>
                            </div>
                        </div>
                    
                        </div>
                    </div>
                </div>';

        echo $output;
   ?>

</div>
<?php }?>