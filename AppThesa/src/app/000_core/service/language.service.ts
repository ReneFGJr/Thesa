import { DOCUMENT } from '@angular/common';
import { Injectable, computed, inject, signal } from '@angular/core';

const translations = {
  'pt-br': {
    publicDescription: "O Thesa está disponível para qualquer pessoa visualizar, sem restrições de acesso.",
    privateDescription: "O Thesa é restrito e somente usuários autorizados podem visualizá-lo.",
    cancelledDescription: "O Thesa foi invalidado ou desativado, estando indisponível para visualização pública ou privada.",
    confirmVisibilityChange: "Você tem certeza que deseja alterar a visibilidade do Thesa?",
    imageUpload: "Upload de Imagem",
    chooseFile: "Escolher arquivo",
    noFileChosen: "Nenhum arquivo selecionado",
    imagePreview: "Prévia da imagem",
    invalidImage: "Por favor, selecione uma imagem válida.",
    uploadCompleted: "Upload concluído com sucesso!",
    uploadFailed: "Erro no upload. Tente novamente.",

    confirmLicenseChange: "Você tem certeza que deseja alterar a licença do Thesa?",
    reservedCopyright: "Reservado & Copyright",
    submit: "Enviar",
    note: "Observação",
    portugueseBrazil: "Português (Brasil)",
    english: "Inglês",
    spanish: "Espanhol",
    french: "Francês",
    italian: "Italiano",
    german: "Alemão",

    confirmTypeChange: "Você tem certeza que deseja alterar o tipo de thesa?",
    openThesaTitle: "Thesa Aberto",
    filterThesauri: "Filtrar tesauros por título...",
    createThesa: "Criar novo Thesa",
    publicStatus: "Público",
    privateStatus: "Privado",
    cancelledStatus: "Cancelado",
    accessThesa: "Acessar",

    termSingular: "termo",
    termPlural: "termos",
    title: "Título",
    thesaurusTitle: "Título do tesauro",
    acronym: "Acrônimo",
    save: "Salvar",
    cancel: "Cancelar",
    savedSuccessfully: "Dados salvos com sucesso!",
    thesaType: "Tipo do Thesa",
    description: "Descrição",
    vocabularyDescription: "Descrição do vocabulário / tesauro",
    methodology: "Metodologia",
    audience: "Público Alvo",
    languages: "Idiomas",
    license: "Licença",
    visibility: "Visibilidade",
    themeIcons: "Tema e Ícones",
    relationTypes: "Tipos de Relações",
    members: "Membros",
    themes: "Temas",
    relations: "Relações",
    thesaurusMembers: "Membros do tesauro",

    search: 'Pesquisar', open: 'Thesa Abertos', mine: 'Meus Thesa',
    documentation: 'Documentação', about: 'Sobre', login: 'Entrar',
    profile: 'Meu perfil', toggleNavigation: 'Alternar navegação',
    selectLanguage: 'Selecionar idioma', openLanguageSelection: 'Abrir seleção de idioma',
    concepts: 'Conceitos', conceptSingular: 'conceito', conceptPlural: 'conceitos',
    alphabeticalDisplay: 'Apresentação Alfabética', systematicDisplay: 'Apresentação Sistemática',
    export: 'Exportação', terms: 'Termos', settings: 'Configurações',
    disableEditing: 'Desativar Edição', enableEditing: 'Habilitar Edição', editingEnabled: 'EDIÇÃO ATIVADA',
    selectTerm: 'Selecione um termo para ver os detalhes.',
    searchTerm: 'Buscar termo...', unassociatedTerms: 'Termos não associados',
  },
  en: {
    publicDescription: "Thesa is available for anyone to view, without access restrictions.",
    privateDescription: "Thesa is restricted and only authorized users can view it.",
    cancelledDescription: "Thesa has been invalidated or deactivated and is unavailable for public or private viewing.",
    confirmVisibilityChange: "Are you sure you want to change the visibility of Thesa?",
    imageUpload: "Image Upload",
    chooseFile: "Choose file",
    noFileChosen: "No file chosen",
    imagePreview: "Image preview",
    invalidImage: "Please select a valid image.",
    uploadCompleted: "Upload completed successfully!",
    uploadFailed: "Upload failed. Please try again.",

    confirmLicenseChange: "Are you sure you want to change the Thesa license?",
    reservedCopyright: "All rights reserved & Copyright",
    submit: "Submit",
    note: "Note",
    portugueseBrazil: "Portuguese (Brazil)",
    english: "English",
    spanish: "Spanish",
    french: "French",
    italian: "Italian",
    german: "German",

    confirmTypeChange: "Are you sure you want to change the Thesa type?",
    openThesaTitle: "Public Thesa",
    filterThesauri: "Filter thesauri by title...",
    createThesa: "Create new Thesa",
    publicStatus: "Public",
    privateStatus: "Private",
    cancelledStatus: "Cancelled",
    accessThesa: "Open",

    termSingular: "term",
    termPlural: "terms",
    title: "Title",
    thesaurusTitle: "Thesaurus title",
    acronym: "Acronym",
    save: "Save",
    cancel: "Cancel",
    savedSuccessfully: "Data saved successfully!",
    thesaType: "Thesa type",
    description: "Description",
    vocabularyDescription: "Vocabulary / thesaurus description",
    methodology: "Methodology",
    audience: "Target audience",
    languages: "Languages",
    license: "License",
    visibility: "Visibility",
    themeIcons: "Theme and Icons",
    relationTypes: "Relationship Types",
    members: "Members",
    themes: "Themes",
    relations: "Relationships",
    thesaurusMembers: "Thesaurus members",

    search: 'Search', open: 'Public Thesa', mine: 'My Thesa',
    documentation: 'Documentation', about: 'About', login: 'Sign in',
    profile: 'My profile', toggleNavigation: 'Toggle navigation',
    selectLanguage: 'Select language', openLanguageSelection: 'Open language selection',
    concepts: 'Concepts', conceptSingular: 'concept', conceptPlural: 'concepts',
    alphabeticalDisplay: 'Alphabetical Display', systematicDisplay: 'Systematic Display',
    export: 'Export', terms: 'Terms', settings: 'Settings',
    disableEditing: 'Disable Editing', enableEditing: 'Enable Editing', editingEnabled: 'EDITING ENABLED',
    selectTerm: 'Select a term to view its details.',
    searchTerm: 'Search for a term...', unassociatedTerms: 'Unassociated terms',
  },
  es: {
    publicDescription: "Thesa está disponible para que cualquier persona lo consulte, sin restricciones de acceso.",
    privateDescription: "Thesa es restringido y solo los usuarios autorizados pueden consultarlo.",
    cancelledDescription: "Thesa ha sido invalidado o desactivado y no está disponible para consulta pública ni privada.",
    confirmVisibilityChange: "¿Está seguro de que desea cambiar la visibilidad de Thesa?",
    imageUpload: "Subir imagen",
    chooseFile: "Elegir archivo",
    noFileChosen: "Ningún archivo seleccionado",
    imagePreview: "Vista previa de la imagen",
    invalidImage: "Seleccione una imagen válida.",
    uploadCompleted: "¡Imagen subida correctamente!",
    uploadFailed: "Error al subir la imagen. Inténtelo de nuevo.",

    confirmLicenseChange: "¿Está seguro de que desea cambiar la licencia de Thesa?",
    reservedCopyright: "Derechos reservados y Copyright",
    submit: "Enviar",
    note: "Nota",
    portugueseBrazil: "Portugués (Brasil)",
    english: "Inglés",
    spanish: "Español",
    french: "Francés",
    italian: "Italiano",
    german: "Alemán",

    confirmTypeChange: "¿Está seguro de que desea cambiar el tipo de Thesa?",
    openThesaTitle: "Thesa abiertos",
    filterThesauri: "Filtrar tesauros por título...",
    createThesa: "Crear nuevo Thesa",
    publicStatus: "Público",
    privateStatus: "Privado",
    cancelledStatus: "Cancelado",
    accessThesa: "Acceder",

    termSingular: "término",
    termPlural: "términos",
    title: "Título",
    thesaurusTitle: "Título del tesauro",
    acronym: "Acrónimo",
    save: "Guardar",
    cancel: "Cancelar",
    savedSuccessfully: "¡Datos guardados correctamente!",
    thesaType: "Tipo de Thesa",
    description: "Descripción",
    vocabularyDescription: "Descripción del vocabulario / tesauro",
    methodology: "Metodología",
    audience: "Público objetivo",
    languages: "Idiomas",
    license: "Licencia",
    visibility: "Visibilidad",
    themeIcons: "Tema e Iconos",
    relationTypes: "Tipos de Relaciones",
    members: "Miembros",
    themes: "Temas",
    relations: "Relaciones",
    thesaurusMembers: "Miembros del tesauro",

    search: 'Buscar', open: 'Thesa abiertos', mine: 'Mis Thesa',
    documentation: 'Documentación', about: 'Acerca de', login: 'Iniciar sesión',
    profile: 'Mi perfil', toggleNavigation: 'Alternar navegación',
    selectLanguage: 'Seleccionar idioma', openLanguageSelection: 'Abrir selección de idioma',
    concepts: 'Conceptos', conceptSingular: 'concepto', conceptPlural: 'conceptos',
    alphabeticalDisplay: 'Presentación alfabética', systematicDisplay: 'Presentación sistemática',
    export: 'Exportación', terms: 'Términos', settings: 'Configuración',
    disableEditing: 'Desactivar edición', enableEditing: 'Habilitar edición', editingEnabled: 'EDICIÓN ACTIVADA',
    selectTerm: 'Seleccione un término para ver los detalles.',
    searchTerm: 'Buscar término...', unassociatedTerms: 'Términos no asociados',
  },
} as const;

export type Language = keyof typeof translations;

@Injectable({ providedIn: 'root' })
export class LanguageService {
  private readonly document = inject(DOCUMENT);
  private readonly storageKey = 'thesa_locale';
  private readonly selected = signal<Language>('pt-br');
  readonly currentLanguage = this.selected.asReadonly();
  readonly labels = computed(() => translations[this.selected()]);
  readonly options = [
    { code: 'pt-br', label: 'Português', flagSrc: 'assets/img/flags/br.svg' },
    { code: 'en', label: 'English', flagSrc: 'assets/img/flags/gb.svg' },
    { code: 'es', label: 'Español', flagSrc: 'assets/img/flags/es.svg' },
  ] as const;
  readonly selectedOption = computed(() =>
    this.options.find(option => option.code === this.selected()) ?? this.options[0]
  );

  constructor() {
    try {
      const saved = this.document.defaultView?.localStorage.getItem(this.storageKey);
      if (this.isLanguage(saved)) this.selected.set(saved);
    } catch {
      // Keep the default when browser storage is unavailable.
    }
  }

  setLanguage(language: string): void {
    if (!this.isLanguage(language)) return;
    this.selected.set(language);
    try {
      this.document.defaultView?.localStorage.setItem(this.storageKey, language);
    } catch {
      // Language switching still works when persistence is blocked.
    }
  }

  private isLanguage(value: unknown): value is Language {
    return value === 'pt-br' || value === 'en' || value === 'es';
  }
}
