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

/* Cited */
$lang['bt_thesauros'] = 'tesauros';
$lang['how_to_cite'] = 'Como citar este trabalho';
$lang['mes_01a'] = 'Jan.';
$lang['mes_02a'] = 'Fev.';
$lang['mes_03a'] = 'Mar.';
$lang['mes_04a'] = 'Maio';
$lang['mes_05a'] = 'Jun.';
$lang['mes_06a'] = 'Jul';
$lang['mes_08a'] = 'Ago.';
$lang['mes_09a'] = 'Set.';
$lang['mes_10a'] = 'Out.';
$lang['mes_11a'] = 'Nov.';
$lang['mes_12a'] = 'Dez.';
/* CAB */
$lang['perfil_coordenador'] = 'Coordenador';
$lang['search'] = 'pesquisar';
$lang['bt_admin'] = 'admin';
$lang['bt_home'] = 'HOME';
$lang['bt_sign_out'] = 'logout';
$lang['bt_sign_in'] = 'fazer login';
$lang['bt_about'] = 'sobre a brapci';
$lang['admin_home'] = 'ADMIN HOME';
$lang['menu_admin'] = 'ADMIN';
$lang['last_update'] = 'Útima atualização';

$lang['to index'] = 'indexando';
$lang['finished'] = 'finalizado';
$lang['revised'] = 'revisado';
$lang['to review'] = 'para revisão';

$lang['pt_BR'] = 'Português';
$lang['en'] = 'Inglês';
$lang['es'] = 'Espanhol';
$lang['fr'] = 'Francês';
	
$lang['oai_journals'] = 'Publicações compatíveis com OAI-PMH';
	
$lang['form_query'] = "Pergunta de Busca";
$lang['form_found'] = "foram localizado(s)";
$lang['form_records'] = 'registro(s)';

$lang['city'] = 'localidade';
$lang['status_article_A'] = 'Em revisão';
$lang['status_article_B'] = '1º revisão';
$lang['status_article_C'] = '2º revisão';
$lang['status_article_D'] = 'revisado';

$lang['issue_new'] = 'novo fascículo';

/* Top Menu */
$lang['sign_in'] = 'ACESSAR';

/* SESSIOn */
$lang['save_session'] = 'Salvar busca >>>';

/* Login */
$lang['login_enter'] = 'Entar';
$lang['login_name'] = 'Informe seu login';
$lang['login_password'] = 'Informe sua senha';
$lang['login_enter'] = 'Entar';
$lang['login_social'] = 'Logue com uma conta existente (recomendado)';
$lang['your_passoword'] = 'sua senha';

$lang['form_year_cut'] = 'delimitação da busca:';

/* Controle de autoridade */
$lang['menu_authority_controle'] = 'Controle de Autoridade';
$lang['menu_authority_person'] = 'Pessoas';
$lang['Search for...'] = 'Busca por ...';
$lang['authority_search'] = 'Busca por autoridades';

/* Tools */
$lang['menu_tools'] = 'Ferramentas';
$lang['change_xls'] = 'Converter CSV para Matrix';
$lang['gerar_list_nomes'] = 'Extrair nomes de uma Matrix';
$lang['gerar_matriz'] = 'Gerar Matrix';
$lang['gerar_pajek'] = 'Gerar arquivo para o Pajek (.pjk)';

$lang['idioma_01'] = 'Idioma preferencial';
$lang['idioma_02'] = 'Idioma alternativo #1';
$lang['idioma_03'] = 'Idioma alternativo #2';

$lang['change_language'] = 'Altera posição dos titulos PRINCIPAL e SECUNDÁRIO';

$lang['select_all'] = 'selecionar todos';
$lang['showing'] = 'mostrando';


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
$lang['frbroo:has_creator'] = 'Autor / criador';

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
