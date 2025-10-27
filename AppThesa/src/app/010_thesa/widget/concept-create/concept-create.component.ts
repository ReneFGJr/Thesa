import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormArray, FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';

@Component({
  selector: 'app-concept-create',
  templateUrl: './concept-create.component.html',
  styleUrl: './concept-create.component.scss',
  standalone: false,
})
export class ConceptCreateComponent {
  @Input() thesaID: number = 0;
  @Input() termListCandidate: any;
  @Output() actionAC = new EventEmitter<string>();

  data: any = [];
  field: string = 'ds_term';
  orign: string = '';
  showError: boolean = false;
  messageError: string = '';

  formAction: FormGroup;

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {
    this.formAction = this.fb.group({
      terms: this.fb.array([], { validators: [Validators.required] }),
      thesaID: [-1],
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  get termsArray(): FormArray {
    return this.formAction.get('terms') as FormArray;
  }

  ngOnInit(): void {
    this.formAction.patchValue({ thesaID: this.thesaID });
  }

  /** ✅ Marca e desmarca individualmente */
  onCheckboxChange(event: any) {
    const id = event.target.value;
    if (event.target.checked) {
      this.termsArray.push(new FormControl(id));
    } else {
      const index = this.termsArray.controls.findIndex((x) => x.value === id);
      if (index >= 0) this.termsArray.removeAt(index);
    }
  }

  /** ✅ Verifica se um termo está marcado */
  isChecked(id: string): boolean {
    return this.termsArray.value.includes(id);
  }

  /** ✅ Selecionar todos */
  selectAll() {
    this.termsArray.clear();
    this.termListCandidate.forEach((term: any) => {
      this.termsArray.push(new FormControl(term.id));
    });
  }

  /** ✅ Desmarcar todos */
  deselectAll() {
    this.termsArray.clear();
  }

  /** ✅ Enviar dados */
  onSubmit(): void {
    this.serviceThesa
      .api_post('concept_create_term', this.formAction.value)
      .subscribe((res) => {
        this.data = res;
        console.log('Resposta do conceito criado:', this.data);
        if (this.data.status == '200') {
          this.termsArray.clear(); // limpa seleção
          this.showError = false;
          this.termListCandidate = [];
          this.actionAC.emit('update');
        } else {
          this.showError = true;
          this.messageError = this.data.result;
        }
      });
  }
}
