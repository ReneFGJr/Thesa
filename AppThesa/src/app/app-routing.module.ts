import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { HomepageComponent } from './010_thesa/page/homepage/homepage.component';
import { ApiDocComponent } from './020_doc/documment/api/api.component';
import { ThOpenComponent } from './010_thesa/widget/th-open/th-open.component';
import { ThesaComponent } from './010_thesa/page/thesa/thesa.component';


const routes: Routes = [
  { path: '', component: HomepageComponent },
  { path: 'documentation', component: ApiDocComponent },
  { path: 'thopen', component: ThOpenComponent },
  { path: 'thesa/:id', component: ThesaComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
