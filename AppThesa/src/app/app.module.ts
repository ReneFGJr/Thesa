import { CUSTOM_ELEMENTS_SCHEMA, NgModule } from '@angular/core';
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
import { HttpClientModule } from '@angular/common/http';
import { ThesaComponent } from './010_thesa/page/thesa/thesa.component';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { ThShowComponent } from './010_thesa/widget/th-show/th-show.component';
import { ConceptComponent } from './010_thesa/widget/concept/concept.component';
import { TermsComponent } from './010_thesa/widget/terms/terms.component';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { ConceptLinkComponent } from './010_thesa/widget/concept-link/concept-link.component';
import { ConceptTHComponent } from './010_thesa/page/concept-th/concept-th.component';
import { AltLabelComponent } from './010_thesa/widget/term/atribute-label/atribute-label.component';
import { NarrowComponent } from './010_thesa/widget/narrow/narrow.component';
import { BroaderComponent } from './010_thesa/widget/broader/broader.component';
import { RelatedComponent } from './010_thesa/widget/related/related.component';
import { NotesComponent } from './010_thesa/widget/notes/notes.component';
import { AboutThComponent } from './010_thesa/page/about-th/about-th.component';
import { ConfigurationComponent } from './010_thesa/page/configuration/configuration.component';
import { ConfigTypeComponent } from './010_thesa/widget/config/config-type/config-type.component';
import { ConfigVisibilityComponent } from './010_thesa/widget/config/config-visibility/config-visibility.component';
import { ConfigIconeComponent } from './010_thesa/widget/config/config-icone/config-icone.component';
import { ConfigMembersComponent } from './010_thesa/widget/config/config-members/config-members.component';
import { AngularEditorModule } from '@kolkov/angular-editor';
import { FormTextareaComponent } from './010_thesa/widget/config/form-textarea/form-textarea.component';
import { ConfigTitleComponent } from './010_thesa/widget/config/config-title/config-title.component';
import { ConfigLicenseComponent } from './010_thesa/widget/config/config-license/config-license.component';
import { ThesaCreateComponent } from './010_thesa/page/thesa-create/thesa-create.component';
import { TermInputComponent } from './010_thesa/widget/term/term-input/term-input.component';
import { ConceptCreateComponent } from './010_thesa/widget/concept-create/concept-create.component';
import { ConceptShowComponent } from './010_thesa/widget/concept-show/concept-show.component';
import { ConceptShowTermsComponent } from './010_thesa/widget/concept-show/concept-show-terms/concept-show-terms.component';
import { ConceptShowConceptsComponent } from './010_thesa/widget/concept-show/concept-show-concepts/concept-show-concepts.component';
import { TermLabelComponent } from './010_thesa/widget/term/term-label/term-label.component';
import { ConceptCardComponent } from './010_thesa/widget/concept-show/concept-card/concept-card.component';
import { ConceptGrafoComponent } from './010_thesa/widget/concept-show/concept-grafo/concept-grafo.component';
import { IndexSystemicComponent } from './010_thesa/page/index-systemic/index-systemic.component';
import { IndexAlphabeticComponent } from './010_thesa/page/index-alphabetic/index-alphabetic.component';
import { ExportPDFComponent } from './010_thesa/page/export/export.component';
import { ConfigLanguageComponent } from './010_thesa/widget/config/config-language/config-language.component';
import { VideoPlayComponent } from './010_thesa/widget/play-media-show/video-play/video-play.component';
import { VideoPlayShowComponent } from './010_thesa/widget/play-media-show/play-media-show.component';
import { ThesaMyComponent } from './010_thesa/page/thesa-my/thesa-my.component';
import { ForgetPasswordComponent } from './030_social/forget-password/forget-password.component';
import { AuthPageComponent } from './030_social/auth-page/auth-page.component';
import { PerfilComponent } from './030_social/perfil/perfil.component';
import { SignupComponent } from './030_social/signup/signup.component';
import { SigninComponent } from './030_social/signin/signin.component';
import { UserMenuComponent } from './030_social/user-menu/user-menu.component';
import { RecoverPasswordComponent } from './030_social/recover-password/recover-password.component';
import { ImagePlayComponent } from './010_thesa/widget/play-media-show/image-play/image-play.component';
import { EditModePageComponent } from './010_thesa/page/edit-mode-page/edit-mode-page.component';
import { LogoutComponent } from './030_social/logout/logout.component';
import { LinkedataComponent } from './010_thesa/widget/linkedata/linkedata.component';
import { ProcessingComponent } from './000_core/widget/processing/processing.component';
import { ThCanceledComponent } from './010_thesa/widget/th-canceled/th-canceled.component';
import { ConceptDeleteComponent } from './010_thesa/widget/concept-delete/concept-delete.component';
import { Concept404Component } from './010_thesa/widget/concept-404/concept-404.component';
import { LinkedataEditComponent } from './010_thesa/widget/linkedata-edit/linkedata-edit.component';
import { TopConceptComponent } from './010_thesa/widget/schema/top-concept/top-concept.component';
import { ConceptTopConceptComponent } from './010_thesa/widget/concept-top-concept/concept-top-concept.component';
import { NgxGraphModule } from '@swimlane/ngx-graph';
import { TermEditConceptComponent } from './010_thesa/widget/term-edit-concept/term-edit-concept.component';
import { ThemeConfigComponent } from './010_thesa/widget/theme/theme-config/theme-config.component';
import { ThemeIconesComponent } from './010_thesa/widget/theme/theme-icones/theme-icones.component';
import { ThemeUploadComponent } from './010_thesa/widget/theme/theme-upload/theme-upload.component';
import { LinkedataListComponent } from './010_thesa/widget/linkedata-list/linkedata-list.component';
import { SkosExactmathComponent } from './010_thesa/widget/skos/skos-exactmath/skos-exactmath.component';
import { SkosExactmathEditComponent } from './010_thesa/widget/skos/skos-exactmath-edit/skos-exactmath-edit.component';
import { ConfigRelatedComponent } from './010_thesa/widget/config/config-related/config-related.component';
import { SystematicViewerComponent } from './010_thesa/widget/systematic-viewer/systematic-viewer.component';
import { ConceptImportThesaComponent } from './010_thesa/widget/concept-import-thesa/concept-import-thesa.component';
import { MessageComponent } from './010_thesa/widget/message/message.component';
import { AboutUsComponent } from './010_thesa/page/about-us/about-us.component';
import { LogListConceptComponent } from './010_thesa/widget/log/log-list-concept/log-list-concept.component';

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
    ThesaComponent,
    ThShowComponent,
    ConceptComponent,
    TermsComponent,
    ConceptLinkComponent,
    ConceptTHComponent,
    AltLabelComponent,
    NarrowComponent,
    BroaderComponent,
    RelatedComponent,
    NotesComponent,
    AboutThComponent,
    ConfigurationComponent,
    ConfigTypeComponent,
    ConfigVisibilityComponent,
    ConfigIconeComponent,
    ConfigMembersComponent,
    FormTextareaComponent,
    ConfigTitleComponent,
    ConfigLicenseComponent,
    ThesaCreateComponent,
    TermInputComponent,
    ConceptCreateComponent,
    ConceptShowComponent,
    ConceptShowTermsComponent,
    ConceptShowConceptsComponent,
    TermLabelComponent,
    ConceptCardComponent,
    ConceptGrafoComponent,
    IndexSystemicComponent,
    IndexAlphabeticComponent,
    ExportPDFComponent,
    ConfigLanguageComponent,
    VideoPlayComponent,
    VideoPlayShowComponent,
    ThesaMyComponent,
    ForgetPasswordComponent,
    AuthPageComponent,
    PerfilComponent,
    SigninComponent,
    SignupComponent,
    ForgetPasswordComponent,
    PerfilComponent,
    UserMenuComponent,
    RecoverPasswordComponent,
    ImagePlayComponent,
    EditModePageComponent,
    LogoutComponent,
    LinkedataComponent,
    ProcessingComponent,
    ThCanceledComponent,
    ConceptDeleteComponent,
    Concept404Component,
    LinkedataEditComponent,
    TopConceptComponent,
    ConceptTopConceptComponent,
    TermEditConceptComponent,
    ThemeConfigComponent,
    ThemeIconesComponent,
    ThemeUploadComponent,
    LinkedataListComponent,
    SkosExactmathComponent,
    SkosExactmathEditComponent,
    ConfigRelatedComponent,
    SystematicViewerComponent,
    ConceptImportThesaComponent,
    MessageComponent,
    AboutUsComponent,
    LogListConceptComponent,
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
    NgxGraphModule,
  ],
  providers: [],
  bootstrap: [AppComponent],
  schemas: [CUSTOM_ELEMENTS_SCHEMA],
})
export class AppModule {}
