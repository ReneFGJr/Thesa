import { Component } from '@angular/core';

@Component({
  selector: 'app-doc-api',
  templateUrl: './api.component.html',
  styleUrl: './api.component.scss',
})
export class ApiDocComponent {
  sections = [
    { id: 'intro', title: 'Introdução' },
    { id: 'auth', title: 'Autenticação' },
    { id: 'endpoints', title: 'Endpoints' },
    { id: 'examples', title: 'Exemplos de Uso' },
    { id: 'errors', title: 'Tratamento de Erros' },
  ];

  selectedSection = 'intro';

  selectSection(id: string) {
    this.selectedSection = id;
  }
}
