import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-user-menu',
  templateUrl: './user-menu.component.html',
  styleUrl: './user-menu.component.scss',
})
export class UserMenuComponent {
  @Input() user: Array<any> | any;
  isLoggedIn: boolean = false;
  iconeLogin: string = 'assets/img/login.svg';

  ngOnInit()
    {
      this.isLoggedIn = this.user ? true : false;
    }
}
