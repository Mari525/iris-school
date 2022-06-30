 <?php


  //log
     $ip = $_SERVER['REMOTE_ADDR'];

     $date = date("h:i:s d.M.Y");
     $entry_line = "\r\n <br/> Date: $date    <br/> Name: $name  <br/> Phone: $phone  <br/> Email: $email  <br/> IP:   $ip <br/>   " ;
     $fp = fopen("log/log.html", "a");
     fputs($fp, $entry_line);
     fclose($fp);


?>
