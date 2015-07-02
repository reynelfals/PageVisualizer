<?php

defined('EMAIL_INI_FILE') or define('EMAIL_INI_FILE', 'email.ini');
require_once 'Mail.php';
class EmailModel {
    const SENDER_KEY='sender';
    const HOST_KEY='host';
    const PORT_KEY='port';
    const SUBJECT_KEY='subject';
    const USERNAME_KEY='username';
    const PSSWD_KEY='password';
    
    private $email_param_array = array();
    public function __construct() {
        $this->email_param_array=  parse_ini_file(EMAIL_INI_FILE);
    }
    public static function validateMailAddr($suppAddr){
        $parts=explode('@',$suppAddr);
        if (count($parts)!=2) return FALSE;
        if (strlen($parts[0]>64)) return FALSE;
        if (strlen($parts[1]>255)) return FALSE;
        
        $atom='[[:alnum:]_!#$%&\'*+\/=?^`{|}~-]+';
        $dotatom='(\.'.$atom.')*';
        $address = '(^'.$atom.$dotatom.'$)';
        $char = '([^\\\\"])';
        $esc = '(\\\\[\\\\"])';
        $text = '('.$char.'|'.$esc.')+';
        $quoted = '(^"'.$text.'"$)';
        $local_part = '/'.$address.'|'.$quoted.'/';
        $local_match = preg_match($local_part, $parts[0]);
        if($local_match === FALSE || $local_match !=1)            return FALSE;
        
        $hostname = '([[:alnum:]]([-[:alnum:]]{0,62}[[:alnum:]])?)';
        $hostnames = '('.$hostname.'(\.'.$hostname.')*)';
        $top = '\.[[:alnum:]]{2,6}';
        $domain_part = '/^'.$hostnames.$top.'$/';
        $domain_match = preg_match($domain_part, $parts[1]);
        if($domain_match === FALSE || $domain_match !=1)            return FALSE;
        
        return TRUE;
    }
    public function sendMail($emailTo, $body) {
        error_reporting(0);
        $this->email_param_array['auth']=true;
        $mailer=Mail::factory('smtp',  $this->email_param_array);
        $headers=array();
        $headers['From']=  $this->email_param_array[EmailModel::SENDER_KEY];
        $headers['Subject']=  $this->email_param_array[EmailModel::SUBJECT_KEY];
        $headers['To']= $emailTo;
        $result=$mailer->send($emailTo,$headers,$body);
        $error='';
        if(PEAR::isError($result)){
            $error=htmlspecialchars($result->getMessage());
            
        }
        return $error;
        
    }
}
