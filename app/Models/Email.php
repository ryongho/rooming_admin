<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;
use App\Models\EmailLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


class Email extends Model
{
    public static function log_regist($result){    
        
        EmailLog::insert([
            'email'=> $result->email,
            'state'=> $result->state ,
            'title'=> $result->title ,
            'content'=> $result->content ,
            'send_date'=> $result->send_date ,
            'created_at'=> Carbon::now(),
        ]);
    }

    public static function send($email)
    {  
    
        $title = $email->title; 
        $subject = "=?EUC-KR?B?".base64_encode(iconv("UTF-8","EUC-KR",$title))."?=";
        
        $content = $email->content;
        
        $mail = new PHPMailer(true);         
        $return = new \stdClass;
        $result =  true;
        
        try {
            //Server settings
            $mail->isSMTP();                                            // Send using SMTP
            $mail->Host       = env('MAIL_HOST');                    // Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
            $mail->Username   = env('MAIL_USERNAME');                     // SMTP username
            $mail->Password   = env('MAIL_PASSWORD');                               // SMTP password
            $mail->CharSet = 'utf-8'; 
            $mail->Encoding = "base64";
            $mail->SMTPSecure = 'ssl';          
            $mail->Port       = env('MAIL_PORT');                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
            

            //Recipients
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), '루밍');

            $mail->addAddress($email->email);     // Add a recipient
            
            // Content
            $mail->isHTML(true);                                  // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $content;

            $email->state = $mail->send();
            $email->send_date = Carbon::now();

            //echo 'Message has been sent';
            //$result =  true;
        } catch (Exception $e) {
            //echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            $email->state = false;
        }
        

        Email::log_regist($email);
        
    
    }

    
}
