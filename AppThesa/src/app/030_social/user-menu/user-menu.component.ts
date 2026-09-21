import { Component, Input } from '@angular/core';
import { LanguageService } from '../../000_core/service/language.service';

@Component({
    selector: 'app-user-menu',
    templateUrl: './user-menu.component.html',
    styleUrl: './user-menu.component.scss',
    standalone: false
})
export class UserMenuComponent {
  constructor(public readonly language: LanguageService) {}
  @Input() user: Array<any> | any;
  iconeLogin: string = 'assets/img/login.svg';

  ngOnInit()
    {

    }
}
