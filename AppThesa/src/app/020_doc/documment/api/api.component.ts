import { Component } from '@angular/core';
import { environment } from '../../../../environments/environment';

@Component({
    selector: 'app-doc-api',
    templateUrl: './api.component.html',
    styleUrl: './api.component.scss',
    standalone: false
})
export class ApiDocComponent {
  url = 'https://www.ufrgs.br/thesa/v2/index.php/api'
  exportApiUrl = environment.apiUrl;
  exportFormats = [
    { label: 'RDF/XML', path: 'xml', mime: 'application/rdf+xml', extension: '.xml' },
    { label: 'Turtle', path: 'turtle', mime: 'text/turtle', extension: '.ttl' },
    { label: 'JSON-LD', path: 'json', mime: 'application/ld+json', extension: '.jsonld' },
    { label: 'TXT', path: 'txt', mime: 'text/plain', extension: '.txt' },
  ];
  sections = [
    { id: 'intro', title: 'Introdução' },
    { id: 'glossario', title: 'Glossário' },
    { id: 'endpoints', title: 'Endpoints' },
    { id: 'exportacao', title: 'Exportação' },
    { id: 'examples', title: 'Exemplos de Uso' },
    { id: 'errors', title: 'Tratamento de Erros' },
    { id: 'api', title: 'Uso das API' },
  ];

  selectedSection = 'intro';

  selectSection(id: string) {
    this.selectedSection = id;
  }
}
