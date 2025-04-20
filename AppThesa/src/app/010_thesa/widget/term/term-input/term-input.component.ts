import { language } from './../../../../../language/language_pt';
import { Component, EventEmitter, Input, Output, output } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';
import { PainelService } from '../../../../000_core/service/painel.service';


@Component({
  selector: 'app-term-input',
  templateUrl: './term-input.component.html'
})
export class TermInputComponent {
  @Input() thesaID: number = 0;
  @Output() action = new EventEmitter<string>();
  field: string = 'ds_term';
  orign: string = '';
  showSuccess: boolean = false;
  showError: boolean = false;
  messageError: string = '';

  formAction: FormGroup;

  data: any = [];
  languages: Array<any> | any

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private painelService: PainelService
  ) {
    this.formAction = this.fb.group({
      terms: [''],
      lang: [1],
      thesaID: [-1],
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  ngOnInit() {
    /* Carrega Idiomas */
    this.serviceThesa.api_post('getLanguages/' + this.thesaID, []).subscribe((res) => {
      this.languages = res;
      console.log('Resposta do servidor:', res);
    });
  }

  // ⛳ Este método é chamado automaticamente quando @Input() muda
  ngOnChanges(): void {
      this.formAction.patchValue({ thesaID: this.thesaID });
  }

  loadOrigin() {
    this.formAction.patchValue({ thesaID: this.thesaID });
    this.painelService.closeConceptPanel('novoPainel');
    this.action.emit('reload');
  }

  onSubmit(): void {
    this.serviceThesa
      .api_post('term_add', this.formAction.value)
      .subscribe((res) => {
        this.data = res;
        console.log('Resposta do servidor:', res);
        if (this.data.status == '200') {
          // Exibe a mensagem de sucesso
          this.showSuccess = true;
          this.formAction.patchValue({ terms: '' });

          this.action.emit('reload');

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
