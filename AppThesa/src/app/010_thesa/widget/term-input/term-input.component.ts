import { Component, EventEmitter, Input, Output, output } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';

@Component({
  selector: 'app-term-input',
  templateUrl: './term-input.component.html',
  styleUrl: './term-input.component.scss',
})
export class TermInputComponent {
  @Input() thesaID: number = 0;
  @Output() saved = new EventEmitter<string>();
  field: string = 'ds_term';
  orign: string = '';
  showSuccess: boolean = false;
  showError: boolean = false;
  messageError: string = '';

  formAction: FormGroup;

  data: any = [];

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {
    this.formAction = this.fb.group({
      terms: [''],
      lang: [1],
      thesaID: [-1],
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  // ⛳ Este método é chamado automaticamente quando @Input() muda
  ngOnChanges(): void {
      this.formAction.patchValue({ thesaID: this.thesaID });
  }

  loadOrigin() {
    this.formAction.patchValue({ thesaID: this.thesaID });
  }

  onSubmit(): void {
    console.log('Valor enviado:', this.formAction.value);
    this.serviceThesa
      .api_post('term_add', this.formAction.value)
      .subscribe((res) => {
        this.data = res;
        console.log('Resposta do servidor:', res);
        if (this.data.status == '200') {
          // Exibe a mensagem de sucesso
          this.showSuccess = true;
          this.formAction.patchValue({ terms: '' });

          this.saved.emit('1');

          // Oculta após 5 segundos
          setTimeout(() => {
            this.showSuccess = false;
          }, 5000);
        } else {
          // Exibe a mensagem de erro
          this.showError = true;
          this.messageError = this.data.message;

          // Oculta após 5 segundos
          setTimeout(() => {
            this.showError = false;
          }, 5000);
        }
      });
  }
}
