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

function email($para,$assunto,$texto,$de=1)
{
    /* de */
    $sql = "select * from mensagem_own where id_m = " . round($de);
    $CI = &get_instance();
    $rlt = $CI -> db -> query($sql);
    $rlt = $rlt -> result_array();
    $line = $rlt[0];

    print_r($line);

    $CI->load->library('email');
    $config = array();
    $config['protocol'] = 'smtp';
    $config['smtp_host'] = $line['smtp_host'];
    $config['smtp_user'] = $line['smtp_user'];
    $config['smtp_pass'] = $line['smtp_pass'];
    $config['smtp_port'] = $line['smtp_port'];
    $config['validate']  = TRUE;
    $config['mailtype']  = 'html';
    $config['charset']   = 'utf-8';
    $config['newline']   = "\r\n";        
    $CI->email->initialize($config);
    $CI->email->set_newline("\r\n");

    $CI->email->from($line['m_email'], 'Meu E-mail');
    $CI->email->subject("Assunto do e-mail");
    $CI->email->reply_to($line['m_email']);
    $CI->email->to($para); 
    //$this->email->cc('email_copia@dominio.com');
    //$this->email->bcc('email_copia_oculta@dominio.com');
    $CI->email->message("Aqui vai a mensagem ao seu destinatário");
    $CI->email->send();
    print_r($CI->email);
}

function enviaremail($para, $assunto, $texto, $de=1, $anexos = array()) {
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
        $CI -> email -> mailtype = 'html';
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
        setTimeout(function() { $(\'#email_enviado\').fadeOut(\'fast\');}, 3000);
        </script>
        ';
        $proto = $CI->email->protocol;
        switch($proto)
        {
            case 'm':
            $to      = 'renefgj@gmail.com';
            $subject = 'Assunto sem caracteres especiais';
            $message = 'conteudo do email. 
            Atencao para codificacao do texto, clientes de email podem interpretar errado';

            $headers = 'From: Brapci.inf.br <brapcici@gmail.com> ' . "\r\n" .
            'Reply-To: brapcici@gmail.com' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();

            $real_sender = '-f brapcici@gmail.com';

            mail($to, $subject, $message, $headers, $real_sender);
            break;              
            default:
            return($CI -> email -> send());
            break;
        }
        return (1);
    } else {
        echo('<font color="red">Proprietário do e-mail (' . $de . ') não configurado (veja mensagem_own)</font>');
        exit ;
    }
}


function enviaremail_xx($para='') {

    $this->load->library('email');
    
    $config = Array('protocol' => 'smtp', 'smtp_host' => 'ssl://smtp.googlemail.com', 'smtp_port' => 465, 'smtp_user' => 'brapcici@gmail.com', 'smtp_pass' => '448545ct', 'mailtype' => 'html', 'charset' => 'iso-8859-1');

    $this -> load -> library('email', $config);
    $this -> email -> from('brapcici@gmail.com', 'Brapci2.0');
    $this -> email -> to('rene.gabriel@ufrgs.br');
    $this -> email -> subject('Brapci 2.0');
    $this -> email -> message("Mensagem");
    if ($this -> email -> send()) {
        $this -> load -> view('sucesso_envia_contato');
    } else {
        print_r($this -> email -> print_debugger());
    }
}
?>