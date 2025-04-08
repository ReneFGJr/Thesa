import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-term-pref-label',
  templateUrl: './term-pref-label.component.html',
  styleUrl: './term-pref-label.component.scss',
})
export class TermPrefLabelComponent {
  @Input() termID:number = 0;
  @Input() thesaID:number = 0;

}
