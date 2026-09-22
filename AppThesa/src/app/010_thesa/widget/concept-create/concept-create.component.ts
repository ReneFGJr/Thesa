import { LanguageService } from '../../../000_core/service/language.service';
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
  isSubmitting: boolean = false;

  formAction: FormGroup;

  constructor(
    public readonly language: LanguageService,
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

  get candidateGroups(): { lang: string; terms: any[] }[] {
    const groups = new Map<string, any[]>();
    for (const term of this.termListCandidate ?? []) {
      const lang = term.lang || '';
      if (!groups.has(lang)) groups.set(lang, []);
      groups.get(lang)!.push(term);
    }
    return Array.from(groups, ([lang, terms]) => ({
      lang,
      terms: terms.sort((a, b) => String(a.term).localeCompare(String(b.term))),
    })).sort((a, b) => a.lang.localeCompare(b.lang));
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
    return this.termsArray.value.includes(String(id));
  }

  /** ✅ Selecionar todos */
  selectAll() {
    this.termsArray.clear();
    this.termListCandidate.forEach((term: any) => {
      this.termsArray.push(new FormControl(String(term.id)));
    });
  }

  /** ✅ Desmarcar todos */
  deselectAll() {
    this.termsArray.clear();
  }

  /** ✅ Enviar dados */
  unlinkSelected(): void {
    if (this.termsArray.length === 0 || this.isSubmitting) return;
    this.isSubmitting = true;
    this.showError = false;
    this.serviceThesa.api_post('term_unlink', this.formAction.value).subscribe({
      next: (res: any) => {
        this.isSubmitting = false;
        if (res.status == '200') {
          this.termsArray.clear();
          this.actionAC.emit('update');
        } else {
          this.showError = true;
          this.messageError = res.message || res.result;
        }
      },
      error: () => {
        this.isSubmitting = false;
        this.showError = true;
        this.messageError = this.language.labels().removeCandidateTermsFailed;
      },
    });
  }
  onSubmit(): void {
    if (this.isSubmitting) return;
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
