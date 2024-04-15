import { Component, Input, Output, EventEmitter } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-term-list',
  templateUrl: './list.component.html'
})
export class ListComponent {
  @Input() public data: Array<any> | any;
  @Output() public IDEvent = new EventEmitter<number>();

  constructor(private router: Router) {}

  NgOnInit() {}

  viewTerm(ID: number = 0) {
    this.IDEvent.emit(ID);
  }
}
