import { Component, Input } from '@angular/core';
import { AbstractControl, FormBuilder, FormGroup, ValidationErrors, ValidatorFn, Validators } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';
import { ServiceThesaService } from '../../000_core/service/service-thesa.service';

@Component({
    selector: 'app-recover-password',
    templateUrl: './recover-password.component.html',
    styleUrl: './recover-password.component.scss',
    standalone: false
})
export class RecoverPasswordComponent {
  @Input() check: string = ''; // ou @Input() check: any = '';
  form: FormGroup;
  data: any; // ou algo como: data: { success: boolean; message: string; };
  active: boolean = true; // variável para controlar o estado do botão

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private serviceThesa: ServiceThesaService
  ) {
    this.form = this.fb.group(
      {
        pass1: ['', [Validators.required, Validators.minLength(5)]],
        pass2: ['', [Validators.required, Validators.minLength(5)]],
      },
      {
        validators: this.passwordsMatchValidator(),
      }
    );
  }

  ngOnInit() {

    let dt = {token: this.check}
    console.log('dt:', this.check);
        this.serviceThesa.api_post('social/checkLink', dt).subscribe(
          (res) => {
            console.log('res:', res);
            this.data = res;
            if (this.data.status != '200') {
              this.active = false;
            }
            else {
              this.active = true;
            }
          },
          (err) => {
            console.error('erro:', err);
            // aqui você pode setar um objeto de erro em `this.data`
          }
        );
  }

  // retorna um ValidatorFn que compara os dois campos
  private passwordsMatchValidator(): ValidatorFn {
    return (group: AbstractControl): ValidationErrors | null => {
      const p1 = group.get('pass1')?.value;
      const p2 = group.get('pass2')?.value;
      return p1 === p2 ? null : { passwordMismatch: true };
    };
  }

  submit() {
    if (this.form.invalid) {
      this.form.markAllAsTouched();
      return;
    }

    const dt = {
      token: this.check,
      password: this.form.value.pass1,
    };

    this.serviceThesa.api_post('social/password', dt).subscribe(
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
