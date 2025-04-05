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
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ConceptLinkComponent } from './010_thesa/widget/concept-link/concept-link.component';
import { ConceptTHComponent } from './010_thesa/page/concept-th/concept-th.component';
import { AltLabelComponent } from './010_thesa/widget/alt-label/alt-label.component';
import { PrefLabelComponent } from './010_thesa/widget/pref-label/pref-label.component';
import { HiddenLabelComponent } from './010_thesa/widget/hidden-label/hidden-label.component';
import { VideoPlayComponent } from './010_thesa/widget/video-play/video-play.component';
import { NarrowComponent } from './010_thesa/widget/narrow/narrow.component';
import { BroaderComponent } from './010_thesa/widget/broader/broader.component';
import { RelatedComponent } from './010_thesa/widget/related/related.component';
import { NotesComponent } from './010_thesa/widget/notes/notes.component';
import { AboutThComponent } from './010_thesa/page/about-th/about-th.component';
import { ConfigurationComponent } from './010_thesa/page/configuration/configuration.component';
import { ConfigIntrodutionComponent } from './010_thesa/widget/config/config-introdution/config-introdution.component';
import { ConfigMethodologyComponent } from './010_thesa/widget/config/config-methodology/config-methodology.component';
import { ConfigDescriptComponent } from './010_thesa/widget/config/config-descript/config-descript.component';
import { ConfigPublicComponent } from './010_thesa/widget/config/config-public/config-public.component';
import { ConfigTypeComponent } from './010_thesa/widget/config/config-type/config-type.component';
import { ConfigVisibilityComponent } from './010_thesa/widget/config/config-visibility/config-visibility.component';
import { ConfigIconeComponent } from './010_thesa/widget/config/config-icone/config-icone.component';
import { ConfigMembersComponent } from './010_thesa/widget/config/config-members/config-members.component';
import { AngularEditorModule } from '@kolkov/angular-editor';
import { FormTextareaComponent } from './010_thesa/widget/config/form-textarea/form-textarea.component';

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
    NotesComponent,
    AboutThComponent,
    ConfigurationComponent,
    ConfigIntrodutionComponent,
    ConfigMethodologyComponent,
    ConfigDescriptComponent,
    ConfigPublicComponent,
    ConfigTypeComponent,
    ConfigVisibilityComponent,
    ConfigIconeComponent,
    ConfigMembersComponent,
    FormTextareaComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    NgbModule,
    HttpClientModule,
    BrowserAnimationsModule,
    FormsModule,
    ReactiveFormsModule,
    AngularEditorModule,
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
