import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-terms',
  templateUrl: './terms.component.html',
  styleUrl: './terms.component.scss',
})
export class TermsComponent {
  @Input() thesa: number = 0;
  terms: any;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.serviceThesa.api_post('terms/' + this.thesa, []).subscribe(
      (res) => {
        this.terms = res;
        console.log(this.terms);
      },
      (error) => error
    );
  }

  onSelectTerm(term: any) {
    console.log('Selecionado:', term);
    // faça algo com o termo selecionado...
  }
}
