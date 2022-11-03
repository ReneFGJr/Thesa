<style>
    .parallax_background_1 {
        /* The image used */
        background-image: url("<?php echo URL; ?>/img/background/background_<?= $bg; ?>.jpg");
    }

    .parallax_background_2 {
        /* The image used */
        background-image: url("<?php echo URL; ?>/img/background/background_<?= $bg + 1; ?>.jpg");
    }
</style>
<!-- Container element -->
<div class="parallax parallax_background_1" style="height: 280px;">
    <div class="container">
        <div class="row">
            <div class="col-md-5 text-center" style="margin-top: 50px;">
                <span class="logo_thesa_text">THESA</span>
                <br>
                <span class="logo_thesa_text_sub">Tesauro Semântico Aplicado</span>
            </div>
            <div class="col-md-2 text-center" style="margin-top: 50px;">
            </div>
            <div class="col-md-5 text-center" style="margin-top: 50px;">
                <span style="color: white;" class="h1">Bem vindo a Thesa</span><br />
                <span style="color: white;">Sistema de Representação de Conhecimento</span>
                <div class="separador">
                    <div class="separator-line"></div>
                    <span style="color: white;">Já tem uma conta?</span>
                    <div class="separator-line"></div>
                </div>
                <a href="/social/login" style="text-decoration: none;">
                    <div style="padding: 10px; background-color: #ffffff; text-decoration: none; border-radius: 20px; color: black; font-size: 12px; font-family: Titillium+Web;">Não tem cadastro? Clique aqui!</div>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="container mt-5 mb-3 font">
    <div class="row">
        <div class="col-12">
            <h1>Apresentação do Thesa</h1>
            <p>O Thesa foi desenvolvido objetivando disponibilizar um instrumento para os estudantes de graduação de biblioteconomia na disciplina de Linguagens Documentárias para a elaboração de tesauros, de modo que possibilite reduzir o trabalho operacional e dar maior atenção ao trabalho de desenvolvimento cognitivo e conceitual referente a modelagem do domínio.</p>
            <p>Como norteador do aplicativo, baseou-se nas normas ISO e NISO vigentes, de forma a compatibilizar suas diretrizes com os requisitos semânticos prementes nas novas demandas dos SOCs. Com base na literatura disponível, nas normas de construção de tesauros da ISO e NISO foram identificados os elementos necessários para o desenvolvimento do protótipo, principalmente no que tange ao levantamento das propriedades de ligação entre os conceitos.</p>
            <p>A estrutura do Thesa é baseada na concepção das relações entre os conceitos, partido do pressuposto que um conceito pode ser representado por um termo, uma imagem, um som, um link ou qualquer outra forma que possa ser explicitada. Nessa abordagem, o conceito é perene, enquanto a sua representação pode variar conforme o contexto histórico ou social, sendo definida uma forma preferencial, e inúmeras formas alternativas e ocultas.</p>
            <p>Como citar: GABRIEL JUNIOR, R. F.; LAIPELT, R. C. Thesa: ferramenta para construção de tesauro semântico aplicado interoperável. <b>Revista P2P & Inovação</b>, Rio de Janeiro, v. 3, n. 2, p.124-145, Mar./Set. 2017.</p>
            <p>Para saber mais: GABRIEL JUNIOR, R. F.; LAIPELT, R. C. Descrição das relações semânticas para aplicação em kos: uso do tesauro semântico aplicado (thesa). <b>Revista P2P e INOVAÇÃO</b>, Rio de Janeiro, v. 6, n. 1, p. 117-135, 2019. DOI: 10.21721/p2p.2019v6n1.p117-135 Acesso em: 13 set. 2022.</p>
            <p>Projeto Financionado com recursos do CNPq Projeto: 439277/2018-3</p>
        </div>
    </div>
</div>

<div class="parallax parallax_background_2" style="height: 280px; padding: 100px;">
    <?php
    $Thesa = new \App\Models\Thesa\Index();
    $data = $Thesa->summary();
    echo view('Thesa/Summary', $data);
    ?>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <h1>Sobre o Thesa</h1>
            <p>O Thesa foi desenvolvido inicialmente como um protótipo utilizando a linguagem php e banco de dados MySql, de forma a possibilitar o compartilhamento e desenvolvimento colaborativo da ferramenta.</p>
            <p>O software funciona em ambiente Web e pode ser baixado gratuitamente, podendo ser utilizado para fins didáticos em disciplinas dos cursos de graduação e pós-graduação ou para uso profissional. O Thesa foi desenvolvido com o princípio de multi-idioma, podendo ser traduzido para qualquer idioma, entretanto sua versão de teste está somente em português, as traduções vão depender de se estabelecerem convênios com instituições nativas de outros idiomas, que demonstrarem interesse pelo uso do software.</p>
            <p>O Thesa utiliza uma concepção de múltiplos tesauros, ou múltiplos esquemas, ou seja, o usuário pode criar um número ilimitado de tesauros em diferentes áreas do conhecimento, os usuários/elaboradores desses tesauros, podem deixá-los para uso público ou privado, possibilitando o acesso de outros usuários. No Thesa partiu-se da concepção de URI, empregada pelo SKOS e sistemas baseados na Web Semântica, ou seja, cada conceito é associado a um endereço permanente na Internet e a um identificador único do conceito, e esse representado por termos por meio de propriedades.</p>
        </div>
    </div>
</div>