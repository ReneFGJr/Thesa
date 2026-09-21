export interface ThesaTypeText {
  name: string;
  description: string;
}

export const typeTranslations: Record<string, Record<string, ThesaTypeText>> = {
  "en": {
    "1": {
      "name": "Controlled vocabulary / Glossary",
      "description": "A controlled vocabulary, also called a glossary in Knowledge Organization contexts, is a structured and standardized set of authorized terms used for indexing, cataloguing, and information retrieval in information systems. Each term is chosen according to precise criteria and has a preferred (authorized) form. It may be associated with synonyms, related terms, hierarchical terms (broader or narrower), and usage notes that guide its correct use."
    },
    "2": {
      "name": "Thesaurus (traditional - BT/NT/RT)",
      "description": "A thesaurus is a controlled vocabulary organized hierarchically and relationally, in which terms are structured according to classifications, equivalence relationships (synonyms and preferred terms), hierarchical relationships (broader and narrower terms), and associative relationships (related terms). In library and information science, a thesaurus standardizes indexing and document retrieval, ensuring semantic consistency and allowing users to navigate both broad categories and specialized concepts."
    },
    "3": {
      "name": "Thesaurus (SKOS/semantic)",
      "description": "A semantic thesaurus is a controlled vocabulary in which terms are not only organized hierarchically (broader and narrower) and linked through equivalence (synonyms) and associative relationships, but also carry explicit meaning through metadata or formal models that enable machines to interpret these relationships. Rather than merely listing keywords, a semantic thesaurus describes each concept in detail, with scopes, definitions, usage notes, and links to other concepts, so that information retrieval systems can infer alignments, hierarchies, and contextual connections."
    },
    "4": {
      "name": "Authority control",
      "description": "Authority control aims to standardize and manage entries for author names, work titles, subjects, and other access points in catalogues and indexes, ensuring consistency, accuracy, and interoperability between different information systems. Authority files — controlled lists with a unique entry for each entity — establish a preferred reference point (authorized form) and record variants, pseudonyms, and disambiguations, enabling effective retrieval and preventing duplication or semantic confusion. It does not use BT, NT, or RT relationships."
    }
  },
  "es": {
    "1": {
      "name": "Vocabulario controlado / Glosario",
      "description": "Un vocabulario controlado, también llamado glosario en contextos de Organización del Conocimiento, es un conjunto estructurado y normalizado de términos autorizados utilizados para la indización, la catalogación y la recuperación de información en sistemas de información. Cada término se elige según criterios precisos y tiene una forma preferida (autorizada), que puede estar asociada con sinónimos, términos relacionados, términos jerárquicos (más generales o más específicos) y notas de uso que orientan su empleo correcto."
    },
    "2": {
      "name": "Tesauro (tradicional - TG/TE/TR)",
      "description": "Un tesauro es un vocabulario controlado organizado de forma jerárquica y relacional, en el que los términos se estructuran según clasificaciones, relaciones de equivalencia (sinónimos y términos preferidos), relaciones jerárquicas (términos más generales y más específicos) y relaciones asociativas (términos relacionados). En biblioteconomía y ciencias de la información, un tesauro permite normalizar la indización y la recuperación de documentos, garantizando la coherencia semántica y permitiendo a los usuarios navegar tanto por categorías amplias como por conceptos especializados."
    },
    "3": {
      "name": "Tesauro (SKOS/semántico)",
      "description": "Un tesauro semántico es un vocabulario controlado en el que los términos no solo se organizan jerárquicamente (más generales y más específicos) y se vinculan mediante equivalencia (sinónimos) y relaciones asociativas, sino que también poseen un significado explícito mediante metadatos o modelos formales que permiten a las máquinas interpretar estas relaciones. En lugar de limitarse a enumerar palabras clave, un tesauro semántico define cada concepto de forma detallada, con alcances, definiciones, notas de uso y enlaces a otros conceptos, para que los sistemas de recuperación de información puedan inferir correspondencias, jerarquías y conexiones contextuales."
    },
    "4": {
      "name": "Control de autoridades",
      "description": "El control de autoridades tiene como objetivo normalizar y gestionar las entradas de nombres de autores, títulos de obras, materias y otros puntos de acceso en catálogos e índices, garantizando la coherencia, la precisión y la interoperabilidad entre distintos sistemas de información. Mediante archivos de autoridades — listas controladas con una entrada única para cada entidad — se establece un punto de referencia preferido (forma autorizada) y se registran variantes, seudónimos y desambiguaciones, lo que permite una recuperación eficaz y evita duplicidades o confusiones semánticas. No utiliza relaciones de tipo TG, TE o TR."
    }
  }
};
