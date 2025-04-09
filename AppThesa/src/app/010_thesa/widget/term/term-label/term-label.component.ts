import { Component, EventEmitter, Input, Output } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-term-label',
  templateUrl: './term-label.component.html',
})
export class TermLabelComponent {
  @Input() termID: Array<any> | any;
  @Input() terms: Array<any> | any;
  @Input() label: string = '';
  @Input() editMode: boolean = false;
  @Output() action = new EventEmitter<any>();
  public termLabel = '';

  ngOnChages() {
    this.ngOnInit();
  }

  togglePanel(act: string) {
    this.action.emit(act);
  }

  ngOnInit() {
    if (this.label === 'prefLabel') {
      this.termLabel = 'Termo Preferencial';
    } else if (this.label === 'altLabel') {
      this.termLabel = 'Termo Alternativo';
    } else if (this.label === 'hiddenLabel') {
      this.termLabel = 'Termo Oculto';
    } else if (this.label === 'broader') {
      this.termLabel = 'Conceito Geral (TG)';
    } else if (this.label === 'narrow') {
      this.termLabel = 'Conceito Específico (TE)';
    } else if (this.label === 'related') {
      this.termLabel = 'Conceito Relacionado (TR)';
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
