import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-th-show',
  templateUrl: './th-show.component.html',
  styleUrl: './th-show.component.scss'
})
export class ThShowComponent {
  @Input() thesa: any;
  @Input() tab: string='';
}
