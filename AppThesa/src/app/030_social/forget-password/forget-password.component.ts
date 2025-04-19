import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';
import { ServiceThesaService } from '../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-forget-password',
  templateUrl: './forget-password.component.html',
  styleUrls: ['./forget-password.component.scss'], // <— corrigi aqui
})
export class ForgetPasswordComponent {
  form: FormGroup;
  data: any; // ou algo como: data: { success: boolean; message: string; };

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private serviceThesa: ServiceThesaService
  ) {
    this.form = this.fb.group({
      email: [
        'renefgj@gmail.com',
        [Validators.required], // <— Validators em array
      ],
    });
  }

  submit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    const { email } = this.form.value;
    this.serviceThesa.api_post('social/forget', { email }).subscribe(
      (res) => {
        console.log('res:', res);
        this.data = res;
      },
      (err) => {
        console.error('erro:', err);
        // aqui você pode setar um objeto de erro em `this.data`
      }
    );
  }
}
