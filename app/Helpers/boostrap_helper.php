<?php
/**
* CodeIgniter Form Helpers
*
* @package     CodeIgniter
* @subpackage  BootStrap
* @category    Helpers
* @author      Rene F. Gabriel Junior <renefgj@gmail.com>
* @link        http://www.sisdoc.com.br/CodIgniter
* @version     v0.21.07.27
*/

function bsicone($type='')
    {
        switch($type)
            {
                case 'harversting':
                    $sx = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclude" viewBox="0 0 16 16">
                            <path d="M0 2a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2H2a2 2 0 0 1-2-2V2zm12 2H5a1 1 0 0 0-1 1v7h7a1 1 0 0 0 1-1V4z"/>
                            </svg>';
                break;

                case 'view':
                    $sx = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                        <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                        <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                        </svg>';
                break;

                case 'del':
                    $sx ='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                        </svg>';
                
                default:
                    $sx = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                           <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                           <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                           </svg>';
                break;
            }
        return $sx;
    }
function bssmall($t)
    {
        $sx = '<small>'.$t.'</small>';
        return $sx;
    }

function bscontainer($fluid=0)
    {
        $class = "container";
        if ($fluid == 1) { $class = "container-fluid"; }
        $sx = '<div class="'.$class.'">';
        return($sx);
    }

function bsrow()
    {
        $sx = '<div class="row">';
        return($sx);
    }       

function bscarousel($d)
    {
        $sx = '';
        $sx .= '<div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">'.cr();
        $sx .= '<div class="carousel-inner">'.cr();
        for ($r=0;$r < count($d);$r++)
            {
                $act = '';
                if ($r==0) { $act = 'active'; }
                $line = $d[$r];
                $img = $line['image'];
                $sx .= '
                <div class="carousel-item '.$act.'">
                    <img class="d-block w-100" src="'.$img.'" alt="Slide '.($r+1).'" style="height: 300px;">
                </div>'.cr();
            }
        $sx .= '</div>'.cr();
        $sx .= '
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
      </div>';

      $sx = '
      <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">'.cr();
        for ($r=0;$r < count($d);$r++)
            {
                $sx .= '<button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="'.$r.'" class="active" aria-current="true" aria-label="Slide '.($r+1).'"></button>'.cr();
            }
        $sx .= '</div>

        <div class="carousel-inner">'.cr();

        for ($r=0;$r < count($d);$r++)
            {
                $act = '';
                if ($r==0) { $act = 'active'; }
                $line = $d[$r];
                $img = $line['image'];
                $sx .= '
                <div class="carousel-item '.$act.'">
                    <img class="d-block w-100" src="'.$img.'" alt="Slide '.($r+1).'" style="height: 300px;">
                </div>'.cr();
            }        
        $sx .= '
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
        </div>      
      ';
      return $sx;
    }

function bs($t,$dt=array())
    {
        $fluid = 0;
        if (isset($dt['fluid'])) { $fluid = $dt['fluid']; }
        $html = brow($t);
        $html = bcontainer($html, $fluid);
        return($html);
    }

function bcontainer($t,$fluid=0)
    {
        $class = "container";
        if ($fluid == 1) { $class = "container-fluid"; }
        $sx = '<div class="'.$class.'">';
        $sx .= $t;
        $sx .= '</div>';
        return $sx;return($sx);
    }

function brow($t,$dt=array())
    {
        $opt = '';
        $sx = '<div class="row '.$opt.'">';
        $sx .= $t;
        $sx .= '</div>';
        return $sx;
    }

function bsc($t,$grid=12,$class='')
    {
        $sx = '';
        $sx .= bscol($grid,$class);
        $sx .= $t;
        $sx .= '</div>';
        return $sx;
    }

function bscard($title='Title',$desc='Desc')
    {
        $sx = '<div class="card mt-1">
        <!--
        <img class="card-img-top" src="..." alt="Card image cap">
        -->
        <div class="card-body">
            <h5 class="card-title">'.$title.'</h5>
            <p class="card-text">'.$desc.'</p>
        </div>
        </div>';  
        return $sx;
    }

function bsclose($n=0)
    {
        $sx = '';
        for ($r=0;$r < $n; $r++)
            {
                $sx .= bsdivclose().cr();
            }
        return($sx);
    }
function bsmessage($txt,$t=0)
    {
        $sx = '
            <div class="alert alert-primary" role="alert">
            '.$txt.'
            </div>';      
        $sx .= cr();
        return($sx);
    }


function bsdivclose()
    {
        return('</div>');
    }
function h($t='',$s=1,$class='')
    {
        $sx = '<h'.$s.' class="'.$class.'">'.$t.'</h'.$s.'>';
        return($sx);
    }  

function bscol($c,$class='')
    {
        switch($c)
            {

                case '1':
                    $sx = '';
                    $sx .= ' col-4';        /* < 756px  */
                    $sx .= ' col-sm-3';     /* > 576px  */
                    $sx .= ' col-md-3';     /* > 768px  */
                    $sx .= ' col-lg-1';     /* > 992px  */
                    $sx .= ' col-xl-1';     /* > 1200px */
                break;                 

                case '2':
                    $sx = '';
                    $sx .= ' col-12';        /* < 756px  */
                    $sx .= ' col-sm-12';     /* > 576px  */
                    $sx .= ' col-md-6';     /* > 768px  */
                    $sx .= ' col-lg-2';     /* > 992px  */
                    $sx .= ' col-xl-2';     /* > 1200px */
                break;   

                case '3':
                    $sx = 'col-md-3';
                    $sx .= ' col-3';
                    $sx .= ' col-sm-6';
                    $sx .= ' col-lg-3';
                    $sx .= ' col-xl-3';
                break; 

                case '4':
                    $sx = '';
                    $sx .= ' col-6';        /* < 756px  */
                    $sx .= ' col-sm-6';     /* > 576px  */
                    $sx .= ' col-md-4';     /* > 768px  */
                    $sx .= ' col-lg-4';     /* > 992px  */
                    $sx .= ' col-xl-4';     /* > 1200px */
                break;                  

                case '5':
                    $sx = 'col-md-12';
                    $sx .= ' col-12';
                    $sx .= ' col-sm-5';
                    $sx .= ' col-lg-5';
                    $sx .= ' col-xl-5';
                break;

                case '6':
                    $sx = 'col-md-6';
                    $sx .= ' col-6';
                    $sx .= ' col-sm-6';
                    $sx .= ' col-lg-6';
                    $sx .= ' col-xl-6';
                break; 

                case '7':
                    $sx = 'col-md-12';
                    $sx .= ' col-12';
                    $sx .= ' col-sm-7';
                    $sx .= ' col-lg-7';
                    $sx .= ' col-xl-7';
                break;

                case '8':
                    $sx = '';
                    $sx .= ' col-12';        /* < 756px  */
                    $sx .= ' col-sm-12';     /* > 576px  */
                    $sx .= ' col-md-8';     /* > 768px  */
                    $sx .= ' col-lg-8';     /* > 992px  */
                    $sx .= ' col-xl-8';     /* > 1200px */
                break; 

                case '9':
                    $sx = 'col-md-12';
                    $sx .= ' col-12';
                    $sx .= ' col-sm-9';
                    $sx .= ' col-lg-9';
                    $sx .= ' col-xl-9';
                break;                                                                          

                case '10':
                    $sx = 'col-md-12';
                    $sx .= ' col-12';
                    $sx .= ' col-sm-10';
                    $sx .= ' col-lg-10';
                    $sx .= ' col-xl-10';
                break;

                case '11':
                    $sx = 'col-md-12';
                    $sx .= ' col-12';
                    $sx .= ' col-sm-11';
                    $sx .= ' col-lg-11';
                    $sx .= ' col-xl-11';
                break;  

                case '12':
                    $sx = '';
                    $sx .= ' col-12';        /* < 756px  */
                    $sx .= ' col-sm-12';     /* > 576px  */
                    $sx .= ' col-md-12';     /* > 768px  */
                    $sx .= ' col-lg-12';     /* > 992px  */
                    $sx .= ' col-xl-12';     /* > 1200px */
                break;                 


                default:
                    $c = sonumero($c);
                    $sx = 'col-md-'.$c;
                    $sx .= ' col-'.$c;
                    $sx .= ' col-sm-'.$c;
                    $sx .= ' col-lg-'.$c;
                    $sx .= ' col-xl-'.$c;
                break;
            }
        return('<div class="'.$sx.' '.$class.'">');
    }
function bs_pages($ini,$stop,$link='')
    {
        $sx = '';
        $sx .= '<nav aria-label="Page navigation example">'.cr();
        $sx .= '<ul class="pagination">'.cr();
        for ($r=$ini;$r <= $stop;$r++)
            {
                $xlink = base_url($link.'/'.chr($r));
                $sx .= '<li class="page-item"><a class="page-link" href="'.$xlink.'">'.chr($r).'</a></li>'.cr();
            }
        $sx .= '</ul>';
        $sx .= '</nav>';
        return($sx);
    }
function bs_alert($type = '', $msg = '') {
    $ok = 0;
    switch($type) {
        case 'success' :
            $ok = 1;
            break;
        case 'secondary' :
            $ok = 1;
            break;
        case 'danger' :
            $ok = 1;
            break;
        case 'warning' :
            $ok = 1;
            break;
        case 'info' :
            $ok = 1;
            break;
        case 'light' :
            $ok = 1;
            break;
        case 'dark' :
            $ok = 1;
            break;
        default :
            $sx = 'TYPE: primary, secondary, success, danger, warning, info, light, dark';
    }
    if ($ok == 1) {
        $sx = '<br><div class="alert alert-' . $type . '" role="alert">
                ' . $msg . '
               </div>' . cr();
    }
    return($sx);
}    