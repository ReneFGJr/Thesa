import { ServiceThesaService } from './../../../../000_core/service/service-thesa.service';
import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-config-related',
  standalone: false,
  templateUrl: './config-related.component.html',
  styleUrl: './config-related.component.scss',
})
export class ConfigRelatedComponent {
  data: any;
  @Input() thesaID: string = '';
  constructor(private ServiceThesa: ServiceThesaService) {}

  onGroupClick(ID:string, checked:string)
    {
      if (checked == '1') {
        checked = '0';
      } else {
        checked = '1';
      }
      let dt = { idGr: ID, thesaID: this.thesaID, checked: checked };
      this.ServiceThesa.api_post('setRelationsType', dt).subscribe(
        (res) => {
          console.log('Updated data:', res);
          this.ngOnInit(); // Refresh the data after update
        },
        (error) => {
          console.error('Error fetching related data:', error);
          this.ngOnInit(); // Refresh the data after update
        }
      );
    }

  ngOnInit() {
    let dt = {'thesaID': this.thesaID };
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
