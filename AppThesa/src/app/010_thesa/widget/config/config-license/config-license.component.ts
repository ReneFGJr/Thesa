import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-config-license',
  templateUrl: './config-license.component.html',
  styleUrl: './config-license.component.scss',
})
export class ConfigLicenseComponent {
  @Input() thesaID: number = 0;
}
