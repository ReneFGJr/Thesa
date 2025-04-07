import { Component, Input } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
  selector: 'app-config-title',
  templateUrl: './config-title.component.html',
  styleUrl: './config-title.component.scss',
})
export class ConfigTitleComponent {
  @Input() thesaID: number = 0;

  formAction: FormGroup;
  orign_title: string = '';
  orign_achronic: string = '';
  showSuccess: boolean = false;

  data: any = [];

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {
    this.formAction = this.fb.group({
      title: [''],
      acronic: [''],
      thesaurus: [-1],
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  loadOrigin() {
    this.formAction.patchValue({ title: this.orign_title });
    this.formAction.patchValue({ acronic: this.orign_achronic });
  }

  // ⛳ Este método é chamado automaticamente quando @Input() muda
  ngOnChanges(): void {
    this.serviceThesa
      .api_post('th/' + this.thesaID + '/' + this.formAction.value.field, [])
      .subscribe((res) => {
        this.data = res;
        this.formAction.patchValue({ title: this.data.th_name });
        this.formAction.patchValue({ acronic: this.data.th_achronic });
        this.formAction.patchValue({ thesaurus: this.thesaID });
        this.orign_title = this.data.th_name;
        this.orign_achronic = this.data.th_achronic;
      });
  }

  onSubmit(): void {
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
