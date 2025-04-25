import { Component, Input } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
    selector: 'app-form-textarea',
    templateUrl: './form-textarea.component.html',
    styleUrl: './form-textarea.component.scss',
    standalone: false
})
export class FormTextareaComponent {
  @Input() thesaID: number = 0;
  @Input() field: string = 'Introduction';
  orign: string = '';
  showSuccess: boolean = false;

  formAction: FormGroup;

  data: any = [];

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {
    this.formAction = this.fb.group({
      description: [''],
      field: [],
      thesaurus: [-1],
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  // ⛳ Este método é chamado automaticamente quando @Input() muda
  ngOnChanges(): void {
    this.formAction.patchValue({ thesaurus: this.thesaID });
    this.formAction.patchValue({ field: this.field });

    this.serviceThesa
      .api_post(
        'getDescription/' + this.thesaID + '/' + this.formAction.value.field,
        []
      )
      .subscribe((res) => {
        this.data = res;
        this.formAction.patchValue({ description: this.data.ds_descrition });
        this.orign = this.data.ds_descrition;
        console.log(this.data);
      });
  }

  loadOrigin() {
    this.formAction.patchValue({ description: this.orign });
  }

  onSubmit(): void {
    console.log('Valor enviado:', this.formAction.value);
    this.serviceThesa
      .api_post('saveDescription', this.formAction.value)
      .subscribe((res) => {
        this.data = res;

        // Exibe a mensagem de sucesso
        this.showSuccess = true;

        // Oculta após 5 segundos
        setTimeout(() => {
          this.showSuccess = false;
        }, 5000);
      });
  }
}
