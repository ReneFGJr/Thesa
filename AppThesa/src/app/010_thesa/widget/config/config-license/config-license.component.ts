import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
    selector: 'app-config-license',
    templateUrl: './config-license.component.html',
    styleUrl: './config-license.component.scss',
    standalone: false
})
export class ConfigLicenseComponent {
  @Input() thesaID: number = 0;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService
  ) {}

  ngOnInit() {
    this.ngOnChanges();
  }
  ngOnChanges() {
    this.serviceThesa.api_post('licences/' + this.thesaID, []).subscribe(
      (res) => {
        console.log(res)
      },
      (error) => error
    );
  }
}
