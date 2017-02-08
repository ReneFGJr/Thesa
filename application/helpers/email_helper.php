<?php
// This file is part of the ProEthos Software.
//
// Copyright 2013, PAHO. All rights reserved. You can redistribute it and/or modify
// ProEthos under the terms of the ProEthos License as published by PAHO, which
// restricts commercial use of the Software.
//
// ProEthos is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details.
//
// You should have received a copy of the ProEthos License along with the ProEthos
// Software. If not, see
// https://raw.githubusercontent.com/bireme/proethos/master/LICENSE.txt

/*
 * Sistema de e-mail
 */
$admin_nome = '';
$email_adm = '';

require_once ("libs/email/PHPMailerAutoload.php");

function enviaremail($para, $assunto, $texto, $de, $anexos = array()) {
	global $sem_copia;

	if (!isset($sem_copia)) { $sem_copia = 0;
	}
	if (!is_array($para)) {
		$para = array($para);
	}
	$CI = &get_instance();
	
	/* de */
	$sql = "select * from mensagem_own where id_m = " . round($de);
	$rlt = $CI -> db -> query($sql);
	$rlt = $rlt -> result_array();
	$line = $rlt[0];	

	$config = Array('protocol' => $line['smtp_protocol'], 'smtp_host' => $line['smtp_host'], 'smtp_port' => $line['smtp_port'], 'smtp_user' => $line['smtp_user'], 'smtp_pass' => $line['smtp_pass'], 'mailtype' => 'html', 'charset' => 'iso-8859-1', 'wordwrap' => TRUE);

	$CI -> load -> library('email', $config);
	$CI -> email -> subject($assunto);
	$CI -> email -> message($texto);

	for ($r = 0; $r < count($anexos); $r++) {
		$CI -> email -> attach($anexos[$r]);
	}



	/* Header & footer */
	$email_header = '';
	$email_footer = '';

	/***************************************************/
	if (count($rlt) == 1) {
		$line = $rlt[0];
		$e_mail = trim($line['m_email']);
		$e_nome = trim($line['m_descricao']);

		/***************** HEADER AND FOOTER */
		$email_header = $line['m_header'];
		$email_footer = $line['m_foot'];

		if (strlen($email_header) > 0) {
			$email_header = '<table width="550"><tr><td><img src="' . base_url($email_header) . '"></td><tr><tr><td><br><br>';
		}
		if (strlen($email_footer) > 0) {
			$email_footer = '</td></tr><tr><td><img src="' . base_url($email_footer) . '"></td></tr></table>';
		}

		$CI -> email -> from($e_mail, $e_nome);
		$CI -> email -> to($para[0]);
		$CI -> email -> subject($assunto);
		$CI -> email -> message($email_header . $texto . $email_footer);

		if ($sem_copia != 1) {
			array_push($para, trim($line['m_email']));
			//array_push($para, 'renefgj@gmail.com');
		}

		/* e-mail com copias */
		$bcc = array();
		for ($r = 1; $r < count($para); $r++) {
			array_push($bcc, $para[$r]);
		}

		if (count($bcc) > 0) {
			$CI -> email -> bcc($bcc);
		}

		$sx = '<div id="email_enviado">';
		$sx .= '<h3>' . msg('email_enviado') . '</h3>';
		for ($r = 0; $r < count($para); $r++) {
			$sx .= $para[$r];
			$sx .= '<br>';
		}
		$sx .= '<br>';
		$sx .= '</div>';
		$sx .= '<script>
				setTimeout(function() {	$(\'#email_enviado\').fadeOut(\'fast\');}, 3000);
				</script>
				';

		$CI -> email -> send();
		return ('ok');
	} else {
		echo('<font color="red">Proprietário do e-mail (' . $de . ') não configurado (veja mensagem_own)</font>');
		exit ;
	}
}

function sendmail($para, $titulo, $texto) {
	global $email_from, $email_from_name, $email_port, $email_smtp, $email_pass, $email_user, $email_auth, $email_debug, $email_replay, $email_sign;
	
	
	
	if (strlen($email_from) == 0) {
		echo '<H1>Erro #120#</h1>';
		echo '<PRE>';
		echo 'Parametros nao informados:
			/* Dados do enviador */
			$email_from = \'\';			/* e-mail do enviador / replay */
			$email_from_name = \'\';	/* Nome do enviador */
			
			/* Tipo de envio */
			$email_auth = \'\'; 			/* ou AUTH ou MAIL */
			
			/* Dados da conta do enviador - obrigatorio para AUTH */
			$email_smtp = \'\';			/* servidor de SMTP */
			$email_user = \'\';			/* usuario da conta do enviador */
			$email_pass = \'\';			/* senha da conta do enviador */	
			
			';
		echo '</pre>';
		exit ;
	}
	$sx = '';
	if ($email_debug == '1')
		{
			$sx = '<tt>'.date("d/m/Y H:i:s").' Debug Mode: '.$email_auth;
		}
	switch ($email_auth) {
		case 'OFF' :
			return(msg('offline_mode'));
			break;
					
		case 'AUTH' :
			$mail = new email;
			$mail -> titulo = $titulo;
			$mail -> texto = $texto . $email_sign;

			$mail -> email = $email_from;
			$mail -> email_replay = $email_replay;
			$mail -> email_name = ($email_from_name);

			$mail -> email_user = $email_user;
			$mail -> email_pass = $email_pass;
			$mail -> email_smtp = $email_smtp;
			$mail -> email_port = $email_port;

			$mail -> debug = round($email_debug);

			$mail -> to = $para;
			$erro = $mail -> method_2_mail();
			return($erro);
			break;

		default :
			$mail = new email;
			$mail -> titulo = $titulo;
			$mail -> texto = $texto . $email_sign;

			$mail -> email = $email_from;
			$mail -> email_replay = $email_replay;
			$mail -> email_name = ($email_from_name);

			$mail -> email_user = $email_user;
			$mail -> email_pass = $email_pass;
			$mail -> email_smtp = $email_smtp;
			$mail -> email_port = $email_port;

			$mail -> debug = round($email_debug);
			
			$mail -> to = $para;
			$mail -> method_1_mail();
			break;
	}
	return($sx);
}

function checaemail($chemail) {
	$result = count_chars($chemail, 0);
	if (($result[64] == 1) and ($result[32] == 0) and ($result[32] == 0) and ($result[13] == 0) and ($result[10] == 0)) {
		$xerr = True;

		if (strpos($chemail, '!')) { $xerr = False;
		}
		if (strpos($chemail, '@.')) { $xerr = False;
		}
	} else {$xerr = False;
	}

	if ($chemail[strlen($chemail) - 1] < 'a') { $xerr = false;
	}
	return ($xerr);
}

/* Classe do e-mail */

class email {
	var $titulo;
	var $texto;
	var $to = '';
	var $cc = array();
	var $cco = array();

	var $debug = 0;

	/* Dados do enviador */
	var $email = '';
	var $email_replay = '';
	var $email_name = '';

	/* Dados do enviador SMTP */
	var $email_user = '';
	var $email_pass = '';
	var $email_smtp = '';
	
	var $email_sign = '';

	function method_1_mail() {
		/* Recupera dados */
		$from = $this -> email;
		$from_name = $this -> email_name;
		$replay = $this -> email_replay;
		$email_to = $this -> to;
		$title = $this -> titulo;
		$body = $this -> texto;

		$headers = '';
		$headers .= "To: " . $e1 . " \n";
		$headers .= "From: " . $form_name . " <" . $form . "> \n";
		$headers .= "Mime-Version: 1.0 \n";
		//	$headers .= "Priority: Normal \n";
		//	$headers .= "Reply-To: " .$email_adm. " \n";
		//	$headers .= "Return-Path: ".$email_adm." \n";
		//	$headers .= "Subject: ".$subject." \n";
		//	$headers .= "X-Assp-Envelope-From:".$email_adm." \n";
		$headers .= 'Content-Type: text/html; charset="utf-8"' . " \n";

		$headers = '';
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "To: " . $e1 . " \n";
		$headers .= "Reply-To: " . $from . " \n";
		$headers .= "Content-type: text/html; charset=utf-8\n";

		$headers = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";
		$headers .= "From: " . $form_name . " <" . $from . "> \r\n";

		//	$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">'."\n".$body;
		mail($to, $subject, $body, $headers) or die("<font color=red >Erro de envio do e-mail para " . $email_to . '</font>');
	}

	function method_2_mail() {
		$message_sample = $this->texto;
		
		if (!($this->email_check($this -> to)))
			{
				return ("empty");
			}
		
		/* Recupera dados */
		$smtp = $this -> email_smtp;
		$user = $this -> email_user;
		$pass = $this -> email_pass;
		$port = $this -> email_port;
		$from = $this -> email;
		$from_name = $this -> email_name;
		$replay = $this -> email_replay;
		$email_to = $this -> to;
		$title = $this -> titulo;
		$body = $this -> texto . $this->email_sign;

		/* Iniciar objeto */
		$mail = new PHPMailer;
		$mail -> isSMTP();
		$mail -> CharSet = "utf-8";
		$mail -> SMTPDebug = 0;
		$mail -> Debugoutput = 'html';
		$mail -> Host = $smtp;
		$mail -> Port = $port;
		$mail -> SMTPAuth = true;
		$mail -> Username = $user;
		$mail -> Password = $pass;
		$mail -> setFrom($from, $from_name);
		
		/* From name */
		$mail -> FromName = $from_name;
		$mail -> From = $from;

		//Set an alternative reply-to address
		$mail -> addReplyTo($replay, htmlspecialchars($from_name));

		//Set who the message is to be sent to
		$mail -> addAddress($email_to, $email_to);

		//Set the subject line
		$mail -> Subject = $title;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		$mail -> msgHTML('$message_sample', dirname(__FILE__));
		//Replace the plain text body with one created manually
		$mail -> Body = $body;
		//$mail -> AltBody = $body; //
		//Attach an image file
		//$mail->addAttachment('images/phpmailer_mini.png');
		//send the message, check for errors
		if (!$mail -> send()) {
			return ("Mailer Error: " . $mail -> ErrorInfo);
		} else {
			return ("Message sent!");
		}
		exit;
	}

	/*
	 * e-mail check
	 */
	function email_check($chemail) {
		$result = count_chars($chemail, 0);
		if (($result[64] == 1) and ($result[32] == 0) and ($result[32] == 0) and ($result[13] == 0) and ($result[10] == 0)) {
			$xerr = True;

			if (strpos($chemail, '!')) { $xerr = False;
			}
			if (strpos($chemail, '@.')) { $xerr = False;
			}
		} else {$xerr = False;
		}

		if ($chemail[strlen($chemail) - 1] < 'a') { $xerr = false;
		}
		return ($xerr);
	}

	/*
	 * Format the e-mail
	 */
	function format_email($email, $name) {
		$name = trim($name);
		$email = trim($email);
		if (strlen($name) > 0) {
			$sx = $name . ' <' . $email . '>';
		} else {
			$sx = $email;
		}
		return ($sx);
	}
}
?>