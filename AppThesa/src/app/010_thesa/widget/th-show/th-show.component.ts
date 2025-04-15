import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-th-show',
  templateUrl: './th-show.component.html',
  styleUrl: './th-show.component.scss',
})
export class ThShowComponent {
  @Input() thesa: any;
  @Input() tab: string = '';
  @Input() editMode: boolean = false;

  constructor(
    private serviceThesa: ServiceThesaService,
    // private serviceStorage: ServiceStorageService,
  ) {}

  ngOnInit() {
    this.ngOnChanges()
  }

  ngOnChanges()
    {
      this.editMode = this.serviceThesa.getEditMode();
    }
}
