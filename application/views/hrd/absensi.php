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
<form action="<?= base_url('absensi') ?>" method="GET">
    <div class="row filter-row">
    
    <div class="col-sm-6 col-md-3">  
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="pegawai"> 
                    <option value="all" selected>Semua</option>
                   <?php 
                       foreach($pegawai->result() as $peg){
                        if($peg->username == $employee){
                            echo "<option value='".$peg->username."' selected>".$peg->name."</option>";

                        }else{
                            echo "<option value='".$peg->username."'>".$peg->name."</option>";

                        }
                       }
                    ?>
                </select>
                <label class="focus-label">Pilih Pegawai</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-3"> 
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
        <div class="col-sm-6 col-md-3"> 
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
        <div class="col-sm-6 col-md-3 ">  
            <button type="submit" class="btn btn-success btn-block"> Search </button>  
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table   datatable text-center">
                <thead>
                    <tr>
                        <th >Pegawai</th>
                        <th >Tanggal</th>
                        <th >Shift</th>
                        <th>Absen In</th>
                        <th>Absen Out</th>
                        <th>Overtime</th>
                        <th >Aksi</th>
                        
                        
                        
                    </tr>
                   
                </thead>
                <tbody>
                    <?php 
                        if($record->num_rows() > 0){
                            foreach($record->result() as $row){
                                $getOvt = $this->model_app->view_where('overtime',array('absensi_id'=>$row->id,'pegawai_id'=>$row->pegawai_id));
                                if($getOvt->num_rows() > 0){
                                    $ovt = $getOvt->row();
                                    $overtime = $ovt->overtime.' jam';
                                    $over = true;
                                }else{
                                    $overtime = '-';
                                    $over = false;
                                }
                    ?>

                    <tr>
                        <td >
                            <h2 class="table-avatar">
                                <a href="<?= base_url('pegawai/detail/'.$row->username) ?>" class="avatar">
                                <?php 
                                if($row->photo != '' AND $row->photo != null){
                                    if(file_exists('upload/user/'.$row->photo)){
                                        echo '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';
                                        
                                    }else{
                                        echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                                    }
                                }else{
                                    echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                                }
                                    
                                ?>
                                </a>
                                <a href="<?= base_url('pegawai/detail/'.$row->username) ?>"><?= $row->name?><span><?= ucfirst($row->position)?></span></a>
                            </h2>
                        </td>
                        <td ><?= fulldate($row->date)?></td>
                        <td><?= $row->shift_name?><small class="d-block"><?= date('H:i',strtotime($row->schedule_in))?> - <?= date('H:i',strtotime($row->schedule_out))?></small></td>
                        <td>
                            <?= date('H:i',strtotime($row->absen_in))?>
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
                        </td>
                        <td>
                            <?php 
                                if($row->absen_out == null){ 
                                    echo "-";
                                }else{
                                    echo date('H:i',strtotime($row->absen_out));
                                    echo "<small class='d-block'>";
                                    if($over){
                                           echo "Overtime"; 
                                    }else if($row->early_out == 'y'){
                                        echo "Early out";
                                    }else{
                                        echo "Ontime";
                                    }
                                    echo "</small>";
                                } 
                            ?>
                        </td>
                        <td><?= $overtime?></td>
                      
                       
                        
                       
                        <td class="text-center" >
                            <a  href="<?= base_url('absensi/detail?absensi='.encode($row->id))?>" class="text-primary" ><i class="fa fa-eye m-r-5"></i> </a>
                        </td>
                    </tr>
                   
                    <?php
                            }
                        }
                    ?>
                   
                </tbody>
            </table>
        </div>
    </div>
    <div class="modal custom-modal fade" id="delete_employee" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Hapus pegawai</h3>
                    <p>Apakah anda yakin akan menghapus pegawai ini?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <form action="<?= base_url('pegawai/delete/')?>" method="POST">
                            <input type="hidden" name="username" id="username" >
                            <button class="btn btn-primary continue-btn">Hapus</button>
                            </form>
                            
                        </div>
                        <div class="col-6 ">
                            <button  data-dismiss="modal" class="btn btn-primary cancel-btn  text-right float-right">Batal</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).on('click','.delete',function(){
        $('#delete_employee').modal('show');
        var username = $(this).attr('data-username');
        $('#username').val(username);
    })
</script>