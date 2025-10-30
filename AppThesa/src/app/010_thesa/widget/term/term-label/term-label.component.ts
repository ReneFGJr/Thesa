import { Component, EventEmitter, Input, Output } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

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
  public termLabel = '';
  editPlus: boolean = true;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {}

  deleteItem(id: string = '', label: string) {
    const confirmacao = confirm(
      'Tem certeza que deseja excluir este rótulo? ' + label
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
    if (this.label === 'prefLabel') {
      this.termLabel = 'Termo Preferencial';
    } else if (this.label === 'altLabel') {
      this.termLabel = 'Termo Alternativo';
    } else if (this.label === 'hiddenLabel') {
      this.termLabel = 'Termo Oculto';
    } else if (this.label === 'broader') {
      this.termLabel = 'Conceito Geral (TG)';
      if (this.terms.length > 0) {
        this.editPlus = false;
      } else {
        this.editPlus = true;
      }
    } else if (this.label === 'narrow') {
      this.termLabel = 'Conceito Específico (TE)';
    } else if (this.label === 'related') {
      this.termLabel = 'Conceito Relacionado (TR)';
    } else if (this.label === 'exactMatch') {
      this.termLabel = 'Exact Match (SKOS)';
    } else if (this.label === 'linkedData') {
      this.termLabel = 'Linked Data (LD)';
    } else if (this.label === 'exactMatch') {
      this.termLabel = 'Exact Match (EM)';
    } else {
      this.termLabel = 'Termo ->' + this.label;
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
