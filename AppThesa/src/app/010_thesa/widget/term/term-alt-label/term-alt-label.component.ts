import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-term-alt-label',
  templateUrl: './term-alt-label.component.html',
  styleUrl: './term-alt-label.component.scss'
})
export class TermAltLabelComponent {
  @Input() termID:number = 0;
  @Input() thesaID:number = 0;
  @Input() property:string = '';
}
