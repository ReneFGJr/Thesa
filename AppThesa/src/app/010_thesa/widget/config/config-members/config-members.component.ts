import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { FormBuilder, FormGroup } from '@angular/forms';

@Component({
  selector: 'app-config-members',
  templateUrl: './config-members.component.html',
  styleUrl: './config-members.component.scss',
})
export class ConfigMembersComponent {
  @Input() thesaID: number = 0;
  members: Array<any> | any;

  form: FormGroup;
  results: Array<any> | any;
  loading = false;

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService
  ) {
    this.form = this.fb.group({
      search: [''],
      type: [''],
    });
  }

  ngOnInit() {
    this.getMembers(this.thesaID);
  }

  removeMember(id: string) {
    alert(id);
  }

  getMembers(thesaID: number) {
    this.serviceThesa.api_post('members/' + thesaID, []).subscribe(
      (res) => {
        console.log(res);
        this.members = res;
      },
      (error) => error
    );
  }

  onSubmit()
    {
      const query = this.form.value.search;
      if (!query) return;

      this.loading = true;
      this.results = [];


    let dt = {'query': query, 'thesaID': this.thesaID};
    console.log(dt);

    this.serviceThesa.api_post('members_search', dt).subscribe(
      (res) => {
        console.log(res);
        this.results = res;
        this.results = this.results.names;
        this.loading = false;
        console.log(res);
      },
      (error) => error
    );
    }
}
