<?php
function show_array($ar)
    {
        $sx = '<table class="table">';
        $sx .= '<tr>';
        $sx .= '<th>'.lang('ai.ID').'</th>';
        $sx .= '<th>'.lang('ai.HEAD').'</th>';
        $sx .= '</tr>';
        foreach ($ar as $k=>$v)
            {
                $sx .= '<tr>';
                $sx .= '<td>';
                $sx .= $k;
                $sx .= '</td>';
                $sx .= '<td>';
                $sx .= $v;
                $sx .= '</td>';
                $sx .= '</tr>';

            }
        $sx .= '</table>';
        return $sx;
    }
function strzero($id,$z)
    {
        $txt = str_pad($id,$z,'0',STR_PAD_LEFT);
        return $txt;
    }

function version()
    {
        $v = 'v0.'.date("y.m.d");
        return ($v);
    }
  function tableview($th,$dt=array())
        {
            $url = $th->path;

            /********** Campos do formulário */
            $fl = $th->allowedFields;
            if (isset($th->viewFields))
                {
                    $fld = implode(",", $th->viewFields);
                    $th->select($fld);
                    $fl = $th->viewFields;
                }
            
            if (isset($_POST['action']))
                {
                    $search = $_POST["search"];
                    $search_field = $_POST["search_field"];
                    $th->like($fl[1],$search);
                    $_SESSION['srch_'] = $search;
                    $_SESSION['srch_tp'] = $search_field;
                } else {
                    //
                    $search = '';
                    $search_field = 0;
                    if (isset($_SESSION['srch_']))
                        {
                            $search = $_SESSION['srch_'];
                            $search_field = $_SESSION['srch_tp'];        
                        }
                    if (strlen($search) > 0)
                        {
                            $th->like($fl[$search_field],$search);
                        }
                }            
            if ($fl[$search_field]==0) { $search_field = 1; }
            $th->orderBy($fl[$search_field]);
            

            $v = $th->paginate(15);
            $p = $th->pager;

            /**************************************************************** TABLE NAME */
            $sx = bsc('<h1>'.lang($th->table).'</h1>',12);
    
            $st = '<table width="100%" class="table">';
            $st .= '<tr><td>';
            $st .= form_open();
            $st .= '</td><td>';
            $st .= '<select name="search_field" class="form-control">'.cr();
            for ($r=1;$r < count($fl);$r++)
                {
                    $sel = '';
                    if ($r==$search_field) { $sel = 'selected'; }
                    $st .= '<option value="'.$r.'" '.$sel.'>'.msg($fl[$r]).'</option>'.cr();
                }
            $st .= '</select>'.cr();
            $st .= '</td><td>';
            $st .= '<input type="text" class="form-control" name="search" value="'.$search.'">';
            $st .= '</td><td>';
            $st .= '<input type="submit" class="btn btn-primary" name="action" value="'.lang('sisdoc.filter').'">';
            $st .= form_close();
            $st .= '</td><td align="right">';
            $st .=  $th->pager->links();
            $st .= '</td><td align="right">';
            $st .= $th->pager->GetTotal();
            $st .= '/'.$th->countAllResults();
            $st .= '/'.$th->pager->getPageCount();    
            $st .= '</td>';

            /*********** NEW */
            $st .= '<td align="right">';
            $st .= anchor($url.'/edit/',lang('sisdoc.new'),'class="btn btn-primary"');
            $st .= '</td></tr>';
            $st .= '</table>';

            $sx .= bs($st,12);

            $sx .= '<table class="table sisdoc_table">';
    
            /* Header */
            $heads = $th->allowedFields;
            $sx .= '<tr>';
            $sx .= '<th class="sisdoc_th">#</th>';
            for($h=1;$h < count($heads);$h++)
                {
                    if (strpos($fl[0],'#'))
                    {                    
                        $sx .= '<th class="sisdoc_th">'.lang($heads[$h]).'</th>';
                    }
                }            
            $sx .= '</tr>'.cr();
    
            /* Data */
            for ($r=0;$r < count($v);$r++)
                {
                    $line = $v[$r];
                    $sx .= '<tr class="sisdoc_tr">';
                    foreach($fl as $field)
                        {                            
                            $vlr = $line[$field];
                            if (strlen($vlr) == 0) { $vlr = ' '; }
                            $sx .= '<td class="sisdoc_td">'.anchor(($url.'/viewid/'.$line[$fl[0]]),$vlr).'</td>';
                        }   
                    /* Botoes */
                    $sx .= '<td><nobr>';
                    $sx .= btn_edit($url.'/edit/'.$line[$fl[0]]);
                    $sx .= '&nbsp;';
                    $sx .= btn_trash($url.'/delete/'.$line[$fl[0]]);
                    $sx .= '</nobr>';
                    $sx .= '</td>';

                    $sx .= '</tr>'.cr();
                }
            $sx .= '</table>';
            $sx .=  $th->pager->links();
            $sx .= bsdivclose();
            $sx .= bsdivclose();
            $sx .= bsdivclose();
            return($sx);    
        }  
function user_id()
{
	if (isset($_SESSION['id'])) 
	{
		$user = $_SESSION["id"];
		if (strlen($user) > 0) 
			{
			return ($user);
			}
	}
	return (0);
}