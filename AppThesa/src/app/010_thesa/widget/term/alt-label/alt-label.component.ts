import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-term-alt-label',
  templateUrl: './alt-label.component.html',
  styleUrl: './alt-label.component.scss',
})
export class AltLabelComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() termID: number = 0;




}
