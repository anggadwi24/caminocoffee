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
    if($row->approve == 'y'){
        $status = 'Diterima';
        $hrd = $this->model_app->view_where('hrd',array('id'=>$row->approve_by));
        if($hrd->num_rows() > 0 ){
            $hrd = $hrd->row();
            $oleh = ',  <span class="text-muted">Oleh</span> <span class="text-xs">'.$hrd->name.'</span>';
        }else{
            $oleh = '';
        }
        $pada =  ',  <span class="text-muted">Pada</span> <span class="text-xs">'.tanggalwaktu($row->approve_at).'</span>';
    }else if($row->approve == 'n'){
        $status = 'Ditolak';
        $oleh = '';
        $pada = '';

    }else if($row->approve == 'p'){
        $status = 'Diproses';
        $oleh = '';
        $pada = '';
    }
?>
<div class="row">
    <div class="col-lg-8 col-xl-9">
        <div class="card">
            <div class="card-body">
                <div class="project-title">
                    <h5 class="card-title"><?=$row->perihal?></h5>
                    <small class="block text-ellipsis "><span class="text-muted">Pengajuan </span><span class="text-xs"><?=$status?></span><?=$oleh?><?=$pada?></small>
                    <small class="block text-ellipsis m-b-15"><span class="text-xs"><?=fulldate($row->start)?></span> - <span class="text-xs"><?=fulldate($row->end)?></span> (<span class="text-xs"><?=daysDifference($row->end,$row->start)+1?></span> <span class="text-muted">Hari </span>)</small>

                </div>
                <p><?=$row->reason?></p>
               
            </div>
        </div>
        
        <div class="card">
            <div class="card-body">
                <h5 class="card-title m-b-20">File pengajuan</h5>
                <ul class="files-list">
                    <?php if(file_exists('upload/pengajuan/'.$row->file)){?>
                    <li>
                        <div class="files-cont">
                            <div class="file-type">
                                <span class="files-icon"><i class="fa fa-image"></i></span>
                            </div>
                            <div class="files-info">
                                <?php $path = './upload/pengajuan/'.$row->file;?>
                                <span class="file-name text-ellipsis"><a href="<?=base_url('upload/pengajuan/'.$row->file)?>" target="_BLANK"><?=$row->file_name?></a></span>
                                <span class="file-author"><a href="javascript:void(0)"><?=$row->name?></a></span> <span class="file-date"><?=tanggalwaktu($row->tanggal)?></span>
                                <div class="file-size">Size: <?=formatSizeUnits(filesize($path)) ?></div>
                            </div>
                           
                        </div>
                    </li>
                    <?php }else{?>
                        <li>
                        <div class="files-cont">
                            <div class="file-type">
                                <span class="files-icon"><i class="fa fa-exclamation-circle  "></i></span>
                            </div>
                            <div class="files-info">
                                <span class="file-name text-ellipsis pt-2"><a href="javascript:void(0)">Tidak ada file</a></span>
                                
                            </div>
                           
                        </div>
                    </li>
                    <?php }?>
                </ul>
            </div>
        </div>
       
    </div>
    <div class="col-lg-4 col-xl-3">
        <div class="card">
            <div class="card-body">
                <h6 class="card-title m-b-15">Detail</h6>
                <div class="justify-content-center ">
                
                    <div class="form-group">
                        <label for="">Tgl Diajukan</label>
                        <h6><?=tanggalwaktu($row->tanggal)?></h6>
                    </div>
                    <div class="form-group">
                        <label for="">Status</label>
                        <h6><?= $status?></h6>
                    </div>
                </div>
                
               
            </div>
        </div>
       
       
    </div>
</div>