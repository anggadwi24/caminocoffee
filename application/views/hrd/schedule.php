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
                    <tr>
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
                                <a href="<?= base_url('pegawai/detail/'.$row->username) ?>"><?= $row->name ?></a>
                            </h2>
                        </td>
                        <?php 
                            $start_date_1 = $start_date;
                            $end_date_1 = $end_date;
                            print_r($start_date_1);
                              for($a = $start_date_1; $a <= $end_date_1; $a->modify('+1 day')){
                                echo '<td>'.$i->format('d').'</td>';
                              }
                            $start_date_1 = $start_date;
                            $end_date_1 = $end_date;
                        
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


<div class="modal custom-modal fade" id="attendance_info" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Attendance Info</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card punch-status">
                            <div class="card-body">
                                <h5 class="card-title">Timesheet <small class="text-muted">11 Mar 2019</small></h5>
                                <div class="punch-det">
                                    <h6>Punch In at</h6>
                                    <p>Wed, 11th Mar 2019 10.00 AM</p>
                                </div>
                                <div class="punch-info">
                                    <div class="punch-hours">
                                        <span>3.45 hrs</span>
                                    </div>
                                </div>
                                <div class="punch-det">
                                    <h6>Punch Out at</h6>
                                    <p>Wed, 20th Feb 2019 9.00 PM</p>
                                </div>
                                <div class="statistics">
                                    <div class="row">
                                        <div class="col-md-6 col-6 text-center">
                                            <div class="stats-box">
                                                <p>Break</p>
                                                <h6>1.21 hrs</h6>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-6 text-center">
                                            <div class="stats-box">
                                                <p>Overtime</p>
                                                <h6>3 hrs</h6>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card recent-activity">
                            <div class="card-body">
                                <h5 class="card-title">Activity</h5>
                                <ul class="res-activity-list">
                                    <li>
                                        <p class="mb-0">Punch In at</p>
                                        <p class="res-activity-time">
                                            <i class="fa fa-clock-o"></i>
                                            10.00 AM.
                                        </p>
                                    </li>
                                    <li>
                                        <p class="mb-0">Punch Out at</p>
                                        <p class="res-activity-time">
                                            <i class="fa fa-clock-o"></i>
                                            11.00 AM.
                                        </p>
                                    </li>
                                    <li>
                                        <p class="mb-0">Punch In at</p>
                                        <p class="res-activity-time">
                                            <i class="fa fa-clock-o"></i>
                                            11.15 AM.
                                        </p>
                                    </li>
                                    <li>
                                        <p class="mb-0">Punch Out at</p>
                                        <p class="res-activity-time">
                                            <i class="fa fa-clock-o"></i>
                                            1.30 PM.
                                        </p>
                                    </li>
                                    <li>
                                        <p class="mb-0">Punch In at</p>
                                        <p class="res-activity-time">
                                            <i class="fa fa-clock-o"></i>
                                            2.00 PM.
                                        </p>
                                    </li>
                                    <li>
                                        <p class="mb-0">Punch Out at</p>
                                        <p class="res-activity-time">
                                            <i class="fa fa-clock-o"></i>
                                            7.30 PM.
                                        </p>
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