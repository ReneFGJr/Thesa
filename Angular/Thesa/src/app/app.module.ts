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
import { AddComponent } from './page/term/add/add.component';
import { EditComponent } from './page/term/edit/edit.component';
import { SearchComponent } from './page/search/search.component';
import { ThesaMainComponent } from './page/main/main.component';
import { ThSelectComponent } from './page/th-select/th-select.component';
import { ThComponent } from './page/th/th.component';
import { ListComponent } from './page/term/list/list.component';
import { AboutComponent } from './page/about/about.component';
import { ConceptComponent } from './page/concept/concept.component';
import { VideoComponent } from './widgat/video/video.component';

@NgModule({
  declarations: [
    AppComponent,
    ViewConceptComponent,
    ThemeHeaderComponent,
    ThemeFooterComponent,
    ThemeNavbarComponent,
    ThesaurosComponent,
    TermComponent,
    AddComponent,
    EditComponent,
    SearchComponent,
    ThesaMainComponent,
    ThSelectComponent,
    ThComponent,
    ListComponent,
    AboutComponent,
    ConceptComponent,
    VideoComponent,
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
