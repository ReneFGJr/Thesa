import { Component, EventEmitter, Input, Output } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
    selector: 'app-term-label',
    templateUrl: './term-label.component.html',
    standalone: false
})
export class TermLabelComponent {
  @Input() termID: Array<any> | any;
  @Input() terms: Array<any> | any;
  @Input() label: string = '';
  @Input() editMode: boolean = false;
  @Input() thesaID: string = '';
  @Output() action = new EventEmitter<any>();
  public termLabel = '';
  editPlus: boolean = true;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {}

  deleteItem(id: string = '', label: string) {
    const confirmacao = confirm(
      'Tem certeza que deseja excluir este conceito?'
    );
    if (confirmacao) {
      // Executa a exclusão
      console.log('Item excluído');
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

  deleteLinkedData(id: string = '') {
    console.log('deleteLinkedData');
  }

  removeLabel(id: string = '', label: string) {
    let url = 'removeRelation';
    let dt = {
      terms: this.termID,
      thesaID: this.serviceStorage.get('thesaID'),
      idr: id,
      type: label,
    };
    this.serviceThesa.api_post(url, dt).subscribe(
      (res) => {
        console.log('Resposta do servidor:', res);
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
    if (this.label === 'prefLabel') {
      this.termLabel = 'Termo Preferencial';
    } else if (this.label === 'altLabel') {
      this.termLabel = 'Termo Alternativo';
    } else if (this.label === 'hiddenLabel') {
      this.termLabel = 'Termo Oculto';
    } else if (this.label === 'broader') {
      this.termLabel = 'Conceito Geral (TG)';
      console.log('====>', this.terms.length);
      if (this.terms.length > 0) {
        this.editPlus = false;
      } else {
        this.editPlus = true;
      }
    } else if (this.label === 'narrow') {
      this.termLabel = 'Conceito Específico (TE)';
    } else if (this.label === 'related') {
      this.termLabel = 'Conceito Relacionado (TR)';
    } else if (this.label === 'linkedData') {
      this.termLabel = 'Linked Data (LD)';
    } else {
      this.termLabel = 'Termo ->' + this.label;
    }
  }

  onSelectTerm(term: any) {
    alert(term);
  }

  newTerm() {
    alert(this.termID);
    alert(this.label);
  }
}
