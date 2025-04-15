import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../service/service-thesa.service';
import { ServiceStorageService } from '../service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-navbar',
  templateUrl: './navbar.component.html',
  styleUrl: './navbar.component.scss'
})
export class NavbarComponent {
  @Input() editMode: boolean = false;

  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit()
    {
      this.editMode = this.serviceThesa.getEditMode();
    }
}
