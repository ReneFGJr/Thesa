import { ServiceThesaService } from './../../../000_core/service/service-thesa.service';
import { Component, EventEmitter, Input, Output } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-linkedata-edit',
  standalone: false,
  templateUrl: './linkedata-edit.component.html',
  styleUrl: './linkedata-edit.component.scss',
})
export class LinkedataEditComponent {
  @Input() conceptID: number = 0;
  @Input() thesaID: number = 0;
  @Output() actionAC: EventEmitter<any> = new EventEmitter<any>();
  @Output() linkAdded = new EventEmitter<{ label: string; uri: string }>();

  linkForm: FormGroup;

  constructor(private fb: FormBuilder,
  private serviceThesa: ServiceThesaService) {
    this.linkForm = this.fb.group({
      label: [''],
      uri: [
        'https://www.wikidata.org/entity/Q8094',
        [Validators.required, Validators.pattern(/^https?:\/\/.+/)],
      ],
    });
  }

  submit() {
    if (this.linkForm.valid) {
      this.linkAdded.emit(this.linkForm.value);
      const dt = {
        label: this.linkForm.value.label,
        URI: this.linkForm.value.uri,
        conceptID: this.conceptID,
        thesaID: this.thesaID,
      };
      this.serviceThesa.api_post('linkedata', dt).subscribe({
        next: (response) => {
          console.log('Link data added successfully:', response);
          //this.linkForm.reset();
        }
      });
    }
  }

}
