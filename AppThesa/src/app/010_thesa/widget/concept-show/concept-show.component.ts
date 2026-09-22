import {
  Component,
  ElementRef,
  EventEmitter,
  Input,
  Output,
  ViewChild,
} from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';
import { Offcanvas } from 'bootstrap';
import { PainelService } from '../../../000_core/service/painel.service';
import { LanguageService } from '../../../000_core/service/language.service';
import { environment } from '../../../../environments/environment';

@Component({
  selector: 'app-concept-show',
  templateUrl: './concept-show.component.html',
  standalone: false,
})
export class ConceptShowComponent {
  @ViewChild('offcanvasNovo') offcanvasNovo!: ElementRef;
  @ViewChild('conceptGrafo') conceptGrafo: any;
  @Input() thesaID: number = 0;
  @Input() thesaStatus: number | string = 1;
  @Input() conceptID: number = 0;
  @Input() dataConcept: any;
  @Input() editMode: boolean = false;
  @Output() termChange: EventEmitter<string> = new EventEmitter<string>();
  terms: Array<any> | any;
  actionAC: string = '';

  tabs: Array<any> = [
    {
      name: 'conceptInformation',
      id: 'information',
      icon: 'fa-solid fa-book',
      content: 'information',
    },
    { name: 'conceptGraph', id: 'graph', icon: 'fa-solid fa-book', content: 'graph' },
    { name: 'conceptCard', id: 'card', icon: 'fa-solid fa-book', content: 'card' },
    { name: 'conceptNotes', id: 'notas', icon: 'fa-solid fa-book', content: 'notas' },
    { name: 'export', id: 'export', icon: 'fa-solid fa-download', content: 'export' },
    { name: 'conceptLog', id: 'log', icon: 'fa-solid fa-book', content: 'log' },
  ];

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute,
    private painelService: PainelService,
    public readonly language: LanguageService
  ) {}

  tabLabel(name: string): string {
    const labels = this.language.labels();
    switch (name) {
      case 'conceptInformation': return labels.conceptInformation;
      case 'conceptGraph': return labels.conceptGraph;
      case 'conceptCard': return labels.conceptCard;
      case 'conceptNotes': return labels.conceptNotes;
      case 'export': return labels.export;
      case 'conceptLog': return labels.conceptLog;
      default: return name;
    }
  }

  exportUrl(format: 'xml' | 'turtle' | 'json' | 'txt'): string {
    const url = `${environment.apiUrl}/export/${this.conceptID}/${format}?scope=concept`;
    const apiKey = this.serviceStorage.get('apikey');
    return Number(this.thesaStatus) !== 1 && apiKey
      ? `${url}&apikey=${encodeURIComponent(apiKey)}`
      : url;
  }

  actionUpdate(ev: Event) {
    let actionACev = ev.toString();
    console.log('#1-actionUpdate#', actionACev);
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
    } else if (this.actionAC === 'related') {
      let url = 'related_candidate/' + this.thesaID + '/' + this.conceptID;
      this.serviceThesa.api_post(url, []).subscribe(
        (res) => {
          this.terms = res;
        },
        (error) => error
      );
    } else if (this.actionAC === 'linkeddata') {
      let url = 'linkeddata/' + this.thesaID + '/' + this.conceptID;
      console.log('linkeddata', url);
    } else if (this.actionAC === 'exactmatch') {
      let url = 'exactmatch/' + this.thesaID + '/' + this.conceptID;
      console.log('exactmatch', url);
    } else if (this.actionAC === 'relateConcept') {
      let url = 'relateConcept/' + this.thesaID + '/' + this.conceptID;
      console.log('relateConcept', url);
    } else if (this.actionAC === 'reload') {
      // Recarrega os dados do conceito
    } else {
      console.log('Ação não definida: ' + this.actionAC);
    }
  }

  updateData() {
    console.log('##########ngOnChanges ConceptShowComponent', this.conceptID);
  }

  action(ev: Event) {
    //alert('OI - concept-show.component.ts ' + ev.toString());
    this.actionAC = ev.toString();
    if (this.actionAC != '') {
      this.updateTerms();

      if (this.actionAC === 'reload') {
        // Recarrega os dados do conceito
        this.termChange.emit(this.actionAC);
        this.painelService.closeConceptPanel('popupConcept');
      } else if (this.actionAC === 'linkeddata') {
        // Atualiza o grafo quando novo linked data é adicionado
        if (this.conceptGrafo) {
          setTimeout(() => {
            this.conceptGrafo.refresh();
          }, 300);
        }
        this.painelService.openConceptPanel('popupConcept');
      } else {
        this.painelService.openConceptPanel('popupConcept');
      }

    }
  }
}
