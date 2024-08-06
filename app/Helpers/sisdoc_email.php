<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function email_smtp_test()
{
    $sx = '';

    $smtpHost = get('smtp_host');
    $smtpPort = get('smtp_port');
    $smtpUser = get('smtp_user');
    $smtpPass = get('smtp_pass');
    $fromEmail = get('from_email');
    $toEmail = get('to_email');

    $sx .= '
            <h2>Teste de Configuração SMTP</h2>
            <form action="" method="POST">
                <label for="smtp_host">Servidor SMTP:</label><br>
                <input type="text" class="form-control full border border-secondary big" id="smtp_host" value="'.$smtpHost. '" name="smtp_host" required><br>

                <label for="smtp_port">Porta SMTP:</label><br>
                <select id="smtp_port" class="form-control full border border-secondary" name="smtp_port" required>
                <option value="' . $smtpPort . '">' . $smtpPort . '</option>
                <option value="25">25</option>
                <option value="587">587</option>
                </select><br>

                <label for="smtp_user">Usuário SMTP:</label><br>
                <input type="text" class="form-control full border border-secondary" id="smtp_user"  value="' . $smtpUser . '" name="smtp_user" required><br>

                <label for="smtp_pass">Senha SMTP:</label><br>
                <input type="text" class="form-control full border border-secondary" id="smtp_pass"  value="' . $smtpPass . '" name="smtp_pass" required><br>

                <label for="from_email">Email Remetente:</label><br>
                <input type="email" class="form-control full border border-secondary" id="from_email"  value="' . $fromEmail . '" name="from_email" required><br>

                <label for="to_email">Email Destinatário (para teste):</label><br>
                <input type="email" class="form-control full border border-secondary" id="to_email"  value="' . $toEmail . '" name="to_email" required><br>

                <input type="submit" class="btn btn-secondary" value="Testar Configuração">
            </form>
            <hr>
        ';


    $email = \Config\Services::email();

    $config = [
        'protocol' => 'smtp',
        'SMTPHost' => $smtpHost,
        'SMTPPort' => $smtpPort,
        'SMTPUser' => $smtpUser,
        'SMTPPass' => $smtpPass,
        'mailType'  => 'html',
        'charset'   => 'utf-8',
        'wordWrap'  => true
    ];

    if ($smtpHost and $smtpPort and $fromEmail and $smtpUser) {

        $email->initialize($config);

        $email->setFrom($fromEmail, 'Teste SMTP');
        $email->setTo($toEmail);
        $email->setSubject('Teste de configuração SMTP');
        $email->setMessage('Este é um email de teste para verificar a configuração do servidor SMTP.');

        if ($email->send()) {
            $sx .= bsmessage('Email enviado com sucesso!', 1);
        } else {
            $sx .= bsmessage('Erro ao enviar o email:', 3);
            print_r($email->printDebugger(['headers']));
        }
    }
    $sx = bs(bsc($sx, 12));
    return $sx;
}

function sendmail($to = array(), $subject = '', $body = '', $attachs = array(), $images = array())
{
    return sendemail($to, $subject, $body, $attachs, $images);
}
function sendemail($to = array(), $subject = '', $body = '', $attachs = array(), $images = array())
{
    if (getenv("email.type") == 'smtp') {
        $email = \Config\Services::email();

        $config = array();
        $config['protocol'] = 'smtp';
        $config['wordWrap'] = true;
        $config['SMTPHost'] = getenv('email.stmp');
        $config['SMTPUser'] = getenv('email.user_auth');
        $config['SMTPPass'] = getenv('email.password');
        $config['SMTPPort'] = getenv('email.stmp_port');
        $cofngi['SMTPCrypto'] = '';
        $config['fromEmail'] = getenv('email.fromEmail');
        $config['fromName'] = getenv('email.fromName');

        $config['UseTLS'] = false;
        $config['FromLineOverride'] = true;
        $config["smtp_crypto"] = "ssl";

        $config['newline'] = chr(13) . chr(10);
        $config['mailType'] = 'html';

        $email->initialize($config);
    } else {
        $config['protocol'] = 'sendmail';
        $config['mailPath'] = '/usr/sbin/sendmail';
        $config['newline'] = "\r\n";

        $config['newline'] = chr(13) . chr(10);
        $config['mailType'] = 'html';

        $email = \Config\Services::email();
        $email->initialize($config);
    }
    /************************* Destinatarios */
    $emails = '';
    $email->setFrom(getenv('email.user_auth'), getenv('email.fromName'));

    $filename = 'img/email/bg-email-hL3a.jpg';
    if (file_exists($filename)) {
        $email->attach($filename);
        $cid = $email->setAttachmentCID($filename);
        $body = troca($body, '$image1', $cid);
    } else {
        echo "Logo not found";
    }

    if (is_array($to)) {
    } else {
        $email->setTo($to);
        $emails = $to;
    }

    //$email->setCC('another@another-example.com');
    //$email->setBCC('them@their-example.com');

    $email->setSubject($subject);
    $email->setMessage($body);

    $sx = '';
    $sx .= 'Enviando para: ' . $emails;
    $sx .= '<br />';

    $email->send(false);
    $sx .= $email->printDebugger(['headers']);
    return $sx;
}
