import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ViewConceptComponent } from './page/view/view.component';
import { ThesaMainComponent } from './page/main/main.component';
import { ThSelectComponent } from './page/th-select/th-select.component';

const routes: Routes = [
  { path: '', component: ThesaMainComponent },
  { path: 'thopen', component: ThSelectComponent},
  { path: 'c/:id', component: ViewConceptComponent },
  { path: 'concept/:id', component: ViewConceptComponent },
  { path: 't/:id', component: ViewConceptComponent },
  { path: 'term/:id', component: ViewConceptComponent },
  { path: 'th/:id', component: ViewConceptComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
