import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-about',
  templateUrl: './about.component.html',
})
export class AboutComponent {
  @Input() public th: number = 0
  @Input() public data:Array<any> | any
}
