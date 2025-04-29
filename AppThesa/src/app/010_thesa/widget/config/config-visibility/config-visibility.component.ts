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

  types: Array<any> | any; // Tipos de thesa

  constructor(
    private serviceThesa: ServiceThesaService,
    private cdr: ChangeDetectorRef
  ) {}
  setType() {
    this.types = [
      {
        id: 1,
        name: 'Público',
        description:
          'O Thesa está disponível para qualquer pessoa visualizar, sem restrições de acesso.',
      },
      {
        id: 2,
        name: 'Privado',
        description:
          'O Thesa é restrito e somente usuários autorizados podem visualizá-lo.',
      },
      {
        id: 9,
        name: 'Cancelado',
        description:
          'O Thesa foi invalidado ou desativado, estando indisponível para visualização pública ou privada.',
      },
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
      this.setType();
      this.busy = false;
      this.cdr.detectChanges(); // <- Aqui força o Angular a atualizar a tela
    });
  }

  selectType(id: number) {
    if (confirm('Você tem certeza que deseja alterar o tipo de thesa?')) {
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
