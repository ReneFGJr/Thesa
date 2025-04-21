import { Component } from '@angular/core';
import { AuthService } from '../../000_core/service/auth.service';
import { Router } from '@angular/router';
import { ServiceThesaService } from '../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-logout',
  templateUrl: './logout.component.html',
  styleUrl: './logout.component.scss'
})
export class LogoutComponent {
  data: Array<any> | any;

  constructor(
    private auth: AuthService,
    private router: Router,
    private serviceThesa: ServiceThesaService
  ) {}

  ngOnInit() {
    this.data = this.auth.logoff();

    // Oculta após 5 segundos
    setTimeout(() => {
      location.assign('/');
    }, 1000);
  }
}
