import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-narrow',
  templateUrl: './narrow.component.html',
  styleUrl: './narrow.component.scss'
})
export class NarrowComponent {
 @Input() term: Array<any> | any;
}
