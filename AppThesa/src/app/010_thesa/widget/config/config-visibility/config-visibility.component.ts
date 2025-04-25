import { Component, Input } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
    selector: 'app-config-visibility',
    templateUrl: './config-visibility.component.html',
    styleUrl: './config-visibility.component.scss',
    standalone: false
})
export class ConfigVisibilityComponent {
  @Input() thesaID: number = 0;

  formAction: FormGroup;
  data: any = [];

  // 5 opções para o radio box
  radioOptions = [
    { label: 'Opção 1', value: '1' },
    { label: 'Opção 2', value: '2' },
    { label: 'Opção 3', value: '3' },
    { label: 'Opção 4', value: '4' },
    { label: 'Opção 5', value: '5' },
  ];

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {
    this.formAction = this.fb.group({
      title: [''],
      acronic: [''],
      classification: [''], // novo campo
      thesaurus: [-1],
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  ngOnChanges(): void {
    this.formAction.patchValue({ thesaurus: this.thesaID });

    this.serviceThesa
      .api_post(
        'getDescription/' + this.thesaID + '/' + this.formAction.value.field,
        []
      )
      .subscribe((res) => {
        this.data = res;
        this.formAction.patchValue({ description: this.data.ds_descrition });
        console.log(this.data);
      });
  }

  onSubmit(): void {
    console.log('Valor enviado:', this.formAction.value);
    this.serviceThesa
      .api_post('saveTh', this.formAction.value)
      .subscribe((res) => {
        this.data = res;
      });
  }
}
