import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';
import { LocalStorageService } from 'src/app/service/local-storage.service';

@Component({
  selector: 'app-term-new',
  templateUrl: './term-new.component.html'
})
export class TermNewComponent {
  public form: boolean = true;
  public th: number = 0;
  public langs: Array<any> = [
    { Language: 'Portugues', Code: 'por' },
    { Language: 'Inglês', Code: 'eng' },
    { Language: 'Espanhol', Code: 'esp' },
  ];
  public myForm: FormGroup | any;
  public data: Array<any> | any;
  constructor(
    private formBuilder: FormBuilder,
    private thesaServiceService: ThesaServiceService,
    private LocalStorageService: LocalStorageService
  ) {}

  ngOnInit() {
    this.th = this.LocalStorageService.get('th');

    this.myForm = this.formBuilder.group({
      terms: ['', Validators.required],
      lang: ['', Validators.required],
      th: [this.th, Validators.required],
    });
  }

  submitForm() {
    if (this.myForm.valid) {
      this.thesaServiceService
        .api_post('term_add', this.myForm.value)
        .subscribe((res) => {
          this.data = res;
        });
    } else {
      alert('Dados inclompletos');
    }
  }
}
