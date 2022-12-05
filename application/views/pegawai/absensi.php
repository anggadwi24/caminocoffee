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
<form action="<?= base_url('absensi')?>" method="GET">
<div class="row filter-row">
   
   <div class="col-sm-4"> 
       <div class="form-group form-focus select-focus focused">
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
   <div class="col-sm-4"> 
       <div class="form-group form-focus select-focus focused">
               <select class="select floating" name="year"> 
                   <option value="<?= date('Y',strtotime('-2 Year'))?>" <?php if(date('Y',strtotime('-2 Year')) == $year){echo "selected";}?> ><?= date('Y',strtotime('-2 Year'))?></option>
                   <option value="<?= date('Y',strtotime('-1 Year'))?>" <?php if(date('Y',strtotime('-1 Year')) == $year){echo "selected";}?> ><?= date('Y',strtotime('-1 Year'))?></option>
                   <option value="<?= date('Y')?>" <?php if(date('Y') == $year){echo "selected";}?> ><?= date('Y')?></option>
                 
               </select>
               <label class="focus-label">Pilih Tahun</label>
       </div>
   </div>
   <div class="col-sm-4">  
       <button type="submit" class="btn btn-success btn-block"> Search </button>  
   </div>     
</div>
</form>
<div class="row">
    <div class="col-md-4">
        <div class="card punch-status">
            <div class="card-body">
                <h5 class="card-title">Tanggal <small class="text-muted"><?= fulldate(date('Y-m-d'))?></small></h5>
                <?php 
                    if($schedule == null){
                        echo ' <div class="punch-det">
                                  
                                    <p>Tidak ada jadwal</p>
                                </div>';
                    }else{
                        if($schedule->status == 'on'){
                            if($shift == null){
                                echo ' <div class="punch-det">
                                          
                                            <p>Shift tidak ditemukan</p>
                                        </div>';
                               }else{
                                echo ' <div class="punch-det">
                                            <h6>Shift '.$shift->name.'</h6>
                                            <p>'.date('H:i',strtotime($shift->schedule_in)).' - '.date('H:i',strtotime($shift->schedule_out)).'</p>
                                        </div>';
                                if($absensi->num_rows() > 0){
                                    $abs = $absensi->row();
                                    echo ' <div class="punch-det">
                                                <h6>Absen In</h6>
                                                <p>'.tanggalwaktu($abs->absen_in).'</p>
                                            </div>';
                                    if($abs->absen_out == null){
                                   
                                        $absen_out = date('H:i');
                                    }else{
                                       
                                        $absen_out = date('H:i',strtotime($abs->absen_out));

                                    }
                                    $absen_in = date('H:i',strtotime($abs->absen_in));
                                    $duration = selisihJam($absen_in,$absen_out);
                                    echo ' <div class="punch-info">
                                                <div class="punch-hours">
                                                    <span>'.$duration.' jam</span>
                                                </div>
                                            </div>';
                                    if($abs->absen_out == null){
                                        echo '<div class="punch-btn-section">
                                                    <form action="'.base_url('absensi/out').'" method="POST">
                                                        <button type="submit" class="btn btn-primary punch-btn">Absen Out</button>
                                                    </form>
                                                </div>';
                                    }else{
                                        echo ' <div class="punch-det">
                                                    <h6>Absen Out</h6>
                                                    <p>'.tanggalwaktu($abs->absen_out).'</p>
                                                </div>';
                                        $getOvt = $this->model_app->view_where('overtime',array('absensi_id'=>$abs->id,'pegawai_id'=>$abs->pegawai_id));
                                        if($getOvt->num_rows() > 0){
                                            $ovt = $getOvt->row();
                                            $overtime = $ovt->overtime.' jam';
                                            $over = true;
                                        }else{
                                            $overtime = '-';
                                            $over = false;
                                        }
                                        echo ' <div class="statistics">
                        
                                                    <div class="stats-box">
                                                        <p>Overtime</p>
                                                        <h6>'.$overtime.'</h6>
                                                    </div>
                                                        
                                                </div>';
                                    }
                                    
                                  
                                }else{
                                    echo '<div class="punch-btn-section">
                                            <form action="'.base_url('absensi/in').'" method="POST">
                                                <button type="submit" class="btn btn-primary punch-btn">Absen In</button>
                                            </form>
                                        </div>';
                                }
                               } 
                        }else{
                            if($schedule->status == 'off'){
                                echo ' <div class="punch-det">
                                  
                                            <p>Hari ini anda libur</p>
                                        </div>';
                            }else{
                                echo ' <div class="punch-det">
                                  
                                            <p>Anda dalam status DC/Cuti</p>
                                        </div>';
                            }
                           
                        }
                      
                    }
                ?>
               
               
                
               
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card att-statistics">
            <div class="card-body">
                <h5 class="card-title">Statistics</h5>
                <div class="stats-list">
                    <div class="stats-info">
                        <p>Today <strong>3.45 <small>/ 8 hrs</small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-primary" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p>This Week <strong>28 <small>/ 40 hrs</small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: 31%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p>This Month <strong>90 <small>/ 160 hrs</small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-success" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p>Remaining <strong>90 <small>/ 160 hrs</small></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-danger" role="progressbar" style="width: 62%" aria-valuenow="62" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p>Overtime <strong>4</strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 22%" aria-valuenow="22" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card recent-activity">
            <div class="card-body">
                <h5 class="card-title">Today Activity</h5>
                <ul class="res-activity-list">
                    <li>
                        <p class="mb-0">Punch In at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            10.00 AM.
                        </p>
                    </li>
                    <li>
                        <p class="mb-0">Punch Out at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            11.00 AM.
                        </p>
                    </li>
                    <li>
                        <p class="mb-0">Punch In at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            11.15 AM.
                        </p>
                    </li>
                    <li>
                        <p class="mb-0">Punch Out at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            1.30 PM.
                        </p>
                    </li>
                    <li>
                        <p class="mb-0">Punch In at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            2.00 PM.
                        </p>
                    </li>
                    <li>
                        <p class="mb-0">Punch Out at</p>
                        <p class="res-activity-time">
                            <i class="fa fa-clock-o"></i>
                            7.30 PM.
                        </p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date </th>
                        <th>Punch In</th>
                        <th>Punch Out</th>
                        <th>Production</th>
                        <th>Break</th>
                        <th>Overtime</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>19 Feb 2019</td>
                        <td>10 AM</td>
                        <td>7 PM</td>
                        <td>9 hrs</td>
                        <td>1 hrs</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>20 Feb 2019</td>
                        <td>10 AM</td>
                        <td>7 PM</td>
                        <td>9 hrs</td>
                        <td>1 hrs</td>
                        <td>0</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>