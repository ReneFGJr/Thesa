import { Component } from '@angular/core';
import { AuthService } from '../../000_core/service/auth.service';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.component.html',
  styleUrl: './perfil.component.scss',
  standalone: false,
})
export class PerfilComponent {
  user: any = {
    name: 'Usuário Exemplo',
    email: 'usuario@example.com',
    bio: 'Desenvolvedor apaixonado por tecnologia.',
    created_at: new Date('2022-01-01'),
    active: true,
    logs: [
      { action: 'Login', date: new Date('2022-01-02') },
      { action: 'Atualização de perfil', date: new Date('2022-01-03') },
    ],
  };

  constructor(private authService: AuthService) {}

  ngOnInit() {
    this.user = this.authService.getUser()
    console.log(this.user);

  }
}
