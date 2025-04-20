import { Component, EventEmitter, Input, Output } from '@angular/core';
import {
  FormArray,
  FormBuilder,
  FormControl,
  FormGroup,
  Validators,
} from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';
import { PainelService } from '../../../../000_core/service/painel.service';

@Component({
  selector: 'app-term-attribut-label',
  templateUrl: './atribute-label.component.html',
})
export class AltLabelComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() termID: number = 0;
  @Input() actionCV: string = '';
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  @Output() action = new EventEmitter<any>();
  formAction: FormGroup;
  title: string = 'Termos';

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private painelService: PainelService
  ) {
    this.formAction = this.fb.group({
      terms: this.fb.array([], Validators.required),
      thesaID: [-1],
      conceptID: [-1],
      verb: this.actionCV,
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  ngOnChanges() {
    setTimeout(() => {
      this.formAction.patchValue({
        thesaID: this.thesaID,
        conceptID: this.conceptID,
        verb: this.actionCV,
      });
      /************ Titulo da Página */
      if (this.actionCV === 'prefLabel') {
        this.title = 'Termo Preferido';
      } else if (this.actionCV === 'altLabel') {
        this.title = 'Termos Alternativos';
      } else if (this.actionCV === 'hiddenLabel') {
        this.title = 'Termos Ocultos';
      }
    });
  }

  addLabel() {
    console.log('Dados Enviados', this.formAction.value);
    let url = 'relateTerms';
    this.serviceThesa.api_post(url, this.formAction.value).subscribe(
      (res) => {
        console.log('Resposta do servidor:', res);
        this.painelService.closeConceptPanel('popupConcept');
        this.action.emit('reload');
      },
      (error) => {
        console.error('Erro ao enviar os dados:', error);
      }
    );
  }

  onCheckboxChange(event: any) {
    const termsArray: FormArray = this.formAction.get('terms') as FormArray;

    if (event.target.checked) {
      termsArray.push(new FormControl(event.target.value));
    } else {
      const index = termsArray.controls.findIndex(
        (x) => x.value === event.target.value
      );
      if (index >= 0) {
        termsArray.removeAt(index);
      }
    }
  }
}
