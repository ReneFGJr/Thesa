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
  selector: 'app-notes-show',
  templateUrl: './notes.component.html',
})
export class NotesComponent implements OnInit, AfterViewInit {
  @Input() notes: any[] = [];
  @Input() editMode: boolean = false;
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  @Output() actionNotes: EventEmitter<any> = new EventEmitter<any>();

  @ViewChild('offcanvasNovo', { static: true }) offcanvasEl!: ElementRef;
  offcanvasInstance!: Offcanvas;

  notasProp: Array<any> | any;
  languages: Array<any> | any;
  formAction!: FormGroup;

  constructor(
    private serviceThesa: ServiceThesaService,
    private fb: FormBuilder
  ) {}

  ngOnInit() {
    // 1) Monta o FormGroup
    this.formAction = this.fb.group({
      noteType: ['', Validators.required],
      note: ['', [Validators.required, Validators.minLength(3)]],
      language: ['', Validators.required],
    });

    // 2) Carrega tipos de nota
    this.serviceThesa.api_post('getNotesType', []).subscribe(
      (res: any) => {
        this.notasProp = res.notes || res;
      },
      (err) => console.error(err)
    );

    // 3) Carrega tipos de nota
    this.serviceThesa.api_post('getLanguages/' + this.thesaID, []).subscribe(
      (res: any) => {
        this.languages = res.notes || res;
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
      thesaID: this.thesaID,
      conceptID: this.conceptID,
      noteType: this.formAction.value.noteType,
      note: this.formAction.value.note,
      language: this.formAction.value.language,
    };

    this.serviceThesa.api_post('saveNote', dt).subscribe(
      (res) => {
        console.log('Resposta do Servidor', res);
      },
      (err) => console.error(err)
    );

    console.log(dt);
    this.offcanvasInstance.hide();
    this.actionNotes.emit('OK');
  }
}
