<div class="page-header">
    <div class="row">
        <div class="col-sm-6 col-lg-8">
            <h3 class="page-title">Selamat Datang <?= $row->name ?>!</h3>
            <ul class="breadcrumb">
                <li class="breadcrumb-item active">Dashboard</li>
            </ul>
            
        </div>
        <div class="col-sm-6 col-lg-4">
      
				<h1 id="hours" class="text-right"></h1>
		
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa fa-users"></i></span>
                <div class="dash-widget-info">
                    <h3><?= $pegawai->num_rows() ?></h3>
                    <span>Pegawai</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa fa-calendar"></i></span>
                <div class="dash-widget-info">
                    <h3><?= $scheduleOn->num_rows() ?></h3>
                    <span>Onduty</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa fa-envelope"></i></span>
                <div class="dash-widget-info">
                    <h3><?= $scheduleDC->num_rows() ?></h3>
                    <span>Cuti/DC</span>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-lg-6 col-xl-3">
        <div class="card dash-widget">
            <div class="card-body">
                <span class="dash-widget-icon"><i class="fa fa-calendar-times-o"></i></span>
                <div class="dash-widget-info">
                    <h3><?= $scheduleOff->num_rows() ?></h3>
                    <span>Off</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5">
	<div class="col-lg-12">
		<div class="card mb-0">
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
					
						<!-- Calendar -->
						<div id="calendar"></div>
						<!-- /Calendar -->
						
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
    <div class="col-md-12 col-lg-12 col-xl-4 d-flex">
    <div class="card flex-fill">
            <div class="card-body">
                <h4 class="card-title">Jadwal hari ini <span class="badge badge-primary  ml-2"><?= $scheduleEmployee->num_rows()?></span></h4>
                <?php 
                    foreach($scheduleEmployee->result() as $sch){
                        if( $sch->photo != ''){
                            if(file_exists('upload/user/'.$sch->photo) ){
                                $img =  '<img src="'.base_url('upload/user/'.$sch->photo).'" alt="'.$sch->name.'">';

                            }else{
                                $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$sch->name.'">';
                            }
                            
                        }else{
                            $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$sch->name.'">';
                        }

                       
                        echo '<div class="leave-info-box">
                        <div class="media align-items-center">
                            <a href="'.base_url('pegawai/detail/'.$sch->username).'" class="avatar">'.$img.'</a>
                            <div class="media-body">
                                <div class="text-sm my-0">'.$sch->name.'</div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-3">
                            <div class="col-6">
                                <h6 class="mb-0">Shift '.$sch->shift_name.'</h6>
                             
                            </div>
                            <div class="col-6 text-right">
                            <h6 class="mb-0">'.date('H:i',strtotime($sch->schedule_in)).' - '.date('H:i',strtotime($sch->schedule_out)).'</h6>
                             
                            </div>
                        </div>
                    </div>';
                    }
                ?>
                
              
                <div class="load-more text-center">
                    <a class="text-dark" href="<?= base_url('absensi') ?>">Lihat lainnya</a>
                </div>
            </div>
        </div>  
    
    </div>
    
  
    
    <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <h4 class="card-title">Absensi Hari ini <span class="badge badge-primary  ml-2"><?= $absensiOn->num_rows()?></span></h4>
                <?php 
                    foreach($absensiOn->result() as $on){
                        if( $on->photo != ''){
                            if(file_exists('upload/user/'.$on->photo) ){
                                $img =  '<img src="'.base_url('upload/user/'.$on->photo).'" alt="'.$on->name.'">';

                            }else{
                                $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$on->name.'">';
                            }
                            
                        }else{
                            $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$on->name.'">';
                        }

                        if($on->early_in == 'y'){
                            if($on->schedule_in < $on->absen_in){
                                $early_in = ' <span class="badge bg-info">Early In</span>';
                            }else{
                                $early_in = ' <span class="badge bg-success">Ontime</span>';
                            }
                        }else{  
                            $early_in = ' <span class="badge bg-warning">Terlambat</span>';
                        }
                        echo '<div class="leave-info-box">
                        <div class="media align-items-center">
                            <a href="'.base_url('pegawai/detail/'.$on->username).'" class="avatar">'.$img.'</a>
                            <div class="media-body">
                                <div class="text-sm my-0">'.$on->name.'</div>
                            </div>
                        </div>
                        <div class="row align-items-center mt-3">
                            <div class="col-6">
                                <h6 class="mb-0">'.date('H:i',strtotime($on->absen_in)).'</h6>
                                <span class="text-sm text-muted">Absen in</span>
                            </div>
                            <div class="col-6 text-right">
                                '.$early_in.'
                            </div>
                        </div>
                    </div>';
                    }
                ?>
                
              
                <div class="load-more text-center">
                    <a class="text-dark" href="<?= base_url('absensi') ?>">Lihat lainnya</a>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-4 d-flex">
        <div class="card flex-fill">
            <div class="card-body">
                <h4 class="card-title">Belum Absen <span class="badge badge-primary  ml-2"><?= $scheduleToday->num_rows() - $absensiOn->num_rows()?></span></h4>
                <?php 
                    foreach($scheduleToday->result() as $tdy){
                        $abs = $this->model_app->view_where('absensi',array('pegawai_id'=>$tdy->pegawai_id,'schedule_id'=>$tdy->schedule_id));
                        if($abs->num_rows() == 0){
                            if( $tdy->photo != ''){
                                if(file_exists('upload/user/'.$tdy->photo) ){
                                    $img =  '<img src="'.base_url('upload/user/'.$tdy->photo).'" alt="'.$tdy->name.'">';
    
                                }else{
                                    $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$tdy->name.'">';
                                }
                                
                            }else{
                                $img = '<img src="'.base_url('upload/user/default.png').'" alt="'.$tdy->name.'">';
                            }
    
                           
                            echo '<div class="leave-info-box">
                            <div class="media align-items-center">
                                <a href="'.base_url('pegawai/detail/'.$tdy->username).'" class="avatar">'.$img.'</a>
                                <div class="media-body">
                                    <div class="text-sm my-0">'.$tdy->name.'</div>
                                </div>
                            </div>
                          
                        </div>';
                        }
                       
                    }
                ?>
                
              
              
            </div>
        </div>
    </div>
</div>
<!-- /Statistics Widget -->

<div class="row">
    <div class="col-md-12 d-flex">
        <div class="card card-table flex-fill">
            <div class="card-header">
                <h3 class="card-title mb-0">Pengajuan Cuti/DC</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-nowrap custom-table mb-0">
                        <thead>
                            <tr>
                                <th>Pegawai</th>
                                <th>Perihal</th>
                                <th>Tanggal</th>
                               
                                <th>Status</th>
                                <th>#</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                                foreach($pengajuan->result() as $cut){
                            ?>
                            <tr>
                                <td>
                                    <h2 class="table-avatar">
                                        <a href="<?= base_url('pegawai/detail/'.$cut->username) ?>" class="avatar">
                                        <?php 
                                            if(file_exists('upload/user/'.$cut->photo)){
                                                echo '<img src="'.base_url('upload/user/'.$cut->photo).'" alt="'.$cut->name.'">';
                                                
                                            }else{
                                                echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$cut->name.'">';
                                            }
                                        ?>
                                        </a>
                                        <a href="<?= base_url('pegawai/detail/'.$cut->username) ?>"><?= $cut->name?> </a>
                                    </h2>
                                </td>
                                <td>
                                    <?= $cut->perihal?>
                                </td>
                                <td>
                                    <?= fulldate($cut->start).' - '.fulldate($cut->end).' ( '.$cut->total_days.' hari)'?>
                                </td>
                                <td>
                                    <span class="badge bg-inverse-warning">Menunggu persetujuan</span>
                                    
                                </td>
                                <td><a href="<?= base_url('pengajuan/detail?no='.encode($cut->pengajuan_id)) ?>" class="text-primary"><i class="fa fa-eye"></i></a></td>
                            </tr>
                            <?php
                                }
                            ?>
                           
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('pengajuan')?>">Lihat semua</a>
            </div>
        </div>
    </div>
    
</div>

