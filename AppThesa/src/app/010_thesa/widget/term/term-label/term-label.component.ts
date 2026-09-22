import { Component, EventEmitter, Input, Output } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';
import { LanguageService } from '../../../../000_core/service/language.service';

@Component({
  selector: 'app-term-label',
  templateUrl: './term-label.component.html',
  standalone: false,
})
export class TermLabelComponent {
  @Input() termID: Array<any> | any;
  @Input() terms: Array<any> | any;
  @Input() label: string = '';
  @Input() editMode: boolean = false;
  @Input() thesaID: string = '';
  @Output() action = new EventEmitter<any>();
  editPlus: boolean = true;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    public readonly language: LanguageService
  ) {}

  deleteItem(id: string = '', label: string) {
    const confirmacao = confirm(
      this.language.labels().deleteLabelConfirm + label
    );
    if (confirmacao) {
      // Executa a exclusão
      console.log('Excluindo rótulo:', id, label);
      this.removeLabel(id, label);
      this.action.emit('reload');
    } else {
      // Cancela a exclusão
      console.log('Exclusão cancelada');
    }
  }
  editLinkedData(id: string = '') {
    console.log('editLinkedData');
  }

  deleteExactMatch(id: string = '') {
    console.log('deleteExactMatch', id);

    let dt = {
      id_em: id,
    };
    if (confirm('Tem certeza que deseja excluir este Exact Match?')) {
      this.serviceThesa.api_post('deleteExactMatch', dt).subscribe(
        (res) => {
          this.action.emit('reload');
        },
        (error) => {
          console.error('Erro ao enviar os dados:', error);
        }
      );
    }
  }

  deleteLinkedData(id: string = '') {
    console.log('deleteLinkedData', id);

    let dt = {
      id_ld: id,
    };
    if (confirm('Tem certeza que deseja excluir este Linked Data?')) {
      this.serviceThesa.api_post('deleteLinkedData', dt).subscribe(
        (res) => {

        },
        (error) => {
          console.error('Erro ao enviar os dados:', error);
        }
      );
    }
  }

  removeLabel(id: string = '', label: string) {
    let url = 'removeRelation';
    let dt = {
      terms: this.termID,
      thesaID: this.thesaID,
      idr: id,
      type: label,
    };
    this.serviceThesa.api_post(url, dt).subscribe(
      (res) => {
        console.log(url);
      },
      (error) => {
        console.error('Erro ao enviar os dados:', error);
      }
    );
  }

  ngOnChages() {
    this.ngOnInit();
  }

  togglePanel(act: string) {
    this.action.emit(act);
  }

  ngOnInit() {
    this.ngOnChanges();
  }

  ngOnChanges() {
    this.editPlus = this.label !== 'broader' || !this.terms?.length;
  }

  labelText(): string {
    const labels = this.language.labels();
    switch (this.label) {
      case 'prefLabel': return labels.preferredTermLabel;
      case 'altLabel': return labels.equivalentTermLabel;
      case 'hiddenLabel': return labels.hiddenTermLabel;
      case 'broader': return labels.broaderConceptLabel;
      case 'narrow': return labels.narrowerConceptLabel;
      case 'related': return labels.relatedConceptLabel;
      case 'exactMatch': return 'Exact Match (SKOS)';
      case 'linkedData': return 'Linked Data (LD)';
      default: return labels.termLabelFallback + ' ->' + this.label;
    }
  }

  onSelectTerm(term: any) {
    alert("TERM1" + term);
  }

  newTerm() {
    alert('TERM2' + this.termID);
    alert(this.label);
  }

  deleteRelated(id: string = '') {
    console.log('deleteRelated', id);
  }
}
