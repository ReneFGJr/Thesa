import { Component } from '@angular/core';

@Component({
    selector: 'app-logo-big',
    templateUrl: './logo-big.component.html',
    styleUrl: './logo-big.component.scss',
    standalone: false
})
export class LogoBigComponent {
  imageUrl = 'assets/img/logo/logo_thesa.svg'
  imageUrlThesa = 'assets/img/logo/THESA-V2-txt-1.svg'
  imageUrlThesa2 = 'assets/img/logo/THESA-V2-txt-2.svg'
}
