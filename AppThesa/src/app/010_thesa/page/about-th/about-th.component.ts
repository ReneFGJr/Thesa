import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
    selector: 'app-about-th',
    templateUrl: './about-th.component.html',
    styleUrl: './about-th.component.scss',
    standalone: false
})
export class AboutThComponent {
  data: any;
  thesa: any;
  thesaID: number = 0;
  id: number = 0;
  termID: number = 0;
  editMode: boolean = false;
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number
      this.thesaID = +params['id']; // (+) converts string 'id' to a number
      this.serviceThesa.api_post('th/' + this.id, []).subscribe(
        (res) => {
          this.data = res;
          this.editMode = this.serviceStorage.mathEditMode(this.data)
        },
        (error) => error
      );
    });
  }
}
