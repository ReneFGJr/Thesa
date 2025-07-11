import { ServiceThesaService } from './../../../../000_core/service/service-thesa.service';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-top-concept',
  templateUrl: './top-concept.component.html',
  styleUrl: './top-concept.component.scss',
})
export class TopConceptComponent {
  @Input() data: any;
  @Input() editMode: boolean = false;
  topConcept: boolean = false;
  topConceptEdit: boolean = false;

  constructor( private serviceThesa: ServiceThesaService) {}

  ngOnChanges(){
    this.ngOnInit()
  }

  ngOnInit() {
    this.topConcept = this.data?.topConcept || false;
    if (
      this.data?.topConcept === undefined ||
      this.data?.topConcept === false
    ) {
      this.topConcept = false;
    }

    if (this.data?.broader.length == 0) {
      this.topConceptEdit = true;
    }
  }

  setTopConcept(v: number) {
    if (v==1) {
      this.topConcept = true;
    } else {
      this.topConcept = false;
    }

    const dt = {
      thesaId: this.data.c_th,
      conceptId: this.data.c_concept,
    };

    this.serviceThesa.api_post('setTopConcept', dt).subscribe(
      (res) => {
        console.log('Top Concept set:', res);
      },
      (error) => error
    );
  }
}
