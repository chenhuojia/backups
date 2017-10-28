<?php
namespace Home\Common\Controller;
use Think\Controller;
class CommonController extends Controller {
    
    public function _initialize(){
       self::public_assign();
    }

   
    public function public_assign(){    
        $data=D('Category')->getAllCategory();
        $banner=D('banner')->getIndex();
        $this->assign('nav',$data);
        $this->assign('banner',$banner);
        
    }

   protected function time_tran($the_time){
        $now_time = time();
        $dur = $now_time - $the_time;
        if($dur < 0){
            return $the_time;
        }else{
            if($dur < 60){
                return $dur.'秒前';
            }elseif($dur < 3600){
              return floor($dur/60).'分钟前';
            }elseif ($dur < 86400){
                return floor($dur/3600).'小时前';
            }elseif($dur < 259200){
                return floor($dur/86400).'天前';
            }else{
                return date('Y-m-d',$the_time);
            }             
        }         
   }
   
   public function sendEmailTmpl($user_email,$user_name,$title,$body,$html=''){
       require dirname(dirname(dirname(__FILE__))).'/Util/mailer/PHPMailerAutoload.php';       
       $mail = new \PHPMailer;
       //$mail->SMTPDebug = 3;                               // Enable verbose debug output       
       $mail->isSMTP();                                      // Set mailer to use SMTP
       $mail->Host = C('SEND_EMAIL_HOST');  // Specify main and backup SMTP servers
       $mail->SMTPAuth = true;                               // Enable SMTP authentication
       $mail->CharSet  = 'UTF-8';
       $mail->Username = C('SEND_EMAIL_USERNAME');                 // SMTP username
       $mail->Password = c('SEND_EMAIL_PASSWORD');                           // SMTP password
       //$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
       //$mail->Port = 587;                                    // TCP port to connect to
       $mail->setFrom(C('SEND_EMAIL_USERNAME'), '伙佳');
       $mail->addAddress($user_email, $user_name);     // Add a recipient
       //$mail->addAddress('ellen@example.com');               // Name is optional
       $mail->addReplyTo(C('SEND_EMAIL_REPLYTO'), '伙佳');
       //$mail->addCC('cc@example.com');
       //$mail->addBCC('bcc@example.com');
       //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
       //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
       $mail->isHTML(true);                                  // Set email format to HTML       
       $mail->Subject = $title;
       $mail->Body    = $body;
      // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
       if($html){
           $mail->msgHTML(file_get_contents($html));
       }
       //$mail->msgHTML($message);
       if(!$mail->send()) {
           return array('code'=>300,'msg'=>$mail->ErrorInfo);           
       } else {
           return array('code'=>200,'msg'=>'Message has been sent');
       }
   }
   
}