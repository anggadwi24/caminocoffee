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
      
<div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Edit Jadwal <small><?= fulldate($row->dates)?></small></h4>
            </div>
            <div class="card-body">
                <form action="<?= base_url('schedule/update')?>" method="POST">
                    <input type="hidden" name="id" value="<?= encode($row->id)?>">
                    <div class="row">
                        <div class="col-8 ">
                            <div class="form-group form-focus select-focus">
                                <select name="shift" id="shift" class="select floating">
                                    <?php 
                                        foreach($shift->result() as $shf){
                                            if($shf->id == $row->shift_id){
                                                echo "<option value='".$shf->id."' selected>".$shf->name."</option>";

                                            }else{
                                                    echo "<option value='".$shf->id."'>".$shf->name."</option>";

                                            }
                                        }
                                        
                                    ?>
                                    <option value="off" <?php if($row->status == 'off'){echo "selected";}?>>Off</option>
                                    <option value="dc" <?php if($row->status == 'dc'){echo "selected";}?>>DC/Cuti</option>
                                </select>
                                <label class="focus-label">Pilih shift</label>
                            </div>
                        </div>
                        <div class="col-4">
                            <button class="btn btn-block btn-lg btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <div class="card">
            
            <div class="card-body">
                <div class="profile-view">
                    <div class="profile-img-wrap">
                        <div class="profile-img">
                            <a href="javascript:void(0)">
                            <?php 
                            if( $rows->photo != ''){
                                if(file_exists('upload/user/'.$rows->photo) ){
                                    echo '<img src="'.base_url('upload/user/'.$rows->photo).'" alt="'.$rows->name.'">';

                                }else{
                                    echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$rows->name.'">';
                                }
                                
                            }else{
                                echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$rows->name.'">';
                            }
                        ?>
                            </a>
                        </div>
                    </div>
                    <div class="profile-basic">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="profile-info-left">
                                    <h3 class="user-name m-t-0 mb-0"><?= ucwords($rows->name)?></h3>
                                    <h6 class="text-muted">Pegawai</h6>
                                    <small class="text-muted"><?php   if($rows->active == 'y'){ echo "Active"; }else{echo "Suspend";} ?></small>
                                    
                                    <div class="small doj text-muted">Tanggal bergabung : <?= tanggal($rows->created_at)?></div>
                                    
                                </div>
                            </div>
                            <div class="col-md-7">
                                <ul class="personal-info">
                                    <li>
                                        <div class="title">Telepon/Hp:</div>
                                        <div class="text"><?= $rows->phone?></div>
                                    </li>
                                    
                                    <li>
                                        <div class="title">Tempat Lahir</div>
                                        <div class="text"><?= $rows->pob ?> </div>
                                    </li>
                                    <li>
                                        <div class="title">Tanggal Lahir</div>
                                        <div class="text"><?= fulldate($rows->dob) ?> </div>
                                    </li>
                                    <li>
                                        <div class="title">Alamat:</div>
                                        <div class="text"><?= $rows->address ?></div>
                                    </li>
                                    <li>
                                        <div class="title">Gender:</div>
                                        <div class="text">Male</div>
                                    </li>
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="pro-edit"><a  href="<?= base_url('pegawai/detail/'.$rows->username)?>"><i class="fa fa-eye"></i></a></div>
                </div>
            </div>
        </div>
                            
            
    </div>
    
</div>