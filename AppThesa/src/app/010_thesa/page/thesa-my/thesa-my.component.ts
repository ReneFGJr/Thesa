import { Component, OnInit } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-thesa-my',
  templateUrl: '../../widget/th-open/th-open.component.html',
})
export class ThesaMyComponent {
  data: Array<any> | any;
  thesa: Array<any> | any;
  searchTerm: string = '';
  title: string = 'Thesa Pessoal'; // título da página

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: Router
  ) {}

  ngOnInit() {
    this.serviceThesa.api_post('thmy', []).subscribe((res) => {
      this.data = res;
      this.thesa = this.data.th;
    });
  }

  // retorna lista filtrada
  filteredThesa(): any[] {
    const term = this.searchTerm.toLowerCase().trim();
    if (!term) {
      return this.thesa;
    }
    return this.thesa.filter((t:Array<any>|any) => t.th_name.toLowerCase().includes(term));
  }

  selectThesa(thesa: any) {
    this.serviceStorage.set('thesa', thesa);
    this.router.navigate(['/thesa/' + thesa]);
  }
}
