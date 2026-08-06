import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';

interface ThesaTermItem {
  id_term?: number | string;
  id?: number | string;
  ID?: number | string;
  TermID?: number | string;
  Term?: string;
  label?: string;
  name?: string;
  Lang?: string;
  lg_code?: string;
  editing?: boolean;
  draftLabel?: string;
  originalLabel?: string;
}

@Component({
  selector: 'app-terms-page',
  templateUrl: './terms.component.html',
  styleUrl: './terms.component.scss',
  standalone: false,
})
export class TermsPageComponent implements OnInit {
  thesaID = 0;
  thesa: any = null;
  terms: ThesaTermItem[] = [];
  editMode = false;
  loading = false;
  error = '';

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit(): void {
    this.router.params.subscribe((params) => {
      this.thesaID = +params['id'];
      if (this.thesaID > 0) {
        this.loadThesa();
        this.loadTerms();
      }
    });
  }

  loadThesa(): void {
    this.serviceThesa.api_post('th/' + this.thesaID, []).subscribe(
      (res) => {
        this.thesa = res;
        this.editMode = this.serviceStorage.mathEditMode(res);
      },
      () => {
        this.error = 'Não foi possível carregar o tesauro.';
      }
    );
  }

  loadTerms(): void {
    this.loading = true;
    this.error = '';
    this.serviceThesa.getId(this.thesaID, 'terms').subscribe(
      (res) => {
        const rawTerms = Array.isArray(res)
          ? res
          : (res as any)?.Terms || (res as any)?.terms || [];

        this.terms = rawTerms.map((term: ThesaTermItem) => this.normalizeTerm(term));
        this.loading = false;
      },
      () => {
        this.error = 'Não foi possível carregar os termos.';
        this.loading = false;
      }
    );
  }

  termLabel(term: ThesaTermItem): string {
    return term.Term || term.label || term.name || 'Termo sem nome';
  }

  termLang(term: ThesaTermItem): string {
    return term.Lang || term.lg_code || '';
  }

  canEditTerm(term: ThesaTermItem): boolean {
    return this.editMode && !!term.TermID;
  }

  startEdit(term: ThesaTermItem): void {
    if (!this.canEditTerm(term)) {
      return;
    }

    term.editing = true;
    term.originalLabel = this.termLabel(term);
    term.draftLabel = term.originalLabel;
  }

  cancelEdit(term: ThesaTermItem): void {
    term.editing = false;
    term.draftLabel = term.originalLabel || this.termLabel(term);
  }

  saveTerm(term: ThesaTermItem): void {
    const id_term = term.TermID;
    const label = (term.draftLabel || '').trim();
    const apikey = this.getApiKey();

    if (!id_term || !label) {
      return;
    }

    this.serviceThesa.api_post('updateTerm', { id_term, label, apikey }).subscribe(
      () => {
        term.Term = label;
        term.label = label;
        term.name = label;
        term.originalLabel = label;
        term.editing = false;
      },
      () => {
        term.editing = false;
        term.draftLabel = term.originalLabel || this.termLabel(term);
        this.error = 'Não foi possível atualizar o termo.';
      }
    );
  }

  private normalizeTerm(term: ThesaTermItem): ThesaTermItem {
    const label = this.termLabel(term);
    return {
      ...term,
      TermID: term.TermID ?? term.id_term ?? term.id ?? term.ID,
      editing: false,
      draftLabel: label,
      originalLabel: label,
    };
  }

  private getApiKey(): string {
    return localStorage.getItem('apikey') || '';
  }

  adjustTerm(term: ThesaTermItem): void {
    if (!this.canEditTerm(term)) {
      return;
    }

    const currentLabel = term.editing ? (term.draftLabel || '') : this.termLabel(term);
    const rawLabel = currentLabel.trim();
    if (!rawLabel) {
      return;
    }

    const normalizedLabel = rawLabel.charAt(0).toUpperCase() + rawLabel.slice(1).toLowerCase();

    term.Term = normalizedLabel;
    term.label = normalizedLabel;
    term.name = normalizedLabel;
    term.draftLabel = normalizedLabel;
    if (!term.editing) {
      term.editing = true;
    }
    this.saveTerm(term);
  }
}
