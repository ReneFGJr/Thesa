import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-user-menu',
  templateUrl: './user-menu.component.html',
  styleUrl: './user-menu.component.scss',
})
export class UserMenuComponent {
  @Input() user: Array<any> | any;
  iconeLogin: string = 'assets/img/login.svg';

  ngOnInit()
    {

    }
}
