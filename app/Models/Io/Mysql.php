<?php

namespace App\Models\Io;

use CodeIgniter\Model;

class Mysql extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'mysqls';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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

    function index($d1,$d2,$d3)
        {
            $sx = h("MySQL");
            $sa = '';
            $sb = $this->menu();
            switch($d1)
                {
                    case 'database':
                        $sa = $this->database();
                        break;
                    case 'backup':
                        $sa = $this->database('B');
                        break;
                    case 'restore':
                        $sa = $this->database('R');
                        break;
                    default:
                        $sa ='';
                        break;
                }
            $sx = bs(bsc($sx,12).bsc($sa,8).bsc($sb,4));
            return $sx;
        }

    function menu()
        {
            $m = [];
            $m[PATH.'/admin/mysql/database'] = 'Database';
            $m[PATH . '/admin/mysql/backup'] = 'Backup Script';
            $m[PATH . '/admin/mysql/restore'] = 'Restore Script';
            return menu($m);
        }

    function database($tp='')
        {
            $sx = '';
            $sql = "SHOW DATABASES;";
            $dt = $this->db->query($sql);
            $dt = $dt->getResult();
            $scr = '';
            $scrR = 'echo "Preparando Restauração"'.cr();
            $scrR .= "Excluindo backup atual" . cr();
            $scrR .= "rm home/brapci/backup/sql/*.sql -R ".cr();

            foreach($dt as $id=>$line)
                {
                    if ($line->Database != 'performance_schema')
                    {
                    switch($tp)
                        {
                            case 'R':
                                $scr .= 'echo "Backup ' . $line->Database . '"' . cr();
                                $scr .= 'mysql ' . $line->Database . ' < /home/brapci/backup/sql/' . $line->Database . '.sql' . cr();
                                $sx .= 'create database ' . $line->Database . ';<br>';
                                break;
                            case 'B':
                                $scr .= 'echo ' . $line->Database . chr(13);
                                $scr .= 'mysqldump ' . $line->Database . ' > /home/brapci/backup/sql/' . $line->Database . '.sql'.chr(13);
                                break;
                            default:
                                $sx .= '<li>' . $line->Database . '</li>';
                                break;
                        }
                    }
                }

        if ($tp == 'R') {
            $sx .= '<pre>'.$scr.'</pre>';
        }

        if ($tp == 'B') {
            $file = '/home/brapci/backup/mysql_restore';
            $sx .= '<pre>';
            $sx .= 'pico /home/brapci/backup/mysql_backup'.cr();
            $sx .= 'chmod 700 /home/brapci/backup/mysql_backup' . cr();
            $sx .= 'echo "==Remover Arquivos==';
            $sx .= 'rm /home/brapci/backup/mysql_backup/sql/*' . cr();

            $sx .= '/home/brapci/backup/mysql_backup' . cr();


            $sx .= '</pre>';

            $sx .= '<pre>'.$scr.'</pre>';
        }


            return $sx;
        }
}
