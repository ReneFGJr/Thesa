import { Component } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { AuthService } from '../../000_core/service/auth.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-social-signin',
  templateUrl: './signin.component.html',
  styleUrl: './signin.component.scss',
})
export class SigninComponent {
  form: FormGroup;

  constructor(
    private fb: FormBuilder,
    private auth: AuthService,
    private router: Router
  ) {
    this.form = fb.group({
      fullname: [''],
      email: [''],
      institution: [''],
      password: [''],
    });
  }

  submit() {
    const { email, password, fullname, institution } = this.form.value;

    const userData = { fullname, institution, password };
    if (localStorage.getItem(email)) {
      alert('Este e-mail já está cadastrado.');
      return;
    }

    localStorage.setItem(email, JSON.stringify(userData));
    alert('Cadastro realizado com sucesso!');
    this.router.navigate(['/login']);
  }
}
