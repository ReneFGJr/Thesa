<?php
class frbrs extends CI_model
	{
		var $table_works = 'works';
		
		function le($id)
			{
				$ln = array();
				return($ln);
			}	
		function works_list()
			{
				$lt = array();
				array_push($lt,$this->frbrs->works_view('0000001'));
				array_push($lt,$this->frbrs->works_view('0000002'));
				array_push($lt,$this->frbrs->works_view('0000003'));
				array_push($lt,$this->frbrs->works_view('0000004'));
				return($lt);
			}
			
		function works_select($wh='')
			{
				$sql = "select * from ".$this->table_works;
			}
		function works_view($id)
			{
				$sx = '<a href="'.base_url('index.php/main/works/'.$id).'">';
				$sx .= '<img src="'.base_url('_acervo/thumb/'.$id.'.jpg').'" style="width: 90%;" class="btn-social">';
				$sx .= '</a>';
				return($sx);
			}
	}
?>
