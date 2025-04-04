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
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    NgbModule,
    HttpClientModule,
    BrowserAnimationsModule,
  ],
  providers: [],
  bootstrap: [AppComponent],
})
export class AppModule {}
