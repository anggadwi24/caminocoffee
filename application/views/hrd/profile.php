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
                <li class="nav-item"><a href="#profil" data-toggle="tab" class="nav-link active">Edit</a></li>
                
               
            </ul>
        </div>
    </div>
</div>
<div class="tab-content">
					
    <!-- Profile Info Tab -->
    <div id="profil" class="pro-overview tab-pane fade show active">
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