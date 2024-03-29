
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); {
    function rp($total){
        $num =  number_format($total,0);
        return 'Rp. '.str_replace(',','.',$num);
    }
    
    function currency($total){
      $num =  number_format($total,0);
      return str_replace(',','.',$num);
    }
    function encode($post){
        $key =  "SISTEMINFORMASIPENJADWALAN2022##";
        $ci = & get_instance();
        return $ci->encrypt->encode($post,$key);

    }
    function decode($post){
        $key =  "SISTEMINFORMASIPENJADWALAN2022##";

       $ci = & get_instance();
       return $ci->encrypt->decode($post,$key);
       
       
   }
   function selisihJam($jam_start,$jam_end){
      $date_awal  = new DateTime($jam_start);
      $date_akhir = new DateTime($jam_end);
      $selisih = $date_akhir->diff($date_awal);
     
      
      $hari = $selisih->format('%d');

      $jam = $selisih->format('%h');
      $menit = $selisih->format('%i');
      if($hari > 0){
        $jam = ($hari*24)+$jam;
      }
      if($menit >= 0 && $menit <= 9){
        $menit = "0".$menit;
      }
      
      $hasil = $jam.".".$menit;
      $hasil = number_format($hasil,2);
      return $hasil;
   }
   function numberReplace($number){
    return str_replace(array('.',','),'',$number);
   }
   function progressColor($percent){
    if($percent >= 0 AND $percent < 25){
       $bg = 'bg-danger';
    }else if($percent >= 25 AND $percent < 50){
      $bg = 'bg-warning';
    }else if($percent >= 50 AND $percent < 75){
      $bg = 'bg-info';
    }else{
      $bg = 'bg-success';
    }
    return $bg;
   }
   function getOvertime($jam_start,$jam_end){
    $date_awal  = new DateTime($jam_start);
    $date_akhir = new DateTime($jam_end);
    $selisih = $date_akhir->diff($date_awal);
    $hari = $selisih->format('%d');
    $jam = $selisih->format('%h');
    $menit = $selisih->format('%i');
    
    if($hari > 0){
      $jam = ($hari*24)+$jam;
    }
    if($menit >= 30){
      $hours = $jam+1;
      $minutes =  0;
    }else{
      $hours = $jam;
      $minutes = $menit;
    }
   
    if($minutes >= 0 && $minutes <= 9){
      $minutes = "0".$minutes;
    }
    $hasil = $hours.".".$minutes;
    $hasil = number_format($hasil,2);
    return $hasil;
 }
   function tanggalwaktu($date){
    date_default_timezone_set('Asia/Makassar');
    // array hari dan bulan
  //   $Hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
    $Bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
  // $Bulan = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    
    // pemisahan tahun, bulan, hari, dan waktu
    $tahun = substr($date,0,4);
    $bulan = substr($date,5,2);
    $tgl = substr($date,8,2);
    $waktu = substr($date,11,5);
    $hari = date("w",strtotime($date));
  //   $result = $Hari[$hari].", ".$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu;
  $result =$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu;

    return $result;
  }
  function hari($date){
    date_default_timezone_set('Asia/Makassar');
    // array hari dan bulan
    $Hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");

    
    
    $hari = date("w",strtotime($date));
 
    $result = $Hari[$hari];
    return $result;
  }
if (!function_exists('tanggal')) {
  function tanggal($date){
    date_default_timezone_set('Asia/Makassar');
    // array hari dan bulan
  //   $Hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
  //   $Bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
  $Bulan = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    
    // pemisahan tahun, bulan, hari, dan waktu
    $tahun = substr($date,0,4);
    $bulan = substr($date,5,2);
    $tgl = substr($date,8,2);
    $waktu = substr($date,11,5);
    $hari = date("w",strtotime($date));
  //   $result = $Hari[$hari].", ".$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu;
  $result =$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun;

    return $result;
  }
}
if (!function_exists('fulldate')) {
  function fulldate($date){
    date_default_timezone_set('Asia/Makassar');
    // array hari dan bulan
  //   $Hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
    $Bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
  // $Bulan = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    
    // pemisahan tahun, bulan, hari, dan waktu
    $tahun = substr($date,0,4);
    $bulan = substr($date,5,2);
    $tgl = substr($date,8,2);
    $waktu = substr($date,11,5);
    $hari = date("w",strtotime($date));
  //   $result = $Hari[$hari].", ".$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu;
  $result =$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun;

    return $result;
  }
}
if (!function_exists('fullyDate')) {
  function fullyDate($date){
    date_default_timezone_set('Asia/Makassar');
    // array hari dan bulan
    $Hari = array("Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu");
    $Bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
  // $Bulan = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
    
    // pemisahan tahun, bulan, hari, dan waktu
    $tahun = substr($date,0,4);
    $bulan = substr($date,5,2);
    $tgl = substr($date,8,2);
    $waktu = substr($date,11,5);
    $hari = date("w",strtotime($date));
  //   $result = $Hari[$hari].", ".$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun." ".$waktu;
  $result =$Hari[$hari].", ".$tgl." ".$Bulan[(int)$bulan-1]." ".$tahun;

    return $result;
  }
}
if (!function_exists('bulan')) {
  function bulan($bulan){
    date_default_timezone_set('Asia/Makassar');
  
    $Bulan = array("Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
  
    $result =$Bulan[(int)$bulan-1];

    return $result;
  }
}
function numberString($number){
  if($number > 0 AND $number < 10){
    $number = '0'.$number;
  }else{
    $number = $number;
  }
  return $number;
}
    function daysDifference($endDate, $beginDate)
    {

        $d1 = new DateTime( $endDate );
        $d2 = new DateTime( $beginDate );
        
        $diff = $d2->diff( $d1 );
        return $diff->days;
    }
    function formatSizeUnits($bytes)
    {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        }
        elseif ($bytes >= 1048576)
        {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        }
        elseif ($bytes >= 1024)
        {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        }
        elseif ($bytes > 1)
        {
            $bytes = $bytes . ' bytes';
        }
        elseif ($bytes == 1)
        {
            $bytes = $bytes . ' byte';
        }
        else
        {
            $bytes = '0 bytes';
        }

        return $bytes;
}
    function title(){
        return "CAMINO COFFEE AND EATERY";
    }
    function logo(){
        return base_url('assets/img/logo.png');
    }
    function initials($str) {
        $ret = '';
        foreach (explode(' ', $str) as $word)
            $ret .= strtoupper($word[0]);
        return $ret;
    }
    function pdf_create($html, $filename='', $paper, $orientation, $stream=TRUE)
    {
        require_once("dompdf/dompdf_config.inc.php");

        $dompdf = new DOMPDF();
        $dompdf->set_paper($paper,$orientation);
        $dompdf->load_html($html, 'UTF-8');
        $dompdf->render();
        
        if ($stream) {
            $dompdf->stream($filename.".pdf", array ('Attachment' => 0));
        } else {
            return $dompdf->output();
        }
    }
    function seo($s) {
      $c = array (' ');
      $d = array ('-','/','\\',',','.','#',':',';','\'','"','[',']','{','}',')','(','|','`','~','!','@','%','$','^','&','*','=','?','+','–');
      $s = str_replace($d, '', $s); // Hilangkan karakter yang telah disebutkan di array $d
      $s = strtolower(str_replace($c, '-', $s)); // Ganti spasi dengan tanda - dan ubah hurufnya menjadi kecil semua
      return $s;
  }
    function pushTelegram($message)
    {
        $chat_id = '@agendakutaselatan';
		$token = '5457890316:AAGHv_Tmr9UWH-Ke6QrHU4_PYYyvBxOmsb0';
    # keep away from banned due to max 20 mesg per mins    
    $timeexecute = rand(10,30);
    sleep($timeexecute);
    
    $strtofind = array("<br>", "<br/>", "<br />");
    $message = str_replace($strtofind, "\r\n", $message);
    
    # formating test to url mesg
    $data = array(
    
        'text' => $message,
        'chat_id' => $chat_id,
        'parse_mode'=>'markdown',
        
    );
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage?parse_mode=markdown&chat_id=".$chat_id;
    $url = $url . "&text=" . urlencode($message);
    $ch = curl_init();
    $optArray = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true
    );
    curl_setopt_array($ch, $optArray);
    $result = curl_exec($ch);
    curl_close($ch);   
     // file_get_contents("https://api.telegram.org/bot".$token."/sendMessage?" . http_build_query($data));
}
    function pushEmail($email,$title,$content){
        require_once ('vendor/phpmailer/phpmailer/src/Exception.php');
        require_once ('vendor/phpmailer/phpmailer/src/PHPMailer.php');
        require_once ('vendor/phpmailer/phpmailer/src/SMTP.php');
    
        $mail = new PHPMailer\PHPMailer\PHPMailer();
        $mail->isSMTP();
        $mail->Host     = 'ssl://smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'suryaadhi2323@gmail.com';
        $mail->Password = 'whza aypj abps cjwi';
        $mail->SMTPSecure = 'ssl';
        $mail->Port     = 465;

        $mail->setFrom('suryaadhi2323@gmail.com', 'SISTEM PENJADWALAN AGENDA');
        $mail->addReplyTo('suryaadhi2323@gmail.com', 'SISTEM PENJADWALAN AGENDA');
        // Add a recipient
        $mail->addAddress($email);

        // Add cc or bcc 
        $mail->addCC($email);
        $mail->addBCC($email);

        // Email subject
        $mail->Subject = $title;

        // Set email format to HTML
        $mail->isHTML(true);

        // Email body content
     
        $mail->Body = $content;
        $mail->AltBody = $title;
      


        // Send email
        if(!$mail->send()){
           return $mail->ErrorInfo;
        }else{
          return true;
         

        }
        // print_r($status);

    }
}