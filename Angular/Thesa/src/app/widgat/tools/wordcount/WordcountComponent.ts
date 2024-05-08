import { Component } from '@angular/core';
import { FormBuilder, FormControl, FormGroup, Validators } from '@angular/forms';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';
import { LocalStorageService } from 'src/app/service/local-storage.service';


@Component({
  selector: 'app-wordcount',
  templateUrl: './wordcount.component.html',
})
export class WordcountComponent {
  public words: number = 0;
  public myForm: FormGroup | any;
  public th: number = 0;
  public data: Array<any> | any;

  constructor(
    private formBuilder: FormBuilder,
    private thesaServiceService: ThesaServiceService,
    private LocalStorageService: LocalStorageService
  ) {}

  ngOnInit() {
    this.myForm = this.formBuilder.group({
      text: ['', Validators.required],
      th: [this.th, Validators.required],
    });
  }

  submitForm() {
    if (this.myForm.valid) {
      this.thesaServiceService
        .api_post('tools/wordcount', this.myForm.value)
        .subscribe((res) => {
          this.data = res;
        });
    } else {
      alert('Dados inclompletos');
    }
  }
}
