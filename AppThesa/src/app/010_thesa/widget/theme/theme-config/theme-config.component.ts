import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-theme-config',
  standalone: false,
  templateUrl: './theme-config.component.html',
  styleUrl: './theme-config.component.scss'
})
export class ThemeConfigComponent {
  @Input() thesaID: number = 0;
  @Input() thesa: any;
}
