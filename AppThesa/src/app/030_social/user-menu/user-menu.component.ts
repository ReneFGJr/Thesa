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
      if (this.user.userID === undefined) {
        this.isLoggedIn = false;
      } else {
        this.isLoggedIn = true;
        if (this.user.name=== null) {
          this.user.name = "logado";
        }
      }
    }
}
