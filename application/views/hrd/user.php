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
<form action="<?= base_url('user') ?>" method="GET">
    <div class="row filter-row">
    <input type="hidden" name="type" value="<?=$type?>">
        <div class="col-sm-6 col-md-8">  
            <div class="form-group form-focus">
                <input type="text" class="form-control floating" name="keyword" value="<?=$keyword?>">
                <label class="focus-label">Nama HRD</label>
                
            </div>
        </div>
    
        <div class="col-sm-6 col-md-4 ">  
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
                        <?php if( $this->session->userdata['isLog']['username'] != $row->username){  ?>
                            <a href="<?= base_url('user/detail/'.$row->username)?>" class="avatar">
                        <?php }else{?>
                            <a href="<?= base_url('profile')?>" class="avatar">
                        <?php }?>
                        
                        <?php 
                            if(file_exists('upload/user/'.$row->photo)){
                                echo '<img src="'.base_url('upload/user/'.$row->photo).'" alt="'.$row->name.'">';
                                
                            }else{
                                echo '<img src="'.base_url('upload/user/default.png').'" alt="'.$row->name.'">';
                            }
                        ?>
                        </a>
                    </div>
                    <?php if( $this->session->userdata['isLog']['username'] != $row->username){ ?>
                    <div class="dropdown profile-action">
                        <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="<?= base_url('user/edit/'.$row->username)?>" ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                            <a class="dropdown-item delete" href="#" data-username="<?=$row->username?>" ><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                            <?php 
                                if($row->active == 'y'){
                            ?>
                                 <a class="dropdown-item" href="<?= base_url('user/status/'.$row->username)?>" ><i class="fa fa-times m-r-5"></i> Suspend</a>
                            <?php }else{?>      
                                <a class="dropdown-item" href="<?= base_url('user/status/'.$row->username)?>" ><i class="fa fa-check m-r-5"></i> Active</a>
                            <?php }?> 

                        </div>
                    </div>
                    <?php }?>
                    <h4 class="user-name m-t-10 mb-0 text-ellipsis"><a href=" <?php if($this->session->userdata['isLog']['username'] != $row->username) {echo base_url('user/detail/'.$row->username); }else{echo base_url('profile');}?>"><?= ucwords($row->name)?></a></h4>
                   
                    <div class="small text-muted"><?php if($row->active == 'y'){echo "Active";}else{ echo "Suspend";}?></div>

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
                    <p>Apakah anda yakin akan menghapus hrd ini?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <form action="<?= base_url('user/delete/')?>" method="POST">
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