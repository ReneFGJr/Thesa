import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';
import { Router } from '@angular/router';
import { ServiceThesaService } from '../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-social-signin',
  templateUrl: './signin.component.html',
  styleUrl: './signin.component.scss',
})
export class SigninComponent {
  form: FormGroup;
  data: Array<any> | any;

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private router: Router,
    private serviceThesa: ServiceThesaService
  ) {
    this.form = this.fb.group({
      email: ['', [Validators.required, Validators.email]], // certo!
      password: ['', Validators.required],
    });
  }

  submit() {
    const { email, password } = this.form.value;

    console.log("email", email);
    console.log("password", password);

    this.serviceThesa.api_post('social/login', { email, password }).subscribe(
      (res) => {
        console.log(res);
      }
    );
  }
}
