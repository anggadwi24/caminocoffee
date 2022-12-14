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
<form action="<?= base_url('rekap') ?>" method="GET">
    <div class="row filter-row">
      
        <div class="col-sm-6 col-md-4"> 
            <div class="form-group form-focus select-focus">
            <select class="select floating" name="month"> 
                   <?php 
                        for($a=0;$a<12;$a++){
                            if($bulan[$a]['code'] == $months){
                                echo "<option value='".$bulan[$a]['code']."' selected>".$bulan[$a]['bulan']."</option>";

                            }else{
                                echo "<option value='".$bulan[$a]['code']."'>".$bulan[$a]['bulan']."</option>";

                            }
                        }
                    ?>
                </select>
                <label class="focus-label">Select Month</label>
            </div>
        </div>
        <div class="col-sm-6 col-md-4"> 
            <div class="form-group form-focus select-focus">
                <select class="select floating" name="year"> 
                   
                    <option value="<?= date('Y',strtotime('-2 Year'))?>" <?php if($years == date('Y',strtotime('-2 Year'))) { echo "selected";} ?>><?= date('Y',strtotime('-2 Year'))?></option>
                    <option value="<?= date('Y',strtotime('-1 Year'))?>" <?php if($years == date('Y',strtotime('-1 Year'))) { echo "selected";} ?>><?= date('Y',strtotime('-1 Year'))?></option>
                    <option value="<?= date('Y')?>" <?php if($years == date('Y')) { echo "selected";} ?>><?= date('Y')?></option>
                    <option value="<?= date('Y',strtotime('+1 Year'))?>"  <?php if($years == date('Y',strtotime('+1 Year'))) { echo "selected";} ?>><?= date('Y',strtotime('+1 Year'))?></option>
                    <option value="<?= date('Y',strtotime('+2 Year'))?>"  <?php if($years == date('Y',strtotime('+2 Year'))) { echo "selected";} ?> > <?= date('Y',strtotime('+2 Year'))?></option>
                 
                   
                </select>
                <label class="focus-label">Select Year</label>
            </div>
        </div>
        <div class="col-sm-12 col-md-4">  
            <button class="btn btn-success  btn-block">Search</button>
         
        </div>     
    </div>
</form>

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table table-striped custom-table table-nowrap mb-0">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <?php 
                              for($i = $begin; $i <= $end; $i->modify('+1 day')){
                                echo '<th>'.$i->format('d').'</th>';
                              }
                        
                        ?>
                     
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        foreach($employee->result() as $row){
                    ?>
                    <?php if($row->active == 'y'){
                        if($this->session->userdata['isLog']['username'] == $row->username){
                            echo "<tr class='bg-info '>";
                            $styles = 'text-dark';
                        }else{
                            echo "<tr  class=''>";
                            $styles = 'text-dark';
                        }
                      
                    }else{
                        echo "<tr class='bg-warning'>";
                        $styles = 'text-white';
                    } ?>
                   
                        <td>
                            <h2 class="table-avatar">
                                <a class="avatar avatar-xs" href="<?= base_url('pegawai/detail/'.$row->username) ?>">
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
                                <a href="<?= base_url('pegawai/detail/'.$row->username) ?>" class="<?=$styles?>"><?= $row->name ?> <span><?= ucfirst($row->position)?></span></a>
                            </h2>
                        </td>
                        <?php 
                            
                          
                              for($a = 1; $a <= $difference;$a++){
                                $date = $years.'-'.$months.'-'.numberString($a);
                                $date = date('Y-m-d',strtotime($date));
                                $check = $this->model_app->view_where('schedule',array('pegawai_id'=>$row->pegawai_id,'dates'=>$date,'months'=>$months,'years'=>$years));
                                if($check->num_rows() > 0){
                                    $sch = $check->row();
                                    if($sch->status == 'on'){
                                        $absen = $this->model_app->view_where('absensi',array('pegawai_id'=>$row->pegawai_id,'schedule_id'=>$sch->id));
                                        if($absen->num_rows() > 0){
                                            $abs = $absen->row();
                                             echo '<td><a href="javascript:void(0);" class="detail '.$styles.'" data-absen="'.encode($abs->id).'" ><i title="Absen" class="fa fa-check text-success"></i>';
                                            $overtime = $this->model_app->view_where('overtime',array('absensi_id'=>$abs->id));
                                            if($overtime->num_rows() > 0){
                                                echo '<i class="fa fa-clock-o d-block text-primary" title="Overtime"></i>';
                                            }
                                             echo '</a></td>';

                                        }else{
                                            echo '<td><i class="fa fa-times text-danger" title="Tidak Absen"></i></td>';

                                        }

                                    }else{
                                        echo '<td title="'.ucfirst($sch->status).'">-</td>';
                                    }
                                          

                                }else{
                                    echo '<td title="Tidak ada schedule">-</td>';
                                }
                              }
                           
                        
                        ?>         
                    </tr>
                    <?php 
                        }
                    ?>
                  
                 
                </tbody>
            </table>
        </div>
    </div>
</div>


<div class="modal custom-modal fade" id="detail" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Absensi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row" id="data">
                    
                </div>
            </div>
        </div>
    </div>
</div>