import { language } from './../../../../../language/language_pt';
import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';
import { FormArray, FormBuilder, FormGroup } from '@angular/forms';

@Component({
    selector: 'app-config-language',
    templateUrl: './config-language.component.html',
    styleUrl: './config-language.component.scss',
    standalone: false
})
export class ConfigLanguageComponent {
  @Input() thesaID: number = 0;
  form: FormGroup;
  languages: any[] = [];
  message: string = '';
  showError: boolean = false;

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService
  ) {
    this.form = this.fb.group({
      languages: this.fb.array([]),
    });
  }

  ngOnInit(): void {
    this.serviceThesa
      .api_post('languages/' + this.thesaID, [])
      .subscribe((res: any) => {
        this.languages = res.languages;
        this.message = res.message;
        this.buildForm();
        // Oculta após 5 segundos
        setTimeout(() => {
          this.showError = false;
        }, 5000);
      });
  }

  buildForm() {
    const formArray = this.form.get('languages') as FormArray;
    this.languages.forEach((language) => {
      formArray.push(
        this.fb.group({
          id_lg: language.id_lg,
          label: language.label,
          checked: !!language.checked,
        })
      );
    });
  }

  get languagesFormArray(): FormArray {
    return this.form.get('languages') as FormArray;
  }

  onSubmit() {
    const selectedLanguages = this.form.value.languages
      .filter((lang: any) => lang.checked)
      .map((lang: any) => lang.id_lg);
    let dt = {'languages': selectedLanguages};

    this.serviceThesa
      .api_post('languages_set/' + this.thesaID, dt)
      .subscribe((res: any) => {
        this.message = res.message;
        this.showError = true;

        setTimeout(() => {
          this.showError = false;
        }, 5000);
      })
  }
}
