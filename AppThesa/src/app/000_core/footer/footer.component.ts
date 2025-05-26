import { Component } from '@angular/core';

@Component({
  selector: 'app-footer',
  templateUrl: './footer.component.html',
  styleUrl: './footer.component.scss',
  standalone: false,
})
export class FooterComponent {
  logo_ppgcin = 'assets/img/logo/logo_ppgcin.png';
  logo_orclab = 'assets/img/logo/logo_orcalab.png';
  logo_cnpq = 'assets/img/logo/cnpq-logo.png';
  logo_github = 'assets/img/logo/github.svg';
}
