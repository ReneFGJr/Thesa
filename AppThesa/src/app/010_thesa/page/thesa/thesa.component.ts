import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
    selector: 'app-thesa',
    templateUrl: './thesa.component.html',
    styleUrl: './thesa.component.scss',
    standalone: false
})
export class ThesaComponent {
  data: Array<any> | any;
  thesa: any;
  termID: number = 0;
  thesaID: number = 0;
  editMode: boolean = false;
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      this.thesaID = +params['th']; // (+) converts string 'id' to a number
    })
  }

}
