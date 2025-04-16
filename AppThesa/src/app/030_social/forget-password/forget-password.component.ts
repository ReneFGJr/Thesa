import { Component } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';
import { ServiceThesaService } from '../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-forget-password',
  templateUrl: './forget-password.component.html',
  styleUrl: './forget-password.component.scss',
})
export class ForgetPasswordComponent {
  form: FormGroup;

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private serviceThesa: ServiceThesaService
  ) {
    this.form = fb.group({
      email: [''],
    });
  }

  submit() {
    const { email } = this.form.value;
    let dt = { email: email };
    console.log(dt);
    this.serviceThesa
      .api_post('social/forget', dt)
      .subscribe((res) => {
        console.log(res);
      });
  }
}
