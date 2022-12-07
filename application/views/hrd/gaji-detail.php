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
                            <h5 class="card-title">Statistik '.bulan($month).'</h5>
                            <div class="row justify-content-center">
                                <div class="profile-widget-one">
                                    <div class="profile-img">
                                        <a href="'.base_url('pegawai/detail/'.$row->username).'" class="avatar">
                                        '.$img.'
                                        </a>
                                    </div>
                                   
                                    <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="'. base_url('pegawai/edit/'.$row->username).'" >'. ucwords($row->name).'</a></h4>
                                
                                  
                
                                </div>
                            </div>
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
                            <h5 class="card-title mb-0">Gaji '.bulan($bulan).'</h5>
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