import { LanguageService } from '../../../../000_core/service/language.service';
import { ChangeDetectorRef, Component, EventEmitter, Input, Output } from '@angular/core';
import { FormBuilder, FormGroup } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../../000_core/service/service-storage.service';

@Component({
  selector: 'app-config-visibility',
  templateUrl: './config-visibility.component.html',
  styleUrl: './config-visibility.component.scss',
  standalone: false,
})
export class ConfigVisibilityComponent {
  @Input() thesaID: number = 0; // modo de edição
  @Input() field: string = ''; // Campo do modo de edição
  @Input() status: number = 0;
  @Output() thesaIDChange = new EventEmitter<string>();

  thesa: any = []; // Dados do thesa
  busy: boolean = false; // Indica se a requisição está em andamento



  constructor(
    public readonly language: LanguageService,
    private serviceThesa: ServiceThesaService,
    private cdr: ChangeDetectorRef
  ) {}
  get types() {
    const labels = this.language.labels();
    return [
      { id: 1, name: labels.publicStatus, description: labels.publicDescription },
      { id: 2, name: labels.privateStatus, description: labels.privateDescription },
      { id: 9, name: labels.cancelledStatus, description: labels.cancelledDescription },
    ];
  }

  ngOnInit() {
    if (!this.busy) {
      this.ngOnChanges();
    }
  }

  ngOnChanges() {
    this.busy = true;
    this.serviceThesa.api_post('th/' + this.thesaID, []).subscribe((res) => {
      this.thesa = res;
      this.busy = false;
      this.cdr.detectChanges(); // <- Aqui força o Angular a atualizar a tela
    });
  }

  selectType(id: number) {
    if (confirm(this.language.labels().confirmVisibilityChange)) {
      let dt = { type: id };
      this.serviceThesa
        .api_post('changeStatus/' + this.thesaID, dt)
        .subscribe((res) => {
          this.status = id;
          console.log('Server', res);
          this.thesaIDChange.emit('reload');
        });
    }
  }
}
