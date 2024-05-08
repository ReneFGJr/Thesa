<?php

namespace App\Models\Tools;

use CodeIgniter\Model;

class Wordcount extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'wordcounts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    //https://wordcount.com/pt/

    function process($txt)
    {
        $txt = $this->sample02();
        # Identifica fim de frases
        $txt = $this->phrase($txt);
        $txtA = $this->line($txt);
        $txtB = $this->BibliographicLegend($txtA);
        pre($txt);
    }

    ############## Remove duplicates
    function BibliographicLegend($ln)
        {
            $lns = [];
            $erase = [];
            for($r=0;$r < count($ln);$r++)
                {
                    $lorg = trim($ln[$r]);
                    $l = $lorg;
                    if ($l != '')
                    {
                    $l = $this->removeNumber($l);
                    $hash = md5($l);
                    if (isset($lns[$hash]))
                        {
                            if (strlen($l) > 5)
                                {
                                    $erase[$lorg] = 1;
                                    $ln[$r] = '[ELIMINADO]';
                                    unset($ln[$r]);
                                }

                        } else {
                            $lns[$hash] = 1;
                        }
                    } else {
                        $ln[$r] = '[ELIMINADO]';
                        unset($ln[$r]);
                    }
                }

                echo "========";
                //pre($erase,false);
                pre($ln);
        }

    function removeNumber($t)
        {
            $ch = ['0','1','2','3','4','5','6','7','8','9'];
            $t = str_replace($ch,'',$t);
            return $t;
        }

    ############## Convert Line
    function line($txt)
    {
        $txt = str_replace(chr(13), '¢', $txt);
        $txt = str_replace(chr(10), '', $txt);
        $ln = explode('¢', $txt);

        $iact = 0;

        for ($r = 0; $r < count($ln); $r++) {
            $firstChar = substr(ascii($ln[$r]), 0, 1);
            if (
                /* Primeiro caracter letra minuscula */
                (($firstChar >= 'a') and ($firstChar <= 'z'))
                //or
                /* Primeiro caracter número */
                //(($firstChar >= '0') and ($firstChar <= '9'))
                /* Termina com ponto e virgula */
                or (substr($ln[$iact], -1) ==';')
            ) {
                $ln[$iact] .= '[ln]' . $ln[$r];
                $ln[$r] = '';
            }
            if (trim($ln[$r]) != '') {
                $iact = $r;
            }
        }
        return $ln;
    }

    ############### Phrase
    function phrase($txt)
    {
        ############ troca NF por CR
        $txt = str_replace(chr(10), chr(13), $txt);

        while (strpos($txt, chr(13) . chr(13)) > 0) {
            $txt = str_replace(chr(13) . chr(13), chr(13), $txt);
        }

        ############ Identifica Endpoints
        $endpoint = ['.', '?', '!'];
        foreach ($endpoint as $id => $char) {
            $endpoint[$id] .= chr(13);
        }
        $changed = ["[.]¢\n"];
        foreach ($endpoint as $char) {
            $txt = str_replace($endpoint, $changed, $txt);
        }

        /* Exceções */
        /* Final com parenteses */
        $excession = [')¢'];
        $excessionCG = [') '];
        $txt = str_replace($excession, $excessionCG, $txt);
        return $txt;
    }

    function sample01()
    {
        $txt = '4	RECUPERAÇÃO DA INFORMAÇÃO

Consideramos importante trazer considerações sobre a Recuperação da Informação (RI) que Hjorland (2021) considera uma área de pesquisa distinta da Organização do Conhecimento. Considera o autor que a RI, atualmente, está mais próxima da Ciência da Computação e defende a ideia de um trabalho conjunto entre especialistas em Sistemas de Organização do Conhecimento e cientistas da computação para que seja possível “fornecer documentos e conhecimentos que estejam de acordo com nossas reivindicações de conhecimento mais bem fundamentadas” (HJORLAND, 2021, p. 22).
Ferneda (2003, p. 11), parafraseando Saracevic (1999) afirma que “a recuperação de Informação pode ser considerada a vertente tecnológica da Ciência da Informação e é resultado da relação desta com a Ciência da Computação”.
Baeza-Yates e Ribeiro-Neto (2013), posicionam a Recuperação da Informação como área de estudo da Ciência da Computação, afirmando que um de seus objetivos é permitir que os usuários de um sistema informatizado possam obter acesso a informações, que lhes interessam, de forma fácil.
Os estudos de Recuperação Inteligente da Informação versam especificamente sobre o uso de Sistemas informatizados para atingir de forma eficiente o objetivo de recuperar informação em um âmbito específico, seja corporativo ou mesmo em ambiente da Internet.
Sequência de passos é necessária para a Recuperação da Informação, as etapas podem ser a formação da coleção de documentos que serão alvos da busca, a indexação, a formulação de uma consulta pelo usuário, a expansão da consulta, o processamento, onde os termos da consulta são efetivamente buscados no índice e por fim o ranqueamento dos documentos recuperados.
Todos esses passos são de grande importância para o sucesso de um sistema de recuperação de Informação, porém, neste trabalho queremos destacar a fase de expansão da consulta no âmbito da Recuperação Inteligente da Informação.
Ferneda (2003, p. 14), para esclarecer de forma sintetizada como pensam alguns autores sobre a Recuperação da Informação afirma que:
No contexto da Ciência da Informação, o termo “recuperação de informação” significa, para uns, a operação pela qual se seleciona documentos, a partir do acervo, em função da demanda do usuário. Para outros, “recuperação de informação” consiste no fornecimento, a partir de uma demanda definida pelo usuário, dos elementos de informação documentária correspondentes. O termo pode ainda ser empregado para designar a operação que fornece uma resposta mais ou menos elaborada a uma demanda, e esta resposta é convertida num produto cujo formato é acordado com o usuário (bibliografia, nota de síntese etc.).

Caso se planejasse realizar a construção de um Sistema de Recuperação de Informação destinado a realizar buscas nos autos de Inquéritos Policiais o primeiro passo seria a formação de um corpus de documentos que abrangeria todas as peças relacionadas a cada IPL instaurado pela Polícia Federal em todo o Brasil e armazenadas em seus sistemas.
Uma vez que tenhamos esse repositório central com todos os documentos, que contêm as informações que possam interessar a um policial (usuário do sistema) que necessita realizar uma consulta, esses documentos precisam ser indexados.
Sobre essa indexação Baeza-Yates e Ribeiro-Neto (2013, p. 6) afirmam que:
Os documentos no repositório central precisam ser indexados para que a recuperação e o ranqueamento sejam efetuados rapidamente. A estrutura de índice mais utilizada é o índice invertido composto por todas as palavras distintas da coleção e para cada palavra, a lista de documentos que a contém. Na criação do índice invertido devem ser excluídas todas as palavras consideradas stop words, como artigos, conectores e aquelas que aparecem em praticamente todos os documentos.

O SRI deve disponibilizar uma interface onde o usuário possa inserir os termos que pretende consultar com o fim de recuperar a informação pretendida, normalmente trata-se de um campo de busca onde são digitadas as palavras ou expressões pelo usuário e por fim acionado o mecanismo de busca.
Uma vez especificados os termos de interesse do usuário, para uma maior eficiência na recuperação, a consulta precisa ser expandida, associando a ela outras informações que possam ampliar as possibilidades de recuperação, exemplificando de maneira simplista, caso o policial pretenda recuperar documentos que tragam informação sobre “abuso sexual infantojuvenil”, o sistema, na expansão da consulta, poderia ampliar esse parâmetro, acrescentando na busca, termos próximos ou sinônimos como “pedofilia”, “pornografia infantil” ou “estupro de vulnerável”.
Corroborando com o que foi exposto acima, podemos citar Baeza-Yates e Ribeiro-Neto (2013, p. 7) quando discorrem que:
Para realizar uma busca, o usuário primeiro especifica uma consulta que reflete sua necessidade de informação. A seguir, a consulta é analisada sintaticamente e expandida com, por exemplo, formas variantes das palavras da consulta. A consulta expandida, que chamaremos de consulta do sistema, é então processada, utilizando-se o índice para recuperar um subconjunto dos documentos.

Considerando que o corpus de documentos está devidamente armazenado e tratado, sendo então criado o índice invertido, onde todos os termos importantes que existem nos documentos são listados e associados a aqueles que os contém; uma vez que a consulta seja realizada pelo usuário, e posteriormente expandida; a consulta do Sistema é confrontada com o índice e os documentos correspondentes são recuperados.
Tendo sido recuperados os documentos tidos como de interesse para o usuário, devem finalmente serem submetidos a um ranqueamento com o fim de apresentá-los numa ordem de importância, exibindo com prioridade os que tenham maior probabilidade de serem considerados mais úteis.
O funcionamento de um Sistema de Recuperação de Informações pode ser avaliado considerando vários parâmetros, sendo os principais a precisão e a revocação.
A precisão considera a quantidade de documentos que foram recuperados que são realmente relevantes para as necessidades de informação do usuário. No exemplo de consulta que foi utilizado anteriormente, a precisão da recuperação de Informações seria considerada maior, conforme a quantidade de documentos recuperados que tratassem de investigações de crimes relacionados a abuso sexual infantojuvenil. Dentre os documentos que forem recuperados pela consulta, quanto maior for a quantidade daqueles considerados relevantes, maior será a precisão e mais eficiente seria considerado o Sistema.
Já a revocação é medida conforme a quantidade de documentos relevantes que existem no corpus e que são efetivamente recuperados pela consulta. Novamente considerando o exemplo de consulta de termo utilizado anteriormente nesse texto, caso existam na base de dados centenas de documentos que versem sobre investigações de crimes relacionados a abuso sexual infantojuvenil, quanto maior o número desses documentos que sejam efetivamente recuperados, maior a revocação do Sistema.
Para que um sistema tenha os melhores índices de precisão e revocação, vários dos componentes de um Sistema de Recuperação de Informação precisam ser aprimorados e desenvolvidos com o máximo de eficiência e entre eles está o componente responsável pela expansão de consultas
Quando um usuário de um Sistema de Recuperação de Informação precisa encontrar documentos que atendam a uma necessidade específica, irá realizar uma consulta usando como parâmetro de busca, termos ou frases que ele entende que trarão o melhor retorno possível.
Os termos escolhidos pelo usuário, nem sempre serão os mais adequados ou suficientes para uma recuperação eficiente e nessa hora os componentes inteligentes do sistema precisam ser utilizados, expandindo a consulta do usuário para chegar a uma consulta que permita uma recuperação mais abrangente e de qualidade.
Gonzalez e Strube de Lima (2003, p. 30) explicam que a expansão de consulta pode ter o objetivo de tornar o conjunto de documentos recuperados maior ou ainda melhorar a precisão da recuperação, no primeiro caso:
[...] os termos expandidos são selecionados entre aqueles similares aos originais encontrados na consulta. Seriam considerados similares aqueles que possuem significado semelhante, mas nem sempre são sinônimos, como “casa” e “prédio”.

Já no segundo caso:
[...] os termos adicionados não são similares, porém apresentam algum tipo de relacionamento (como o que ocorre entre “casa” e “morar”) com os termos originais, deduzido por motivação linguística ou através de dados estatísticos.

Cardoso (2008, p. 2), chama de Reformulação Automática de Consultas a técnica que:
[...] procura reformular a consulta inicial do utilizador de forma automática, adicionando termos fortemente relacionados com a pesquisa, removendo termos irrelevantes ou geradores de ruído, e atribuindo pesos de importância a cada termo. No final, a linha de consulta reformulada será mais precisa e fiel à necessidade de informação real do utilizador, e mais robusta em relação às diferenças de vocabulário patente entre documentos e consultas.

Os Sistemas de Organização do Conhecimento podem ser utilizados na expansão de consultas em Sistemas de Recuperação da Informação, Baeza-Yates e Ribeiro-Neto (2013) discutem sobre duas variações possíveis para a análise global, ambas baseadas em tesauros, são elas a “expansão de consulta baseada em um tesauro de similaridade” e a “expansão de consultas com base em um tesauro estatístico”.
Fachin (2010, p. 259) implementou importante pesquisa sobre a existência de “aplicações de mecanismos de Recuperação Inteligente da Informação que usem ontologia como recurso na recuperação precisa e eficaz da Informação”, ao final encontrou quatro artigos que tratam do tema e que foram analisados.
Saias (2003, p. 29) acredita que:
 Como meio estruturado capaz de representar informação semântica, as ontologias podem ser utilizadas no âmbito de Sistemas de Recuperação de Informação com o objectivo de melhorar os resultados. A existência de relações hierárquicas, e não só, sobre a semântica da informação poderá potenciar uma melhoria da eficiência nos SRI.

Principalmente no contexto de um domínio específico, nos parece que o uso de ontologias, bem como tesauros, pode incrementar em muito a eficiência da recuperação da informação.
Considerando tudo o que foi exposto concluímos que para aprimorar a recuperação de informação nos autos de IPL, necessariamente é preciso implementar um Sistema de Organização do Conhecimento e para tanto, faz-se necessário um Mapeamento Terminológico.

5	METODOLOGIA

Considerando a totalidade dessa pesquisa, a classificamos quanto a sua finalidade como uma pesquisa aplicada, visto que busca colaborar na solução de um problema específico que é a recuperação do conhecimento contido nos autos do Inquérito Policial de forma mais eficiente (PRODANOV; FREITAS, 2013).
Quanto aos objetivos da pesquisa, a classificamos como exploratória, considerando que acaba por realizar o mapeamento terminológico em um domínio específico formado por profissionais policiais, membros da Polícia Federal e cuja missão é investigar crimes relacionados ao abuso infantojuvenil.
A abordagem usada é qualitativa, tendo o autor uma atuação fundamental ao buscar conceitos teóricos e analisar as peças utilizadas como base para a extração de termos, sem preocupações estatísticas ou quantificação de dados analisados.
Por fim, cabe esclarecer que os procedimentos adotados durante o trabalho envolvem a pesquisa bibliográfica e documental.
O método utilizado, para atingir os objetivos propostos, iniciou-se com uma revisão bibliográfica que teve como finalidade buscar esclarecimentos sobre o trabalho da Polícia Federal na execução de sua função investigativa, quando atua na apuração de infrações penais e materializa todo o trabalho nos autos de Inquérito Policial. Esta etapa proporcionou a identificação do problema na recuperação do conhecimento gerado e armazenado, atualmente, com uso do sistema ePol.
Ainda utilizando o método de pesquisas bibliográficas, buscou-se situar a pesquisa no contexto da Ciência da Informação, mais especificamente nas áreas de estudo da Organização do Conhecimento e da Recuperação da Informação. Esta fase trouxe ainda, noções de Sistemas de Organização do Conhecimento e procedimentos para sua implementação, Sistemas de Recuperação Inteligente da Informação, bem como definições de termos como “informação” e “conhecimento”.
A análise da bibliografia deu suporte à formulação do método exposto a seguir, para realização de mapeamento terminológico objetivando propiciar melhorias na recuperação do conhecimento contido nos autos de inquéritos policiais federais.
Com base em Campos, Gomes e Motta (2004) e Shitaku et al. (2021) o método de desenvolvimento de cada fase do mapeamento terminológico está descrito a seguir.
1)	Planejamento:
a)	Delimitação do tema:
Autos de Inquérito Policial referentes às investigações de crimes relacionados a abusos sexuais infantojuvenil na Internet.
b)	Identificação do público ao qual o tesauro se destina:
Policiais Federais que atuam em investigações relacionadas a abuso sexual infantojuvenil em todo território nacional.
c)	Levantamento de fontes para a busca dos termos que constituirão o tesauro:
Dos Autos de Inquérito Policial, selecionamos 20 documentos. A escolha das peças foi por documentos produzidos por policiais no curso das investigações, endereçados ao Delegado que preside os autos e, geralmente, com uso de linguagem própria do domínio.
Os documentos selecionados apresentam uma variação quanto aos seus autores, evitando documentos produzidos por um único policial. Isso se faz necessário porque os Policiais Federais que participam das investigações possuem formação em diferentes áreas do saber e isso pode influenciar, de alguma forma, nos termos e estilo de escrita por eles utilizados na redação, o que pode trazer maior riqueza terminológica.
2)	Levantamento de vocabulário
a)	Coleta dos termos:
Foi realizada uma leitura qualitativa na íntegra das peças em busca de termos que pudessem refletir a linguagem do domínio. Foram coletados termos considerando todas as variações gramaticais e semânticas observadas.
b)	Organização dos conceitos:
Aos serem coletados, os termos foram agrupados primeiramente sendo inseridos em uma tabela, usando um editor de textos. A análise dos termos coletados permitiu a definição de categorias. Para definição dos conceitos, inicialmente foi realizada busca em normas legais, com objetivo de verificar se já existe uma definição deles, quando positivo, foi avaliado se ela se encaixa à realidade do domínio. Quando não foi encontrada uma definição na legislação, a busca continuou em obras de doutrina das ciências policiais, jurídicas e até mesmo da Tecnologia da Informação, conforme o caso. Também foi considerada a própria evidência semântica do discurso apresentado no texto analisado.
3)	Forma de apresentação:
Como explanado no item anterior, o levantamento inicial dos termos e sua categorização foram registrados em editor de texto simples, uma vez concluída essa etapa, os termos coletados foram revisados com o objetivo de confirmar sua adequação, realização de ajustes que sejam necessários e escolha dos termos descritores.
Pelo princípio da lógica, desdobrou-se (mas não se restringiu) relações conceituais hierárquicas e mapeamento de sinônimos entre os termos pertencentes a uma mesma categoria. Pelo mesmo princípio (mas não restringido), mapeou-se relações associativas entre termos de categorias diversas.
Os termos, conceitos e relações foram inseridos no sistema Thesa. Uma vez inseridas as relações conceituais, o sistema produziu automaticamente uma visualização em forma de mapa semântico, que se configura em um formato amigável para que outros policiais possam compreender o mapeamento semântico.
4)	Avaliação
Utilizando uma funcionalidade da ferramenta Thesa, foi criado um arquivo PDF com o conteúdo do tesauro, sendo esse arquivo então encaminhado para três policiais federais, objetivando que indicassem concordância ou eventuais discordâncias quanto às categorias, termos escolhidos como descritores, conceitos e relações encontrados. Por meio de conversas telefônicas, foram dadas explicações básicas sobre a pesquisa e sobre o conteúdo do arquivo.
';
        return $txt;
    }

    function sample02()
    {
        $txt = 'Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 22
Modelizando práticas para a
socialização de informações: a
construção de saberes no ensino
superior
Leilah Santiago Bufrem
Doutora em Ciências da Comunicação pela Universidade de São
Paulo, Pós-Doutora pela Universidad Autonoma
de Madrid. Professora Titular do Departamento de
Ciência e Gestão da Informação da Universidade
Federal do Paraná, Curitiba, Paraná, Brasil
Francisco Daniel de Oliveira Costa
Aluno do curso de Gestão da Informação da
Universidade Federal do Paraná. Bolsista de
Iniciação Científica (PIBIC/CNPq)
Rene Faustino Gabriel Junior
Bacharel em Biblioteconomia e Documentação
pela Pontifícia Universidade Católica do Paraná.
Mestrando do programa Ciência, Gestão e
Tecnologia da Informação na Universidade
Federal do Paraná-UFPR
José Simão de Paula Pinto
Doutor em Medicina pela Universidade Federal do
Paraná. Coordenador do Programa de pósgraduação em Ciência, Gestão e Tecnologia da
Informação . Professor Adjunto do Departamento
de Ciência e Gestão da Informação da
Universidade Federal do Paraná
Propõe uma metodologia para a criação de ambiente
integrado de monitoramento e gerenciamento de
publicações periódicas para composição de dados da Base
de Dados Referencial de Artigos de Periódicos em Ciência
da Informação (Brapci). Define e analisa variáveis
orientadoras da pesquisa e, por meio de um estudo
exploratório e mapeamento das demandas dos usuários,
realiza a testagem de um protótipo para a concretização
da proposta. Descreve o planejamento, construção,
implementação e validação do produto funcional do
sistema, utilizando a rede internet como plataforma de
aplicação.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 23
Palavras-chave: Bases de dados; Compartilhamento da
informação; Arquitetura da informação; Brapci.
Standardizing practices for socializing
information: the building of knowledge
in higher education
This paper proposes a methodology to create an
integrated environment monitoring for the management
and publishing of the Referential Data Base of Articles
from Information Science Periodicals (Brapci). Defines and
examines variables guiding the research and, through an
exploratory study and mapping of users demands, makes
the accomplishment of a prototype for implementing the
proposal. It describes the planning, construction,
development and validation of the functional system using
the Internet as a network application platform.
Keywords: Data Base; Information sharing; Information
architecture; Brapci.
Recebido em 01.04.2010 Aceito em 30.06.2010
1 Introdução
O formato e a rapidez no acesso às informações têm sido
influenciados pela Internet, fenômeno que transformou os modos de
produção e recuperação dos produtos do conhecimento. A plataforma
tecnológica da Word Wide Web tornou onipresente uma entidade antes
rara: o ambiente de informações compartilhadas, concretizando o que tem
sido denominado de site ou sítio.
No intuito de resgatar princípios voltados ao ideal da socialização da
informação, modelos de organização e pesquisas operacionais têm sido
desenvolvidos, resultando em expressões de esforços teórico-práticos em
áreas para as quais a abordagem transdisciplinar tem sido efetivamente
uma vantajosa estratégia. Uma dessas áreas é a Ciência da Informação
(CI), especialmente quando se reúnem vertentes advindas de projetos
voltados à produção, organização e utilização da informação científica
para a criação de conhecimentos.
A organização das modalidades mais adequadas voltadas a esses
propósitos é efetivada por um domínio hoje muito alentado, a arquitetura
da informação que, segundo Fox (2001), é a arte e a ciência de estruturar
e organizar sistemas para auxiliar as pessoas a alcançarem seus objetivos
na busca informacional. As etapas para o desenvolvimento da arquitetura
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 24
da informação, contudo, requerem a elaboração de uma estratégia que
abrange análise do conteúdo, testes com os usuários e opiniões
resultantes de entrevistas, frente às dificuldades de se organizar sites com
excesso de informações. Hylton (2000), em uma revisão de literatura
sobre o tema, destaca que a qualidade no uso de um site pode ser medida
pelo comportamento e satisfação do usuário no momento do acesso e no
resultado da busca pela informação esperada.
O trabalho de modelagem de informações atende à exigência de
torná-las relevantes e oportunas, requerendo estudo especializado. A
implantação desses modelos, por meio de informações acessíveis e
compartilhadas de modo universal, é um tipo específico de atividade
relativamente recente, que consiste tanto em aplicação científica de
saberes específicos quanto em atividade artística, um ato de arquitetura,
estruturando informações cruas em ambientes compartilhados de forma
útil, navegável e funcional às reais necessidades, de modo a resistir à
entropia e reduzir a confusão.
No Brasil, atualmente, alguns títulos de revistas científicas
encontram-se disponíveis para consulta em sites de acesso livre, mas
ainda são insuficientes por abrangerem apenas as revistas eletrônicas
e/ou serem disponibilizados on-line. Daí a necessidade sentida de se criar
uma base de dados referenciais de revistas nacionais da área de CI,
reunindo a literatura científica impressa e eletrônica a ela relacionada e
possibilitando estudos quantitativos e qualitativos sobre a produção
editorial da área.
Os estudos sobre revistas científicas na literatura recente, mais
especificamente nos dez últimos anos, embora com marcante presença
dos dados quantitativos como base empírica para reforçar argumentações,
revelam uma tendência à análise e interpretação de caráter qualitativo,
especialmente justificada pela complexidade de fatores intervenientes nas
atividades de produção e divulgação científicas.
Contribuições de Unger e Freire (2006) para este estudo partem de
uma visão da relevância da informação na sociedade contemporânea e do
propósito de organizar estruturas que alcancem os possíveis usuários.
Assim, profissionais da informação devem considerar para o
desenvolvimento de suas atividades: o contexto sócio-econômico-cultural
em que se inserem o agregado e seus estoques de informação e o grupo
de usuários que lhes interessa; um modelo de sistema de informação que
atenda às características desses usuários potenciais e uma linguagem
documentária, que melhor represente o conhecimento oculto nos estoques
de informação, de modo a diminuir as barreiras na comunicação entre o
sistema e seus usuários. Ao permear o processo de planejamento de uma
base de dados, a presença do usuário impõe-se para a definição dos
propósitos e como orientação para a avaliação dos processos e do produto
gerado. O corpus deve ser, portanto, representativo das necessidades
reais e potenciais da comunidade usuária.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 25
A Base de Dados Referencial de Artigos de Periódicos em Ciência da
Informação (Brapci1
) é o produto de informação do projeto de pesquisa
“Opções metodológicas em pesquisa: a contribuição da área da
informação para a produção de saberes no ensino superior”, cujo objetivo
tem sido subsidiar estudos e propostas na área de CI, fundamentando-se
em atividades planejadas institucionalmente. Com esse propósito, são
identificados os títulos de periódicos da área de CI e indexados seus
artigos, constituindo-se o corpus da base de dados referenciais.
Inicialmente ambientada no ProCite 5, a Base tem catalogado referências
e resumos de textos publicados em periódicos nacionais impressos e
eletrônicos da área de CI a partir de 1972.
Desde sua concepção até o desenvolvimento de uma versão
funcional, a Brapci está contribuindo para estudos analíticos e descritivos
sobre a produção editorial de uma área em desenvolvimento, ao subsidiar
com uma ferramenta dinâmica estudantes, professores e pesquisadores
não somente da área de CI, mas também de outras a ela relacionadas.
Além disso, integra pesquisadores e estudantes de graduação e pósgraduação na construção de saberes relacionados às áreas que
contribuem para o processo de pesquisa requerido para sua concretização.
O objetivo deste estudo é o desenvolvimento de um produto final,
visando a disponibilização dos dados da Brapci para acesso público,
utilizando-se a Internet como meio disseminador e ferramentas que
possibilitem sua manutenção e operacionalização. Para efetivação desse
propósito mais amplo, foram desmembrados objetivos específicos: o
planejamento do conteúdo textual do sistema, partindo dos resultados do
mapeamento e da pesquisa de campo; a classificação, separação e
hierarquização das sessões do sistema; a elaboração do projeto visual,
definindo-se tipologia, iconografia e padrões gráficos e de cores do
sistema; o estudo e definição das opções de linguagens e ferramentas
operacionais apropriadas para a execução do produto; o desenvolvimento
e programação do produto; a validação do produto, com a realização de
pesquisa de campo instrumentalizada por grupo de foco composto por
pesquisadores da área; e a realização, com base na validação, das
considerações finais e modificações no produto e metodologia
desenvolvidos. O ambiente específico de informações compartilhadas,
integrando usuários e pesquisadores, adquiriu caráter inovador ao resultar
em uma superfície ergonômica, acessível, usável e facilmente recuperável.
Seus processos foram observados e devidamente documentados. Para a
adaptação aos diferentes perfis de usuários de uma base com essas
características, o delineamento da máscara ou espelho que comportou os
itens e as interfaces pertinentes aos perfis foram objetos de constante
atualização, exatidão, conformação de estilo apropriado ao conteúdo e
adaptação aos usos.
Dessa forma, além de seu propósito pedagógico, como o estímulo à
investigação científica pelo grupo de estudantes de graduação e pós-

1 BASE REFERENCIAL DE ARTIGOS DE PERIÓDICOS EM CIÊNCIA DA INFORMAÇÃO. Curitiba:
UFPR, 2009. Disponível em: <www.brapci.ufpr.br>. Acesso em 30 out. 2009.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 26
graduação que compõem a equipe de pesquisa, ampliaram-se, com este
estudo, as possibilidades de análise e interpretação das informações
constantes em bases de dados com vistas à ampliação da confiabilidade
dos resultados de busca.
2 Referencial teórico
O vasto universo de saberes registrados, no qual se destacam as
revistas científicas, oferece perspectivas para a compreensão da história
da produção intelectual de áreas específicas, especialmente quando
analisado seu conteúdo sob uma visão diacrônica. A experiência enseja a
reflexão sobre conteúdos, categorias, linhas, enfoques e métodos
utilizados nas pesquisas (BUFREM, 2006).
Esse contexto e as repercussões dos avanços científicos sobre as
formas de produção do conhecimento são comentados por Ladrière (1978,
p. 10), para quem a ciência age sobre a realidade, transformando-a,
sobretudo, por meio da tecnologia, face visível de suas produções. Impõese, todavia, a tarefa crítica à prática no sentido de se procurarem formas
coerentes de intervenção na realidade, especialmente em programas de
formação acadêmica. Se a pesquisa pode ser considerada um meio de
conhecimento integrador de teoria e prática, o seu aperfeiçoamento é a
razão pela qual acadêmicos e profissionais vêm tomando consciência da
necessidade de ampliar a compreensão a respeito das possibilidades
teóricas e concretas ao seu alcance para avaliar e aperfeiçoar suas formas
de aquisição do saber.
Essa tem sido a razão pela qual pesquisadores vêm procurando
ampliar sua compreensão sobre as possibilidades e formatos de produção
e representação do texto científico. O estudo justifica-se, sobremodo, no
universo da produção do saber nas instituições de ensino superior, vasto e
estimulante em sua complexidade, graças aos desafios das matérias do
conhecimento que se desdobram em suas problemáticas e aos modos de
apreensão dessas matérias, como que a confirmar que ao saber científico
não se podem estabelecer fronteiras. Ao discutir a necessidade de
avaliação prévia e amadurecimento nas ideias pioneiras de
democratização na publicação do conhecimento científico e avanço na sua
aceitação, Mueller (2006) reconhece também o papel das editoras e das
elites de cada área, bem como as relações de poder e influência na
direção e velocidade do percurso das publicações eletrônicas de acesso
livre e sua incorporação ao sistema de comunicação científica, como
canais legítimos desse processo.
Ao definir as bases de dados como repositórios dos conhecimentos
consensuais gerados pela ciência moderna, Sayão (1996) as considera
constituintes da memória da ciência oficialmente aceita. Sua existência
justifica-se especialmente pela necessidade que os pesquisadores e
estudantes têm de informações sobre as fontes disponíveis para o
domínio, sempre relativo, da literatura de sua área e dos meios existentes
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 27
para difusão de seus próprios estudos. Além disso, a publicação científica
tornou-se, em seu processo histórico, um instrumento indispensável tanto
como meio de promoção acadêmica quanto como modalidade de
promoção e fortalecimento do ciclo criação, organização e difusão do
conhecimento. Por conseguinte, sua divulgação é um dos fatores que mais
influenciam a realização deste estudo.
É indiscutível a importância das bases de dados na realização de
pesquisas quantitativas e qualitativas, ao facilitarem a rápida localização e
análise da informação nelas contida. Além disso, a possibilidade da
avaliação feita pelos pares é processo indispensável, segundo Meadows
(1999), para que se obtenham o consenso dos pesquisadores e a
consequente credibilidade quanto aos resultados da pesquisa comunicada.
Com a crescente adoção das tecnologias de informação, pode-se
considerar uma base de dados uma fonte que, segundo Dias (2000),
indica aquelas obras de uso pontual e recorrente. Seu uso tem se tornado
cada vez mais comum devido às vantagens que oferece em relação aos
índices impressos, especialmente como instrumento de recuperação de
informação que permite realizar pesquisas complexas, o que se torna
inviável nos instrumentos impressos convencionais. Todas essas
facilidades representam uma grande economia de tempo para o usuário,
permitindo que uma pesquisa seja executada bem mais rapidamente com
o uso dos computadores (CENDÓN, 2000).
O trabalho de adaptação e aperfeiçoamento contínuo de uma base
requer a consideração de indicadores, tais como: os dispositivos de saída
dos dados, as informações disponibilizadas, a visualização da informação,
o retorno possível do usuário, o comportamento do sistema e os sistemas
de apoio à base. É precisamente na comunicação e na necessidade de que
ambas as partes utilizem os mesmos códigos que reside a chave do arco
de êxito ou fracasso da interação, segundo García López (2007).
Nesse processo, a visualização implica mais do que simplesmente o
ato de olhar, segundo concepção de García López (2007), a partir de
Dürsteler (2002), uma vez que é uma construção mental, próxima do
conhecimento e, portanto, uma apreensão intelectual. Se o entendimento
significa a contextualização, inclusão e interiorização de algo é um ato que
se modifica e modifica a estrutura nocional dos sujeitos. A qualidade de
uma base de dados é, portanto, fator de estímulo, conducente e
mobilizador desse entendimento, para o qual contribuem metáforas
visuais, mapas, muros de perspectivas ou árvores de representações
hierárquicas ou em rede, mapas de dispersão, lentes e browser, com os
focos que permitem.
Nenhuma dessas formas, entretanto, dispensa, quando se projeta a
qualidade na relação ser humano/máquina, a recorrência a técnicas de
transformação visual, como propõem Gutwin e Fedak (2004 apud GARCÍA
LÓPEZ, 2007).
Outras virtudes decorrentes desse propósito mais amplo referem-se
ao uso concreto propiciado pela modalidade de apresentação e atualização
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 28
dos dados. Nesse sentido, a arquitetura de uma base de dados é efetiva
para a integração entre técnicas e propósitos.
Ao argumentarem que “uma arquitetura bem elaborada pode
permitir uma interação mais rápida e fácil entre o usuário e a informação”,
Camargo e Vidotti (2006, p. 105) justificam que a arquitetura da
informação, de um modo geral, unifica os métodos de organização,
classificação e recuperação de informação com a exibição espacial da área
de arquitetura, utilizando-se de tecnologias de informação e comunicação
e, em especial, da Internet.
A arquitetura da informação envolve quatro elementos básicos, que
visam à criação de estruturas digitais, nas quais priorizam a organização
descritiva, temática, representacional, visual e navegacional de
informações (ROSENFELD; MORVILLE, 2002):
a-sistemas de organização, responsáveis pela estruturação e formas
de agrupamentos do conteúdo do site;
b-sistemas de rotulagem, responsáveis pela denominação do
conteúdo do grupo informacional, agindo na representação ou
identificação de conteúdos específicos;
c-sistemas de navegação, responsáveis pela apresentação de pontos
de referência ao usuário, por meio de barras de navegação e mapas do
site;
d-sistemas de busca, responsáveis pelo auxílio ao usuário nas
consultas, inclusive prever as buscas que o usuário pode fazer e o
conjunto de respostas possíveis.
Esse processo pode ser complementado pela usabilidade que,
segundo Nielsen (1993), não se trata de um requisito único na elaboração
de uma interface para usuários, visto que consiste em cinco atributos
básicos: facilidade de aprendizado (learnability); efetividade de uso
(efficiency); de fácil memorização (memorability); minimização de
possibilidades de erro (errors); e satisfação do usuário durante a
navegação (satisfaction). O único meio de se avaliar a qualidade da
usabilidade é através de testes com os reais usuários durante o processo
de navegação.
Ao tratar dos requisitos de usabilidade, Santos (2006) os considera
fatores responsáveis pela qualidade da interação do usuário com o
aplicativo. Tais conceitos são importantíssimos em aplicações Internet, já
que as mesmas estão "sempre disponíveis e em constante crescimento"
(BROWN, 2003, p. 188; YU, 2005, p. 220).
Essas e outras leituras evidenciaram a importância de um projeto de
arquitetura da informação voltado à disponibilização da Brapci na Web,
não só para contribuir com o processo de indexação, mas, principalmente,
com a qualidade do acesso pelo usuário à base referencial, de modo a
facilitar a recuperação e o entendimento da informação desejada.
As contribuições da literatura, sem dúvida, esclarecem aspectos da
prática concreta relacionada às fases de planejamento, construção,
atualização, padronização, disponibilização e utilização de bases de dados.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 29
Entretanto, por mais atuais e abrangentes que sejam seus ensinamentos,
indicam a complexidade das estruturas que suportam esse tipo de
realização.
3 Metodologia
A continuidade do levantamento bibliográfico é fundamental para
uma atualização permanente sobre as mais recentes aquisições desse
domínio. Foram realizados estudos e análises sobre softwares já
existentes de auxílio à construção de documentos científicos, citação
bibliográfica e recuperação de referências, como medida de
benchmarking.
Por meio da análise dos dados disponíveis na Brapci e do material
de pesquisa já produzido pela equipe do projeto, foram coletadas
informações sobre a base, suas vertentes e sua estrutura de
armazenamento para a análise dos requisitos funcionais relacionados à
base e às equipes de todo o projeto de pesquisa. O diagnóstico foi
complementado com discussões envolvendo os pesquisadores deste
projeto e professores dos cursos de graduação em Gestão da Informação
da UFPR.
O estudo exploratório foi fundamental para a definição das variáveis
orientadoras da pesquisa de campo e englobou as atividades relacionadas
à leitura e à observação das discussões.
A pesquisa de campo para mapeamento das demandas dos usuários
foi realizada a partir da lista de discussão da Ancib. Foram distribuídos
eletronicamente questionários para todos os membros da lista e
estabelecido prazo de um mês para retorno. Por ter sido utilizada uma
lista de discussão eletrônica não foi possível definir o universo dos
pesquisadores, mas no prazo estabelecido retornaram 47 questionários.
Para codificação e tabulação dos dados foi utilizada uma planilha do MSExcel como meio de identificar as necessidades informacionais dos
pesquisadores.
A análise e a interpretação dos dados fundamentaram-se na
literatura pertinente e suas considerações aliadas ao mapeamento das
necessidades, os resultados e as observações das leituras realizadas, que
serviram como base para a definição dos requisitos e indicadores de
qualidade do produto.
Com a coleta de dados concluída, iniciou-se a fase de modelagem do
produto, constituída pelo planejamento do conteúdo textual do sistema,
utilizando-se os resultados do mapeamento das análises e das definições
dos requisitos e indicadores levantados empiricamente. Foram então
classificadas, separadas e hierarquizadas as sessões do sistema. Na fase
de projeto, as abordagens sugeridas por Yu (2005) referem-se à
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 30
estrutura; ambiente de desenvolvimento; e ferramentas, tipos de
arquivos, linguagens e padrões.
Para elaboração do projeto visual definiram-se a tipologia, a
iconografia e os padrões gráficos e de cores do sistema por meio de
estudo da literatura e de casos similares. Da mesma forma, as opções de
linguagens e ferramentas operacionais apropriadas para a execução do
produto foram estudadas e definidas pela literatura, assim como as
limitações e configurações técnicas oferecidas pelo Centro de Computação
Eletrônica (CCE) da Universidade Federal do Paraná (UFPR). Da linguagem
escolhida para o produto e o sistema gerenciador de banco de dados
(SGBD) foram utilizados respectivamente um servidor Apache¹ com
suporte ao PHP 2 e o MySQL, por utilizarem licença livre. Analisaram-se os
fluxos de navegação possíveis, para uma avaliação qualitativa e de
usabilidade de cada fase do sistema.
O desenvolvimento e a implantação do produto foram realizados de
forma restrita ao grupo de foco selecionado com o objetivo de avaliá-lo,
constituído de pesquisadores bolsistas de Iniciação Científica e estudantes
de graduação e pós-graduação da UFPR. Procurou-se, por meio dessa
técnica, identificar a percepção do grupo quanto à utilização e aplicação
da Brapci. Dessa forma, foram previstas questões que permitiram aos
participantes do grupo, com auxílio de um moderador, comentar as
questões levantadas (KRUEGER; CASEY, 2000). O produto do sistema foi
desenvolvido pelo grupo, com suporte técnico de infraestrutura do CCE da
UFPR, para a realização das sessões. Realizou-se na sequência um estudo
qualitativo das informações para disponibilização irrestrita, livre e
universal da base on-line pela Internet.
Os processos de criação, manutenção e organização de produtos
voltados ao inventário das produções científicas têm sido uma
preocupação crescente da sociedade, proporcional ao crescimento dos
registros do conhecimento e ao consequente volume das informações.
Produtos, serviços e estruturas gigantescas são gerados com potencial de
armazenamento, gerenciamento e disponibilidade. Nesse cenário de
inovações, os profissionais da informação buscam especializações,
modelos e parâmetros de usabilidade e pertinência.
Entretanto, o simples desenvolvimento de um sistema gerenciador,
sem uma arquitetura das informações com indicadores de qualidade em
seu planejamento, pode resultar em um produto final inadequado. A
utilização de ferramentas que ofereçam estruturas lógicas e induzam à
respostas satisfatórias em relação aos objetivos do usuário salientam-se
como contraponto àquelas que não apresentam a mínima organização e
frustram as necessidades dos seus utilizadores (ROSENFELD; MORVILLE,
2002). O projeto adequado é importante, pois o ambiente proporciona ao
usuário interação com a informação. Na FIG. 1 vê-se que a informação
para interação é gerada a partir do conteúdo (1), gerando opções ao
usuário (2), que as seleciona (3), gerando uma busca (4) e entrega (5) do

¹ Servidor Apache, um software livre de servidor para web. Disponível em: <http://www.apache.org/>.
Acesso em: 30 out. 2009.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 31
conteúdo solicitado. Embora simples, o modelo define a interação
usuário/servidor por meio do ambiente web (interação, seleção e
entrega).
FIGURA 1 – Cenário para entrega de informações
Fonte: Adaptado de BROWN (2003, p. 24).
O estudo defende a utilização desses conceitos, agregados às
competências do Gestor da Informação, para a concepção e a implantação
de um sistema desde a criação do banco de dados, ambiente gráfico e
funcionalidades até a satisfação do usuário com os resultados obtidos no
acesso e utilização das funcionalidades oferecidas.
Para atingir o propósito mais amplo desta pesquisa, foram
estruturados os elementos com vistas a um modelo conceitual de um
produto funcional do sistema de gerenciamento e publicação dos dados da
Brapci. Visando sua concepção e implantação, utilizou-se na metodologia
a estrutura advinda da teoria de desenvolvimento de produtos segundo
Rozenfeld e colaboradores (2006), de acordo com a FIG. 2.
FIGURA 2 – Fases do desenvolvimento do produto
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 32
Fonte: Adaptado de ROZENFELD et. al (2006).
Para a concepção da Brapci, a partir dos resultados do mapeamento
e dados levantados na primeira fase do projeto, realizou-se o
planejamento do conteúdo textual e funcionalidades do sistema, por meio
de estudo exploratório para determinar perfis de usuários e fluxos de
trabalho na base. Foram utilizados os conceitos da arquitetura da
informação para definir a relevância de cada item, suas instâncias para
cada grupo de usuários, a estrutura da informação e a prioridade de
apresentação dessas informações. Desse modo, realizou-se a modelagem
de dados por meio de sua decomposição e da metodologia formal para
avaliação de um esquema relacional, conforme proposta de Silberschatz,
Korth e Sudarshan (2006). A interface do sistema proposto foi projetada
por meio da construção de wireframes. Todas as etapas do processo
foram acompanhadas paralelamente por discussões entre os integrantes
do Grupo de Pesquisa, o que favoreceu o aperfeiçoamento contínuo no
produto e na metodologia desenvolvidos.
A partir da análise do fluxo de trabalho, foram definidos os perfis de
usuários da base e mapeamento das funções do sistema (QUADRO 1).
Categorizadas as permissões de uso, elaborou-se um novo processo para
indexação e manutenção da Brapci com a possibilidade de ocorrerem
atividades simultâneas e on-line diretamente no sistema (FIG. 3).
 QUADRO 1 Atividades permitidas a cada perfil de usuário
Atividade Perfil autorizado a realizar
Indexar novo Periódico Coordenador
Indexar novas Edições Editor / Indexador
Indexar novos Artigos Todos
Realizar modificações nos
Periódicos
Coordenador
Realizar modificações nas
Edições
Editor
Realizar modificações nos
Artigos
Editor / Indexador
Excluir Periódicos Nenhum*
Excluir Edições Nenhum*
Excluir Artigos Editor
Autorizar/Suspender
Publicação da Edição
Coordenador / Editor
Gerar Relatórios Todos
Criar Usuários Coordenador / Editor
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 33
Criar perfil de Usuário Coordenador
Nota: (*) A exclusão de Periódicos e Edições só é permitida ao administrador do banco de dados da
Brapci para que não ocorra perda de integridade relacional dos dados.
Fonte: COSTA, 2009.
FIGURA 3 – Processo proposto para indexação e manutenção da BRAPCI
Fonte: COSTA, 2009.
Com a definição dos perfis e das atividades que cada usuário pode
realizar na Base, partiu-se para o processo de migração da base de dados
Brapci para uma nova modelagem de dados, demandando a decomposição
de uma tabela única (do ProCite5) para múltiplas tabelas, utilizando-se
um SGBD, por meio da metodologia proposta por Silberschatz, Korth e
Sudarshan (2006), pela qual todas as dependências funcionais devem
estar relacionadas exclusivamente à chave primária.
Nessa modelagem de dados, priorizou-se a utilização de várias
tabelas relacionais, partindo-se do Periódico (Revista) e desmembrandoas em outras tabelas, técnica que facilita a normatização dos registros,
pois é criado um padrão que consequentemente contribui para a qualidade
da base de dados. Buscou-se, com a modelização dos dados, uma
especificidade que possibilitasse a identificação da autoria, sua titulação e
instituição afiliada, tipo de seção publicada, edições, palavras-chave em
diversos idiomas e metodologias dos trabalhos. Essa modelização permitiu
a seleção de dados para análise de conteúdo, análise de produção por
autor, titulação e outras características, conforme as necessidades do
pesquisador (FIG. 4)
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 34
FIGURA 4 – Diagrama entidade-relacionamento do novo modelo de banco
de dados
Fonte: Os autores (2009).
Utilizando-se os conceitos de arquitetura da informação levantados e
definidos os requisitos da base de dados, foi elaborada a modelagem
conceitual de sua interface sob a forma de wireframes.
O desenvolvimento do produto físico foi acompanhado pela
coordenação do projeto e, após sua finalização e implantação, avaliado
pelos usuários. A detecção e correção de falhas e inovações, cujas
consequências são vitais para o aprimoramento da tecnologia empregada,
estão sendo realizadas para o aperfeiçoamento do novo sistema pelo
Grupo.
A nova estrutura possibilitou, ainda, a integração através do
protocolo Open Archives Iniciative Protocol for Metadata Harvesting (OAIPMH)3
 , utilizado pelo SEER com revistas e periódicos nacionais,
otimizando o processo de indexação e permitindo o aumento da
abrangência do corpus da Base por meio de coletas periódicas. Desse
modo, há a possibilidade de transferência desses conhecimentos para
outros domínios, respeitadas as especificações e peculiaridades que os
conformam.
Como mérito principal dessa realização, os resultados esperados
atingem o ideal universitário da integração ensino-pesquisa-extensão, em
um verdadeiro processo pedagógico de construção e socialização de
saberes.

2 OAI-PMH (Open Archives Initiative Protocol for Metadata Harvesting) é um protocolo desenvolvido
pela Open Archives Initiative, utilizado para distribuir e coletar metadados relativos a descritores de
documentos.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 35
4 Um retrato da Brapci
Desde a ideia inicial de reunir a literatura pertinente da área de CI
em um único local que facilitasse a busca e recuperação da informação
para pesquisadores, acadêmicos e a comunidade em geral já se passaram
mais de dez anos. O resultado final na criação de um produto que possa
ser acessado por todos em um ambiente virtual e coletivo é a
concretização dessa ideia.
Nos três primeiros anos da implantação do projeto (2000-2003),
foram levantados os títulos de treze periódicos da área e, paralelamente,
realizada busca no acervo da Biblioteca do Setor de Ciências Sociais
Aplicadas (BSCSA) da UFPR, onde foram verificados os periódicos do
acervo físico relacionados à área de CI. Os fascículos não encontrados
foram solicitados aos editores ou, após localização no Catálogo Coletivo
Nacional de Publicações Seriadas (CCN)4, a outras bibliotecas, para que
fossem preenchidos os dados de acordo com os campos definidos para a
sua representação na base que inicialmente era desenvolvida no ProCite3
.
Nos anos seguintes (2004-2008), foram realizadas análises
específicas sobre as características temáticas, metodológicas e formais da
literatura, obedecendo-se aos pressupostos iniciais e incorporação à base
das publicações que atendiam a esses critérios. Essa análise possibilitou a
ampliação significativa da quantidade de títulos selecionados; dos treze
iniciais para 27 títulos até 2008, registrando-se 4.637 artigos publicados
(BUFREM, 2008).
Em 2009, foram incorporados mais três títulos de publicações à
Brapci e implementado o mecanismo de coleta automática de registros,
utilizando-se o protocolo OAI-PMH de arquivos abertos. Isso possibilitou
um crescimento significativo da base em quase dois mil registros
adicionais, graças ao acesso em ambiente virtual5 de edições antes não
acessíveis, as quais foram identificadas e incorporadas por meio de trocas
de metadados.
Até outubro do ano de 2009, a Brapci reuniu trinta publicações
periódicas vigentes e históricas produzidas no Brasil, concentrando 6167
artigos em CI. As publicações disponíveis na base estão relacionadas no
QUADRO 2.

3 O Catálogo Coletivo Nacional de Publicações Seriadas (CCN), coordenado pelo IBICT, é uma rede cooperativa
de unidades de informação localizadas no Brasil com o objetivo de reunir, em um único Catálogo Nacional de
acesso público, as informações sobre publicações periódicas técnico científicas reunidas em centenas de
catálogos distribuídos nas diversas bibliotecas do país.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 36
QUADRO 2 Principais características e informações das revistas indexadas
Revista ISSN Publicação Artigos Disponib.
Arquivística.net 1808-4826 Vigente 56 E
Arquivo & Administração 0100-2244 Vigente 60 I
BIBLOS: Revista do Departamento de
Biblioteconomia e História
0102-4388 Vigente 349 I e
E
Cadernos de Biblioteconomia 0102-6607 Histórica 74 I
Ciência da Informação 0100-1965
1518-8353*
Vigente 1035 I&E
Comunicação & Informação 1415-5842 Vigente 135 I
DataGramaZero 1517-3801 Vigente 184 E
Em Questão: Revista da Faculdade de
Biblioteconomia e Comunicação da UFRGS
1807-8893
1808-5245*
Vigente 229 I
E
Encontros Bibli: Revista Eletrônica de
Biblioteconomia e Ciência da Informação
1518-2924 Vigente 267 E
Estudos Avançados em Biblioteconomia e Ciência
da Informação
0100-9869 Histórica 23 I
ETD - Educação Temática Digital 1517-2539
1676-2592
a partir
dez. 2005
Vigente 369 E
Inclusão Social 1808-8678 Vigente 64 E
Infociência 1415-0018 Vigente 13 I
Informação & Informação 1414-2139 Vigente 202 I&E
Informação & Sociedade: Estudos 0104-0146
1809-4783*
Vigente 452 E
Informare: Cadernos do Programa de PósGraduação em Ciência da Informação
0104-9461 Histórica 61
Liinc em revista 1808-3536 Vigente 66 E
Perspectivas em Ciência da Informação 1413-9936 Vigente 370 I
Revista ACB: Biblioteconomia em Santa Catarina 1414-0594 Vigente 399 I&E
Revista Brasileira de Biblioteconomia e
Documentação
0100-0691
1980-6949*
Vigente 284 I&E
Revista da Escola de Biblioteconomia da UFMG 0100-0829 Histórica. Mudou
nome
309 I
Revista de Biblioteconomia & Comunicação 0103-0361 Histórica. Mudou
nome
102 I
Revista de Biblioteconomia de Brasília 0100-7157 Vigente 562 I&E
Revista Digital de Biblioteconomia & Ciência da
Informação
1678-765X Vigente 147 E
Revista do Departamento de Biblioteconomia e
História
0101-045X Histórica. Mudou
nome
9 I
Revista Eletrônica Informação e Cognição 1807-8281 Vigente 50 E
Revista On-line da Biblioteca Prof. Joel Martins 1517-3992 Histórica. Mudou
nome
59 E
Revista Ponto de Acesso 1981-6766 Vigente 68 I&E
Tendências da Pesquisa Brasileira em Ciência da
Informação
1983-5116 Vigente 17 E
Transinformação 0103-3786 Vigente 352 I&E
 6467
Nota: (*) ISSN Eletrônico.
Fonte: Os autores (2009).
O crescimento da base tem se desenvolvido com o monitoramento
das publicações e incorporações de novas edições pelos indexadores. Dos
6467 artigos disponibilizados na base, 70% concentram-se em dez
revistas (QUADRO 2). São vigentes vinte e três das revistas e sete delas
estão na Base pelo valor histórico e as contribuições para a área.
O Gráfico 8 demonstra a produção nacional na área de CI pela
quantidade de artigos publicados referentes ao ano de produção. Observa-
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 37
se um crescimento significativo a partir de 1996 e outro em 2006 e, esse
último, mantendo-se estável em 2007 e 2008. O ano base de 2009
registra uma produção de 262 artigos publicados, valor que não
representa ainda um decréscimo das publicações, principalmente pelas
dificuldades de manutenção da periodicidade das revistas.
GRÁFICO 1 – INSERÇÃO DE ARTIGOS NA BASE DE DADOS POR ANO DE
PUBLICAÇÃO
Fonte: Os autores (2009).
Com a disponibilização, em abril de 2008, de um módulo público
com um mecanismo de busca simplificado foi possível abrir à comunidade
acadêmica a consulta à Base. Considerando-se que toda a
disponibilização, para que possa ser aperfeiçoada, necessita de uma
monitoração e de um retorno de seus usuários com críticas e sugestões,
todas as consultas realizadas na base foram registradas, incrementandose um log para posterior análise. Essas etapas reforçam a mais
importante das responsabilidades em um website, a gestão do conteúdo
(BROWN, 2003).
É possível observar a adequação da estrutura criada ao disposto na
literatura de apoio, de modo especial em relação aos elementos básicos
para a criação de estruturas digitais, nas quais são priorizadas a
organização descritiva, temática, representacional, visual e navegacional
de informações (ROSENFELD; MORVILLE, 2002). Esses elementos se
referem: aos sistemas de organização, responsáveis pela estruturação e
formas de agrupamentos do conteúdo do site; aos processos de
rotulagem, responsáveis pela denominação do conteúdo do grupo
informacional, agindo na representação ou identificação de conteúdos
específicos; e aos sistemas de navegação, responsáveis pela apresentação
de pontos de referência ao usuário, por meio de barras de navegação e
mapas do site aos sistemas de busca, responsáveis pelo auxílio ao usuário
nas consultas.
Resta salientar que cada um dos elementos do sistema tem sido
alvo de estudos específicos e considerados parcialmente para maior
aprofundamento das especificações. Entretanto, com este relato,
pretendeu-se oferecer aos pesquisadores um retrato do que até aqui se
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 38
construiu e dos marcos constitutivos da trajetória da pesquisa mais ampla
que tem se construído desde a fase de planejamento da Brapci.
5 Considerações
A base de dados Brapci, em menos de dois anos de acesso público
pela Internet, vem se consolidando como importante fonte de informação
na área de CI, disponibilizando trinta publicações vigentes e históricas.
Seleciona e reúne grande parte da literatura pertinente e relevante da
especialidade, com critérios de inserção para indexação do corpus a ser
incorporado.
A construção da metodologia Brapci foi iniciada com um estudo
exploratório que possibilitou a coleta de informações para o diagnóstico
detalhado das necessidades relacionadas à implantação de um ambiente
que possibilitasse a construção e manutenção de uma base de dados
temática. Discussões dentro do grupo de pesquisa contribuíram e foram
fundamentais para o diagnóstico e a posterior validação da metodologia
de construção da base, como referência para a área de CI.
A metodologia definiu variáveis orientadoras em pesquisa de campo
por meio de estudo exploratório com o universo de pesquisadores
participantes da lista de discussão da Associação Nacional de Pesquisa e
Pós-Graduação em Ciência da Informação (Ancib), possibilitando a
definição de algumas demandas dos pesquisadores.
Com a identificação dos requisitos para o sistema, foi possível a
concepção do modelo a ser utilizado na metodologia de construção da
base de dados. Todas as etapas do processo foram acompanhadas
paralelamente por discussões entre os integrantes do Grupo de Pesquisa,
com indicações de pontos vulneráveis e contribuições, o que favoreceu as
modificações no produto e na metodologia.
Em sua segunda fase, o projeto construiu um produto funcional do
sistema, partindo dos requisitos identificados na pesquisa de campo da
fase anterior com planejamento do conteúdo textual, classificação e
hierarquização das sessões, elaboração do projeto visual bem como a
tipologia, iconografia e padronização gráfica a ser utilizada no sistema.
Das opções de linguagens e ferramentas operacionais apropriadas
para a execução do produto foram adotados dois critérios, o acesso livre e
a adequação à infraestrutura e diretrizes da UFPR.
 Com o produto implantado, foi realizada sua validação com uma
pesquisa de campo instrumentalizada dentro do grupo de foco composto
por pesquisadores da área e indexadores da base, cujo propósito foi um
estudo comparativo da metodologia apresentada em contrapartida à
utilizada anteriormente no ProCite 5. O produto demonstrou-se, em sua
interface, mais amigável ao usuário indexador e a implantação em um
ambiente virtual solucionou uma das principais barreiras da metodologia
anterior, a qual não possibilitava manutenção remota nem tampouco uso
simultâneo de mais de um usuário, podendo apenas um indexador por vez
trabalhar na base.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 39
Com a indexação dos artigos em execução foi possível sua
disponibilização para consulta à base de dados pela Internet. Essa
disponibilização das fontes de informações referenciais mostrou fragilidade
no mecanismo de busca, pois, quando se utilizava a lógica booleana, os
usuários reportavam dificuldade na recuperação das informações,
principalmente pela impossibilidade do mecanismo recuperar termos
compostos. Com base no retorno dos usuários, o grupo de estudo está
implantando um novo mecanismo de busca que inclua essa opção, além
da recuperação por autores, palavras-chave e títulos de forma simplificada
ou avançada.
Uma facilidade da nova metodologia é o monitoramento periódico de
novas edições das publicações com a utilização do protocolo de arquivos
abertos OAI-PMH, que possibilita a coleta de novos trabalhos publicados,
indicando aos indexadores novos artigos. Assim, dispensam-se as visitas
periódicas aos sites das publicações. De todas as revistas vigentes
somente uma não utiliza esse protocolo para disponibilização de seu
conteúdo.
Outro problema constatado, quando da disponibilização on-line, foi a
volatilidade dos links que apontavam para o conteúdo completo em
algumas fontes de informação. Como o processo de indexação é realizado
apenas uma vez, esses links podem ficar desatualizados, principalmente
quando a mantenedora da publicação sofre migração de versões dos
sistemas ou pela simples descontinuidade da publicação. O grande
número de “links quebrados” requer dos indexadores constante
acompanhamento e monitoramento dos retornos dos usuários que
possibilite a correção dos endereços. A indicação do link incorreto
impossibilita ao pesquisador a recuperação direta da fonte, reduzindo a
precisão das informações.
Uma proposta de aperfeiçoamento para a metodologia da Brapci,
agregando a ela as condições de repositório, seria a incorporação dos
artigos completos com a indicação e a manutenção do link da fonte
original. A viabilidade dessa proposta provém, principalmente, do fato de
que todas as publicações vigentes utilizam a política de arquivos abertos e
disponibilização do conteúdo na íntegra, sem restrições. Entretanto, para
que o mesmo possa ocorrer em relação às publicações históricas
(descontinuadas), serão necessárias medidas relacionadas aos direitos
autorais.
Todos os procedimentos aqui relatados foram planejados e
executados em processo coletivo de aprendizagem, em atividades
promovidas pelo Grupo de Pesquisa Educação, Pesquisa e Perfil
Profissional em Informação (E3PI), com envolvimento dos professores e
estudantes de graduação e pós-graduação. Reforça-se, desse modo, o
caráter pedagógico e o valor do projeto, assim como de seus
desdobramentos em atividades de extensão, com propósitos de
manutenção, estudos e aperfeiçoamento da Brapci.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 40
 Referências
BROWN, P. Information architecture with XML: a management strategy.
West Sussex: John Wiley, 2003.
BUFREM, L. S. Práticas de organização e divulgação da produção
intelectual em ciência da informação no Brasil. Encontros Bibli (UFSC), v.
esp., p. 36-53, 2008.
______. Revistas científicas: características, funções e critérios de
qualidade. In: POBLACIÓN, D. A.; WITTER, G. P.; SILVA, J. F. M. (Orgs.).
Comunicação e produção científica: contexto, indicadores e avaliação. São
Paulo: Angellara, 2006. p. 191-214.
CAMARGO, L. S. A.; VIDOTTI, S. A. B. G. Arquitetura da informação para
biblioteca digital personalizável. Encontros Bibli: Revista Eletrônica de
Biblioteconomia e Ciência da Informação, Florianópolis, v. 11, n. esp., p.
103-118, 2006.
CENDÓN, B. V. Serviços de indexação e resumo. In: CAMPELLO, B. S.;
CENDÓN, B. V.; KREMER, J. M. (Orgs.) Fontes de informação para
pesquisadores e profissionais. Belo Horizonte: UFMG, 2000. p. 217-248.
COSTA, F. D. O. Concepção e implantação de protótipo para
gerenciamento e disponibilização da Brapci na web. In: EVENTO
NACIONAL DE INICIAÇÃO CIENTÍFICA, 17. Anais... Curitiba: UFPR, 2009.
DIAS, E. W. Obras de referência. In: CAMPELLO, B. S.; CENDÓN, B. V.;
KREMER, J. M. (Orgs.). Fontes de informação para pesquisadores e
profissionais. Belo Horizonte: UFMG, 2000. p. 199-216.
DÜRSTELER, J. Information visualization, what is it all about? Inf@Vis! The
digital magazine of InfoVis.net, n. 100, 2002. Disponível em:
<http://www.infovis.net/printMag.php?num=100&lang=2>. Acesso em:
22 ago. 2006.
FOX, C. Making IA real: an overview of an Information Architecture
Strategy. In: THE INTERNET CONFERENCE & EXHIBITION FOR
LIBRARIANS & INFORMATION MANAGERS, Pasadena, CA, Nov 6th 2001.
Proceedings… Internet Librarian, 2001. Disponível em:
<http://www.onlineinc.com/il2001/tuesday.htm>. Acesso em: 25 set.
2007.
GARCÍA LÓPEZ, G. L. Los sistemas automatizados de acceso a la
información bibliográfica: evaluación y tendencias en la era de Internet.
Salamanca: Ediciones Universidad de Salamanca, 2007.
GUTWIN, C.; FEDAK, C. Interacting with big interfaces on small screens: a
comparison of fisheye, zoom, and panning techniques. Graphics Interface,
London, Ontario, n. 49, p. 145-152, 2004
HYLTON, J. Usable information designs. D-Lib Magazine, Reston, Virginia,
v. 6, n. 6, jun. 2000. Disponível em:
<http://www.dlib.org/dlib/june00/06bookreview.html>. Acesso em: 26 set. 2007.
Modelizando práticas para a socialização de informações: a
construção de saberes no ensino superior
Leilah Santiago Bufrem; Francisco Daniel de
Oliveira Costa; René Faustino Gabriel Junior;
José Simão de Paula Pinto
Perspectivas em Ciência da Informação, v.15, n.2, p.22-41, maio./ago. 2010 41
KRUEGER, R. A.; CASEY, M. A. Focus groups: a practical guide for applied
research. 3. ed. Thousand Oaks: Sage, 2000.
LADRIÈRE, J. Filosofia e práxis cientifica. Rio de Janeiro: F. Alves, 1978.
MEADOWS, A. J. A comunicação científica. Brasília: Briquet de Lemos,
1999.
MUELLER, S. P. M. A comunicação científica e o movimento de acesso livre
ao conhecimento. Ciência da Informação, Brasília, v. 35, n. 2, p. 27-38,
2006.
NIELSEN, J. Usability engineering. San Diego: Morgan Kaufmann, 1993.
ROSENFELD, L.; MORVILLE, P. Information architecture for the World
Wide Web. 2. ed. Sebastopol, CA: O´Reilly, 2002.
ROZENFELD, H. et al. Gestão de desenvolvimento de produtos: uma
referência para a melhoria do processo. São Paulo: Saraiva, 2006.
SANTOS, R. L. G. Usabilidade de interfaces para sistemas de recuperação
de informação na web: estudo de caso de bibliotecas on-line de
universidades federais brasileiras. 2006. Tese (Doutorado em Design) -
Programa de Pós-graduação em Design, Pontifícia Universidade Católica
do Rio de Janeiro, Rio de Janeiro, 2006.
SAYÃO, L. F. Bases de dados: a metáfora da memória científica. Ciência
da Informação, Brasília, v. 25, n. 3, p. 314-318, 1996.
SILBERSCHATZ, A., KORTH, H. F., SUDARSHAN S. Sistema de Banco de
Dados. Rio de Janeiro: Campus, 2006.
UNGER, R. J. G.; FREIRE, I. M. Sistemas de informação e linguagens
documentárias no contexto dos regimes de informação: um exercício
conceitual. Revista Digital de Biblioteconomia & Ciência da Informação,
Campinas, v. 4, n. 1, p. 102-115, jul./dez. 2006.
YU, H. Content and workflow management for library websites: case
studies. California, USA: Information Science Publishing, 2005. p. 26-27. ';
        return $txt;
    }
}
