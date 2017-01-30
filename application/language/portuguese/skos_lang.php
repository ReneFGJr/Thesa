<?php
// This file is part of the Brapci Software. 
// 
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software. 
// 
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt 
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
 */
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
$lang['status_1'] = 'Em edição';
$lang['status_2'] = 'Publicado';
$lang['fullName'] = 'Nome completo';
$lang['Welcome_signup'] = 'Cadastro de usuário';
$lang['Username'] = 'Nome do usuário';
$lang['Sign Up Send'] = 'Solicitar cadastro';
$lang['Voltar'] = 'Nome do usuário';

$lang['all'] = 'todas';
$lang['Welcome'] = 'Bem vindo a';
$lang['edit'] = 'editar';
$lang['last_update'] = 'atualização';


/* Login */
$lang['Sign In'] = 'Entrar';
$lang['Sign Up'] = 'Quero me cadastrar';
$lang['logout'] = 'Sair';

$lang['thesaurus_name'] = 'Nome do tesauro';
$lang['thesaurus_description'] = 'Descrição';
$lang['save'] = 'salvar';



/* Propriedades */
$lang['skos:prefLabel'] = 'Descritor preferencial';
$lang['skos:altLabel'] = 'Variações Terminológica / Termo alternativo';
$lang['skos:hiddenLabel'] = 'Variações léxicas (erros grafia)';
$lang['skos:narrower'] = 'Descritor Específico:';
$lang['ex:coordinationOf'] = 'Coordenação';
$lang['skosxl:isSingular'] = 'singular de';
$lang['skosxl:isPlural'] = 'plural de';
$lang['skosxl:isMasculine'] = 'masculino de';
$lang['skosxl:isFeminine'] = 'feminino de';
$lang['skosxl:literalForm'] = 'extenso de ';
$lang['skosxl:abbreviation_of'] = 'abreviatura de';


$lang['skos:definition'] = 'Definição do descritor';
$lang['skos:example'] = 'Nota de exemplo';
$lang['skos:scopeNote'] = 'Nota de escopo / nota de aplicação';
$lang['skos:changeNote'] = 'Nota de alteração';

$lang['description'] = 'Descrição';

$lang['concept_DEF'] = 'Notas';
$lang['concept_NT'] = 'Nota de escopo';
$lang['concept_ALT'] = 'Termo Equivalente (usado por)';
$lang['concept_HID'] = 'Termo Equivalente (oculto)';
$lang['concept_BT'] = 'Termo Geral';
$lang['concept_NR'] = 'Termo(s) específico(s)';
$lang['concept_TR'] = 'Termo(s) relacionado(s)';
$lang['concept_IMG'] = 'Iconografia';
$lang['concept'] = 'Conceito';
$lang['pref_term'] = 'Descritor';
$lang['Concept_add'] = 'Adicionar Descritores/Termos';
$lang['Terms'] = 'Descritores';
$lang['concept_FLE'] = 'Genero / Flexão';
$lang['select_a_concept'] = 'Selecione um conceito';
$lang['save'] = 'Gravar';
$lang['my_thesauros'] = 'Thesaurus';
$lang['status'] = 'Situação';
$lang['date'] = 'Data';
$lang['type'] = 'Tipo';
$lang['term'] = 'descritor';
$lang['terms'] = 'descritores';
$lang['concepts'] = 'conceitos';
$lang['Conceitual map'] = 'Mapa conceitual';
$lang['Report Thesaurus'] = 'Thesauro Impresso';

$lang['thesaurus_open'] = "TESAUROS ABERTOS";
$lang['thesaurus_myth'] = "MEUS TESAUROS";
$lang['presentation'] = "APRESENTAÇÃO";
$lang['about'] = "SOBRE";
$lang['download'] = "DOWNLOAD";
$lang['contact'] = "CONTATO";
$lang['sign_in'] = "ACESSAR";

?>
