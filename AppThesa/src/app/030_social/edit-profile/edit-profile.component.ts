import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AuthService } from '../../000_core/service/auth.service';
import { SocialService } from '../../000_core/service/social.service';

@Component({
  selector: 'app-edit-profile',
  templateUrl: './edit-profile.component.html',
  styleUrl: './edit-profile.component.scss',
  standalone: false,
})
export class EditProfileComponent implements OnInit {
  profileData: any = null;
  userData: any = null;
  tesauros: any[] = [];
  isLoading = true;
  errorMessage = '';
  successMessage = '';
  userId: string | number = '';
  apiKeyVisible = false;

  constructor(
    private socialService: SocialService,
    private authService: AuthService,
    private route: ActivatedRoute,
    private router: Router
  ) {}

  ngOnInit() {
    this.getUserIdAndLoadProfile();
  }

  /**
   * Obtém o ID do usuário e carrega seu perfil
   */
  private getUserIdAndLoadProfile() {
    const user = this.authService.getUser();
    this.userId = user?.userID || this.route.snapshot.queryParamMap.get('id') || '';

    if (!this.userId) {
      this.errorMessage = 'ID do usuário não encontrado. Faça login novamente.';
      this.isLoading = false;
      return;
    }

    this.loadUserProfile();
  }

  /**
   * Carrega o perfil do usuário da API
   */
  private loadUserProfile() {
    this.isLoading = true;
    this.errorMessage = '';

    this.socialService.getUserProfile(this.userId).subscribe({
      next: (response) => {
        if (response.status === '200') {
          this.profileData = response;
          this.userData = response.user;
          this.tesauros = response.tesauros || [];
        } else {
          this.errorMessage = response.message || 'Erro ao carregar perfil';
        }
        this.isLoading = false;
      },
      error: (error) => {
        console.error('Erro ao carregar perfil:', error);
        this.errorMessage = 'Erro ao carregar dados do perfil. Tente novamente.';
        this.isLoading = false;
      },
    });
  }

  /**
   * Formata a data para exibição
   */
  formatDate(date: string): string {
    if (!date || date === '0000-00-00 00:00:00') {
      return 'Nunca';
    }
    return new Date(date).toLocaleDateString('pt-BR');
  }

  /**
   * Cria a URL da imagem do tesauro
   */
  getThesaImageUrl(iconPath: string): string {
    return `http://thesa/${iconPath}`;
  }

  /**
   * Volta para a página anterior
   */
  goBack() {
    this.router.navigate(['/social/perfil']);
  }

  /**
   * Navega para a página de edição de dados pessoais
   */
  editPersonalData() {
    // Implementar página de edição de dados pessoais
    console.log('Editar dados pessoais');
  }

  /**
   * Abre o tesauro em uma nova aba
   */
  openThesa(thesaId: string | number) {
    window.open(`/thesa/${thesaId}`, '_blank');
  }

  /**
   * Alterna a visibilidade da API Key
   */
  toggleApiKeyVisibility() {
    this.apiKeyVisible = !this.apiKeyVisible;
  }
}
