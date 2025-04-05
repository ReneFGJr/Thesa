import { Component, Input } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
  selector: 'app-config-introdution',
  templateUrl: './config-introdution.component.html',
  styleUrl: './config-introdution.component.scss',
})
export class ConfigIntrodutionComponent {
  @Input() thesaID: number = 0;
  formAction: FormGroup;
  data: any = [];

  editorConfig: AngularEditorConfig = {
    editable: true,
    spellcheck: true,
    height: '200px',
    minHeight: '150px',
    placeholder: 'Digite o conteúdo...',
    translate: 'no',
    defaultParagraphSeparator: 'p',
    toolbarHiddenButtons: [
      ['subscript', 'superscript'],
      ['insertImage', 'insertVideo'],
    ],
  };

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {
    this.formAction = this.fb.group({
      description: [''],
      field: ['Introduction'],
      thesaurus: [-1],
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  // ⛳ Este método é chamado automaticamente quando @Input() muda
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
      .api_post('saveDescription', this.formAction.value)
      .subscribe((res) => {
        this.data = res;
      });
  }
}
