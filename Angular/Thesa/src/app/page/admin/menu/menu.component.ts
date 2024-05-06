import { Component, EventEmitter, Output } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-admin-menu',
  templateUrl: './menu.component.html',
  styleUrls: ['./menu.component.css'],
})
export class MenuComponent {
  @Output() public eventAction = new EventEmitter<string>();
  constructor(private router: Router) {}

  doIT(actionName: string = '') {
    this.eventAction.emit(actionName);
  }
}
