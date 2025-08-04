import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { ServiceStorageService } from '../../../000_core/service/service-storage.service';
import { PainelService } from '../../../000_core/service/painel.service';

@Component({
  selector: 'app-concept-relation',
  templateUrl: './concept.component.html',
  standalone: false,
})
export class ConceptComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() termID: number = 0;
  @Input() actionCV: string = '';
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  @Output() actionAC: any = new EventEmitter<any>();
  data: Array<any> = [];
  formAction: FormGroup;
  title: string = 'Termos';
  related: Array<any> = [];

  filterText: string = '';

  /******************* Filtro */
  filteredTerms(): any[] {
    if (!this.terms) return [];
    if (!this.filterText) return this.terms;

    const filter = this.filterText.toLowerCase();
    console.log('Filter:', filter);
    return this.terms.filter((term: any) =>
      term.Term?.toLowerCase().includes(filter)
    );
  }

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService,
    private serviceStorage: ServiceStorageService,
    private painelService: PainelService
  ) {
    this.formAction = this.fb.group({
      terms: this.fb.array([], Validators.required),
      thesaID: [-1],
      conceptID: [-1],
      verb: this.actionCV,
      apikey: [this.serviceStorage.get('apikey')],
    });
  }

  onSubmit() {
    console.log(this.formAction.value); // Aqui você acessa o ID selecionado
    let data = {
      c1: this.conceptID,
      c2: this.formAction.value.termId,
      property: this.actionCV,
      thesaurus: this.thesaID,
    };
    this.serviceThesa.api_post('relateConcept', data).subscribe((res) => {
      this.actionAC.emit('relateConcept');
    });
  }

  cancelButton() {
    this.actionAC.emit('relateConcept');
  }

  ngOnChanges() {
    this.formAction = this.fb.group({
      termId: [''], // valor inicial
    });

    setTimeout(() => {
      this.formAction.patchValue({
        thesaID: this.thesaID,
        conceptID: this.conceptID,
        verb: this.actionCV,
      });
      /************ Titulo da Página */
      if (this.actionCV === 'broader') {
        this.title = 'Conceito Geral';
      } else if (this.actionCV === 'narrow') {
        this.title = 'Conceito Específico';
      } else if (this.actionCV === 'related') {
        this.title = 'Conceito Relacionado';
        let dt = { thesaID: this.thesaID };
        this.serviceThesa.api_post('getRelationsTh', dt).subscribe((res) => {
          let dd:any = res
          this.related = dd?.relations || [];
        });
      }
    });
  }
}
