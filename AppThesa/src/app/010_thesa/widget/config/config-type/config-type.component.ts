import { LanguageService } from '../../../../000_core/service/language.service';
import { typeTranslations, ThesaTypeText } from './type-translations';
import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-config-type',
  templateUrl: './config-type.component.html',
  styleUrl: './config-type.component.scss',
  standalone: false,
})
export class ConfigTypeComponent {
  @Input() thesaID: number = 0; // modo de edição
  @Input() field: string = ''; // Campo do modo de edição
  thesa: any = []; // Dados do thesa
  busy: boolean = false; // Indica se a requisição está em andamento
  type: number = 0; // Tipo de thesa
  types: Array<any> | any; // Tipos de thesa

  constructor(public readonly language: LanguageService, private serviceThesa: ServiceThesaService) {}

  typeText(type: ThesaTypeText & { id: string | number }): ThesaTypeText {
    return typeTranslations[this.language.currentLanguage()]?.[String(type.id)] ?? type;
  }

  ngOnInit() {
    this.serviceThesa.api_post('thesaTypes', []).subscribe((res) => {
      this.types = res;
      this.type = this.thesa.th_type;
    });

    if (!this.busy) {
      this.ngOnChanges();
    }
  }

  ngOnChanges() {
    this.busy = true;
    this.serviceThesa.api_post('th/' + this.thesaID, []).subscribe((res) => {
      this.thesa = res;
      console.log(this.thesa);
      this.type = this.thesa.th_type;
      this.busy = false;
    });
  }

  selectType(id:number)
    {
      if (confirm(this.language.labels().confirmTypeChange)) {
        let dt = {type: id}
        this.serviceThesa
          .api_post(
            'typeChange/' + this.thesaID,
            dt
          )
          .subscribe((res) => {
            this.type = id;
            console.log('Server', res);
          });
      }
    }
}
