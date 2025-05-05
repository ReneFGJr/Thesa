import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-concept-delete',
  standalone: false,
  templateUrl: './concept-delete.component.html',
  styleUrl: './concept-delete.component.scss',
})
export class ConceptDeleteComponent {
  @Input() conceptID: number = 0;
  @Input() editMode: boolean = false;
  data: any;

  constructor(private thesaaService: ServiceThesaService) {}

  deleteConcept() {
    if (confirm('Você tem certeza que deseja excluir o conceito?')) {
      // Lógica para excluir o conceito
      this.thesaaService
        .api_post('concept_delete/' + this.conceptID, [])
        .subscribe(
          (res) => {
            this.data = res;
            if (this.data.editMode == 'allow') {
              this.editMode = true;
            } else {
              this.editMode = false;
            }
          },
          (error) => error
        );
    }
  }
}
