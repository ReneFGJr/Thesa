import { Component } from '@angular/core';
import { environment } from '../../../../environments/environment';
import { LanguageService, Language } from '../../../000_core/service/language.service';

const sections = ['intro', 'glossario', 'endpoints', 'exportacao', 'examples', 'errors', 'api'] as const;

const copy: Record<Language, Record<string, string>> = {
  'pt-br': {
    intro: 'Introdução', glossario: 'Glossário', endpoints: 'Endpoints', exportacao: 'Exportação', examples: 'Exemplos de uso', errors: 'Tratamento de erros', api: 'Uso da API',
    welcome: 'Bem-vindo à API do Thesa. Esta documentação apresenta os principais recursos.',
    glossaryTitle: 'Glossário do Thesa', concept: 'Conceito', term: 'Termo', microthesaurus: 'Microtesauro', thesaurus: 'Tesauro',
    conceptText: 'Um conceito é uma ideia ou representação mental que expressa o significado de algo. É abstrato e independente da linguagem. Na organização do conhecimento, conceitos dão sentido aos termos e estruturam relações em tesauros, ontologias e classificações.',
    termText: 'Um termo é uma palavra ou expressão que representa um conceito em determinado contexto. Os termos podem variar conforme o idioma, a cultura e o contexto. Em sistemas de organização do conhecimento, eles são associados a conceitos para tornar a informação acessível e compreensível.',
    microthesaurusText: 'Um microtesauro é uma parte de um tesauro dedicada a um domínio ou tema específico.',
    thesaurusText: 'Um tesauro é um vocabulário organizado de conceitos e termos, com relações que apoiam a descrição e a recuperação da informação.',
    exportTitle: 'Exportação pela API', exportIntro: 'Use GET /api/export/:id/:formato para baixar um tesauro inteiro ou um conceito em um dos formatos abaixo.',
    format: 'Formato', file: 'Arquivo', whichId: 'Qual ID usar',
    exportThesaurus: 'Para exportar toda a base, informe o ID do tesauro. Por exemplo, /api/export/6/xml exporta o tesauro 6.',
    exportConcept: 'Para exportar apenas um conceito, informe o ID do conceito e acrescente ?scope=concept. Por exemplo, /api/export/366/xml?scope=concept. Esse parâmetro é necessário quando o mesmo número identifica um conceito e um tesauro. Sem scope, o ID do tesauro tem prioridade.',
    privateAccess: 'Acesso a tesauros não públicos',
    privateAccessText: 'Envie a APIKEY de um proprietário ou colaborador em ?apikey=SUA_APIKEY ou no cabeçalho X-API-Key. Para um conceito privado, combine ?scope=concept&apikey=SUA_APIKEY. Tesauros públicos dispensam a chave. Evite compartilhar links com APIKEY, pois URLs podem aparecer no histórico e em logs.',
    exportExamples: 'Exemplos', exportStatuses: 'A API retorna 200 no download, 403 quando não há autorização e 404 para um ID inexistente.',
    errorText: 'Erros seguem o formato padrão HTTP com mensagens descritivas.',
    publicApi: 'APIs abertas', exportApi: 'API de exportação', maintenanceApi: 'API de manutenção do tesauro', statisticsApi: 'API de estatísticas', method: 'Método', description: 'Descrição',
    listOpen: 'Lista todos os tesauros públicos.', thDetails: 'Dados detalhados de um tesauro.', thConcepts: 'Conceitos do tesauro.', termDetails: 'Termo dentro dos tesauros.',
    exportDetails: 'Baixa o tesauro completo ou um conceito em RDF/XML, Turtle, JSON-LD ou TXT.', viewExport: 'Ver formatos, parâmetros e exemplos',
    apiKey: ':apikey — chave de API do usuário', thId: ':thesaID — ID do tesauro', thesaurusId: ':thesaurus — ID do tesauro', pathTh: ':th — ID do tesauro', pathTerm: ':idT — ID do termo', pathConcept: ':idC — ID do conceito', idTh: ':idTh — ID do tesauro', idC: ':idC — ID do conceito',
    optionalKey: ':apikey (opcional) — retorna editMode (true ou false)', titleParam: ':title — título do instrumento de controle de vocabulário', acronic: ':acronic — sigla do instrumento', typeParam: ':type — tipo do instrumento',
    typeOptions: '1: Vocabulário controlado / Glossário; 2: Tesauro tradicional (TG/TE/TR); 3: Tesauro SKOS/semântico; 4: Controle de autoridade',
    visibilityParam: ':visibility — visibilidade (1: restrito; 2: público)', finality: ':finality — finalidade (1: testar a ferramenta; 2: ensino/pesquisa; 3: produção de tesauro)',
    termsParam: ':terms — IDs dos termos a relacionar', verb: ':verb — tipo de relação (altLabel: termo equivalente; hiddenLabel: termo oculto)',
    field: ':field — campo da descrição (title, achronic, introduction, Methodology, Audience, License)', descriptionParam: ':description — conteúdo da descrição', property: ':property — tipo de vínculo (broader, related)', c1: ':c1 — conceito principal', c2: ':c2 — conceito secundário',
    noteId: ':noteID (opcional) — ID da nota a alterar', noteRecord: 'noteID — ID do registro da nota', conceptId: ':conceptID — ID do conceito', note: ':note — conteúdo da nota', noteType: ':noteType — tipo da nota', noteLang: ':lang — idioma da nota (por, eng, spn, fra, ger, ...)',
    getNote: 'Recupera os dados de uma nota.', health: 'Saúde do sistema.', resume: 'Dados resumidos de todos os tesauros.',
  },
  en: {
    intro: 'Introduction', glossario: 'Glossary', endpoints: 'Endpoints', exportacao: 'Export', examples: 'Usage examples', errors: 'Error handling', api: 'Using the API',
    welcome: 'Welcome to the Thesa API. This documentation covers its main features.',
    glossaryTitle: 'Thesa glossary', concept: 'Concept', term: 'Term', microthesaurus: 'Microthesaurus', thesaurus: 'Thesaurus',
    conceptText: 'A concept is an idea or mental representation that expresses the meaning of something. It is abstract and independent of language. In knowledge organization, concepts give meaning to terms and structure relationships in thesauri, ontologies, and classifications.',
    termText: 'A term is a word or expression that represents a concept in a given context. Terms can vary by language, culture, and context. In knowledge organization systems, they are associated with concepts to make information accessible and understandable.',
    microthesaurusText: 'A microthesaurus is a part of a thesaurus devoted to a specific domain or topic.',
    thesaurusText: 'A thesaurus is an organized vocabulary of concepts and terms, with relationships that support information description and retrieval.',
    exportTitle: 'Exporting through the API', exportIntro: 'Use GET /api/export/:id/:formato to download a complete thesaurus or a concept in one of the formats below.',
    format: 'Format', file: 'File', whichId: 'Which ID to use',
    exportThesaurus: 'To export the entire database, provide the thesaurus ID. For example, /api/export/6/xml exports thesaurus 6.',
    exportConcept: 'To export one concept, provide its ID and add ?scope=concept. For example, /api/export/366/xml?scope=concept. This parameter is needed when the same number identifies a concept and a thesaurus. Without scope, the thesaurus ID takes precedence.',
    privateAccess: 'Access to nonpublic thesauri',
    privateAccessText: 'Send an owner or collaborator APIKEY as ?apikey=YOUR_APIKEY or in the X-API-Key header. For a private concept, combine ?scope=concept&apikey=YOUR_APIKEY. Public thesauri do not require a key. Avoid sharing links with an APIKEY because URLs may appear in browser history and logs.',
    exportExamples: 'Examples', exportStatuses: 'The API returns 200 for a download, 403 when access is denied, and 404 for an unknown ID.',
    errorText: 'Errors use standard HTTP status codes with descriptive messages.',
    publicApi: 'Public APIs', exportApi: 'Export API', maintenanceApi: 'Thesaurus maintenance API', statisticsApi: 'Statistics API', method: 'Method', description: 'Description',
    listOpen: 'Lists all public thesauri.', thDetails: 'Detailed data for a thesaurus.', thConcepts: 'Concepts in a thesaurus.', termDetails: 'Term in the thesauri.',
    exportDetails: 'Downloads a full thesaurus or a concept as RDF/XML, Turtle, JSON-LD, or TXT.', viewExport: 'View formats, parameters, and examples',
    apiKey: ':apikey — user API key', thId: ':thesaID — thesaurus ID', thesaurusId: ':thesaurus — thesaurus ID', pathTh: ':th — thesaurus ID', pathTerm: ':idT — term ID', pathConcept: ':idC — concept ID', idTh: ':idTh — thesaurus ID', idC: ':idC — concept ID',
    optionalKey: ':apikey (optional) — returns editMode (true or false)', titleParam: ':title — vocabulary title', acronic: ':acronic — vocabulary abbreviation', typeParam: ':type — vocabulary type',
    typeOptions: '1: Controlled vocabulary / Glossary; 2: Traditional thesaurus (BT/NT/RT); 3: SKOS/semantic thesaurus; 4: Authority control',
    visibilityParam: ':visibility — visibility (1: restricted; 2: public)', finality: ':finality — purpose (1: test the tool; 2: teaching/research; 3: thesaurus production)',
    termsParam: ':terms — IDs of the terms to relate', verb: ':verb — relation type (altLabel: alternative term; hiddenLabel: hidden term)',
    field: ':field — description field (title, achronic, introduction, Methodology, Audience, License)', descriptionParam: ':description — description content', property: ':property — relation type (broader, related)', c1: ':c1 — primary concept', c2: ':c2 — secondary concept',
    noteId: ':noteID (optional) — ID of the note to update', noteRecord: 'noteID — note record ID', conceptId: ':conceptID — concept ID', note: ':note — note content', noteType: ':noteType — note type', noteLang: ':lang — note language (por, eng, spn, fra, ger, ...)',
    getNote: 'Retrieves note data.', health: 'System health.', resume: 'Summary data for all thesauri.',
  },
  es: {
    intro: 'Introducción', glossario: 'Glosario', endpoints: 'Endpoints', exportacao: 'Exportación', examples: 'Ejemplos de uso', errors: 'Gestión de errores', api: 'Uso de la API',
    welcome: 'Bienvenido a la API de Thesa. Esta documentación presenta sus principales funciones.',
    glossaryTitle: 'Glosario de Thesa', concept: 'Concepto', term: 'Término', microthesaurus: 'Microtesauro', thesaurus: 'Tesauro',
    conceptText: 'Un concepto es una idea o representación mental que expresa el significado de algo. Es abstracto e independiente del idioma. En la organización del conocimiento, los conceptos dan sentido a los términos y estructuran relaciones en tesauros, ontologías y clasificaciones.',
    termText: 'Un término es una palabra o expresión que representa un concepto en un contexto determinado. Los términos pueden variar según el idioma, la cultura y el contexto. En los sistemas de organización del conocimiento se asocian con conceptos para hacer la información accesible y comprensible.',
    microthesaurusText: 'Un microtesauro es una parte de un tesauro dedicada a un dominio o tema específico.',
    thesaurusText: 'Un tesauro es un vocabulario organizado de conceptos y términos, con relaciones que facilitan la descripción y recuperación de la información.',
    exportTitle: 'Exportación mediante la API', exportIntro: 'Use GET /api/export/:id/:formato para descargar un tesauro completo o un concepto en uno de los formatos siguientes.',
    format: 'Formato', file: 'Archivo', whichId: 'Qué ID utilizar',
    exportThesaurus: 'Para exportar toda la base, indique el ID del tesauro. Por ejemplo, /api/export/6/xml exporta el tesauro 6.',
    exportConcept: 'Para exportar un solo concepto, indique su ID y añada ?scope=concept. Por ejemplo, /api/export/366/xml?scope=concept. Este parámetro es necesario cuando el mismo número identifica un concepto y un tesauro. Sin scope, tiene prioridad el ID del tesauro.',
    privateAccess: 'Acceso a tesauros no públicos',
    privateAccessText: 'Envíe la APIKEY de un propietario o colaborador como ?apikey=SU_APIKEY o en la cabecera X-API-Key. Para un concepto privado, combine ?scope=concept&apikey=SU_APIKEY. Los tesauros públicos no requieren clave. Evite compartir enlaces con APIKEY porque las URL pueden aparecer en el historial y los registros.',
    exportExamples: 'Ejemplos', exportStatuses: 'La API devuelve 200 en una descarga, 403 cuando se deniega el acceso y 404 para un ID inexistente.',
    errorText: 'Los errores usan códigos HTTP estándar con mensajes descriptivos.',
    publicApi: 'API públicas', exportApi: 'API de exportación', maintenanceApi: 'API de mantenimiento del tesauro', statisticsApi: 'API de estadísticas', method: 'Método', description: 'Descripción',
    listOpen: 'Lista todos los tesauros públicos.', thDetails: 'Datos detallados de un tesauro.', thConcepts: 'Conceptos del tesauro.', termDetails: 'Término de los tesauros.',
    exportDetails: 'Descarga un tesauro completo o un concepto en RDF/XML, Turtle, JSON-LD o TXT.', viewExport: 'Ver formatos, parámetros y ejemplos',
    apiKey: ':apikey — clave API del usuario', thId: ':thesaID — ID del tesauro', thesaurusId: ':thesaurus — ID del tesauro', pathTh: ':th — ID del tesauro', pathTerm: ':idT — ID del término', pathConcept: ':idC — ID del concepto', idTh: ':idTh — ID del tesauro', idC: ':idC — ID del concepto',
    optionalKey: ':apikey (opcional) — devuelve editMode (true o false)', titleParam: ':title — título del vocabulario', acronic: ':acronic — abreviatura del vocabulario', typeParam: ':type — tipo de vocabulario',
    typeOptions: '1: Vocabulario controlado / Glosario; 2: Tesauro tradicional (TG/TE/TR); 3: Tesauro SKOS/semántico; 4: Control de autoridades',
    visibilityParam: ':visibility — visibilidad (1: restringido; 2: público)', finality: ':finality — finalidad (1: probar la herramienta; 2: docencia/investigación; 3: producción de tesauros)',
    termsParam: ':terms — IDs de los términos que se van a relacionar', verb: ':verb — tipo de relación (altLabel: término alternativo; hiddenLabel: término oculto)',
    field: ':field — campo de la descripción (title, achronic, introduction, Methodology, Audience, License)', descriptionParam: ':description — contenido de la descripción', property: ':property — tipo de relación (broader, related)', c1: ':c1 — concepto principal', c2: ':c2 — concepto secundario',
    noteId: ':noteID (opcional) — ID de la nota que se va a modificar', noteRecord: 'noteID — ID del registro de la nota', conceptId: ':conceptID — ID del concepto', note: ':note — contenido de la nota', noteType: ':noteType — tipo de nota', noteLang: ':lang — idioma de la nota (por, eng, spn, fra, ger, ...)',
    getNote: 'Recupera los datos de una nota.', health: 'Estado del sistema.', resume: 'Datos resumidos de todos los tesauros.',
  },
};

@Component({ selector: 'app-doc-api', templateUrl: './api.component.html', styleUrl: './api.component.scss', standalone: false })
export class ApiDocComponent {
  constructor(public readonly language: LanguageService) {}

  url = 'https://www.ufrgs.br/thesa/v2/index.php/api';
  exportApiUrl = environment.apiUrl;
  exportFormats = [
    { label: 'RDF/XML', path: 'xml', mime: 'application/rdf+xml', extension: '.xml' },
    { label: 'Turtle', path: 'turtle', mime: 'text/turtle', extension: '.ttl' },
    { label: 'JSON-LD', path: 'json', mime: 'application/ld+json', extension: '.jsonld' },
    { label: 'TXT', path: 'txt', mime: 'text/plain', extension: '.txt' },
  ];
  glossary = [
    { title: 'concept', text: 'conceptText' }, { title: 'term', text: 'termText' },
    { title: 'microthesaurus', text: 'microthesaurusText' }, { title: 'thesaurus', text: 'thesaurusText' },
  ];
  apiGroups = [
    { title: 'publicApi', items: [
      { path: 'thopen', method: 'GET', text: 'listOpen', params: [] },
      { path: 'th/:th', method: 'GET', text: 'thDetails', params: ['pathTh'] },
      { path: 'terms/:th', method: 'GET', text: 'thConcepts', params: ['pathTh', 'optionalKey'] },
      { path: 't/:idT', method: 'GET', text: 'termDetails', params: ['pathTerm'] },
      { path: 'c/:idC', method: 'GET', text: 'thConcepts', params: ['pathConcept'] },
    ] },
    { title: 'exportApi', items: [
      { path: 'export/:id/:formato', method: 'GET', text: 'exportDetails', params: [] },
    ] },
    { title: 'maintenanceApi', items: [
      { path: 'createThesa', method: 'POST', text: '', params: ['apiKey', 'titleParam', 'acronic', 'typeParam', 'typeOptions', 'visibilityParam', 'finality'] },
      { path: 'removeRelation', method: 'POST', text: '', params: ['apiKey', 'termsParam', 'thId', 'verb'] },
      { path: 'relateTerms', method: 'POST', text: '', params: ['apiKey', 'termsParam', 'thId', 'verb'] },
      { path: 'saveDescription', method: 'POST', text: '', params: ['apiKey', 'field', 'thesaurusId', 'descriptionParam'] },
      { path: 'relateConcept', method: 'POST', text: '', params: ['apiKey', 'thesaurusId', 'property', 'c1', 'c2'] },
      { path: 'term_add', method: 'POST', text: '', params: ['apiKey'] },
      { path: 'concept_create_term', method: 'POST', text: '', params: ['apiKey'] },
      { path: 'term_list/:idTh', method: 'POST', text: '', params: ['apiKey', 'idTh'] },
      { path: 'term_pref_list/:idTh/:idC', method: 'POST', text: '', params: ['apiKey', 'idTh', 'idC'] },
      { path: 'saveNote', method: 'POST', text: '', params: ['apiKey', 'field', 'thesaurusId', 'noteId', 'conceptId', 'note', 'noteType', 'noteLang'] },
      { path: 'deleteNote', method: 'POST', text: '', params: ['apiKey', 'noteRecord'] },
      { path: 'getNote', method: 'POST', text: 'getNote', params: ['apiKey', 'noteRecord'] },
      { path: 'tools', method: 'POST', text: '', params: ['apiKey'] },
      { path: 'import', method: 'POST', text: '', params: ['apiKey'] },
    ] },
    { title: 'statisticsApi', items: [
      { path: 'status', method: 'GET', text: 'health', params: [] },
      { path: 'resume', method: 'GET', text: 'resume', params: [] },
    ] },
  ];
  get sections() { return sections.map(id => ({ id, title: this.t(id) })); }
  selectedSection = 'intro';
  t(key: string): string { return copy[this.language.currentLanguage()][key] ?? key; }
  selectSection(id: string) { this.selectedSection = id; }
}