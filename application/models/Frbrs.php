<?php
class frbrs extends CI_model
	{
		var $table_catalogs = 'catalogs';
		
		function le($id)
			{
				$ln = array();
				return($ln);
			}	
		function catalogs_list()
			{
				$lt = array();
				array_push($lt,$this->frbrs->catalogs_view('0000001'));
				array_push($lt,$this->frbrs->catalogs_view('0000002'));
				array_push($lt,$this->frbrs->catalogs_view('0000003'));
				array_push($lt,$this->frbrs->catalogs_view('0000004'));
				return($lt);
			}
			
		function catalogs_select($wh='')
			{
				$sql = "select * from ".$this->table_catalogs;
			}
		function catalogs_view($id)
			{
				$sx = '<a href="'.base_url('index.php/main/catalogs/'.$id).'">';
				$sx .= '<img src="'.base_url('_acervo/thumb/'.$id.'.jpg').'" style="width: 90%;" class="btn-social">';
				$sx .= '</a>';
				return($sx);
			}
			
		function rdf_class_edit($class)
			{
				$sql = "select * from rdf_resource							
							INNER JOIN rdf_prefix ON rs_prefix = id_prefix
							LEFT JOIN auth_id_name on ia_propriety = id_rs
						where RS_GROUP = '$class'
						order by RS_GROUP";
				$rlt = $this->db->query($sql);
				$rlt = $rlt->result_array();

								
				$sx = '<table width="100%" class="table">';
				for ($r = 0;$r < count($rlt);$r++)
					{
						$line = $rlt[$r];
						$sx .= '<tr>';
						$sx .= '<td>';
						$sx .= '<tt>';
						$sx .= $line['prefix_ref'];
						$sx .= ':';
						$sx .= $line['rs_propriety'];
						$sx .= '</tt>';
						$sx .= '</td>';
						
						$sx .= '<td>';
						$sx .= '<tt>';
						$sx .= msg($line['rs_propriety']);
						$sx .= '</tt>';
						$sx .= '</td>';
						
						$sx .= '</tr>';
					}
				$sx .= '</table>';
				return($sx);
			}
	}
?>
