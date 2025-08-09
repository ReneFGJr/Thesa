import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';

interface Opcao {
  value: string;
  label: string;
}

@Component({
  selector: 'app-concept-import-thesa',
  standalone: false,
  templateUrl: './concept-import-thesa.component.html',
  styleUrl: './concept-import-thesa.component.scss',
})
export class ConceptImportThesaComponent {
  form: FormGroup;
  urlForm: FormGroup;
  submitted = false;
  data: any;

  opcoes: Opcao[] = [
    { value: 'opcao-1', label: 'Opção 1' },
    { value: 'opcao-2', label: 'Opção 2' },
    { value: 'opcao-3', label: 'Opção 3' },
  ];

  constructor(
    private fb: FormBuilder,
    private thesaaService: ServiceThesaService
  ) {
    this.form = this.fb.group({
      texto: ['', [Validators.required, Validators.minLength(3)]],
      escolha: ['', Validators.required],
    });

    this.urlForm = this.fb.group({
      url: [
        'http://localhost:4200/c/332',
        [
          Validators.required,
          Validators.minLength(10),
          Validators.pattern('https?://.+'),
        ],
      ],
    });
  }

  onSubmit(): void {
    this.submitted = true;
    if (this.form.invalid) return;
    console.log('Valores do formulário:', this.form.value);
    // ...faça algo com os dados
  }

  onSubmitUrl(): void {
    this.submitted = true;
    if (this.urlForm.invalid) return;
    console.log('Valores do formulário de URL:', this.urlForm.value);
    if (confirm('Você tem certeza que deseja excluir o conceito?')) {
      // Lógica para excluir o conceito
      let dt = { uri: this.urlForm.get('url')?.value };
      this.thesaaService
        .api_post('concept_import_uri', dt)
        .subscribe(
          (res) => {
            this.data = res;
            console.log('Conceito excluído com sucesso:', this.data);
          },
          (error) => error
        );
    }
  }

  reset(): void {
    this.submitted = false;
    this.form.reset();
  }

  reset2(): void {
    this.submitted = false;
    this.urlForm.reset();
  }
}
