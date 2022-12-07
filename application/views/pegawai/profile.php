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

<div class="card mb-0">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="javascript:void(0)">
                            <?php 
                            if( $row->photo != ''){
                                if(file_exists('upload/user/'.$row->photo) ){
                                    echo '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';

                                }else{
                                    echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                                }
                                
                            }else{
                                echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                            }
                        ?>
                            </a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0"><?= ucwords($row->name)?></h3>
                                    <h6 class="text-muted"><?=strtoupper($row->level)?></h6>
                                    <small class="text-muted"><?php   if($row->active == 'y'){ echo "Active"; }else{echo "Suspend";} ?></small>
                                  
                                    <div class="small doj text-muted">Tanggal bergabung : <?= tanggal($row->created_at)?></div>
                                   
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Telepon/Hp:</div>
                                        <div class="text"><?= $row->phone?></div>
                                    </li>
                                    
                                    <li>
                                        <div class="title">Tempat Lahir</div>
                                        <div class="text"><?= $row->pob ?> </div>
                                    </li>
                                    <li>
                                        <div class="title">Tanggal Lahir</div>
                                        <div class="text"><?= fulldate($row->dob) ?> </div>
                                    </li>
                                    <li>
                                        <div class="title">Alamat:</div>
                                        <div class="text"><?= $row->address ?></div>
                                    </li>
                                   
                                </ul>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card tab-box">
    <div class="row user-tabs">
        <div class="col-lg-12 col-md-12 col-sm-12 line-tabs">
            <ul class="nav nav-tabs nav-tabs-bottom">
                <li class="nav-item"><a href="#absensi" data-toggle="tab" class="nav-link active">Absensi</a></li>

                <li class="nav-item"><a href="#profil" data-toggle="tab" class="nav-link ">Edit</a></li>

                
               
            </ul>
        </div>
    </div>
</div>
<div class="tab-content">
    <div id="absensi" class="pro-overview tab-pane fade show active">  
        <?php 
  $month = date('m');
  $years = date('Y');
  $bulan = date('m');
  $years = date('Y');
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
<div class="row justify-content-center" >
    <div class="col-md-6">
        <div class="card">
            <div class="card-body pb-5">
                    <h5 class="card-title">Absensi bulan <?= bulan($month)?></h5>
                    <div class="row">
                    <?php 
                        $schedule = $this->model_app->view_where('schedule',array('months'=>$month,'years'=>$years,'dates <'=>date('Y-m-d'),'pegawai_id'=>$row->pegawai_id));
                        if($schedule->num_rows() > 0){
                            foreach($schedule->result() as $sch){
                                if($sch->status == 'on' ){
                                    $absen = $this->model_app->view_where('absensi',array('pegawai_id'=>$row->pegawai_id,'schedule_id'=>$sch->id));
                                    if($absen->num_rows() > 0){
                                        $abs  = $absen->row();
                                        if($abs->early_in == 'y' AND $abs->early_out == 'n'){
                                            $class = 'bg-success text-white';
                                            $text = 'Absen tepat waktu & Pulang tepat waktu';
                                        }else if($abs->early_in == 'y' AND $abs->early_out == 'y'){
                                            $class = 'bg-info text-white';
                                            $text = 'Absen tepat waktu & Pulang mendahului';

                                        }else{
                                            $class ='bg-danger text-white';
                                            $text = 'Absen terlambat & Pulang mendahului';

                                        }
                                        
                                        echo '<div class="col-6 mb-1">
                                        <div class="stats-info '.$class.'">'.date('d',strtotime($sch->dates)). ' '.bulan($sch->months).'<small class="d-block">'.$text.'</small></div>
                                        
                                    </div>';
                                    }
                                   
                                }
                              
                            }
                        }else{
                            echo "<div class='col-12'><i><h6>Anda tidak memiliki jadwal</h6></i></div>";
                        }
                        ?>
                    </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body pb-5">
                 <h5 class="card-title">Alpa bulan <?= bulan($month)?></h5>
                 <div class="row">
                    <?php 
                        $schedule = $this->model_app->view_where('schedule',array('months'=>$month,'years'=>$years,'dates <'=>date('Y-m-d'),'pegawai_id'=>$row->pegawai_id));
                        if($schedule->num_rows() > 0){
                            foreach($schedule->result() as $sch){
                                if($sch->status == 'on' ){
                                    $absen = $this->model_app->view_where('absensi',array('pegawai_id'=>$row->pegawai_id,'schedule_id'=>$sch->id));
                                    if($absen->num_rows() == 0){
                                        echo '<div class="col-6 mb-1">
                                        <div class="stats-info bg-danger text-white">'.date('d',strtotime($sch->dates)). ' '.bulan($sch->months).'</div>
                                        
                                    </div>';
                                    }
                                   
                                }
                              
                            }
                        }else{
                            echo "<div class='col-12'><i><h6>Anda tidak memiliki jadwal</h6></i></div>";
                        }
                    ?>
                 </div>
            </div>
        </div>
    </div>
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
                            <h5 class="card-title">Statistik  bulan '.bulan($month).'</h5>
                            
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
   
        echo $output;
   ?>
   </div>
    </div>                     
    <!-- Profile Info Tab -->
    <div id="profil" class="pro-overview tab-pane fade show ">
        <div class="row">
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <form action="<?= base_url('profile/update/')?>" method="POST" enctype="multipart/form-data">
                    <div class="card-body">
                        <h3 class="card-title">Data Personal </h3>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Nama </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="name" placeholder="Masukan nama HRD" value="<?=$row->name?>" >
                                <div class="invalid-feedback d-block"><?= form_error('name') ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Telepon/Hp </label>
                            <div class="col-md-9">
                                <input type="number" class="form-control" name="phone" placeholder="Masukan telepon/hp HRD" value="<?=$row->phone?>" >
                                <div class="invalid-feedback d-block"><?= form_error('phone') ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Tempat Lahir </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control" name="pob" placeholder="Masukan tempat lahir" value="<?=$row->pob?>" >
                                <div class="invalid-feedback d-block"><?= form_error('pob') ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Tanggal Lahir </label>
                            <div class="col-md-9">
                                <input type="text" class="form-control datetimepicker" name="dob" placeholder="Masukan tanggal lahir" value="<?=date('d/m/Y',strtotime($row->dob))?>" >
                                <div class="invalid-feedback d-block"><?= form_error('dob') ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Alamat</label>
                            <div class="col-md-9">
                                <textarea rows="5" cols="5" class="form-control" name="address" placeholder="Masukan alamat HRD"><?=$row->address?></textarea>
                                <div class="invalid-feedback d-block"><?= form_error('address') ?></div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-form-label col-md-3">Foto </label>
                            <div class="col-md-9">
                                <input type="file" class="form-control" name="file" placeholder="Masukan foto" accept="image/*" >
                                <div class="invalid-feedback d-block">*Isi jika ingin mengganti</div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-12 text-right">
                                <button class="btn btn-primary ">Simpan</button>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
            <div class="col-md-6 d-flex">
                <div class="card profile-box flex-fill">
                    <form action="<?=  base_url('profile/updateLogin')?>" method="POST">
                        <div class="card-body">
                            <h3 class="card-title">Data Login</h3>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">Username </label>
                                <div class="col-md-10">
                                    <input type="text" class="form-control" name="username" placeholder="Masukan username HRD"  value="<?=$row->username?>">
                                    <div class="invalid-feedback d-block"><?= form_error('username') ?></div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-form-label col-md-2">Password </label>
                                <div class="col-md-10">
                                    <input type="password" class="form-control" name="password" placeholder="Masukan password HRD" >
                                    <div class="invalid-feedback d-block">*Isi jika ingin mengganti</div>

                                    <div class="invalid-feedback d-block"><?= form_error('password') ?></div>
                                    
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-12 text-right">
                                    <button class="btn btn-primary ">Simpan</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
    </div>
</div>