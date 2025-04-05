import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-concept-th',
  templateUrl: './concept-th.component.html',
  styleUrl: './concept-th.component.scss',
})
export class ConceptTHComponent {
  data: any;
  dataTH: any;
  thesa: any = {};
  termID: number = 0;
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      this.termID = +params['id']; // (+) converts string 'id' to a number

      this.serviceThesa.api_post('c/' + this.termID, []).subscribe((res) => {
        this.data = res;
        this.thesa = this.data.c_th;

        /* Dados do Thesauro */
        this.serviceThesa.api_post('th/' + this.thesa, []).subscribe((res) => {
          this.thesa = res;
        });
      });
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
