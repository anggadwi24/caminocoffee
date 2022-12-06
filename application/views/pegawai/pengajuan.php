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
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table datatable table-striped custom-table mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Perihal </th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        
                        <th>Aksi</th>
                      
                       
                    </tr>
                </thead>
                <tbody>
                    <?php 
                         $no =1;
                        foreach($record->result() as $row){
                            if($row->approve == 'y'){
                                if($row->approve_by != null AND $row->approve_at != null){
                                    $status = 'Disetujui';
                                }else{
                                    $status = 'Dalam pengajuan';
                                }
                            }else if($row->approve == 'p'){
                                $status = 'Dalam pengajuan';
                            }else if($row->approve == 'n'){
                                $status = 'Ditolak ';
                            }
                            $diff = daysDifference($row->end,$row->start)+1;
                            $act = '<a href="'.base_url('pengajuan/detail?no='.encode($row->id)).'"><i class="fa fa-eye"></i></a>';
                            echo "<tr>
                                    <td>".$no."</td>
                                    <td>".$row->perihal."</td>
                                    <td>".fulldate($row->start)." - ".fulldate($row->end)." (".$diff." hari)</td>
                                    <td>".$status."</td>
                                    
                                    <td>".$act."</td>


                                    
                                 </tr>";
                            $no++;
                        }
                    ?>
                  
                </tbody>
            </table>
        </div>
    </div>
</div>