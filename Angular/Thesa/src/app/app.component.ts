import { Component } from '@angular/core';
import { ThesaServiceService } from './service/thesa-service.service';
import { TranslateService } from '@ngx-translate/core';
import { lastValueFrom } from 'rxjs';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css'],
})
export class AppComponent {
  title = 'Thesa';

  currentLanguage: string = 'pt';

  constructor(
    private translate: TranslateService,
    private apiService: ThesaServiceService
  ) {}

  async ngOnInit(): Promise<void> {
    await this.setLanguage();
  }

  async setLanguage(): Promise<void> {
    const ipInfo$ = this.apiService.getIPInfo();
    const ipInfo = await lastValueFrom(ipInfo$);

    this.translate.setDefaultLang('en');
    if (ipInfo?.country_code?.toUpperCase() == 'BR') {
      this.translate.setDefaultLang('pt');
      this.currentLanguage = 'pt';
    }
  }

  changeLanguage(): void {
    alert('Hello' + this.currentLanguage);
    if (this.currentLanguage == 'en') {
      this.translate.use('pt');
      this.currentLanguage = 'pt';
    } else {
      this.translate.use('en');
      this.currentLanguage = 'en';
    }
  }
}
