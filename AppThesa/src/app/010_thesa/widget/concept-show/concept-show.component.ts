import { Component, ElementRef, EventEmitter, Input, Output, ViewChild } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';
import { Offcanvas } from 'bootstrap';

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
  editMode: boolean = true;
  terms: Array<any> | any;

  tabs: Array<any> = [
    {
      name: 'Informação',
      id: 'information',
      icon: 'fa-solid fa-book',
      content: 'information',
    },
    { name: 'Notas', id: 'notas', icon: 'fa-solid fa-book', content: 'notas' },
  ];

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  updateTerms() {
    let url = 'term_list/' + this.thesaID;

    console.log('URL:', url);
    this.serviceThesa.api_post(url, []).subscribe(
      (res) => {
        this.terms = res;
        console.log("Termos",this.terms);
      },
      (error) => error
    );
  }

  action(ev: Event) {
    this.updateTerms();
    this.actionAC = ev.toString();
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
