import { Component, Input, OnInit } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-th-open',
  templateUrl: './th-open.component.html',
})
export class ThOpenComponent implements OnInit {
  data: any;
  thesa: any[] = [];
  searchTerm: string = ''; // termo de busca
  title: string = 'Thesa Aberto'; // título da página
  @Input() editMode: boolean = false; // modo de edição
  @Input() canCreate: string = ''; // ID do modo de edição

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: Router
  ) {}

  ngOnInit() {
    this.serviceThesa.api_post('thopen', []).subscribe((res) => {
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
    return this.thesa.filter((t) => t.th_name.toLowerCase().includes(term));
  }

  selectThesa(id: any) {
    this.serviceStorage.set('thesa', id);
    this.router.navigate(['/thesa/' + id]);
  }
}
