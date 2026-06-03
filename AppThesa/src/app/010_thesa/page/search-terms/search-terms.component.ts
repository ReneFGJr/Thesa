import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { AuthService } from '../../../000_core/service/auth.service';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { environment } from '../../../../environments/environment';

declare var bootstrap: any;

@Component({
  selector: 'app-search-terms',
  templateUrl: './search-terms.component.html',
  styleUrl: './search-terms.component.scss',
  standalone: false
})
export class SearchTermsComponent implements OnInit {
  searchTerm: string = '';
  selectedThesaurus: string = '';
  searchResults: any[] = [];
  isLoading: boolean = false;
  errorMessage: string = '';
  noResults: boolean = false;

  // Propriedades para o painel de detalhes
  showDetailPanel: boolean = false;
  conceptDetail: any = null;
  conceptLoading: boolean = false;
  conceptError: string = '';

  constructor(
    private router: Router,
    private serviceStorage: ServiceStorageService,
    private authService: AuthService,
    private serviceThesa: ServiceThesaService
  ) {}

  ngOnInit(): void {
    // Recuperar o ID do thesaurus do localStorage
    const thesaActual = this.serviceStorage.get('thesaActual');

    if (!thesaActual) {
      // Se não existe thesaActual, redirecionar
      const user = this.authService.getUser();
      if (user) {
        // Usuário logado - redirecionar para /thmy
        this.router.navigate(['/thmy']);
      } else {
        // Usuário não logado - redirecionar para /thopen
        this.router.navigate(['/thopen']);
      }
      return;
    }

    // Se existe thesaActual, carregar normalmente
    this.selectedThesaurus = thesaActual;
  }

  searchTerms(): void {
    if (!this.searchTerm.trim()) {
      this.errorMessage = 'Por favor, digite um termo para pesquisar.';
      this.searchResults = [];
      this.noResults = false;
      return;
    }

    if (!this.selectedThesaurus) {
      this.errorMessage = 'Nenhum thesaurus selecionado. Por favor, selecione um thesaurus primeiro.';
      this.searchResults = [];
      this.noResults = false;
      return;
    }

    this.isLoading = true;
    this.errorMessage = '';
    this.noResults = false;

    // Chamar a API para buscar o termo
    this.serviceThesa.api_post('search-term', {
      thesaID: this.selectedThesaurus,
      term_name: this.searchTerm
    }).subscribe({
      next: (response: any) => {
        this.isLoading = false;
        if (response.status === '200' && response.concept) {
          this.searchResults = Array.isArray(response.concept) ? response.concept : [response.concept];
        } else {
          this.noResults = true;
          this.searchResults = [];
        }
      },
      error: (error: any) => {
        this.isLoading = false;
        console.error('Erro na busca:', error);
        this.errorMessage = 'Erro ao realizar a busca. Tente novamente.';
        this.searchResults = [];
      }
    });
  }

  openConcept(conceptId: string): void {
    if (!conceptId) {
      this.conceptError = 'ID do conceito inválido';
      console.error('ID do conceito é inválido:', conceptId);
      return;
    }

    console.log('Abrindo conceito com ID:', conceptId);

    this.conceptLoading = true;
    this.conceptError = '';
    this.conceptDetail = null;

    // Buscar detalhes do conceito via API
    this.serviceThesa.getId(+conceptId, 'c').subscribe({
      next: (response: any) => {
        console.log('Resposta da API:', response);
        this.conceptLoading = false;

        if (response) {
          // Aceita qualquer resposta válida (não vazia)
          this.conceptDetail = response;
          this.showDetailPanel = true;

          // Usa setTimeout para garantir que o DOM foi renderizado antes de abrir o modal
          setTimeout(() => {
            this.openDetailModal();
          }, 100);
        } else {
          this.conceptError = 'Nenhum dado retornado do servidor';
          console.error('Resposta vazia da API');
        }
      },
      error: (error: any) => {
        this.conceptLoading = false;
        console.error('Erro ao buscar conceito:', error);
        this.conceptError = 'Erro ao carregar detalhes. Tente novamente.';
      }
    });
  }

  openDetailModal(): void {
    console.log('Tentando abrir modal...');
    console.log('Bootstrap disponível:', typeof bootstrap !== 'undefined');

    const modalElement = document.getElementById('conceptDetailModal');
    console.log('Modal elemento encontrado:', !!modalElement);

    if (modalElement) {
      try {
        const modal = new bootstrap.Modal(modalElement);
        modal.show();
        console.log('Modal aberto com sucesso');
      } catch (error) {
        console.error('Erro ao abrir modal:', error);
      }
    } else {
      console.error('Elemento do modal não encontrado');
    }
  }

  closeDetailPanel(): void {
    this.showDetailPanel = false;
    this.conceptDetail = null;
    this.conceptError = '';
  }

  clearSearch(): void {
    this.searchTerm = '';
    this.searchResults = [];
    this.errorMessage = '';
    this.noResults = false;
    this.closeDetailPanel();
  }
}
