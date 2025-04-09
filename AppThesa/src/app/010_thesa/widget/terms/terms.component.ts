import { Component, ElementRef, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';
import { Offcanvas } from 'bootstrap';

@Component({
  selector: 'app-terms',
  templateUrl: './terms.component.html',
  styleUrl: './terms.component.scss',
})
export class TermsComponent {
  @ViewChild('offcanvasNovo') offcanvasNovo!: ElementRef;
  @Input() thesa: number = 0;
  @Input() actionVC: Array<any> = [];
  @Output() termChange: EventEmitter<any> = new EventEmitter<any>();

  action: string = '';
  termsList: any;
  terms: any;
  filterText: string = '';
  selectedTerm: any = null;
  selectedConcept: any = null;
  editMode = true;
  termTotal = 0;
  termID = 0;
  isPanelOpen = false;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  //<!-------------- TooglePanel --------------->
  togglePanel(action: string) {
    const bsOffcanvas = new Offcanvas(this.offcanvasNovo.nativeElement);
    bsOffcanvas.show();
  }

  ngOnChanges() {
    this.serviceThesa.api_post('terms/' + this.thesa, []).subscribe(
      (res) => {
        this.terms = res;
      },
      (error) => error
    );
    this.updateTermosList();
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
    this.termID = term;
    this.termChange.emit(term);
  }
}
