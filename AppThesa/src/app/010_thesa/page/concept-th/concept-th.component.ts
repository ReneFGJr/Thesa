import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
    selector: 'app-concept-th',
    templateUrl: './concept-th.component.html',
    styleUrl: './concept-th.component.scss',
    standalone: false
})
export class ConceptTHComponent {
  data: any;
  dataTH: any;
  thesa: any = {};
  thesaID: number = 0;
  termID: number = 0;
  editMode: boolean = false;
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    console.log('#1-initTerm');
    this.data = this.router.params.subscribe((params) => {
      this.termID = +params['id']; // (+) converts string 'id' to a number

      this.serviceThesa.api_post('c/' + this.termID, []).subscribe((res) => {
        this.data = res;
        this.thesaID = this.data.c_th;

        console.log('#1-initTerm', this.data);

        if (this.data.status == '404') {
          this.serviceStorage.set('error', 'Termo não encontrado');
          } else {
          /* Dados do Thesauro */
          this.serviceThesa
            .api_post('th/' + this.thesaID, [])
            .subscribe((res) => {
              this.thesa = res;

              /* Edição */
              if (this.thesa.editMode == 'allow') {
                this.editMode = true;
              } else {
                this.editMode = false;
              }
            });
          }
      });
    });
  }

  changeTerm(term: any) {
    console.log('#21-changeTerm', term);
    const id = Number(term);
    if (!isNaN(id)) {
      this.termID = id;
    } else {
      console.error('Valor inválido para term:', term);
      // Aqui você pode exibir um alerta, desmarcar a seleção, etc.
    }
  }
}
