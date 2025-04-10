import { Component } from '@angular/core';

@Component({
  selector: 'app-doc-api',
  templateUrl: './api.component.html',
  styleUrl: './api.component.scss',
})
export class ApiDocComponent {
  url = 'https://www.ufrgs.br/thesa/v2/index.php/api'
  sections = [
    { id: 'intro', title: 'Introdução' },
    { id: 'glossario', title: 'Glossário' },
    { id: 'endpoints', title: 'Endpoints' },
    { id: 'examples', title: 'Exemplos de Uso' },
    { id: 'errors', title: 'Tratamento de Erros' },
    { id: 'api', title: 'Uso das API' },
  ];

  selectedSection = 'intro';

  selectSection(id: string) {
    this.selectedSection = id;
  }
}
