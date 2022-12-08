<?php
ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="path/to/font-awesome/css/font-awesome.min.css">
    <title>REKAP ABSEN <?= bulan($months).' '.$years?></title>

    <style>
        body{
            padding: 0;
            overflow-x: hidden;
            font-family: 'aileron', sans-serif;
            color: #5B5B5B;
            margin: 0;
            min-height: 100vh;
            

        }

        .head{
            padding: 0px 50px;
        }
        .head h1{
            font-size: 70px;
        }

        .head img {
            max-width: 200px;
            margin-left: auto;
            margin-top:-10px;
            margin-left:45px;
           
        }

        .info{
            padding: 0 50px;
        }

        .info table{
            border-collapse: collapse;
        }

        .info td{
            border: solid 1px #5B5B5B;
        }
        .info th{
            border: solid 1px #5B5B5B;

        }
        .info td, .info th{
            padding: 10px 10px;
        }

        .syarat{
            padding: 5px 100px;
        }







        .icon3 img{
            width: 10px;
        }
        .texticon{
           padding: 0 30px 0 20px;
           
            color: #E1251B;
            font-size: 20px;
            font-weight: 700;
        }
        td.bulet{
            
            height: 50px;
          
            background: #E1251B;
            border-radius: 0px 30px 0px 0px;
        }
        img.explore{
            padding-right: 100px;
            width: 250px;
        }
        .footer2{
    
            background-color: #E1251B;
            padding: 0 50px;
            color: #fff;
            position: absolute;
            bottom: 0;
            width: 100%;
            min-height: 2.5rem;
          
            
        }

        .fot-logo img{
            width: 150px;
        }
       
        .footer2 p{
           font-size:10px;
            line-height: 142.5%;
            margin: 10px;
        }
        .fot-text{
            margin: 10px;
            padding: 0 10px;
        }
        

        #page-container {
            position: relative;
            min-height:100vh;
            padding-bottom: 300px;
            
            }

            #content-wrap {
            padding-bottom: 2.5rem;    /* Footer height */
            }

            #footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            min-height: 2.5rem;            /* Footer height */
}
    </style>
</head>
<body>
   
<div id="page-container">
                <div id="content-wrap">
                  
      
                  <div class="info">
                      <h3>REKAP ABSENSI BULAN <?= bulan($months).' '.$years?></h3>
                     
                      <h6></h6>
                      <div>
                          <table width="100%">
                             
                              <tr>
                                    <th>Pegawai</th>
                                    <?php 
                                        for($i = $begin; $i <= $end; $i->modify('+1 day')){
                                            echo '<th>'.$i->format('d').'</th>';
                                        }
                                    
                                    ?>
                                
                                </tr>
                             
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
                            <?= $row->name ?>
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
                                             echo '<td><i title="Absen" class="fa fa-check text-success"></i>';
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
      
                          </table>
                      </div>
                  </div>
                  
                </div>
               
              </div>
     
</body>
</html>