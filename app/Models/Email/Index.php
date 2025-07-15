<?php

namespace App\Models\Email;

use CodeIgniter\Model;
use CodeIgniter\Email\Email;

class Index extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'thesa_references';
    protected $primaryKey       = 'id_ref ';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_ref', 'ref_th', 'ref_cite',
        'ref_content', 'ref_status','',
        '','',''
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    function email_test($email)
        {
            if ($email == '') {
                $email = 'renefgj@gmail.com';
            }
            $subject = "Teste de email - ".date("Y-m-d H:i:s");
            $toName = 'Usuário teste';

            // Corpo do email com a imagem inline
            $message = "
                <h2>Olá, {$toName}!</h2>
                <p>Seja muito bem-vindo ao nosso sistema. Estamos felizes por tê-lo conosco.</p>
                <p>Qualquer dúvida, entre em contato com nossa equipe de suporte.</p>
                <br>
                <p>Atenciosamente,<br>Equipe do Sistema</p>
        ";

            $dt = $this->sendEmail($email,$subject, $message);
            return $dt;
        }



    function sendEmail($toEmail, $subject, $txt)
    {
        $email = \Config\Services::email();

        // Força as configurações via env
        $config = [
            'protocol'  => getenv('email.protocol'),
            'SMTPHost'  => getenv('email.SMTPHost'),
            'SMTPUser'  => getenv('email.SMTPUser'),
            'SMTPPass'  => getenv('email.SMTPPass'),
            'SMTPPort'  => getenv('email.SMTPPort'),
            'SMTPCrypto' => getenv('email.SMTPCrypto'),
            'mailType'  => getenv('email.mailType'),
            'charset'   => 'utf-8',
            'newline'   => "\r\n",
            'wordWrap'  => true,
        ];

        $email->initialize($config);

        // Carrega configurações do .env
        $email->setFrom(getenv('email.fromEmail'), getenv('email.fromName'));
        $email->setTo($toEmail);
        $email->setCC('renefgj@gmail.com');
        $email->setSubject($subject);

        // Caminho da imagem que será embutida
        $pathToImage = FCPATH . 'img/logo/thesa_email.png';

        // Anexa a imagem como inline e pega o CID gerado
        $email->attach($pathToImage);
        if (file_exists($pathToImage)) {
            $email->attach($pathToImage);
            $cid = $email->setAttachmentCID($pathToImage);
        } else {
            $cid = ''; // ou definir uma imagem padrão
        }


        $message = '<table width="600px" style="border: 1px solid #ccc; border-radius: 5px; padding: 10px; font-family: Arial, sans-serif; font-size: 14px; color: #333;">';
        $message .= '<tr valign="top">';
        $message .= '<td style="text-align: center; padding:15px;" width="33%">';
        $message .= '<img src="cid:' . $cid . '" alt="Cabeçalho" style="max-width: 200px;">';
        $message .= '</td>';
        $message .= '<td width="66%">';
        $message .= $txt;
        $message .= '</td>';
        $message .= '</tr>';
        $message .= '</table>';

        $email->setMessage($message);

        try {
            if ($email->send()) {
                return ['status' => '200', 'message' => 'Email enviado com sucesso!'];
            } else {
                return [
                    'status' => '500',
                    'error' => $email->printDebugger(['headers']),
                    'config' => $config,
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => '500',
                'error' => $e->getMessage(),
                'config' => $config,
            ];
        }
    }
}
