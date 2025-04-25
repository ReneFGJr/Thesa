import { Component } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { ActivatedRoute } from '@angular/router';

@Component({
    selector: 'app-homepage',
    templateUrl: './homepage.component.html',
    styleUrl: './homepage.component.scss',
    standalone: false
})
export class HomepageComponent {
  lang: string = 'por';
  constructor(
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private router: ActivatedRoute
  ) {}

  ngOnInit() {

  }
}
