<?php
class _install extends CI_model
    {
        function check() {
            $file = 'instaled.xxx';
            if (!file_exists($file))
            {
                echo 'Instaling... wait';
                $this->install();
                //file_put_contents($file,date("Y-m-d H:i:s"));
            }
        }
        function install()
            {
                $schema = $this->db->database;
                $dir = '_documment/_sql_db/';
                $f = scandir($dir);
                for ($r=0;$r < count($f);$r++)
                    {
                        if (strpos($f[$r],'.sql'))
                            {
                                $table = $f[$r];
                                $table = substr($table,0,strlen($table)-4);
                                if ($this->tableExiste($table,$schema) == 0)
                                {
                                    echo '<br>Installing '.$table;
                                    $sql = file_get_contents($dir.$table.'.sql');
                                    $this->db->query($sql);

                                    $fd = $dir.$table.'.rg';
                                    if (file_exists($fd))
                                        {
                                            $sql = file_get_contents($dir.$table.'.rg');
                                            $this->db->query($sql);        
                                            echo '..dados';
                                        }
                                }
                            }
                    }
                return('');
            }

        function tableExiste($t,$s='')
            {
                $sql = "
                SELECT COUNT(*) as total
                FROM information_schema.tables 
                WHERE table_schema = '$s' 
                AND table_name = '$t';
                ";

                $rlt = $this->db->query($sql);
                $rlt = $rlt->result_array();

                if ($rlt[0]['total'] > 0)
                    {
                        return(1);
                    } else {
                        return(0);
                    }
            }
    }

