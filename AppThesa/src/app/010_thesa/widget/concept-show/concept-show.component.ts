import { Component, ElementRef, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';
import { Offcanvas } from 'bootstrap';
import { language } from '../../../../language/language_pt';

@Component({
  selector: 'app-concept-show',
  templateUrl: './concept-show.component.html',
})
export class ConceptShowComponent {
  @ViewChild('offcanvasNovo') offcanvasNovo!: ElementRef;
  @Input() conceptID: number = 0;
  actionAC: string = '';
  data: any;
  @Input() thesaID: number = 0;
  @Input() editMode: boolean = false;
  terms: Array<any> | any;

  /* Messagems */
  title = language.thesa.conceptShow.title;

  tabs: Array<any> = [
    {
      name: 'Informação',
      id: 'information',
      icon: 'fa-solid fa-book',
      content: 'information',
    },
    { name: 'Grafo', id: 'graph', icon: 'fa-solid fa-book', content: 'graph' },
    { name: 'Ficha', id: 'card', icon: 'fa-solid fa-book', content: 'card' },
    { name: 'Notas', id: 'notas', icon: 'fa-solid fa-book', content: 'notas' },
  ];

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  actionUpdate(ev: Event) {
     let actionACev = ev.toString();
    console.log('#1-actionUpdate', actionACev);
    this.ngOnChanges()
  }

  updateTerms() {
    if (this.actionAC === 'altLabel' || this.actionAC === 'hiddenLabel') {
      let url = 'term_list/' + this.thesaID;
      this.serviceThesa.api_post(url, []).subscribe(
        (res) => {
          this.terms = res;
        },
        (error) => error
      );
    } else if (this.actionAC === 'prefLabel') {
      let url = 'term_pref_list/' + this.thesaID + '/' + this.conceptID;
      this.serviceThesa.api_post(url, []).subscribe(
        (res) => {
          this.terms = res;
        },
        (error) => error
      );
    } else if (this.actionAC === 'broader') {
      let url = 'broader_candidate/' + this.thesaID + '/' + this.conceptID;
      this.serviceThesa.api_post(url, []).subscribe(
        (res) => {
          this.terms = res;
        },
        (error) => error
      );
    } else {
      console.log('Ação não definida: ' + this.actionAC);
    }
  }

  action(ev: Event) {
    this.actionAC = ev.toString();
    this.updateTerms();
    this.openConceptPanel('popupConcept');
  }

  openConceptPanel(type: string) {
    const el = document.getElementById('popupConcept');
    if (el) {
      const bsCanvas = new Offcanvas(el);
      bsCanvas.show();
    }
  }

  ngOnInit() {
    this.ngOnChanges();
  }

  ngOnChanges() {
    this.serviceThesa.api_post('c/' + this.conceptID, []).subscribe(
      (res) => {
        this.data = res;
      },
      (error) => error
    );
  }
}
