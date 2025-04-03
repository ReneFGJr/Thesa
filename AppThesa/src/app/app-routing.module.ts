import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomepageComponent } from './010_thesa/page/homepage/homepage.component';
import { ApiDocComponent } from './020_doc/documment/api/api.component';

const routes: Routes = [
  { path: '', component: HomepageComponent },
  { path: 'documentation', component: ApiDocComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
