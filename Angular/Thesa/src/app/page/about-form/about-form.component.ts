import { Component } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-about-form',
  templateUrl: './about-form.component.html'
})
export class AboutFormComponent {
  public userForm: FormGroup | any

  constructor(private fb: FormBuilder) {}

  ngOnInit(): void {
    this.userForm = this.fb.group({
      th_name: ['XX', Validators.required],
      th_achronic: ['||', Validators.required],
      th_description: ['ZZ'],
      th_type: ['', Validators.required],
      //th_achronic: ['', Validators.required],
      //th_description: ['', [Validators.required, Validators.email]],
    });
  }

  onSubmit(): void {
    if (this.userForm.valid) {
      console.log('Dados do usuário:', this.userForm.value);
      // Aqui você pode adicionar lógica para enviar os dados para um serviço ou realizar outras operações
    }
  }
}
