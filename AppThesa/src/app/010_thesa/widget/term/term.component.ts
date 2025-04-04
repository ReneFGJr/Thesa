import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-term',
  templateUrl: './term.component.html',
  styleUrl: './term.component.scss'
})
export class TermComponent {
  @Input() term: number = 0;
  data: any;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnChanges() {
      this.serviceThesa.api_post('term/' + this.term, []).subscribe(
        (res) => {
          this.data = res;
        },
        (error) => error
      );
  }
}
