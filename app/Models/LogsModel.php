<?php

namespace App\Models;

use CodeIgniter\Model;

class LogsModel extends Model
{
    protected $table = 'logs';
    protected $primaryKey = 'id_log';
    protected $allowedFields = [
        'log_user',
        'log_thesa',
        'log_concept',
        'log_action',
        'log_description',
        'created_at'
    ];
    protected $useTimestamps = false;

    protected $returnType = 'array';

    function getLogs($id)
    {
        $dt = $this
        ->join('users','users.id_us=logs.log_user','left')
            ->where('log_concept',$id)
            ->orderBy('created_at', 'DESC')->findAll();
        return $dt;
    }

    function registerLogs($th,$concept,$action, $description)
    {
        $data = [
            'log_user'    => session()->get('user_id') ?? 0,
            'log_thesa'   => $th,
            'log_concept' => $concept,
            'log_action'  => $action,
            'log_description' => $description,
            'created_at'  => date('Y-m-d H:i:s')
        ];
        $this->insert($data);
    }
}
