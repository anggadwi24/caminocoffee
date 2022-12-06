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

<form action="<?= base_url('pengajuan/store')?>" method="POST" enctype="multipart/form-data">
<div class="row">
    
    <div class="col-lg-12">
    
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">Form Pengajuan Cuti/DC</h4>
            </div>
            <div class="card-body">
               
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Perihal </label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="perihal" placeholder="Masukan Perihal" value="<?=set_value('name')?>" >
                            <div class="invalid-feedback d-block"><?= form_error('perihal') ?></div>
                        </div>
                    </div>
                 
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Tanggal Mulai - Sampai</label>
                        <div class="col-md-5">
                            <input type="text" class="form-control datetimepicker" name="start" placeholder="Masukan tanggal mulai" value="<?=set_value('start')?>" >
                            <div class="invalid-feedback d-block"><?= form_error('start') ?></div>
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control datetimepicker" name="end" placeholder="Masukan tanggal sampai" value="<?=set_value('end')?>" >
                            <div class="invalid-feedback d-block"><?= form_error('end') ?></div>
                        </div>
                    </div>
                   
                   
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">Alasan</label>
                        <div class="col-md-10">
                            <textarea rows="5" cols="5" class="form-control" name="alasan" placeholder="Masukan alasan"><?=set_value('address')?></textarea>
                            <div class="invalid-feedback d-block"><?= form_error('alasan') ?></div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-form-label col-md-2">File </label>
                        <div class="col-md-10">
                            <input type="file" class="form-control" name="file" placeholder="Masukan file" accept="image/*" >
                            <div class="invalid-feedback d-block"><?= form_error('file') ?></div>
                        </div>
                    </div>
                  
                
            </div>
        </div>
        
        
        
    </div>
    <div class="col-12 text-right">
            <button class="btn btn-primary  mt-5">Simpan</button>
    </div>
    
</div>