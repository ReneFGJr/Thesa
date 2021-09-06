<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Form Helpers
 *
 * @package     CodeIgniter
 * @subpackage  Helpers
 * @category    Helpers
 * @author      Rene F. Gabriel Junior <renefgj@gmail.com>
 * @link        http://www.sisdoc.com.br/CodIgniter
 * @version     v0.20.05.04
 */

 class language
    {
        function __construct()
        {
            
        }

        function set_session($language)
            {
                $_SESSION['lang'] = $language;
                return(1);
            }

        function language()
            {
                $lang = get("lang");
                if (strlen($lang) != '')
                    {
                        $this->set_session($lang);
                    }
                if (!isset($_SESSION['lang']))
                    {
                        $lang = $this->browser_detect();
                        $this->set_session($lang);
                    } else {
                        $lang = $_SESSION['lang'];
                    }
                return($lang);
            }

        function menu_language()
            {
                $language = $this->language();
                $lg = array('pt_br'=>'Português','es'=>'Español','en'=>'English');
                $sx = '';
                $sx .= '<span class="navbar-text"></span>';
                if (isset($_SERVER['ORIG_SCRIPT_NAME']))
                    {
                        $l1 = $_SERVER['ORIG_SCRIPT_NAME'].'/';
                        $lk = substr($l1,strpos($l1,PATH)+strlen(PATH),strlen($l1));                
                    } else {
                        if (isset($_SERVER['DOCUMENT_URI']))
                        {
                            $l1 = $_SERVER['DOCUMENT_URI'].'/';
                            $lk = substr($l1,strpos($l1,PATH)+strlen(PATH),strlen($l1));                
                        } else {
                            $lk = '';
                        }
                    }
                $url = base_url(PATH.$lk.'?lang=');
                
                $sx = '<li class="nav-item dropdown ml-auto">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    '.$lg[$language].'
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                  <a class="dropdown-item" href="'.$url.'pt_br'.'">Português</a>
                  <a class="dropdown-item" href="'.$url.'en'.'">English</a>
                  <a class="dropdown-item" href="'.$url.'es'.'">Español</a>
                </div>
              </li>';
                return($sx);
            }

        function browser_detect()
            {
                if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
                    {
                        $lang = $_SERVER['HTTP_ACCEPT_LANGUAGE'];
                        $lang = substr($lang,0,strpos($lang,','));
                    } else {
                        $lang = 'pt_br';
                    }
                switch($lang)
                    {
                        case 'pt-BR':
                            $lang='pt_br';
                            break;
                        case 'en-US':
                            $lang='en';
                            break;
                        case 'en':
                            $lang='en';
                            break;
                        case 'es':
                            $lang='es';
                            break;
                        default:
                            $lang = 'pt_br';
                            break;
                    }
                return($lang);
            }
    }

if (!function_exists(('msg')))
	{
		function msg($t)
			{
				$CI = &get_instance();
				if (strlen($CI->lang->line($t)) > 0)
					{
						return($CI->lang->line($t));
					} else {
						return($t);
					}
			}
	}