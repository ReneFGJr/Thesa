import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../service/service-thesa.service';
import { ServiceStorageService } from '../service/service-storage.service';
import { ActivatedRoute, Router } from '@angular/router';
import { environment } from '../../../environments/environment';

@Component({
    selector: 'app-navbar',
    templateUrl: './navbar.component.html',
    styleUrl: './navbar.component.scss',
    standalone: false
})
export class NavbarComponent {
  @Input() editMode: boolean = false;
  @Input() user: Array<any> = [];
  logo = 'assets/img/logo/logo_thesa.svg';
  URLhome = environment.Url;

  goURL(URL: string) {
    this.router.navigate([URL]);
  }

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: Router
  ) {}
}
