import { ServiceThesaService } from './../../000_core/service/service-thesa.service';
import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';
import { Router } from '@angular/router';

@Component({
    selector: 'app-signup',
    templateUrl: './signup.component.html',
    styleUrl: './signup.component.scss',
    standalone: false
})
export class SignupComponent {
  form: FormGroup;

  loading: boolean = false; // <— Adicionei esta propriedade para controle de loading
  signuped: boolean = false; // <— Adicionei esta propriedade para controle de loading
  data: Array<any> | any;

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private router: Router,
    private ServiceThesa: ServiceThesaService
  ) {
    this.form = fb.group({
      fullname: ['Rene FG Jr', Validators.required],
      email: ['rene.gabriel@ufrgs.br', Validators.required],
      institution: ['UFRGS', Validators.required],
    });
  }

  submit() {
    const { email, fullname, institution } = this.form.value;

    const userData = { fullname, email, institution };

    if (this.form.valid) {
      this.loading = true; // <— Inicia o loading
      console.log(userData);
      let url = 'social/signup';
      this.ServiceThesa.api_post(url, userData).subscribe(
        (response) => {
          // Handle success, e.g., navigate to a different page or show a success message
          this.loading = false; // <— Inicia o loading
          this.signuped = true; // <— Inicia o loading
          this.data = response

        },
        (error) => {
          console.error('Error creating user:', error);
          // Handle error, e.g., show an error message to the user
        }
        //    this.router.navigate(['/login']);
      );
    }
  }
}
