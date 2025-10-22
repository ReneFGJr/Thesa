import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-log-list-concept',
  standalone: false,
  templateUrl: './log-list-concept.component.html'
})
export class LogListConceptComponent {
  @Input() data: any;
}
