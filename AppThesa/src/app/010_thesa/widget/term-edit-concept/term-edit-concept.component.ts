import { Component, Input } from '@angular/core';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-term-edit-concept',
  standalone: false,
  templateUrl: './term-edit-concept.component.html',
  styleUrl: './term-edit-concept.component.scss',
})
export class TermEditConceptComponent {
  @Input() editMode: boolean = false;
  @Input() data: any;

  constructor(private serviceThesa: ServiceThesaService) {}

  public editing: boolean = false;
  public originalTerm: string = '';

  copyLabel(): void {
    navigator.clipboard.writeText(this.data.label).then(
      () => {}
    );
  }

  saveDefinition(def: any): void {
    // Aqui você pode enviar para API, serviço, ou apenas logar
    console.log('Salvando definição:', def.nt_content);
  }

  cancelChanges(): void {
    // Aqui você pode reverter as alterações ou simplesmente desativar o modo de edição
    console.log('Alterações canceladas');
    this.editing = false; // Desativa o modo de edição
    this.data.label = this.originalTerm; // Restaura o termo original
  }

  enableEditing(): void {
    this.editing = true; // Ativa o modo de edição
    this.originalTerm = this.data.label; // Guarda o termo original
    }

  saveChanges(): void {
    // Aqui você pode enviar as alterações para a API ou serviço
    console.log('Salvando alterações:', this.data.label);
    this.editing = false; // Desativa o modo de edição após salvar
    let data = {
      id_term: this.data.id_term,
      label: this.data.label,
    };
    this.serviceThesa.api_post('updateTerm', data).subscribe(
      (res) => {
        console.log('Conceito atualizado com sucesso:', res);
      },
      (error) => {
        console.error('Erro ao atualizar conceito:', error);
      }
    );
  }
}
