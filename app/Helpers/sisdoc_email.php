<?php
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
        $email = \Config\Services::email();
        $email->initialize($config);
    }
    /************************* Destinatarios */
    $emails = '';
    $email->setFrom(getenv('email.user_auth'), getenv('email.fromName'));

    if (is_array($to))
        {

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
