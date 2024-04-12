import { Component, Input } from '@angular/core';

@Component({
  selector: 'thesa-term',
  templateUrl: './term.component.html',
})
export class TermComponent {
  @Input() public data: Array<any> | any;

  ngOnInit()
    {
      console.log("+++++++++++++++")
      console.log(this.data);
    }

}
