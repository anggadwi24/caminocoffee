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
<form action="<?= base_url('pegawai') ?>" method="GET">
    <div class="row filter-row">
    <input type="hidden" name="type" value="<?=$type?>">
        <div class="col-sm-6 col-md-8">  
            <div class="form-group form-focus">
                <input type="text" class="form-control floating" name="keyword" value="<?=$keyword?>">
                <label class="focus-label">Nama Pegawai</label>
                
            </div>
        </div>
    
        <div class="col-sm-6 col-md-4 ">  
            <button type="submit" class="btn btn-success btn-block"> Search </button>  
        </div>
    </div>
</form>

<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table datatable">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Phone</th>
                        <th>TTL</th>
                        <th class="text-nowrap">Tgl Daftar</th>
                        
                        <th class="text-right no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if($record->num_rows() > 0){
                            foreach($record->result() as $row){
                    ?>

<tr>
                        <td>
                            <h2 class="table-avatar">
                                <a href="<?= base_url('pegawai/detail/'.$row->username) ?>" class="avatar">
                                <?php 
                                    if(file_exists('upload/user/'.$row->photo)){
                                        echo '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';
                                        
                                    }else{
                                        echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                                    }
                                ?>
                                </a>
                                <a href="<?= base_url('pegawai/detail/'.$row->username) ?>"><?= $row->name?> <span> <?php 
                                if($row->active == 'y'){ echo "Active"; }else{echo "Suspend";}
                            ?></span></a>
                            </h2>
                        </td>
                        <td><?= $row->username?></td>
                        <td><?= $row->phone?></td>
                        <td><?=$row->pob.', '.date('d M Y',strtotime($row->dob))?></td>
                       
                       
                        <td><?= date('d M Y H:i',strtotime($row->created_at))?></td>
                       
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item" href="<?= base_url('pegawai/detail/'.$row->username)?>" ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <a class="dropdown-item delete" href="#" data-username="<?=$row->username?>" ><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                    <?php 
                                        if($row->active == 'y'){
                                    ?>
                                        <a class="dropdown-item" href="<?= base_url('pegawai/status/'.$row->username)?>" ><i class="fa fa-times m-r-5"></i> Suspend</a>
                                    <?php }else{?>      
                                        <a class="dropdown-item" href="<?= base_url('pegawai/status/'.$row->username)?>" ><i class="fa fa-check m-r-5"></i> Active</a>
                                    <?php }?> 

                                </div>
                            </div>
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
