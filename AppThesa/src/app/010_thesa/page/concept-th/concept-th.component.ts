import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-concept-th',
  templateUrl: './concept-th.component.html',
  standalone: false,
})
export class ConceptTHComponent {
  data: any;
  @Input() thesa: any = {};
  @Input() thesaID: number = 0;
  @Input() termID: number = 0;
  termList: Array<any> | any;
  termListCandidate: Array<any> | any;
  editMode: boolean = false;
  dataConcept: any;
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  dadosTesauro(thesaID: number) {
    /* Dados do Thesauro */
    this.serviceThesa.api_post('th/' + thesaID, []).subscribe((res) => {
      this.thesa = res;
      /* Edição */
      this.editMode = this.serviceStorage.mathEditMode(res);
    });
  }

  dadosTermos(thesaID: number) {
    /* Dados do Termo */
    this.serviceThesa
      .api_post('terms/' + this.thesaID, [])
      .subscribe((res) => {
        this.termList = res;
      });
  }

  dadosTermosCandidatos(thesaID: number) {
    /* Dados dos Termos Candidatos */
    this.serviceThesa.api_post('term_list/' + thesaID, []).subscribe((res) => {
      this.termListCandidate = res;
    });
  }

  dadosConcept(termID: number) {
    this.serviceThesa.api_post('c/' + termID, []).subscribe(
      (res) => {
        this.dataConcept = res;
      },
      (error) => error
    );
  }

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      if (params['id']) {
        console.log('ID: ', params['id']);
        this.termID = +params['id']; // (+) converts string 'id' to a number

        this.serviceThesa.api_post('c/' + this.termID, []).subscribe((res) => {
          this.data = res;
          this.thesaID = this.data.c_th;

          if (this.data.status == '404') {
            this.serviceStorage.set('error', 'Termo não encontrado');
          } else {
            /* Dados do Thesauro */
            this.changeTerm(this.termID);
          }
        });
      } else {
        /********************* Mostra tesauro */
        this.updateData();
      }
    });
  }

  updateData() {
        this.dadosTesauro(this.thesaID);
        this.dadosTermos(this.thesaID);
        this.dadosTermosCandidatos(this.thesaID);
  }

  changeTerm(term: any) {
    const id = Number(term);

    if (!isNaN(id)) {
      this.termID = id;
    } else {
      if (term == 'reload') {
      } else {
        this.termID = 0;
      }
    }

    // Carrega dados do conceito
    if (this.termID > 0) {
      this.dadosConcept(this.termID);
    }
    this.updateData();
  }
}
