import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { Router } from '@angular/router';

@Component({
    selector: 'app-thesa-create',
    templateUrl: './thesa-create.component.html',
    styleUrl: './thesa-create.component.scss',
    standalone: false
})
export class ThesaCreateComponent {
  thesaID: number = 0;

  formAction: FormGroup;
  orign_title: string = '';
  orign_achronic: string = '';
  showSuccess: boolean = false;
  showError: boolean = false;

  data: any = [];

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {
    this.formAction = this.fb.group({
      title: ['', [Validators.required, Validators.minLength(10)]],
      acronic: ['', [Validators.required, Validators.minLength(4)]],
      thesaurus: [-1],
      type: [0, [Validators.required, Validators.min(1)]],
      visibility: [0, [Validators.required, Validators.min(1)]],
      finality: [0, [Validators.required, Validators.min(1)]],
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
        this.formAction.patchValue({ title: '' });
        this.formAction.patchValue({ acronic: '' });
        this.formAction.patchValue({ type: 0 });
        this.formAction.patchValue({ visibility: 0 });
        this.formAction.patchValue({ finality: 0 });
        this.formAction.patchValue({ thesaurus: -1 });
        this.orign_title = '';
        this.orign_achronic = '';
      });
  }

  onSubmit(): void {
    if (this.formAction.invalid) {
      this.showError = true;

      // Oculta após 5 segundos
      setTimeout(() => {
        this.showError = false;
      }, 5000);

    } else {
      this.serviceThesa
        .api_post('createThesa', this.formAction.value)
        .subscribe((res) => {
          this.data = res;
          // Exibe a mensagem de sucesso
          this.showSuccess = true;
          console.log(this.data);

          // Oculta após 5 segundos
          setTimeout(() => {
            this.showSuccess = false;
            //Router.navigate(['/thesa']);
          }, 5000);
        });
    }
  }
}
