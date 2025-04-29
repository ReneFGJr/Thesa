import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
  selector: 'app-config-license',
  templateUrl: './config-license.component.html',
  styleUrl: './config-license.component.scss',
  standalone: false,
})
export class ConfigLicenseComponent {
  @Input() thesaID: number = 0; // modo de edição
  @Input() field: string = ''; // Campo do modo de edição
  thesa: any = []; // Dados do thesa
  busy: boolean = false; // Indica se a requisição está em andamento
  licence: number = 0; // Tipo de thesa
  licences: Array<any> | any; // Tipos de thesa

  constructor(private serviceThesa: ServiceThesaService) {}

  ngOnInit() {
    this.serviceThesa.api_post('thesaLicences', []).subscribe((res) => {
      this.licences = res;
      this.licences = this.licences.Licences;
    });

    if (!this.busy) {
      this.ngOnChanges();
    }
  }

  ngOnChanges() {
    this.busy = true;
    this.serviceThesa.api_post('th/' + this.thesaID, []).subscribe((res) => {
      this.thesa = res;
      this.licence = this.thesa.th_licence;
      this.busy = false;
    });
  }

  selectType(id: number) {
    if (confirm('Você tem certeza que deseja alterar o tipo de thesa?')) {
      let dt = { type: id };
      this.serviceThesa
        .api_post('typeLicence/' + this.thesaID, dt)
        .subscribe((res) => {
          this.licence = id;
          console.log('Server', res);
        });
    }
  }
}
