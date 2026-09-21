import { LanguageService } from '../../../../000_core/service/language.service';
import { licenseDescriptions } from './license-translations';
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

  constructor(public readonly language: LanguageService, private serviceThesa: ServiceThesaService) {}

  licenseDescription(license: { id: string | number; description: string }): string {
    return licenseDescriptions[this.language.currentLanguage()]?.[String(license.id)] ?? license.description;
  }

  licenseName(license: { id: string | number; name: string }): string {
    return String(license.id) === '8' ? this.language.labels().reservedCopyright : license.name;
  }

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
    if (confirm(this.language.labels().confirmLicenseChange)) {
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
