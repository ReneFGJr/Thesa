import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';

@Component({
    selector: 'app-th-show',
    templateUrl: './th-show.component.html',
    styleUrl: './th-show.component.scss',
    standalone: false
})
export class ThShowComponent {
  @Input() thesa: any;
  @Input() thesaID: number = 0;
  @Input() tab: string = '';
  @Input() editMode: boolean = false;

  editModeLocal: boolean = false;
  data: Array<any> | any;

  constructor(
    private serviceThesa: ServiceThesaService, // private serviceStorage: ServiceStorageService,
    private serviceStorage: ServiceStorageService
  ) {}

  ngOnInit() {
    this.editModeLocal = this.serviceStorage.getEditMode();
    this.serviceThesa.api_post('th/' + this.thesaID, []).subscribe(
      (res) => {
        this.data = res;
        if (this.data.editMode == 'allow') {
          this.editMode = true;
        } else {
          this.editMode = false;
        }
      },
      (error) => error
    );
  }
}
