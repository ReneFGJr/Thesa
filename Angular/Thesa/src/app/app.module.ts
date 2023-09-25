import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { HttpClient, HttpClientModule } from '@angular/common/http';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { ViewConceptComponent } from './page/view/view.component';
import { ThemeHeaderComponent } from './theme/header.component';
import { ThemeFooterComponent } from './theme/footer.component';
import { ThemeNavbarComponent } from './theme/navbar.component';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { ThesaurosComponent } from './page/thesauros/thesauros.component';
import { TranslateLoader, TranslateModule } from '@ngx-translate/core';
import { TranslateHttpLoader } from '@ngx-translate/http-loader';
import { TermComponent } from './page/term/term.component';

@NgModule({
  declarations: [
    AppComponent,
    ViewConceptComponent,
    ThemeHeaderComponent,
    ThemeFooterComponent,
    ThemeNavbarComponent,
    ThesaurosComponent,
    TermComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    HttpClientModule,
    NgbModule,
    TranslateModule.forRoot({
      loader: {
        provide: TranslateLoader,
        useFactory: HttpLoaderFactory,
        deps: [HttpClient],
      },
    }),
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}

export function HttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http);
}
