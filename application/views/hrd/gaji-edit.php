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
                                   
                                    <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="'. base_url('pegawai/edit/'.$row->username).'" >'. ucwords($row->name).'<small class="d-block text-muted">'. ucfirst($row->position).'</small></a></h4>
                                
                                  
                
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
                           
                            <form action="'.base_url('gaji/update').'" method="POST">
                                <input type="hidden" name="id" value="'.encode($row->id).'">

                                <div class="row">
                                    <div class="col-12 form-group">
                                        <label>Gaji Pokok</label>
                                        <input type="text" name="pokok"  class="form-control  rupiah" value="'.$row->basic_salary.'">
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Tunjangan Makan</label>
                                        <input type="text" name="meal"  class="form-control  rupiah" value="'.$row->meal_salary.'">
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Overtime</label>
                                        <input type="text" name="overtime" class="form-control rupiah" value="'.$row->overtime_pay.'">
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Insentif</label>
                                        <input type="text" name="insentif"  class="form-control  rupiah" value="'.$row->incentive.'">
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>BPJS</label>
                                        <input type="text" name="bpjs" class="form-control  rupiah" value="'.$row->bpjs.'">
                                    </div>
                                    <div class="col-12 form-group">
                                        <label>Potongan</label>
                                        <input type="text" name="potongan" class="form-control  rupiah" value="'.$row->cut_salary.'">
                                    </div>
                                    <div class="col-12 form-group text-right mt-4">
                                        <button class="btn btn-primary">Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>';

        echo $output;
   ?>

</div>