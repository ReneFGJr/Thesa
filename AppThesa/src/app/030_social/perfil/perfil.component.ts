import { Component, OnInit } from '@angular/core';
import { AuthService } from '../../000_core/service/auth.service';
import { SocialService } from '../../000_core/service/social.service';

@Component({
  selector: 'app-perfil',
  templateUrl: './perfil.component.html',
  styleUrl: './perfil.component.scss',
  standalone: false,
})
export class PerfilComponent implements OnInit {
  user: any = {
    name: 'Usuário Exemplo',
    email: 'usuario@example.com',
    bio: 'Desenvolvedor apaixonado por tecnologia.',
    created_at: new Date('2022-01-01'),
    active: true,
    apikey: '',
    logs: [
      { action: 'Login', date: new Date('2022-01-02') },
      { action: 'Atualização de perfil', date: new Date('2022-01-03') },
    ],
  };

  isLoading = true;
  userId: string | number = '';
  apiKeyVisible = false;

  constructor(
    private authService: AuthService,
    private socialService: SocialService
  ) {}

  ngOnInit() {
    this.loadUserProfile();
  }

  private loadUserProfile() {
    const authUser = this.authService.getUser();
    this.userId = authUser?.userID || '';

    if (this.userId) {
      this.socialService.getUserProfile(this.userId).subscribe({
        next: (response) => {
          if (response.status === '200' && response.user) {
            this.user = {
              ...response.user,
              bio: response.user.instituicao || 'Instituição não informada',
              active: response.user.verificado === '1',
              apikey: response.user.apikey,
              apikey_ativo: response.user.apikey_ativo,
            };
          }
          this.isLoading = false;
        },
        error: (error) => {
          console.error('Erro ao carregar perfil:', error);
          this.isLoading = false;
        },
      });
    } else {
      this.isLoading = false;
    }
  }

  toggleApiKeyVisibility() {
    this.apiKeyVisible = !this.apiKeyVisible;
  }

  copyApiKeyToClipboard() {
    if (this.user?.apikey) {
      navigator.clipboard.writeText(this.user.apikey).then(() => {
        alert('API Key copiada para a área de transferência!');
      }).catch(() => {
        alert('Erro ao copiar. Tente manualmente.');
      });
    }
  }
}
