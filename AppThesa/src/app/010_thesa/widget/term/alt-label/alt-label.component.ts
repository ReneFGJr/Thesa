import { Component, Input } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
  selector: 'app-term-alt-label',
  templateUrl: './alt-label.component.html',
})
export class AltLabelComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() termID: number = 0;
  @Input() actionCV: string = '';
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  formAction: FormGroup;

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
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
    });
  }

  addLabel() {
    console.log('Dados Enviados', this.formAction.value);
    let url = 'relateTerms';
    this.serviceThesa.api_post(url, this.formAction.value).subscribe(
      (res) => {
        console.log('Resposta do servidor:', res);
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
