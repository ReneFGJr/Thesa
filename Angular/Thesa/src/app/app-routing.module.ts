import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { ViewConceptComponent } from './page/view/view.component';
import { ThesaMainComponent } from './page/main/main.component';
import { ThSelectComponent } from './page/th-select/th-select.component';
import { ThComponent } from './page/th/th.component';
import { AboutFormComponent } from './page/about-form/about-form.component';
import { TermNewComponent } from './page/admin/term-new/term-new.component';
import { WordcountComponent } from './widgat/tools/wordcount/WordcountComponent';

const routes: Routes = [
  { path: '', component: ThesaMainComponent },
  { path: 'thopen', component: ThSelectComponent },
  { path: 'c/:id', component: ViewConceptComponent },
  { path: 'concept/:id', component: ViewConceptComponent },
  { path: 't/:id', component: ViewConceptComponent },
  { path: 'term/:id', component: ViewConceptComponent },
  { path: 'th/:id', component: ThComponent },
  { path: 'about/edit', component: AboutFormComponent },
  { path: 'tools/wordcount', component: WordcountComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {
  public words: number = 0

 }
