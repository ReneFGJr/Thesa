import { Component, EventEmitter, Input, Output } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-terms',
  templateUrl: './terms.component.html',
  styleUrl: './terms.component.scss',
})
export class TermsComponent {
  @Input() thesa: number = 0;
  @Output() termChange = new EventEmitter<any>();
  termsList: any;
  terms: any;
  filterText: string = '';
  selectedTerm: any = null;
  selectedConcept: any = null;
  editMode = true;
  termTotal = 0;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnChanges() {
    this.serviceThesa.api_post('terms/' + this.thesa, []).subscribe(
      (res) => {
        this.terms = res;
      },
      (error) => error
    );
    this.updateTermosList()

  }

  updateTermosList() {
        this.serviceThesa.api_post('term_list/' + this.thesa, []).subscribe(
      (res) => {
        this.termsList = res;
        this.termTotal = this.termsList.Terms.length;
      },
      (error) => error
    );
  }

  filteredTerms(): any[] {
    if (!this.terms?.terms) return [];

    return this.terms.terms.filter((term: any) =>
      term.Term.toLowerCase().includes(this.filterText.toLowerCase())
    );
  }

  onSelectTerm(term: any) {
    console.log('Selecionado:', term);
    // faça algo com o termo selecionado...
    this.termChange.emit(term);
  }
}
