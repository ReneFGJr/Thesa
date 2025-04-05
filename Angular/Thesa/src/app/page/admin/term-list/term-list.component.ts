import { Component } from '@angular/core';
import { FormArray, FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';
import { LocalStorageService } from 'src/app/service/local-storage.service';

@Component({
  selector: 'app-term-list',
  templateUrl: './term-list.component.html',
})
export class TermListComponent {
  public th: number = 0;
  public form: FormGroup | any;
  public data: Array<any> | any;
  public items: Array<any> = [];

  constructor(
    private fb: FormBuilder,
    private thesaServiceService: ThesaServiceService,
    private LocalStorageService: LocalStorageService
  ) {}

  ngOnInit() {
    this.th = this.LocalStorageService.get('th');

    this.form = this.fb.group({
      it: [this.items, Validators.required],
      th: [this.th, Validators.required],
    });

    this.thesaServiceService
      .api_post('term_list', this.form.value)
      .subscribe((res) => {
        this.data = res;
      });
  }

  checkBox(ch: any, id: string) {
    const index = this.items.indexOf(id);
    if (index > -1) {
      this.items.splice(index, 1)
    } else {
      this.items.push(id);
    }
  }

  onSubmit() {
    this.thesaServiceService
      .api_post('concept_create_term', this.form.value)
      .subscribe((res) => {
        console.log(res)
      });
  }
}
