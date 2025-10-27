import { Component, ElementRef, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';
import { Offcanvas } from 'bootstrap';
import { PainelService } from '../../../000_core/service/painel.service';

@Component({
  selector: 'app-terms',
  templateUrl: './terms.component.html',
  styleUrl: './terms.component.scss',
  standalone: false,
})
export class TermsComponent {
  @ViewChild('offcanvasNovo') offcanvasNovo!: ElementRef;
  @Input() thesa: number = 0;
  @Input() actionVC: Array<any> = [];
  @Input() editMode: boolean = false;
  @Input() termList: Array<any> | any;
  @Input() termListCandidate: Array<any> | any;
  @Output() termChange: EventEmitter<any> = new EventEmitter<any>();

  action: string = '';
  termsList: any;

  filterText: string = '';
  selectedTerm: any = null;
  selectedConcept: any = null;
  termTotal = 0;
  termID = 0;
  isPanelOpen = false;

  constructor(
    private serviceThesa: ServiceThesaService,
    private painelService: PainelService
  ) {}

  actionACfcn(ev: Event | any): string {
    let action = ev.toString();

    if (action === 'cancel') {
      return ""
    }
    alert("Recebido evento: " + ev.toString());

    let actionACev = ev.toString();
    this.painelService.closeConceptPanel('termPainel');

    /* Atualiza dados */
    this.updateData();
    return "OK"
  }

  updateData() {
    this.termChange.emit('update');
  }

  //<!-------------- TooglePanel --------------->
  togglePanel(action: string) {
    const bsOffcanvas = new Offcanvas(this.offcanvasNovo.nativeElement);
    bsOffcanvas.show();
  }

  loadTermsNotAssociated() {
    this.updateTermosList();
  }

  ngOnChanges() {
    //this.termChange.emit('update');
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
    if (!this.termList?.terms) return [];

    return this.termList.terms.filter((term: any) =>
      term.Term.toLowerCase().includes(this.filterText.toLowerCase())
    );
  }

  onSelectTerm(term: any) {
    this.termID = term;
    this.termChange.emit(term);
  }
}
