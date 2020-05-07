<?php
// This file is part of the Brapci Software. 
// 
// Copyright 2015-2020, UFPR/UFRGS. All rights reserved. You can redistribute it and/or modify
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
 * @date: 2020-05-06
 */

$lang['context_01_section'] = 'Apresentação do Thesa';
$lang['context_01'] = '
<p>O Thesa foi desenvolvido objetivando disponibilizar um instrumento para os estudantes de graduação de biblioteconomia na disciplina de Linguagens Documentárias para a elaboração de tesauros, de modo que possibilite reduzir o trabalho operacional e dar maior atenção ao trabalho de desenvolvimento cognitivo e conceitual referente a modelagem do domínio.</p>
<p>Como norteador do aplicativo, baseou-se nas normas ISO e NISO vigentes, de forma a compatibilizar suas diretrizes com os requisitos semânticos prementes nas novas demandas dos SOCs. Com base na literatura disponível, nas normas de construção de tesauros da ISO e NISO foram identificados os elementos necessários para o desenvolvimento do protótipo, principalmente no que tange ao levantamento das propriedades de ligação entre os conceitos.</p>
<p>A estrutura do Thesa é baseada na concepção das relações entre os conceitos, partido do pressuposto que um conceito pode ser representado por um termo, uma imagem, um som, um link ou qualquer outra forma que possa ser explicitada. Nessa abordagem, o conceito é perene, enquanto a sua representação pode variar conforme o contexto histórico ou social, sendo definida uma forma preferencial, e inúmeras formas alternativas e ocultas.</p>
<p>Como citar: GABRIEL JUNIOR, R. F.; LAIPELT, R. C. Thesa: ferramenta para construção de tesauro semântico aplicado interoperável. <b>Revista P2P & Inovação</b>, Rio de Janeiro, v. 3, n. 2, p.124-145, Mar./Set. 2017.</p>
';

$lang['context_02_section'] = 'Sobre o Thesa';
$lang['context_02'] = '
<p>O Thesa foi desenvolvido inicialmente como um protótipo utilizando a linguagem php e banco de dados MySql, de forma a possibilitar o compartilhamento e desenvolvimento colaborativo da ferramenta.</p> 
<p>O software funciona em ambiente Web e pode ser baixado gratuitamente, podendo ser utilizado para fins didáticos em disciplinas dos cursos de graduação e pós-graduação ou para uso profissional. O Thesa foi desenvolvido com o princípio de multi-idioma, podendo ser traduzido para qualquer idioma, entretanto sua versão de teste está somente em português, as traduções vão depender de se estabelecerem convênios com instituições nativas de outros idiomas, que demonstrarem interesse pelo uso do software.</p>
<p>O Thesa utiliza uma concepção de múltiplos tesauros, ou múltiplos esquemas, ou seja, o usuário pode criar um número ilimitado de tesauros em diferentes áreas do conhecimento, os usuários/elaboradores desses tesauros, podem deixá-los para uso público ou privado, possibilitando o acesso de outros usuários. No Thesa partiu-se da concepção de URI, empregada pelo SKOS e sistemas baseados na Web Semântica, ou seja, cada conceito é associado a um endereço permanente na Internet e a um identificador único do conceito, e esse representado por termos por meio de propriedades.</p>
';

$lang['context_03_section'] = 'Como baixar uma versão do Thesa';
$lang['context_03'] = '
<p>A versão beta (de teste) do Thesa pode ser acessada no endereço <a href="http://www.ufrgs.br/tesauros">http://www.ufrgs.br/tesauros</a>, 
    o software também pode ser baixado no GitHub em <a href="https://github.com/ReneFGJr/Thesa" target="_new">https://github.com/ReneFGJr/Thesa</a>. Ressalta-se que o Thesa é um open source (código aberto), podendo ser modificado ou aperfeiçoado, desde que mantendo os créditos, e ainda aceita contribuições de melhoramentos pela comunidade.</p>
';

$lang['context_04_section'] = 'Contato';
$lang['context_04'] = '
<p>
<b>Universidade Federal do Rio Grande do Sul</b>
<br>Departamento de Ciência da Informação
<br>Curso de Biblioteconomia
<br>
<br>
Pesquisadores envolvidos:
<ul>
<li>Prof. Dr. Rene Faustino Gabriel Junior &lt;renefgj@gmail.com&gt;</li>
<li>Prof. Dr. Rita do Carmo Laipelt &lt;ritacarmo@yahoo.com.br&gt;</li>
</ul>
</p>
<br><br>
<p class="small"><a rel="license" href="http://creativecommons.org/licenses/by/4.0/"><img alt="Licença Creative Commons" style="border-width:0" src="https://i.creativecommons.org/l/by/4.0/88x31.png" /></a><br />Este trabalho está licenciado com uma Licença <a rel="license" href="http://creativecommons.org/licenses/by/4.0/">
<br>Creative Commons - Atribuição  4.0 Internacional</a>.
';

$lang['language_include_info'] = 'Na coluna da esqueda estão os idiomas habilitados no seu tesauro.';
$lang['col_language_enable'] = 'idiomas habilitados';
$lang['action'] = 'ação';
$lang['col_language_disable'] = 'idiomas inativos';

$lang['::: select_the_relation'] = '::: selecione uma opção :::';
$lang['about'] = "SOBRE";
$lang['Action'] = 'Ação';
$lang['action_not_defined'] = 'Ação não definida no sistema';
$lang['add'] = 'adicionar';
$lang['add_term'] = 'Termo adicionado com sucesso!';
$lang['add_term_title'] = 'Adicionar novo Termo';
$lang['adds'] = 'Adicionar';
$lang['admin_home'] = 'ADMIN HOME';
$lang['all'] = 'todas';
$lang['already_an_account'] = 'Já tem uma conta?';
$lang['already_inserted'] = 'Termo já existe';
$lang['alter'] = 'alterar';
$lang['associated  altLabel'] = 'Associado';
$lang['authority_search'] = 'Busca por autoridades';
$lang['bt_about'] = 'sobre a brapci';
$lang['bt_admin'] = 'admin';
$lang['bt_clear'] = 'limpar filtro';
$lang['bt_home'] = 'HOME';
$lang['bt_search'] = 'filtrar';
$lang['bt_sign_in'] = 'fazer login';
$lang['bt_sign_out'] = 'logout';
$lang['bt_thesauros'] = 'tesauros';
$lang['cancel'] = 'cancelar';
$lang['change'] = 'alterado';
$lang['change_language'] = 'Altera posição dos titulos PRINCIPAL e SECUNDÁRIO';
$lang['change_my_data'] = 'Meus dados';
$lang['change_old'] = 'termo anterior';
$lang['change_password_successful'] = 'Senha alterada com sucesso';
$lang['change_prefLabel'] = 'Alterar termo Preferencial';
$lang['change_xls'] = 'Converter CSV para Matrix';
$lang['check_all'] = 'Validar tudo';
$lang['city'] = 'localidade';
$lang['close'] = 'fechar';
$lang['collaborator'] = 'Colaborador';
$lang['collaborator_insered'] = 'colaborador vinculado com sucesso!';
$lang['collaborators'] = 'Colaboradores';
$lang['collaborators_add'] = 'Adicionar colaboradores';
$lang['collaborators_info'] = 'Informe o e-mail do colaborador';
$lang['Conceitual map'] = 'Mapa conceitual';
$lang['conceitual_map'] = 'Mapa conceitual';
$lang['concept'] = 'Conceito';
$lang['Concept_add'] = 'Adicionar Descritores/Termos';
$lang['concept_add'] = 'Adicionar Termos';
$lang['concept_add_infor'] = 'O termo é uma palavras simples ou composta que será utilizada para representar uma conceito.\n\nUtilise o ponto e virgula (;) para separa os novos termos, ou insira um em cada linha. Pode-se inserir uma sequencias de termos em um idioma específico.';
$lang['concept_ALT'] = 'Termo Equivalente (usado por)';
$lang['concept_BT'] = 'Termo Geral';
$lang['concept_create'] = 'Conceito criado com o termo ';
$lang['concept_DEF'] = 'Notas';
$lang['concept_FLE'] = 'Genero / Flexão';
$lang['concept_HID'] = 'Termo Equivalente (oculto)';
$lang['concept_IMG'] = 'Iconografia';
$lang['concept_not_found'] = 'Conceito não localizado';
$lang['concept_NR'] = 'Termo(s) específico(s)';
$lang['concept_NT'] = 'Nota de escopo';
$lang['concept_TR'] = 'Termo(s) relacionado(s)';
$lang['concepts'] = 'conceitos';
$lang['configuration'] = 'Configurações';
$lang['Contact'] = "Contato";
$lang['copy_to_clipboard'] = 'Copiar para clipboard';
$lang['created_in'] = 'Criado em';
$lang['date'] = 'Data';
$lang['dc:creator'] = 'Agente(s)';
$lang['delete_term_confirm'] = 'Excluir term do tesauro?';
$lang['Descript'] = 'Descrição';
$lang['description'] = 'Descrição';
$lang['Don’t have an account?'] = 'Não tem uma conta?';
$lang['download'] = "DOWNLOAD";
$lang['edit'] = 'editar';
$lang['edit_th'] = 'Personalizar Thesa';
$lang['email_already_inserted'] = 'E-mail já cadastrado!';
$lang['email_invalid'] = 'e-mail inválido';
$lang['email_not_exist'] = 'e-mail não cadastrado';
$lang['email_recover_password'] = 'Prezado <b>$nome</b>, </br></br><p>Você solicitou o envio de sua senha para esse e-mail em $data $hora.<p></br></br>Sua senha é <b>$password</b></p>';
$lang['en'] = 'Inglês';
$lang['English'] = 'Inglês';
$lang['ERRO_600'] = 'E-mail não cadastrado no sistema';
$lang['es'] = 'Espanhol';
$lang['ex:coordinationOf'] = 'coordenação';
$lang['ex:coordinationOfActionProduct'] = 'ação/produto';
$lang['ex:coordinationOfCauseEffect'] = 'causa/efeito';
$lang['ex:coordinationOfKinship'] = 'afinidade';
$lang['ex:coordinationOfOpposition'] = 'oposição/antonímia';
$lang['ex:coordinationOfPartOf'] = 'processo/parte de';
$lang['ex:coordinationOfProductCharacteristics'] = 'características do produto';
$lang['ex:unionOf'] = 'equivalente a';
$lang['export_to'] = 'Exportar para';
$lang['finished'] = 'finalizado';
$lang['Forgot Password?'] = 'Esqueceu a senha?';
$lang['Forgot'] = 'Esqueci minha senha';
$lang['forgot_password'] = 'Esqueci minha senha';
$lang['form_found'] = "foram localizado(s)";
$lang['form_query'] = "Pergunta de Busca";
$lang['form_records'] = 'registro(s)';
$lang['form_user_name'] = 'E-mail do usuário';
$lang['form_user_password'] = 'Senha de acesso';
$lang['form_year_cut'] = 'delimitação da busca:';
$lang['fr'] = 'Francês';
$lang['frbroo:has_creator'] = 'Autor / criador';
$lang['fullName'] = 'Nome completo';
$lang['gerar_list_nomes'] = 'Extrair nomes de uma Matrix';
$lang['gerar_matriz'] = 'Gerar Matrix';
$lang['gerar_pajek'] = 'Gerar arquivo para o Pajek (.pjk)';
$lang['glossario'] = 'Glossário';
$lang['glossario_cap'] = 'Apresentação Alfabética';
$lang['has_prefTerm'] = 'como preferencial';
$lang['has_send_email_to'] = 'Verifique seu e-mail, vou enviado uma confirmação para o endereço ';
$lang['help_info'] = 'Ajuda';
$lang['hidden_log'] = 'Ocultar registro de eventos';
$lang['how_to_cite'] = 'Como citar este trabalho';
$lang['how_to_cite_thesa'] = 'Como citar o Thesa';
$lang['icones'] = 'Icone do tesauro';
$lang['idioma_01'] = 'Idioma preferencial';
$lang['idioma_02'] = 'Idioma alternativo #1';
$lang['idioma_03'] = 'Idioma alternativo #2';
$lang['issue_new'] = 'novo fascículo';
$lang['item_already_deleted'] = 'Term já estava excluído';
$lang['language'] = 'idioma';
$lang['languages_config'] = 'Configuração de idiomas';
$lang['last_update'] = 'atualização';
$lang['last_update'] = 'Útima atualização';
$lang['login'] = 'Acessar';
$lang['login_enter'] = 'Entar';
$lang['login_name'] = 'Informe seu login';
$lang['login_password'] = 'Informe sua senha';
$lang['login_recover_password'] = 'Recuperar a senha';
$lang['login_social'] = 'Logue com uma conta existente (recomendado)';
$lang['logout'] = 'Sair';
$lang['Lowercase'] = 'Forçar caixa baixa dos termos';
$lang['lowercase'] = 'Inserir em caixa baixa';
$lang['MANUAL'] = 'MANUAL';
$lang['menu_admin'] = 'ADMIN';
$lang['menu_authority_controle'] = 'Controle de Autoridade';
$lang['menu_authority_person'] = 'Pessoas';
$lang['menu_config'] = 'Configurações';
$lang['menu_tools'] = 'Ferramentas';
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
$lang['my_thesauros'] = 'Meu Thesa';
$lang['my_thesauros'] = 'Meus tesauros';
$lang['my_thesaurus'] = 'Meus tesauros';
$lang['new_thesaurus'] = 'Criar novo vocabulário controlado / tesauros';
$lang['no_match_password'] = 'Senhas não conferem';
$lang['not_record'] = 'sem registro';
$lang['oai_journals'] = 'Publicações compatíveis com OAI-PMH';
$lang['open_thesauros'] = 'TH abertos';
$lang['open_thesaurus'] = 'Tesauros Abertos';
$lang['outputs'] = 'Relatórios';
$lang['password'] = 'senha de acesso';
$lang['password_is_requered'] = 'Senha é obrigatória';
$lang['password_more_shot'] = 'Senha muito curta';
$lang['perfil_coordenador'] = 'Coordenador';
$lang['por'] = 'Português';
$lang['Portuguese'] = 'Português';
$lang['pref_term'] = 'Descritor';
$lang['preferences'] = 'Dados do tesauro';
$lang['presentation'] = "APRESENTAÇÃO";
$lang['printer'] = 'Imprimir';
$lang['rdf_link'] = 'Arquivo RDF';
$lang['remove_propriety'] = 'Remover propriedade';
$lang['Report Thesaurus'] = 'Thesauro Impresso';
$lang['report_a_bug'] = 'Notifique um problema';
$lang['report_the_bug'] = 'Descreva o problema';
$lang['resend_password'] = 'Reenviar senha';
$lang['resend_password_text'] = 'Sua senha será enviar para seu e-mail cadastrado, cheque sua caixa de entrada ou a lista de Spam para localizar o link de acesso ao sistemas';
$lang['resend_validation'] = 'Reenviar validação';
$lang['return'] = 'Voltar';
$lang['return_in'] = 'retornando em';
$lang['revised'] = 'revisado';
$lang['rules'] = 'Ferramentas';
$lang['save'] = 'Gravar';
$lang['save'] = 'salvar';
$lang['save_continue'] = 'Salvar e continuar';
$lang['save_session'] = 'Salvar busca >>>';
$lang['scopeNote'] = 'Nota de escopo';
$lang['Search for...'] = 'Busca por ...';
$lang['search'] = 'pesquisar';
$lang['select_a_concept'] = 'Selecione um conceito';
$lang['select_all'] = 'selecionar todos';
$lang['send_bug'] = 'Reportar o problema'; 
$lang['send_password'] = 'enviar senha';
$lang['showing'] = 'mostrando';
$lang['Sign In'] = 'Entrar';
$lang['Sign Up Send'] = 'Solicitar cadastro';
$lang['Sign Up'] = 'Quero me cadastrar';
$lang['sign_in'] = "ACESSAR";
$lang['sign_in'] = 'ACESSAR';
$lang['SignIn'] = 'Entrar no Sistema';
$lang['SignUp'] = 'Cadastre-se';
$lang['signup_success'] = 'Cadastro realizado com sucesso';
$lang['signup_success'] = 'Sucesso!';
$lang['signup_success_msg'] = 'Foi enviado um e-mail para sua conta com instruções';
$lang['signup_success_msg'] = 'Parabéns $name, seu cadastro foi efetivado. <BR>Verifique seu e-mail ($email) para validar seu acesso!';
$lang['signup_success_msg'] = 'You have successfully signed up. Please folow ';
$lang['signup_user_already_exist'] = 'E-mail $email já existe na base de dados, para recuperar a senha clique no $link.';
$lang['signup_user_already_exist'] = 'Este e-mail já existe no sistema';
$lang['skos:action_against_agent'] = 'ação ou objeto/contra agente';
$lang['skos:altLabel'] = 'Variações Terminológica / Termo alternativo';
$lang['skos:broader.isPartOfBody'] = 'é parte do (corpo/orgãos)';
$lang['skos:broader.isPartOfDisciplina'] = 'é parte (área do conhecimento)';
$lang['skos:broader.isPartOfGeo'] = 'é parte de (geográfico)';
$lang['skos:broader.isPartOfSocialStructure'] = 'é parte (estrutura social)';
$lang['skos:broader.isTypeOf'] = 'é um tipo de (especificidade)';
$lang['skos:changeNote'] = 'Nota de alteração';
$lang['skos:definition'] = 'Definição do conceito';
$lang['skos:example'] = 'Nota de exemplo';
$lang['skos:hiddenLabel'] = 'Variações léxicas (erros grafia)';
$lang['skos:is_equivalent_another_language'] = 'é traduzido por';
$lang['skos:is_feminine_of'] = 'é feminino de';
$lang['skos:is_gerund'] = 'é gerundio de';
$lang['skos:is_verbal_flexion'] = 'é flexão verbal';
$lang['skos:material_product'] = 'matéria prima/produto';
$lang['skos:narrower'] = 'Descritor Específico:';
$lang['skos:object_field'] = 'campo de estudo/objeto ou fenômeno';
$lang['skos:object_field2'] = '';
$lang['skos:objective_action'] = 'ação/paciente ou objetivo';
$lang['skos:part_of_object'] = 'objeto/suas partes';
$lang['skos:prefLabel'] = 'Descritor preferencial';
$lang['skos:process_agent'] = 'agente/processo';
$lang['skos:property_action'] = 'ação/propriedade';
$lang['skos:property_object'] = 'conceito ou objeto/propriedade';
$lang['skos:scopeNote'] = 'Nota de escopo / nota de aplicação';
$lang['skosxl:abbreviation_of'] = 'abreviatura de';
$lang['skosxl:acronym'] = 'sigla';
$lang['skosxl:code'] = 'notação (código)';
$lang['skosxl:garantiaLiteraria'] = 'Garantia literária';
$lang['skosxl:has_gerund'] = 'tem como gerundio';
$lang['skosxl:hiddenLabel'] = 'tem com termo oculto';
$lang['skosxl:is_gerund'] = 'é gerundio de';
$lang['skosxl:is_gerund'] = 'gerundio de';
$lang['skosxl:is_synonymous'] = 'é variação de';
$lang['skosxl:is_synonymous'] = 'variação de';
$lang['skosxl:is_verbal_inflection'] = 'é flexão verbal de';
$lang['skosxl:is_verbal_inflection'] = 'flexão verbal de';
$lang['skosxl:isFeminine'] = 'feminino de';
$lang['skosxl:isMasculine'] = 'masculino de';
$lang['skosxl:isPlural'] = 'plural de';
$lang['skosxl:isSingular'] = 'singular de';
$lang['skosxl:literalForm'] = 'extenso de ';
$lang['skosxl:noteCited'] = 'Nota de citação';
$lang['skosxl:synonymous'] = 'tem como variação';
$lang['skosxl:verbal_inflection'] = 'tem como flexão verbal';
$lang['status'] = 'Situação';
$lang['status_1'] = 'Acesso restrito / em edição';
$lang['status_2'] = 'Acesso público';
$lang['status_article_A'] = 'Em revisão';
$lang['status_article_B'] = '1º revisão';
$lang['status_article_C'] = '2º revisão';
$lang['status_article_D'] = 'revisado';
$lang['success_action'] = 'Ação realizada com sucesso';
$lang['term'] = 'descritor';
$lang['term'] = 'termo';
$lang['Term_create_concept'] = 'Criar conceito';
$lang['Term_delete_concept'] = 'Excluir descritor';
$lang['Term_delete_concept'] = 'Excluir termo';
$lang['Term_edit_concept'] = 'Editar descritor';
$lang['Term_edit_concept'] = 'Editar termo';
$lang['term_relations'] = 'Termos relacionados';
$lang['term_search'] = 'buscar termos';
$lang['Terms'] = 'Descritores';
$lang['Terms'] = 'Termos';
$lang['terms_add'] = 'Inserção de novos termos';
$lang['terms_info'] = 'Qualificação dos termos';
$lang['terms_list'] = 'Descritores do tesauro';
$lang['terms_list'] = 'Termos do tesauro';
$lang['TH_atual'] = 'TH atual';
$lang['th_type_1'] = 'Dicionário';
$lang['th_type_2'] = 'Glossário';
$lang['th_type_3'] = 'Controle de autoridade de assuntos';
$lang['th_type_3'] = 'Controle de autoridade de pessoas e instituições';
$lang['th_type_4'] = 'Tesauro';
$lang['th_type_5'] = 'Tesauro Semântico';
$lang['th_type_6'] = 'Ontologia';
$lang['th_type_7'] = 'StopWords';
$lang['thesaurus_audience'] = 'Público alvo/audiência';
$lang['thesaurus_audience'] = 'Público alvo';
$lang['thesaurus_description'] = 'Descrição do vocabulário / tesauro';
$lang['thesaurus_description'] = 'Sobre o tesauro';
$lang['thesaurus_introdution'] = 'Introdução';
$lang['thesaurus_language_pref'] = 'Idioma preferencial';
$lang['thesaurus_methodology'] = 'Metodologia';
$lang['thesaurus_multilanguage'] = 'Multi-idioma';
$lang['thesaurus_myth'] = "MEUS TESAUROS";
$lang['thesaurus_name'] = 'Nome do tesauro';
$lang['thesaurus_open'] = "TESAUROS ABERTOS";
$lang['thesaurus_status'] = 'Acesso';
$lang['thesaurus_status'] = 'Tipo';
$lang['thesaurus_type'] = 'Tipo';
$lang['to index'] = 'indexando';
$lang['to review'] = 'para revisão';
$lang['to_invite'] = 'incorporar';
$lang['type'] = 'Tipo';
$lang['Unauthorized_access'] = 'Acesso não autorizado';
$lang['update_in'] = 'Atualizado em';
$lang['User'] = 'Usuário';
$lang['user_invalid_password'] = 'Usuário ou senha incorretos';
$lang['user_login'] = 'Acessar o Thesa';
$lang['user_logout'] = 'Sair';
$lang['user_not_found'] = 'Usuário não localizado no cadastro';
$lang['user_not_validaded'] = 'Usuário não validado';
$lang['user_perfil'] = 'Perfil do usuário';
$lang['userEmail'] = 'e-mail';
$lang['userName'] = 'Nome do usuário';
$lang['Username'] = 'Usuário (email)';
$lang['Version'] = 'Versão';
$lang['view_log'] = 'Mostrar registro de eventos';
$lang['Voltar'] = 'voltar';
$lang['Welcome'] = 'Bem vindo a';
$lang['Welcome_signup'] = 'Cadastro de usuário';
$lang['your_bug_has_reported'] = 'Obrigado, <br><br>Seu erro foi reportado com sucesso!';
$lang['your_email'] = 'e-mail';
$lang['your_name'] = 'Seu nome';
$lang['your_passoword'] = 'sua senha';
$lang['ERRO #515'] = 'Erro 515 - Formato não suportado!';
$lang['image_upload'] = 'Upload de Imagem';
$lang['image_upload_info'] = 'Relaciona uma imagem a um conceito do Thesa, são aceito os formatos JPG, PNG e GIF.';
$lang['pref_term_other_language'] = 'Termos preferenciais';
?>