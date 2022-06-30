<?php
// Файлы phpmailer
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

include_once 'where.php';


// Переменные, которые отправляет пользователь
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$text = $_POST['text'];
$captcha = $_POST['captcha'];

$name = stripslashes($name);   $name = htmlspecialchars($name);
$phone = stripslashes($phone);   $phone = htmlspecialchars($phone);
$email = stripslashes($email);   $email = htmlspecialchars($email);
$text = stripslashes($text);   $text = htmlspecialchars($text);
$captcha = stripslashes($captcha);   $captcha = htmlspecialchars($captcha);

$url= $_SERVER['HTTP_HOST'];
$clientNotice="Клиент с ".$url;


// Формирование самого письма
$title = "Клиент отправил запрос";
$body = "
<h2>Клиент отправил запрос</h2>
<b> Имя: </b> $name<br>
<b> Телефон: </b> $phone <br>
<b> Почта: </b> $email<br><br>
<b> Сообщение: </b><br>$text
";

// Настройки PHPMailer
$mail = new PHPMailer\PHPMailer\PHPMailer();
try {
    $mail->isSMTP();
    $mail->CharSet = "UTF-8";
    $mail->SMTPAuth   = true;
//$mail->SMTPDebug = 2;
    $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};

    $mail->Host       =  $smtp_server;   //'smtp.spaceweb.ru';  SMTP ������� ����� �����
    $mail->Username   =  $smtp_emailfrom; //'2@webrel.ru';  ����� �� �����
    $mail->Password   =  $smtp_parol; //'smtp23sVmtp2';  ������ �� �����
    $mail->SMTPSecure =  $smtp_ssl ;    //'ssl';
    $mail->Port       =  $smtp_port;   //465;

    $mail->setFrom($smtp_emailfrom, $clientNotice); // Адрес самой почты и имя отправителя

    // Получатель письма

    $mail->addAddress($smtp_emailto); // Ещё один, если нужен



    // Прикрипление файлов к письму
if (!empty($file['name'][0])) {
    for ($ct = 0; $ct < count($file['tmp_name']); $ct++) {
        $uploadfile = tempnam(sys_get_temp_dir(), sha1($file['name'][$ct]));
        $filename = $file['name'][$ct];
        if (move_uploaded_file($file['tmp_name'][$ct], $uploadfile)) {
            $mail->addAttachment($uploadfile, $filename);
            $rfile[] = "Файл $filename прикреплён";
        } else {
            $rfile[] = "Не удалось прикрепить файл $filename";
        }
    }
}
// Отправка сообщения
$mail->isHTML(true);
$mail->Subject = $title;
$mail->Body = $body;

// Проверяем отправленность сообщения
$sendNotice="0";
$sendresult=0;


 if  ($captcha=="OWucmb42X12NG")
     {
     $sendresult= $mail->send() ;

      include_once 'log/log.php';


     $sendNotice="ВАШЕ ПИСЬМО ОТПРАВЛЕНО";
     }
 else  { $sendNotice="ОШИБКА В КАПТЧЕ"; }


if (   $sendresult==1 ) {$result = "success";}
else {$result = "error";}


} catch (Exception $e) {
    $result = "error";
    $status = "Сообщение не было отправлено. Причина ошибки: {$mail->ErrorInfo}";
}


// Отображение результата



echo <<<EOL
<!DOCTYPE html>
<html lang="ru">
<head>
        <meta charset="UTF-8">
        <title> ВАШЕ ПИСЬМО ОТПРАВЛЕНО </title>

</head>
<body>

     <div>       <h2>  $sendNotice  </h2>

        <h3><a href="/index.php">На сайт $url  </a></h3>
        </div>
<style>
html * { text-align:center }
div {width:50%; border:0px solid yellow; margin:100px auto}
div h2 { font-size:26px; line-heignt:30px }
div h3 { font-size:26px; line-heignt:30px }
 @media screen  and (orientation:portrait)
    {
div {width:90%; border:0px solid yellow; margin:50px auto}
div h2 { font-size:65px; line-heignt:75px }
div h3 { font-size:65px; line-heignt:75px }
 }

</style>
</body>
</html>
EOL;


echo '<p style="position:absolute; bottom:0; left:0">';
echo json_encode(["result" => $result, "resultfile" => $rfile, "status" => $status]);
echo '</p>';


