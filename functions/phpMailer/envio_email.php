<?php error_reporting(E_ALL);



$mail = new PHPMailer();

try {
    //Server settings
    //$mail->SMTPDebug = 2;                                       // Enable verbose debug output
    // $mail->isSMTP();                                            // Set mailer to use SMTP
    // $mail->Host       = 'mail2.cloudari.com';  // Specify main and backup SMTP servers
    // $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
    // // $mail->Username   = 'pedrogil@soltury.com';                     // SMTP username
    // $mail->Password   = 'Pedrogil1?';                               // SMTP password
    // $mail->SMTPSecure = 'tls';                                  // Enable TLS encryption, `ssl` also accepted
    // $mail->Port       = 587;                                    // TCP port to connect to

    //Recipients
    $mail->setFrom('pedrogil@soltury.com' , 'soltury');
    $mail->addAddress('pedrogil@soltury.com');     // Add a recipient
    // $mail->addAddress('ellen@example.com');               // Name is optional
    // $mail->addReplyTo('info@example.com', 'Information');
    // $mail->addCC('cc@example.com');
    // $mail->addBCC('bcc@example.com');

    // Attachments
    // $mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
                                      // Set email format to HTML
    $mail->Subject = 'Here is the subjéct on incudes';
    $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
    //$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    if($mail->send()){

        echo 'Message has been sent';

    }else{
        echo 'Message has not been sent';

    }
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>