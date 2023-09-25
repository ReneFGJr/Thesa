import { Component, Input } from '@angular/core';

@Component({
  selector: 'thesa-thesauros',
  templateUrl: './thesauros.component.html',
})
export class ThesaurosComponent {
 @Input() public thesa:Array<any>|any
}
