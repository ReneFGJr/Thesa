import { CUSTOM_ELEMENTS_SCHEMA, NgModule } from '@angular/core';
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
import { AboutComponent } from './page/about/about.component';
import { ConceptComponent } from './page/concept/concept.component';
import { VideoComponent } from './widgat/video/video.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { MatAutocompleteModule } from '@angular/material/autocomplete';
import { MatInputModule } from '@angular/material/input';
import { MatFormFieldModule } from '@angular/material/form-field';
import { SearchTermComponent } from './widgat/search-term/search-term.component';
import { ReactiveFormsModule } from '@angular/forms';
import { ModalCitedRefComponent } from './widgat/modal-cited-ref/modal-cited-ref.component';
import { NgxGraphModule } from '@swimlane/ngx-graph';
import { NetworkVisComponent } from './widgat/network-vis/network-vis.component';
import { AboutFormComponent } from './page/about-form/about-form.component';
import { TermNewComponent } from './page/admin/term-new/term-new.component';
import { ConceptNewComponent } from './page/admin/concept-new/concept-new.component';
import { MenuComponent } from './page/admin/menu/menu.component';
import { TermListComponent } from './page/admin/term-list/term-list.component';
import { WordcountComponent } from './widgat/tools/wordcount/WordcountComponent';

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
    AboutComponent,
    ConceptComponent,
    VideoComponent,
    SearchComponent,
    SearchTermComponent,
    ModalCitedRefComponent,
    NetworkVisComponent,
    AboutFormComponent,
    TermNewComponent,
    ConceptNewComponent,
    MenuComponent,
    TermListComponent,
    WordcountComponent,
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
    BrowserAnimationsModule,
    MatFormFieldModule,
    MatInputModule,
    MatAutocompleteModule,
    ReactiveFormsModule,
    NgxGraphModule,

  ],
  providers: [],
  bootstrap: [AppComponent],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class AppModule {}

export function HttpLoaderFactory(http: HttpClient) {
  return new TranslateHttpLoader(http);
}
