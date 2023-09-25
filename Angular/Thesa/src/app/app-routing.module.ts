import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ViewConceptComponent } from './page/view/view.component';

const routes: Routes = [
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
