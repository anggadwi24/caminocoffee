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
    <title>Invoice</title>

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

        .info td, .info th{
   
            text-align: left;
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
                  <div class="head">
                      <table width="100%">
                          <tr>
                              <td style="width:75%">
                                  <h3>SLIP GAJI <?=strtoupper(bulan($row->months.' '.$row->years))?></h3>

                               

                                  <table>
                                    <tr>
                                          <td>NAMA <span style="padding-left: 20px;"></span></td>
                                          <td>:</td>
                                          <td><?=strtoupper($row->name)?></td>
                                      </tr>
                                      <tr>
                                          <td>JABATAN <span style="padding-left: 20px;"></span></td>
                                          <td>:</td>
                                          <td><?=strtoupper($row->position)?></td>
                                      </tr>
                                    
                                  </table>
                              
                              
                              </td>
                              <td>
                              
                                  <img src="./upload/logo.png" alt="" style="width: 100px;height:100px;">
                              
                              </td>
                          </tr>
      
                      </table>
      
                  </div>
      
                  <div class="info">
                        <h3>STATISTIK <?= strtoupper(bulan($month))  ?> <?= $years?></h3>
                      <div>
                          <table width="100%">
                              <tr>
                                  <th>HARI KERJA </th><th>:</th><th> <?= $lastMonthResult->totalAbsen?> / <?= $lastMonth->total?> HARI </th>
                               
                              </tr>
                              <tr>
                                <th>JAM KERJA </th><th>:</th><th><?= round($lastMonthResult->durasi,2).' / '.$hours?> JAM</th>
                                
                              </tr>
                             
                              <tr>
                                 <th>TERLAMBAT </th><th>:</th><th>    <?= $terlambat->totalEarlyIn.' / '.$lastMonthResult->totalAbsen?> HARI</th>
                              </tr>
                              <tr>
                                
                                <th>MENDAHULUI SHIFT PULANG </th><th>:</th><th>   <?= $pulang->totalEarlyOut.' / '.$lastMonthResult->totalAbsen?> HARI</th>
                              </tr>
                              <tr>
                                <th>OVERTIME </th><th>:</th><th>   <?= $overtime->ovt?> JAM</th>
                              </tr>
                              <tr>
                                <th>CUTI/DC </th><th>:</th><th>    <?= $cuti->total?> HARI</th>
                              </tr>
                              <tr>
                              <th>LIBUR </th><th>:</th><th>    <?= $off->total?> HARI</th>
                              </tr>
                            
      
                          </table>
                      </div>
                  </div>
                  <div class="info">
                        <h3>GAJI <?= strtoupper(bulan($row->months))  ?> <?= $row->years?></h3>
                      <div>
                          <table width="100%">
                              <tr>
                                  <th>GAJI POKOK </th>
                                  <th>:</th>
                                  <th><?=currency($row->basic_salary)?></th>
                               
                              </tr>
                              <tr>
                                  <th>TUNJANGAN MAKAN </th>
                                  <th>:</th>
                                  <th><?=currency($row->meal_salary)?></th>
                               
                              </tr>
                              <tr>
                                  <th>OVERTIME </th>
                                  <th>:</th>
                                  <th><?=currency($row->overtime_pay)?></th>
                               
                              </tr>
                              <tr>
                                  <th>INSENTIF </th>
                                  <th>:</th>
                                  <th><?=currency($row->incentive)?></th>
                               
                              </tr>
                              <tr>
                                  <th>BPJS </th>
                                  <th>:</th>
                                  <th><?=currency($row->bpjs)?></th>
                               
                              </tr>
                              <tr>
                                  <th>POTONGAN </th>
                                  <th>:</th>
                                  <th>-<?=currency($row->cut_salary)?></th>
                               
                              </tr>
                              <tr>
                                <th colspan="3"><hr></th>
                              </tr>
                              <tr>
                                  <th>GAJI YANG DITERIMA </th>
                                  <th></th>
                                  <th><?=rp($row->total_salary)?></th>
                               
                              </tr>
                            
      
                          </table>
                      </div>
                  </div>
                
                </div>
                
              </div>
     
</body>
</html>