import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-related',
  templateUrl: './related.component.html',
  styleUrl: './related.component.scss'
})
export class RelatedComponent {
 @Input() terms: Array<any> | any;
}
