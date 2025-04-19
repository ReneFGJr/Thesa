import {
  Component,
  Input,
  Output,
  EventEmitter,
  ViewChild,
  ElementRef,
  AfterViewInit,
  OnInit,
} from '@angular/core';
import { Offcanvas } from 'bootstrap';
import { ServiceThesaService } from '../../../000_core/service/service-thesa.service';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';

@Component({
  selector: 'app-notes',
  templateUrl: './notes.component.html',
})
export class NotesComponent implements OnInit, AfterViewInit {
  @Input() notes: any[] = [];
  @Input() editMode: boolean = false;
  @Input() thesaId: string = '';
  @Input() conceptId: string = '';

  @ViewChild('offcanvasNovo', { static: true }) offcanvasEl!: ElementRef;
  offcanvasInstance!: Offcanvas;

  notasProp: Array<any> | any;
  formAction!: FormGroup;

  constructor(
    private serviceThesa: ServiceThesaService,
    private fb: FormBuilder
  ) {}

  ngOnInit() {
    // 1) Monta o FormGroup
    this.formAction = this.fb.group({
      property: ['', Validators.required],
      nt_content: ['', [Validators.required, Validators.minLength(3)]],
    });

    // 2) Carrega tipos de nota
    this.serviceThesa.api_post('getNotesType', []).subscribe(
      (res: any) => {
        this.notasProp = res.notes || res;
      },
      (err) => console.error(err)
    );
  }

  ngAfterViewInit() {
    this.offcanvasInstance = new Offcanvas(this.offcanvasEl.nativeElement);
  }

  newNotes() {
    // limpa e abre o painel
    this.formAction.reset();
    this.offcanvasInstance.show();
  }

  saveNote() {
    if (this.formAction.invalid) return;

    let dt = {
      thesaID: this.thesaId,
      conceptID: this.conceptId,
      property: this.formAction.value.property,
      nt_content: this.formAction.value.nt_content,
    };

    console.log(dt);
    this.offcanvasInstance.hide();
  }
}
