import { ServiceThesaService } from './../../../../000_core/service/service-thesa.service';
import { Component } from '@angular/core';

@Component({
  selector: 'app-config-related',
  standalone: false,
  templateUrl: './config-related.component.html',
  styleUrl: './config-related.component.scss'
})
export class ConfigRelatedComponent {
  data: any;
  constructor(private ServiceThesa: ServiceThesaService) {}

ngOnInit() {
    let dt = {}
    console.log('ConfigRelatedComponent - ngOnInit');
    this.ServiceThesa.api_post('getRelations', dt).subscribe(
      (res) => {
        this.data = res;
      },
      (error) => {
        console.error('Error fetching related data:', error);
      }
    );
  }
}
