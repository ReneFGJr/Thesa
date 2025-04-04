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
  terms: any;
  filterText: string = '';
  selectedTerm: any = null;
  selectedConcept: any = null;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.serviceThesa.api_post('terms/' + this.thesa, []).subscribe(
      (res) => {
        this.terms = res;
        console.log(this.terms);
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
