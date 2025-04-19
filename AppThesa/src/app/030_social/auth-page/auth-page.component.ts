import { Component } from '@angular/core';
import { ServiceThesaService } from '../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-auth-page',
  templateUrl: './auth-page.component.html',
  styleUrl: './auth-page.component.scss',
})
export class AuthPageComponent {
  data: Array<any> | any;
  pageID: string = '';
  check: string = '';
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {
    this.data = this.router.params.subscribe((params) => {
      this.pageID = params['id']; // (+) converts string 'id' to a number
      this.check = params['chk']; // (+) converts string 'id' to a number
      ;
      if (!this.pageID) {
        this.pageID = 'login';
      }
    });
  }
}
