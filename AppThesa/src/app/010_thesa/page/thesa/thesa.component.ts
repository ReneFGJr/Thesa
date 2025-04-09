import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-thesa',
  templateUrl: './thesa.component.html',
  styleUrl: './thesa.component.scss',
})
export class ThesaComponent {
  data: any;
  thesa: any;
  termID: number = 0;
  thesaID: number = 0;
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      this.thesaID = +params['id']; // (+) converts string 'id' to a number

      this.serviceThesa.api_post('th/' + this.thesaID, []).subscribe(
        (res) => {
          this.data = res;
        },
        (error) => error
      );
    });
  }

  changeTerm(term: any) {
    const id = Number(term);
    if (!isNaN(id)) {
      this.termID = id;
    } else {
      console.error('Valor inválido para term:', term);
      // Aqui você pode exibir um alerta, desmarcar a seleção, etc.
    }
  }
}
