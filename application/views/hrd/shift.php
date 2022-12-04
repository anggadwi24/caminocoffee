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
        <div class="table-responsive">
            <table class="table table-striped custom-table datatable">
                <thead>
                    <tr>
                        <th>Shift</th>
                        <th>Jam</th>
                        
                        <th class="text-right no-sort">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        if($record->num_rows() > 0){
                            foreach($record->result() as $row){
                    ?>

<tr>
                    
                        <td><?= $row->name?></td>
                        <td><?= date('H:i',strtotime($row->schedule_in)).' - '.date('H:i',strtotime($row->schedule_out)) ?></td>
                      
                       
                       
                      
                       
                        <td class="text-right">
                            <div class="dropdown dropdown-action">
                                <a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="material-icons">more_vert</i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a class="dropdown-item edit" href="#" data-id="<?=$row->id?>" ><i class="fa fa-pencil m-r-5"></i> Edit</a>
                                    <a class="dropdown-item delete" href="#" data-id="<?=$row->id?>" ><i class="fa fa-trash-o m-r-5"></i> Delete</a>
                                   

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
<div id="add_shift" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="<?= base_url('shift/store')?>" method="POST">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Shift <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="shift">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Jam Masuk <span class="text-danger">*</span></label>
                                <input class="form-control timepicker" type="text" name="schedule_in">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Jam Keluar <span class="text-danger">*</span></label>
                                <input class="form-control timepicker" type="text" name="schedule_out">
                            </div>
                        </div>
                       
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="edit_shift" class="modal custom-modal fade" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Shift</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" id="formEdit" method="POST">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="col-form-label">Shift <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" name="shift" id="shift">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Jam Masuk <span class="text-danger">*</span></label>
                                <input class="form-control timepicker" type="text" name="schedule_in" id="in">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="col-form-label">Jam Keluar <span class="text-danger">*</span></label>
                                <input class="form-control timepicker" type="text" name="schedule_out" id="out">
                            </div>
                        </div>
                       
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="modal custom-modal fade" id="delete_shift" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-header">
                    <h3>Hapus shift</h3>
                    <p>Apakah anda yakin akan menghapus shift ini?</p>
                </div>
                <div class="modal-btn delete-action">
                    <div class="row">
                        <div class="col-6">
                            <form action="<?= base_url('shift/delete/')?>" method="POST">
                            <input type="hidden" name="id" id="id" >
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
        $('#delete_shift').modal('show');
        var id = $(this).attr('data-id');
        $('#id').val(id);
    })
</script>