import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-pdf-export',
  templateUrl: './export.component.html',
  styleUrl: './export.component.scss'
})
export class ExportPDFComponent {
data: any;
  thesa: any;
  id: number = 0;
  termID: number = 0;
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number

      this.serviceThesa.api_post('th/' + this.id, []).subscribe(
        (res) => {
          this.data = res;
        },
        (error) => error
      );
    });
  }
}
