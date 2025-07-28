import { Component, EventEmitter, Input, Output, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ServiceThesaService } from '../../../../000_core/service/service-thesa.service';

@Component({
  selector: 'app-skos-exactmath-edit',
  standalone: false,
  templateUrl: './skos-exactmath-edit.component.html',
  styleUrl: './skos-exactmath-edit.component.scss',
})
export class SkosExactmathEditComponent {
  @Input() conceptID: number = 0;
  @Input() thesaID: number = 0;
  @Output() actionAC: EventEmitter<any> = new EventEmitter<any>();
  @Output() linkAdded = new EventEmitter<{ label: string; uri: string }>();

  linkForm: FormGroup;
  message: string = '';
  data: any;

  constructor(
    private fb: FormBuilder,
    private serviceThesa: ServiceThesaService
  ) {
    this.linkForm = this.fb.group({
      label: [{ value: '', disabled: true }],
      uri: [
        'http://data.loterre.fr/ark:/67375/Q1W-MJMTQSSG-Q',
        [Validators.required, Validators.pattern(/^https?:\/\/.+/)],
      ],
    });
  }

  ngOnChanges(): void {
    this.message = '';
  }

  OnInit() {
    this.message = '';
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
      this.serviceThesa.api_post('exactmatch', dt).subscribe({
        next: (response) => {
          console.log('Link data added successfully:', response);
          this.data = response;
          if (this.data.status != '200') {
            this.message = this.data?.message || 'Error adding link data';
          } else if (this.data?.status == '200') {
            this.linkForm.reset();
          }

        },
      });
    }
  }
}
