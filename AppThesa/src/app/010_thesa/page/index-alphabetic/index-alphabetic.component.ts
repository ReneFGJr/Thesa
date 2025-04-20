import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-index-alphabetic',
  templateUrl: './index-alphabetic.component.html',
  styleUrl: './index-alphabetic.component.scss',
})
export class IndexAlphabeticComponent {
  data: any;
  thesa: any;
  id: number = 0;
  termID: number = 0;
  editMode: boolean = false;
  concepts: Array<any> | any;
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
          if (this.data.editMode == 'allow') {
            this.editMode = true;
          } else {
            this.data = false;
          }
        },
        (error) => error
      );

      this.serviceThesa.api_post('index_alphabetic/' + this.id, []).subscribe((res) => {
        this.concepts = res;
      });
    });
  }
}
