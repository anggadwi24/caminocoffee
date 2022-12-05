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
<?php 
     $getOvt = $this->model_app->view_where('overtime',array('absensi_id'=>$row->absensi_id,'pegawai_id'=>$row->pegawai_id));
     if($getOvt->num_rows() > 0){
         $ovt = $getOvt->row();
         $overtime = $ovt->overtime.' jam';
         $over = true;
     }else{
         $overtime = '-';
         $over = false;
     }
?>
<div class="row">
      

    <div class="col-md-12">
        <div class="card">
            
            <div class="card-body">
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
                                    <h6 class="text-muted">Pegawai</h6>
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
                                    <li>
                                        <div class="title">Gender:</div>
                                        <div class="text">Male</div>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-edit"><a  href="<?= base_url('pegawai/detail/'.$row->username)?>"><i class="fa fa-eye"></i></a></div>
                </div>
            </div>
        </div>
      
            
    </div>
    
    <div class="col-md-12">
        <div class="card punch-status">
            <div class="card-body">
                <h5 class="card-title">Tanggal <small class="text-muted"><?= fulldate($row->date) ?></small></h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="punch-det">
                            <h6>Absen In</h6>
                            <p><?= tanggalwaktu($row->absen_in) ?>  
                            <?php 
                                    if($row->absen_out == null){
                                        $out = 'Belum absen out';
                                        $absen_out = date('H:i');
                                    }else{
                                        $out = tanggalwaktu($row->absen_out);
                                        $absen_out = date('H:i',strtotime($row->absen_out));

                                    }
                                    $absen_in = date('H:i',strtotime($row->absen_in));
                                    $duration = selisihJam($absen_in,$absen_out);
                            ?>
                            <small class="d-block">
                                        <?php 
                                        if($row->early_in == 'y'){ 
                                            echo "Early in";
                                        }else{ 
                                            if($row->absen_in > $row->schedule_in){
                                                echo "Terlambat";

                                            }else{
                                                echo "Ontime";

                                            }
                                        }
                                        ?>
                            </small>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="punch-det">
                            <h6>Absen Out</h6>
                            <?php 
                                if($row->absen_out != null){
                            ?>
                                <p>
                                    <?= tanggalwaktu($row->absen_out) ?> 
                                    <?php 
                                        echo "<small class='d-block'>";
                                        if($over){
                                                echo "Overtime"; 
                                        }else if($row->early_out == 'y'){
                                            echo "Early out";
                                        }else{
                                            echo "Ontime";
                                        }
                                        echo "</small>";
                                    ?>

                                </p>
                            <?php 
                                }else{ 
                                    echo "<p>-</p>";
                                }
                            ?>   
                        </div>               
                    </div>
                </div>
                
              
              
              
                <div class="punch-info">
                    <div class="punch-hours">
                        <span><?= $duration ?> jam</span>
                    </div>
                </div>
               
                <div class="statistics">
                    
                    <div class="stats-box">
                        <p>Overtime</p>
                        <h6><?= $overtime ?></h6>
                    </div>
                       
                   
                </div>
            </div>
        </div>
    </div>
    
 
</div>