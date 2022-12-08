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

<div class="row staff-grid-row">
    <?php 
        if($record->num_rows() > 0){
            foreach($record->result() as $row){
    ?>
            <div class="col-md-4 col-sm-6 col-12 col-lg-4 col-xl-3">
                <div class="profile-widget">
                    <div class="profile-img">
                        <a href="<?= base_url('pegawai/detail/'.$row->username)?>" class="avatar">
                        <?php 
                            if(file_exists('upload/user/'.$row->photo)){
                                echo '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';
                                
                            }else{
                                echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                            }
                        ?>
                        </a>
                    </div>
                    <div class="dropdown profile-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?= base_url('gaji/detail?slip='.encode($row->id))?>" ><i class="fa fa-eye m-r-5"></i> Detail</a>

                            <a class="dropdown-item" href="<?= base_url('gaji/edit?slip='.encode($row->id))?>" ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item" href="<?= base_url('gaji/pdf?slip='.encode($row->id))?>" ><i class="fa fa-file-pdf-o m-r-5"></i> Download</a>
                          
                            
                        </div>
                    </div>
                    <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href="<?= base_url('pegawai/edit/'.$row->username)?>"><?= ucwords($row->name)?></a></h4>
                   
                    <div class="small text-muted"><?= ucfirst($row->position)?> </div>
                    <h4 class="user-name m-t-10 mb-0 text-ellipsis"><?= rp($row->total_salary) ?></h4>

                </div>
            </div>
    <?php
            }
        }
    ?>
    
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