<?php
/*
Ceres Control Panel

This is a control panel program for eAthena and other Athena SQL based servers
Copyright (C) 2005 by Beowulf and Dekamaster

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

To contact any of the authors about special permissions send
an e-mail to cerescp@gmail.com
*/

include_once 'config.php'; // loads config variables
include_once 'functions.php';
require_once 'PHPMailer/PHPMailerAutoload.php';

function confirm_account($username, $email) {
	global $CONFIG_smtp_server, $CONFIG_smtp_port, $CONFIG_smtp_username,
           $CONFIG_smtp_password, $CONFIG_smtp_mail, $CONFIG_name;

    $mensagem = "----------------------------\n";
    if (isset($username))
        $mensagem.= "Username: ".$username;
    $mensagem.= "\n----------------------------\n";

    $maildef = read_maildef("confirm_account");
    $maildef = str_ireplace("#account_info#",$mensagem,$maildef);
    $maildef = str_ireplace("#server_name#",$CONFIG_name,$maildef);
    $maildef = str_ireplace("#support_mail#",$CONFIG_smtp_mail,$maildef);
    $maildef = nl2br($maildef);

send_email($email, $CONFIG_name . ": Account Registration Confirmation", $maildef);

}

function deny_account($username, $email) {
	global $CONFIG_smtp_server, $CONFIG_smtp_port, $CONFIG_smtp_username,
           $CONFIG_smtp_password, $CONFIG_smtp_mail, $CONFIG_name;
           
    $mensagem = "----------------------------\n";
    if (isset($username))
        $mensagem.= "Username: ".$username;
    $mensagem.= "\n----------------------------\n";

    $maildef = read_maildef("deny_account");
    $maildef = str_ireplace("#account_info#",$mensagem,$maildef);
    $maildef = str_ireplace("#server_name#",$CONFIG_name,$maildef);
    $maildef = str_ireplace("#support_mail#",$CONFIG_smtp_mail,$maildef);
    $maildef = nl2br($maildef);

send_email($email, $CONFIG_name . ": Account Registration Denied", $maildef);

}

function send_activation($username, $email, $link) {
	global $CONFIG_smtp_server, $CONFIG_smtp_port, $CONFIG_smtp_username,
           $CONFIG_smtp_password, $CONFIG_smtp_mail, $CONFIG_name;

    $mensagem = "----------------------------\n";
    if (isset($username))
        $mensagem.= "Username: ".$username;
    $mensagem.= "\n----------------------------\n";

    $maildef = read_maildef("send_activation");
    $maildef = str_ireplace("#account_info#",$mensagem,$maildef);
    $maildef = str_ireplace("#activation_link#",$link,$maildef);
    $maildef = str_ireplace("#server_name#",$CONFIG_name,$maildef);
    $maildef = str_ireplace("#support_mail#",$CONFIG_smtp_mail,$maildef);
    $maildef = nl2br($maildef);

    send_email($email, $CONFIG_name . ": Game Account Activation", $maildef);

}

function recover_password($username, $password, $email) {
    global $CONFIG_smtp_server, $CONFIG_smtp_port, $CONFIG_smtp_username,
           $CONFIG_smtp_password, $CONFIG_smtp_mail, $CONFIG_name;

    $message = "----------------------------\n";
    $message.= "Account Name: ".$username;
    $message.= "\nPassword: ".$password;
    $message.= "\n----------------------------\n";

    $maildef = read_maildef("recover_password");
    $maildef = str_ireplace("#account_info#", $message, $maildef);
    $maildef = str_ireplace("#server_name#", $CONFIG_name, $maildef);
    $maildef = str_ireplace("#support_mail#", $CONFIG_smtp_mail, $maildef);
    $maildef = nl2br($maildef);
    
    return send_email($email, $CONFIG_name . ": Account Password Recovery", $maildef);
}

function send_email($receiver, $subject, $body) {
	global $CONFIG_smtp_server, $CONFIG_smtp_port, $CONFIG_smtp_username,
           $CONFIG_smtp_password, $CONFIG_smtp_mail, $CONFIG_name,
           $CONFIG_smtp_auth, $CONFIG_debug;

    $mail=new PHPMailer();

    $mail->isSMTP();
    if ($CONFIG_debug) {
        $mail->SMTPDebug = 3;
        //Ask for HTML-friendly debug output
        $mail->Debugoutput = 'html';
    }
    
    $mail->SMTPAuth = $CONFIG_smtp_auth;
    $mail->SMTPSecure = 'tls';

    $mail->Host       = $CONFIG_smtp_server;
    $mail->Port       = $CONFIG_smtp_port;

    $mail->Username   = $CONFIG_smtp_username;
    $mail->Password   = $CONFIG_smtp_password;

    //$mail->From       = $CONFIG_smtp_mail;
    //$mail->FromName   = $CONFIG_name;
    $mail->setFrom($CONFIG_smtp_mail, $CONFIG_name);
    $mail->Subject    = $subject;
    $mail->Body       = $body;

    $mail->WordWrap   = 50;

    $mail->addAddress($receiver, $receiver);
    $mail->addReplyTo($CONFIG_smtp_mail, $CONFIG_name);

    $mail->isHTML(true);

    if(!$mail->Send()) {
        return $mail->ErrorInfo;
    } else {
        return "Message has been sent";
    }
}

?>
