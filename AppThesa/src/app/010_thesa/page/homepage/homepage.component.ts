import { Component } from '@angular/core';
import { LanguageService } from '../../../000_core/service/language.service';

const homeContent = {
  'pt-br': {
    introduction: 'Apresentação do Thesa',
    introductionParagraphs: [
      'O Thesa é um tesauro semântico aplicado desenvolvido para estudantes de graduação de biblioteconomia na disciplina de Linguagens Documentárias para a elaboração de tesauros. Ele foi desenvolvido com o objetivo de reduzir o trabalho operacional e dar maior atenção ao desenvolvimento cognitivo e conceitual da modelagem do domínio.',
      'O Thesa utiliza uma concepção de múltiplos tesauros, ou múltiplos esquemas: o usuário pode criar um número ilimitado de tesauros em diferentes áreas do conhecimento.',
      'O aplicativo baseia-se nas normas ISO e NISO vigentes para compatibilizar suas diretrizes com os requisitos semânticos dos sistemas de organização do conhecimento. A literatura e as normas de construção de tesauros orientaram a identificação dos elementos do protótipo, especialmente as propriedades que ligam os conceitos.',
      'A estrutura do Thesa baseia-se nas relações entre conceitos. Um conceito pode ser representado por um termo, uma imagem, um som, um link ou outra forma explícita. O conceito permanece, enquanto sua representação pode variar conforme o contexto histórico ou social, com uma forma preferencial e formas alternativas e ocultas.',
    ],
    cite: 'Como citar:',
    history: 'Histórico do Thesa',
    historyParagraphs: [
      'O Thesa foi desenvolvido inicialmente como um protótipo em PHP e MySQL para possibilitar o compartilhamento e o desenvolvimento colaborativo da ferramenta.',
      'O software funciona na Web e pode ser baixado gratuitamente para uso didático em cursos de graduação e pós-graduação ou para uso profissional. Foi projetado para oferecer suporte a vários idiomas; a disponibilidade de traduções depende da colaboração de instituições interessadas.',
      'O Thesa permite criar vários tesauros em diferentes áreas do conhecimento e definir seu acesso como público ou privado. Seguindo a concepção de URI usada pelo SKOS e pela Web Semântica, cada conceito tem um endereço permanente na Internet e um identificador único; termos o representam por meio de propriedades.',
    ],
    thesauri: 'tesauros', concepts: 'conceitos', terms: 'termos',
  },
  en: {
    introduction: 'About Thesa',
    introductionParagraphs: [
      'Thesa is an applied semantic thesaurus tool developed for undergraduate library science students studying documentary languages and thesaurus construction. It aims to reduce routine work so students can focus on the cognitive and conceptual work of modeling a domain.',
      'Thesa supports multiple thesauri, or schemes: users can create an unlimited number of thesauri across different fields of knowledge.',
      'The application draws on current ISO and NISO standards to align their guidance with the semantic requirements of knowledge organization systems. The literature and thesaurus construction standards informed the prototype, especially the properties that link concepts.',
      'Thesa is structured around relationships between concepts. A concept can be represented by a term, image, sound, link, or another explicit form. The concept persists while its representation may change with historical or social context, with one preferred form and alternative or hidden forms.',
    ],
    cite: 'How to cite:',
    history: 'History of Thesa',
    historyParagraphs: [
      'Thesa was first developed as a prototype in PHP and MySQL to support sharing and collaborative development.',
      'The software runs on the Web and is available as a free download for teaching in undergraduate and graduate courses or for professional use. It was designed to support multiple languages; translations depend on collaboration with interested institutions.',
      'Thesa lets users create multiple thesauri in different fields and make them public or private. Following the URI approach used by SKOS and the Semantic Web, each concept has a permanent Internet address and a unique identifier; terms represent it through properties.',
    ],
    thesauri: 'thesauri', concepts: 'concepts', terms: 'terms',
  },
  es: {
    introduction: 'Presentación de Thesa',
    introductionParagraphs: [
      'Thesa es una herramienta de tesauros semánticos aplicados desarrollada para estudiantes de biblioteconomía en la asignatura de lenguajes documentales y elaboración de tesauros. Busca reducir el trabajo operativo para dedicar más atención al desarrollo cognitivo y conceptual del modelado de dominios.',
      'Thesa admite múltiples tesauros o esquemas: el usuario puede crear un número ilimitado de tesauros en diferentes áreas del conocimiento.',
      'La aplicación se basa en las normas ISO y NISO vigentes para armonizar sus directrices con los requisitos semánticos de los sistemas de organización del conocimiento. La literatura y las normas de construcción de tesauros orientaron el prototipo, especialmente las propiedades que vinculan los conceptos.',
      'La estructura de Thesa se basa en las relaciones entre conceptos. Un concepto puede representarse mediante un término, una imagen, un sonido, un enlace u otra forma explícita. El concepto permanece, mientras que su representación puede variar según el contexto histórico o social, con una forma preferente y formas alternativas u ocultas.',
    ],
    cite: 'Cómo citar:',
    history: 'Historia de Thesa',
    historyParagraphs: [
      'Thesa se desarrolló inicialmente como un prototipo en PHP y MySQL para facilitar el intercambio y el desarrollo colaborativo de la herramienta.',
      'El software funciona en la Web y se puede descargar gratuitamente para uso didáctico en estudios de grado y posgrado o para uso profesional. Se diseñó para admitir varios idiomas; las traducciones dependen de la colaboración con instituciones interesadas.',
      'Thesa permite crear varios tesauros en diferentes áreas del conocimiento y definir su acceso como público o privado. Siguiendo el concepto de URI utilizado por SKOS y la Web Semántica, cada concepto tiene una dirección permanente en Internet y un identificador único; los términos lo representan mediante propiedades.',
    ],
    thesauri: 'tesauros', concepts: 'conceptos', terms: 'términos',
  },
};

@Component({
  selector: 'app-homepage',
  templateUrl: './homepage.component.html',
  styleUrl: './homepage.component.scss',
  standalone: false,
})
export class HomepageComponent {
  constructor(public readonly language: LanguageService) {}

  get content() {
    return homeContent[this.language.currentLanguage()];
  }
}