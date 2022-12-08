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
    <title>PEGAWAI <?= title()?></title>

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
                      <h3>PEGAWAI <?= title()?></h3>
                      <?php
                        if($keyword){
                            echo "<h6>PENCARIAN : ".$keyword."</h6>";
                        }
                      ?>
                      <h6></h6>
                      <div>
                          <table width="100%">
                              <tr>
                                  <th>Nama</th>
                                  <th>Jabatan</th>
                                  <th>Username</th>
                                  <th>Telepon/Hp</th>
                                  <th>TTL</th>
                                  <th>Alamat</th>
                                  <th>Tanggal Daftar</th>
                              </tr>
                              <?php 
                                foreach($record->result() as $row){
                                    echo "<tr>
                                            <td>".$row->name."</td>
                                            <td>".ucfirst($row->position)."</td>

                                            <td>".$row->username."</td>
                                            <td>".$row->phone."</td>
                                            <td>".$row->pob.", ".fulldate($row->dob)."</td>
                                            <td>".$row->address."</td>
                                            <td>".tanggalwaktu($row->created_at)."</td>

                                         </tr>";
                                }
                              ?>
      
                          </table>
                      </div>
                  </div>
                  
                </div>
               
              </div>
     
</body>
</html>