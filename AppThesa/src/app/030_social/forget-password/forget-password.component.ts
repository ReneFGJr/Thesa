import { Component } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';

@Component({
  selector: 'app-forget-password',
  templateUrl: './forget-password.component.html',
  styleUrl: './forget-password.component.scss'
})
export class ForgetPasswordComponent {
  form: FormGroup;

  constructor(private fb: FormBuilder, private auth: AuthService) {
    this.form = fb.group({
      email: [''],
    });
  }

  submit() {
    const { email } = this.form.value;
    if (this.auth.resetPassword(email)) {
      alert('Um link de recuperação foi enviado para o seu e-mail (simulado).');
    } else {
      alert('Email não encontrado.');
    }
  }
}
