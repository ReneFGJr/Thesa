import { Component, Input } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { PainelService } from '../../../000_core/service/painel.service';

@Component({
  selector: 'app-concept-relation',
  templateUrl: './concept.component.html',
  styleUrl: './concept.component.scss',
})
export class ConceptComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() termID: number = 0;
  @Input() actionCV: string = '';
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  formAction: FormGroup;
  title: string = 'Termos';

  form: FormGroup = this.fb.group({
    termId: [''], // valor inicial
    });

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

  close() {
    this.painelService.closeConceptPanel('popupConcept');
  }

  onSubmit() {
    console.log(this.form.value); // Aqui você acessa o ID selecionado
  }

  ngOnChanges() {
    this.form = this.fb.group({
      termId: [''], // valor inicial
    });

    setTimeout(() => {
      this.formAction.patchValue({
        thesaID: this.thesaID,
        conceptID: this.conceptID,
        verb: this.actionCV,
      });
      /************ Titulo da Página */
      if (this.actionCV === 'broader') {
        this.title = 'Conceito de Geral';
      } else if (this.actionCV === 'narrow') {
        this.title = 'Conceito Específico';
      } else if (this.actionCV === 'related') {
        this.title = 'Conceito Relacionado';
      }
    });
  }
}
