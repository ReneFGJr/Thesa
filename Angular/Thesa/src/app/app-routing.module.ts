import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ViewConceptComponent } from './page/view/view.component';
import { ThesaMainComponent } from './page/main/main.component';
import { ThSelectComponent } from './page/th-select/th-select.component';
import { ThComponent } from './page/th/th.component';
import { AboutFormComponent } from './page/about-form/about-form.component';

const routes: Routes = [
  { path: '', component: ThesaMainComponent },
  { path: 'thopen', component: ThSelectComponent },
  { path: 'c/:id', component: ViewConceptComponent },
  { path: 'concept/:id', component: ViewConceptComponent },
  { path: 't/:id', component: ViewConceptComponent },
  { path: 'term/:id', component: ViewConceptComponent },
  { path: 'th/:id', component: ThComponent },
  { path: 'about/edit' ,component: AboutFormComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
