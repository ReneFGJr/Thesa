import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';
import { ServiceThesaService } from '../../000_core/service/service-thesa.service';

@Component({
    selector: 'app-forget-password',
    templateUrl: './forget-password.component.html',
    styleUrls: ['./forget-password.component.scss'],
    standalone: false
})
export class ForgetPasswordComponent {
  form: FormGroup;
  data: any; // ou algo como: data: { success: boolean; message: string; };
  loading: boolean = false; // <— Adicionei esta propriedade para controle de loading

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private serviceThesa: ServiceThesaService
  ) {
    this.form = this.fb.group({
      email: [
        '',
        [Validators.required], // <— Validators em array
      ],
    });
  }

  submit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    this.loading = true; // <— Inicia o loading

    const { email } = this.form.value;
    this.serviceThesa.api_post('social/forget', { email }).subscribe(
      (res) => {
        console.log('res:', res);
        this.data = res;
        this.loading = false; // <— Inicia o loading
      },
      (err) => {
        console.error('erro:', err);
        this.loading = false; // <— Inicia o loading
        // aqui você pode setar um objeto de erro em `this.data`
      }
    );
  }
}
