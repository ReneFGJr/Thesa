import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-broader',
  templateUrl: './broader.component.html',
  styleUrl: './broader.component.scss'
})
export class BroaderComponent {
  @Input() term: Array<any> | any;

}
