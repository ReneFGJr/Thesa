import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute, Router } from '@angular/router';

@Component({
  selector: 'app-thesa',
  templateUrl: './thesa.component.html',
  styleUrl: './thesa.component.scss'
})
export class ThesaComponent {
  data: any;
  thesa: any;
  id: number = 0
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number

      this.serviceThesa.api_post('th/'+this.id, []).subscribe(
        (res) => {
          this.data = res;
          console.log(this.data);
        },
        (error) => error
      );
    });
  }
}
