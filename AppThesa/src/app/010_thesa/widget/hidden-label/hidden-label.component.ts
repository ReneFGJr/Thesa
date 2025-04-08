import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-hidden-label',
  templateUrl: './hidden-label.component.html',
  styleUrl: './hidden-label.component.scss'
})
export class HiddenLabelComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;

}
