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
  @Output() actionAC: EventEmitter<any> = new EventEmitter<any>();
  data: any;
  editMode: boolean = true;

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

  action(ev: Event) {
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
