import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-alt-label',
  templateUrl: './alt-label.component.html',
  styleUrl: './alt-label.component.scss',
})
export class AltLabelComponent {
  @Input() terms: Array<any> | any;
}
