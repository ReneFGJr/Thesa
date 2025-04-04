import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { NgbModule } from '@ng-bootstrap/ng-bootstrap';
import { NavbarComponent } from './000_core/navbar/navbar.component';
import { FooterComponent } from './000_core/footer/footer.component';
import { HeaderComponent } from './000_core/header/header.component';
import { HomepageComponent } from './010_thesa/page/homepage/homepage.component';
import { LogoBigComponent } from './010_thesa/widget/logo-big/logo-big.component';
import { ApiDocComponent } from './020_doc/documment/api/api.component';
import { ThOpenComponent } from './010_thesa/widget/th-open/th-open.component';
import { LoginComponent } from './010_thesa/social/login/login.component';
import { SigninComponent } from './010_thesa/social/signin/signin.component';
import { SignupComponent } from './010_thesa/social/signup/signup.component';
import { LogoutComponent } from './010_thesa/social/logout/logout.component';
import { ForgoutComponent } from './010_thesa/social/forgout/forgout.component';
import { HttpClientModule } from '@angular/common/http';
import { ThesaComponent } from './010_thesa/page/thesa/thesa.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ThShowComponent } from './010_thesa/widget/th-show/th-show.component';
import { ConceptComponent } from './010_thesa/widget/concept/concept.component';
import { TermComponent } from './010_thesa/widget/term/term.component';
import { TermsComponent } from './010_thesa/widget/terms/terms.component';
import { FormsModule } from '@angular/forms';
import { ConceptLinkComponent } from './010_thesa/widget/concept-link/concept-link.component';
import { ConceptTHComponent } from './010_thesa/page/concept-th/concept-th.component';
import { AltLabelComponent } from './010_thesa/widget/alt-label/alt-label.component';
import { PrefLabelComponent } from './010_thesa/widget/pref-label/pref-label.component';
import { HiddenLabelComponent } from './010_thesa/widget/hidden-label/hidden-label.component';
import { VideoPlayComponent } from './010_thesa/widget/video-play/video-play.component';
import { NarrowComponent } from './010_thesa/widget/narrow/narrow.component';
import { BroaderComponent } from './010_thesa/widget/broader/broader.component';
import { RelatedComponent } from './010_thesa/widget/related/related.component';

@NgModule({
  declarations: [
    AppComponent,
    NavbarComponent,
    FooterComponent,
    HeaderComponent,
    HomepageComponent,
    LogoBigComponent,
    ApiDocComponent,
    ThOpenComponent,
    LoginComponent,
    SigninComponent,
    SignupComponent,
    LogoutComponent,
    ForgoutComponent,
    ThesaComponent,
    ThShowComponent,
    ConceptComponent,
    TermComponent,
    TermsComponent,
    ConceptLinkComponent,
    ConceptTHComponent,
    AltLabelComponent,
    PrefLabelComponent,
    HiddenLabelComponent,
    VideoPlayComponent,
    NarrowComponent,
    BroaderComponent,
    RelatedComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    NgbModule,
    HttpClientModule,
    BrowserAnimationsModule,
    FormsModule,
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
