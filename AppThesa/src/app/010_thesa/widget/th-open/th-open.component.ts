import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { Router, RouterLink } from '@angular/router';

@Component({
  selector: 'app-th-open',
  templateUrl: './th-open.component.html',
  styleUrl: './th-open.component.scss',
})
export class ThOpenComponent {
  data: Array<any> | any;
  thesa: Array<any> | any;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: Router
  ) {}

  ngOnInit() {
    console.log('th-open.component.ts ngOnInit()');
    this.serviceThesa.api_post('thopen', []).subscribe((res) => {
      this.data = res;
      this.thesa = this.data.th;
    });
  }

  selectThesa(thesa: any) {
    this.serviceStorage.set('thesa', thesa);
    this.router.navigate(['/thesa/' + thesa]);
  }
}
