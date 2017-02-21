<?php
require_once('cron.php');
require_once('Model/Lib/phpmailer/PHPMailerAutoload.php');
$conn_args = array(
    'host'=> '192.168.2.50',
    'port'=> '5672',
    'login'=>'oa',
    'password'=> '95105333',
    'vhost' =>'oa'
);
$exchange_name = 'oa';
$queue_name    = 'email';
$routing_key   = 'oa_email';
$conn = new AMQPConnection($conn_args);
if (!$conn->connect()) die("Cannot connect to the broker");
//设置queue名称，使用exchange，绑定routingkey
$channel = new AMQPChannel($conn);
$q = new AMQPQueue($channel);
$q->setName($queue_name);
$q->setFlags(AMQP_DURABLE | AMQP_AUTODELETE);
$count = $q->declare();
$q->bind($exchange_name, $routing_key);
//Create a new PHPMailer instance
$mail = new PHPMailer;
//Tell PHPMailer to use SMTP
$mail->isSMTP();
$mail->IsHTML(true);
$mail->SMTPOptions = array(
    'ssl' => array(
        'verify_peer' => false,
        'verify_peer_name' => false,
        'allow_self_signed' => true
    )
);
//Enable SMTP debugging
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
$mail->SMTPDebug = 0;
//Ask for HTML-friendly debug output
$mail->CharSet    = 'UTF-8';
$mail->Debugoutput = 'html';
//$mail->SMTPSecure = 'ssl';
//Set the hostname of the mail server
$mail->Host = "mail.job5156.com";
//Set the SMTP port number - likely to be 25, 465 or 587
$mail->Port = 25;
//Whether to use SMTP authentication
$mail->SMTPAuth = true;
//Username to use for SMTP authentication
$mail->Username = "oaadmin@chitone.com.cn";
//Password to use for SMTP authentication
$mail->Password = "oa123!@#_";
//Set who the message is to be sent from
$mail->setFrom("oaadmin@chitone.com.cn", 'OA');

for ($i=1;$i<=$count;$i++)
{
    $messages = $q->get(AMQP_AUTOACK);
    $body = json_decode($messages->getBody() , true);
    #var_dump($body);
    //$mail->From     = "1154505909@qq.com";
    //Set an alternative reply-to address
    $mail->addReplyTo($body['address']['email'], $body['address']['name']);
    //Set who the message is to be sent to
    $mail->addAddress($body['address']['email'], $body['address']['name']);
    //Set the subject line
    $mail->Subject = $body['title'];
    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->MsgHTML($body['body']);
    $mail->send();
    //Replace the plain text body with one created manually
    //$mail->AltBody = $body['body'];
    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');

    //send the message, check for errors
    // if (!$mail->send()) {
    //     echo "Mailer Error: " . $mail->ErrorInfo;
    // } else {
    //     echo "Message sent!";
    // }
    // break;
}
?>