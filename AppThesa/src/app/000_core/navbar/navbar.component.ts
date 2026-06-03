import { Component, Input, OnInit, OnDestroy } from '@angular/core';
import { ServiceThesaService } from '../service/service-thesa.service';
import { ServiceStorageService } from '../service/service-storage.service';
import { Router } from '@angular/router';
import { environment } from '../../../environments/environment';
import { Subscription } from 'rxjs';

declare var bootstrap: any; // garante acesso à API JS do Bootstrap

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss',
  standalone: false,
})
export class NavbarComponent implements OnInit, OnDestroy {
  @Input() editMode: boolean = false;
  @Input() user: Array<any> = [];
  logo = 'assets/img/logo/logo_thesa.svg';
  URLhome = environment.Url;
  hasThesaSelected: boolean = false;
  private storageSubscription: Subscription | null = null;

  goURL(URL: string) {
    this.router.navigate([URL]);
  }

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: Router
  ) {}

  ngOnInit(): void {
    this.checkThesaActual();
    this.monitorStorageChanges();
  }

  ngOnDestroy(): void {
    if (this.storageSubscription) {
      this.storageSubscription.unsubscribe();
    }
  }

  // Verifica se existe thesaActual no localStorage
  checkThesaActual() {
    const thesaActual = this.serviceStorage.get('thesaActual');
    this.hasThesaSelected = !!thesaActual;
  }

  // Monitora mudanças no localStorage em tempo real
  monitorStorageChanges() {
    this.storageSubscription = this.serviceStorage
      .getStorageChanges()
      .subscribe((change) => {
        if (change.key === 'thesaActual') {
          this.checkThesaActual();
        }
      });
  }

  // Fecha o menu quando um link é clicado
  closeMenu() {
    const navbar = document.getElementById('navbarNavX');
    if (navbar && navbar.classList.contains('show')) {
      const collapse =
        bootstrap.Collapse.getInstance(navbar) ||
        new bootstrap.Collapse(navbar);
      collapse.hide();
    }
  }

  // Alterna o menu quando o botão hamburguer é clicado
  toggleMenu() {
    const navbar = document.getElementById('navbarNavX');
    if (navbar) {
      const collapse =
        bootstrap.Collapse.getInstance(navbar) ||
        new bootstrap.Collapse(navbar);
      if (navbar.classList.contains('show')) {
        collapse.hide();
      } else {
        collapse.show();
      }
    }
  }
}
