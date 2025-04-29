import { Component, Input, OnInit } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { Router } from '@angular/router';

@Component({
  selector: 'app-th-open',
  templateUrl: './th-open.component.html',
  styleUrls: ['./th-open.component.scss'],
  standalone: false,
})
export class ThOpenComponent implements OnInit {
  public data: any;
  public dataCheck: any;
  public thesa: any[] = [];
  public searchTerm: string = ''; // termo de busca
  public title: string = 'Thesa Aberto'; // título da página
  public allow: boolean = false; // permite criar nova thesa

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

    /***************************************** Permite criar nova thesa */
    this.serviceThesa.api_post('canCreateNewThesa', []).subscribe((res) => {
      this.dataCheck = res;
      if (this.dataCheck.total < 1) {
        this.allow = true; // permite criar nova thesa
      }
      if (this.dataCheck.multiples == 1) {
        this.allow = true; // permite criar nova thesa
      }
    });
    /*********************************************************************/
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
