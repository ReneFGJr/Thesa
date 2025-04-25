import { Component } from '@angular/core';
import { AuthService } from './000_core/service/auth.service';

@Component({
    selector: 'app-root',
    templateUrl: './app.component.html',
    styleUrl: './app.component.scss',
    standalone: false
})
export class AppComponent {
  title = 'AppThesa';
  user: Array<any> = [];

  constructor(
    private auth: AuthService,
  ) {}

  ngOnInit(){
    this.user = this.auth.getUser();
  }
}
