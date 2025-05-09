import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomepageComponent } from './010_thesa/page/homepage/homepage.component';
import { ApiDocComponent } from './020_doc/documment/api/api.component';
import { ThOpenComponent } from './010_thesa/widget/th-open/th-open.component';
import { ThesaComponent } from './010_thesa/page/thesa/thesa.component';
import { ConceptTHComponent } from './010_thesa/page/concept-th/concept-th.component';
import { AboutThComponent } from './010_thesa/page/about-th/about-th.component';
import { ConfigurationComponent } from './010_thesa/page/configuration/configuration.component';
import { ThesaCreateComponent } from './010_thesa/page/thesa-create/thesa-create.component';
import { IndexSystemicComponent } from './010_thesa/page/index-systemic/index-systemic.component';
import { IndexAlphabeticComponent } from './010_thesa/page/index-alphabetic/index-alphabetic.component';
import { ExportPDFComponent } from './010_thesa/page/export/export.component';
import { ThesaMyComponent } from './010_thesa/page/thesa-my/thesa-my.component';
import { AuthPageComponent } from './030_social/auth-page/auth-page.component';
import { EditModePageComponent } from './010_thesa/page/edit-mode-page/edit-mode-page.component';


const routes: Routes = [
  { path: '', component: HomepageComponent },
  { path: 'home', component: HomepageComponent },
  { path: 'documentation', component: ApiDocComponent },
  { path: 'thopen', component: ThOpenComponent },
  { path: 'thmy', component: ThesaMyComponent },
  { path: 'thesa/:id', component: ThesaComponent },
  { path: 'about/:id', component: AboutThComponent },
  { path: 'configuration/:id', component: ConfigurationComponent },
  { path: 'chageEditMode/:thesaID/:id', component: EditModePageComponent },
  { path: 'c/:id', component: ConceptTHComponent },
  { path: 'create', component: ThesaCreateComponent },
  { path: 'export/:id', component: ExportPDFComponent },
  { path: 'systemic_index/:id', component: IndexSystemicComponent },
  { path: 'alphabetic_index/:id', component: IndexAlphabeticComponent },
  { path: 'export/:id', component: ExportPDFComponent },
  { path: 'social/:id', component: AuthPageComponent },
  { path: 'social/:id/:chk', component: AuthPageComponent },
  { path: 'social', component: AuthPageComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
