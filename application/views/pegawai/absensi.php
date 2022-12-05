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
<div class="row">
    <div class="col-md-6">
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
                                    if($abs->early_in == 'y'){
                                        if($abs->absen_in <= $shift->schedule_in){
                                            $ket = "Early in";

                                        }else{
                                            $ket =  "Ontime";

                                        } 
                                       
                                    }else{ 
                                        $ket =  "Terlambat";
                                    }
                                    echo "<div class='row'>";
                                    echo ' <div class="col-md-6">
                                                <div class="punch-det">
                                                    <h6>Absen In</h6>
                                                    <p>'.tanggalwaktu($abs->absen_in).'</p>
                                                    <small class="d-block">'.$ket.'</small>
                                                </div>
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
                                    $ketOut =  "<small class='d-block'>";
                                    if($over){
                                        $ketOut .=  "Overtime"; 
                                    }else if($abs->early_out == 'y'){
                                        $ketOut .=  "Early out";
                                    }else{
                                        $ketOut .=  "Ontime";
                                    }
                                    $ketOut .=  "</small>";
                                    if($abs->absen_out != null){
                                    echo '<div class="col-md-6"> <div class="punch-det">
                                                <h6>Absen Out</h6>
                                                <p>'.tanggalwaktu($abs->absen_out).'</p>
                                                '.$ketOut.'
                                            </div></div>';
                                    }else{
                                        echo ' <div class="col-md-6">
                                                <div class="punch-det">
                                                    <h6>Absen Out</h6>
                                                    <p>-</p>
                                                </div>
                                                </div>';
                                    }
                                    echo "</div>";
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
                                       
                                      
                                        if($over){
                                            echo ' <div class="statistics">
                        
                                                    <div class="stats-box">
                                                        <p>Overtime</p>
                                                        <h6>'.$overtime.'</h6>
                                                    </div>
                                                        
                                                </div>';
                                        }
                                        
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
   
    <div class="col-md-6">
        <div class="card att-statistics">
            <div class="card-body pb-5">
                <h5 class="card-title">Statistik</h5>
                <div class="stats-list mb-2">
                    <div class="stats-info">
                        <?php if($todayStat->num_rows() > 0){ 
                            $tStat = $todayStat->row();
                        ?>
                        <p>Hari ini <strong><?= $tStat->duration?> <small>/ 8 hrs</small></strong></p>
                        <?php $percent = round($tStat->duration/8*100,0) ?>
                        <div class="progress">
                            <div class="progress-bar <?= progressColor($percent)?>" role="progressbar" style="width: <?=$percent?>%" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <?php }else{
                        ?>
                         <p>Hari ini <strong>0 <small>/ 8 hrs</small></strong></p>
                        <div class="progress">
                            <div class="progress-bar <?= progressColor(0)?>" role="progressbar" style="width: 0%" aria-valuenow="31" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                        <?php 
                        }?>
                    </div>
                    <div class="stats-info">
                        <p>Minggu ini <strong><?= $weekStatResult->durasi ?> <small>/ <?= $weekStat->total*8 ?> hrs</small></strong></p>
                        <?php 
                            $percent = round($weekStatResult->durasi/($weekStat->total*8)*100,0);
                        ?>
                        <div class="progress">
                            <div class="progress-bar  <?= progressColor($percent)?>" role="progressbar" style="width: <?=$percent?>%" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="stats-info">
                        <p>Bulan ini <strong><?= $monthStatResult->durasi ?> <small>/ <?= $monthStat->total*8 ?> hrs</small></strong></p>
                        <?php 
                            $percent = round($monthStatResult->durasi/($monthStat->total*8)*100,0);
                        ?>
                        <div class="progress">
                            <div class="progress-bar  <?= progressColor($percent)?>" role="progressbar" style="width: <?=$percent?>%" aria-valuenow="<?=$percent?>" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                   
                   
                    <div class="stats-info">
                        <p>Overtime <strong><?= $lembur->ovt ?></strong></p>
                        <div class="progress">
                            <div class="progress-bar bg-info" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                </div>
            </div>
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
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date </th>
                        <th>Absen In</th>
                        <th>Absen Out</th>
                        <th>Durasi</th>
                      
                        <th>Overtime</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                         $no =1;
                        foreach($record->result() as $row){
                            $overt = $this->model_app->view_where('overtime',array('absensi_id'=>$row->id));
                            if($overt->num_rows() > 0){
                                $ovr = $overt->row();
                                $overtimes = $ovr->overtime;
                            }else{
                                $overtimes = 0;
                            }
                            if($row->absen_out == null){
                                $absen_out = '-';
                                if($row->date != date('Y-m-d')){
                                    $act = '<form action="'.base_url('absensi/late').'" method="POST"><input type="hidden" name="id" value="'.encode($row->id).'"><button class="btn btn-primary btn-block btn-sm">Absen Out</button></form>';

                                }else{
                                    $act = '-';
                                }
                            }else{
                                $act = '-';
                                $absen_out = tanggalwaktu($row->absen_out);
                            }
                            echo "<tr>
                                    <td>".$no."</td>
                                    <td>".fulldate($row->date)."</td>
                                    <td>".tanggalwaktu($row->absen_in)."</td>
                                    <td>".$absen_out."</td>
                                    <td>".$row->duration." jam</td>
                                    <td>".$overtimes." jam</td>
                                    <td>".$act."</td>


                                    
                                 </tr>";
                            $no++;
                        }
                    ?>
                  
                </tbody>
            </table>
        </div>
    </div>
</div>