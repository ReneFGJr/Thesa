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

$lang['change_old'] = 'termo anterior';
$lang['has_prefTerm'] = 'como preferencial';
$lang['change'] = 'alterado';
$lang['Action'] = 'Ação';
$lang['Descript'] = 'Descrição';
$lang['User'] = 'Usuário';
$lang['Term_create_concept'] = 'Criar conceito';
$lang['concept_create'] = 'Conceito criado com o termo ';
$lang['hidden_log'] = 'Ocultar registro de eventos';
$lang['view_log'] = 'Mostrar registro de eventos';
$lang['to_invite'] = 'incorporar';
$lang['email_invalid'] = 'e-mail inválido';
$lang['email_not_exist'] = 'e-mail não cadastrado';
$lang['collaborator_insered'] = 'colaborador vinculado com sucesso!';
$lang['collaborators'] = 'colaboradores';
$lang['collaborator'] = 'colaborador';
$lang['not_record'] = 'sem registro';	
$lang['has_send_email_to'] = 'Verifique seu e-mail, vou enviado uma confirmação para o endereço ';
$lang['no_match_password'] = 'Senhas não conferem';
$lang['password_more_shot'] = 'Senha muito curta';
$lang['password_is_requered'] = 'Senha é obrigatória';
$lang['change_password_successful'] = 'Senha alterada com sucesso';
$lang['new_thesaurus'] = 'Criar novo vocabulário controlado / tesauros';
$lang['status_1'] = 'Acesso restrito / em edição';
$lang['status_2'] = 'Acesso público';
$lang['fullName'] = 'Nome completo';
$lang['Welcome_signup'] = 'Cadastro de usuário';
$lang['Username'] = 'Usuário (email)';
$lang['Sign Up Send'] = 'Solicitar cadastro';
$lang['Voltar'] = 'voltar';
$lang['concept_add'] = 'Adicionar Termos';
$lang['all'] = 'todas';
$lang['Welcome'] = 'Bem vindo a';
$lang['edit'] = 'editar';
$lang['last_update'] = 'atualização';
$lang['glossario'] = 'Glossário';
$lang['delete_term_confirm'] = 'Excluir term do tesauro?';
$lang['success_action'] = 'Ação realizada com sucesso';
$lang['item_already_deleted'] = 'Term já estava excluído';
$lang['concept_not_found'] = 'Conceito não localizado';
$lang['copy_to_clipboard'] = 'Copiar para clipboard';
$lang['rdf_link'] = 'Arquivo RDF';


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
$lang['skosxl:acronym'] = 'sigla';


$lang['skos:definition'] = 'Definição do conceito';
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
$lang['my_thesauros'] = 'Meus tesauros';
$lang['status'] = 'Situação';
$lang['date'] = 'Data';
$lang['type'] = 'Tipo';
$lang['term'] = 'descritor';
$lang['terms'] = 'descritores';
$lang['concepts'] = 'conceitos';
$lang['Conceitual map'] = 'Mapa conceitual';
$lang['Report Thesaurus'] = 'Thesauro Impresso';

$lang['skosxl:code'] = 'notação (código)';

$lang['thesaurus_open'] = "TESAUROS ABERTOS";
$lang['thesaurus_myth'] = "MEUS TESAUROS";
$lang['presentation'] = "APRESENTAÇÃO";
$lang['about'] = "SOBRE";
$lang['download'] = "DOWNLOAD";
$lang['contact'] = "CONTATO";
$lang['sign_in'] = "ACESSAR";

?>
