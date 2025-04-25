import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
    selector: 'app-config-members',
    templateUrl: './config-members.component.html',
    styleUrl: './config-members.component.scss',
    standalone: false
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
      search: ['', Validators.required],
      type: ['', Validators.required],
    });
  }

  ngOnInit() {
    this.getMembers(this.thesaID);
  }

  removeMember(id: string) {
    if (confirm('Deseja remover este membro?')) {
    let dt = {'id': id, 'thesaID': this.thesaID};
        this.serviceThesa.api_post('members_remove', dt).subscribe(
          (res) => {
            this.getMembers(this.thesaID);
          },
          (error) => error
        );
    }
  }

  getMembers(thesaID: number) {
    this.serviceThesa.api_post('members/' + thesaID, []).subscribe(
      (res) => {
        this.members = res;
      },
      (error) => error
    );
  }

  onSubmit()
    {
      const query = this.form.value.search;
      const type = this.form.value.type;
      if (!query) return;

      this.loading = true;
      this.results = [];


    let dt = {'query': query, 'type': type, 'thesaID': this.thesaID};
    console.log('=======',dt);

    this.serviceThesa.api_post('members_register', dt).subscribe(
      (res) => {
        this.results = res;
        console.log('Resposta do servidor:', res);
        this.results = this.results.names;
        this.loading = false;
        this.getMembers(this.thesaID);
      },
      (error) => error
    );
    }
}
