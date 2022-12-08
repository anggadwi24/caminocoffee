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
<form action="<?= base_url('schedule') ?>" method="GET">
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
                                <a href="<?= base_url('pegawai/detail/'.$row->username) ?>" class="<?=$styles?>"><?= $row->name ?><small class="d-block text-muted"><?= ucfirst($row->position)?></small></a>
                            </h2>
                        </td>
                        <?php 
                            
                          
                              for($a = 1; $a <= $difference;$a++){
                                $date = $years.'-'.$months.'-'.numberString($a);
                                $date = date('Y-m-d',strtotime($date));
                                $check = $this->model_app->view_where('schedule',array('pegawai_id'=>$row->pegawai_id,'dates'=>$date,'months'=>$months,'years'=>$years));
                                if($check->num_rows() > 0){
                                    $checks = $check->row();
                                    if($checks->shift_id != NULL AND $checks->status == 'on'){
                                        $shift = $this->model_app->view_where('shift',array('id'=>$checks->shift_id));
                                        if($shift->num_rows() > 0){
                                            $shf = $shift->row();
                                          
                                            echo '<td><a href="javascript:void(0);" class="detail '.$styles.'" data-date="'.$date.'" data-username="'.$row->username.'" data-id="'.encode($checks->id).'">'.$shf->name.'</a></td>';

                                        }else{
                                            echo '<td><a href="javascript:void(0);" class="detail text-warning" data-date="'.$date.'"  data-username="'.$row->username.'" data-id="'.encode($checks->id).'">-</a></td>';
                                        }
                                    }else{
                                        if($checks->status == 'off'){
                                            $sts = 'Off';
                                        }else{
                                            $sts = 'DC';
                                        }
                                        echo '<td><a href="javascript:void(0);" class="detail text-danger" data-date="'.$date.'"  data-username="'.$row->username.'" data-id="'.encode($checks->id).'">'.$sts.'</a></td>';
                                        
                                    }
                                }else{
                                    echo '<td><a href="javascript:void(0);" class="detail '.$styles.'" data-date="'.$date.'"  data-username="'.$row->username.'" data-id="'.encode('not').'">-</a></td>';
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


<div class="modal custom-modal fade" id="detail_schedule" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Keterangan Jadwal</h5>
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