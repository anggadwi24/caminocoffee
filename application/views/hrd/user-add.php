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
<form action="<?= base_url('user/store')?>" method="POST" enctype="multipart/form-data">
<div class="row">
    
    <div class="col-lg-12">
    
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form HRD</h4>
            </div>
            <div class="card-body">
               
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Nama </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="name" placeholder="Masukan nama HRD" value="<?=set_value('name')?>" >
                            <div class="invalid-feedback d-block"><?= form_error('name') ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Telepon/Hp </label>
                        <div class="col-md-10">
                            <input type="number" class="form-control" name="phone" placeholder="Masukan telepon/hp HRD" value="<?=set_value('phone')?>" >
                            <div class="invalid-feedback d-block"><?= form_error('phone') ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Tempat Lahir </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="pob" placeholder="Masukan tempat lahir" value="<?=set_value('pob')?>" >
                            <div class="invalid-feedback d-block"><?= form_error('pob') ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Tanggal Lahir </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control datetimepicker" name="dob" placeholder="Masukan tanggal lahir" value="<?=set_value('dob')?>" >
                            <div class="invalid-feedback d-block"><?= form_error('dob') ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Alamat</label>
                        <div class="col-md-10">
                            <textarea rows="5" cols="5" class="form-control" name="address" placeholder="Masukan alamat HRD"><?=set_value('address')?></textarea>
                            <div class="invalid-feedback d-block"><?= form_error('address') ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Foto </label>
                        <div class="col-md-10">
                            <input type="file" class="form-control" name="file" placeholder="Masukan foto" accept="image/*" >
                            <div class="invalid-feedback d-block"><?= form_error('photo') ?></div>
                        </div>
                    </div>
                  
                
            </div>
        </div>
        <div class="card mb-0">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Login</h4>
            </div>
            <div class="card-body">
                
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Username </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="username" placeholder="Masukan username HRD"  value="<?=set_value('username')?>">
                            <div class="invalid-feedback d-block"><?= form_error('username') ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Password </label>
                        <div class="col-md-10">
                            <input type="password" class="form-control" name="password" placeholder="Masukan password HRD" >
                            <div class="invalid-feedback d-block"><?= form_error('password') ?></div>
                        </div>
                    </div>
               
            </div>
        </div>
        
        
    </div>
    <div class="col-12 text-right">
            <button class="btn btn-primary  mt-5">Simpan</button>
    </div>
    
</div>
</form>