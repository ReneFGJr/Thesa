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
    standalone: false
})
export class NotesComponent implements OnInit, AfterViewInit {
  @Input() notes: any[] = [];
  @Input() editMode: boolean = false;
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  @Output() actionNotes = new EventEmitter<any>();

  @ViewChild('offcanvasNovo', { static: true }) offcanvasEl!: ElementRef;
  offcanvasInstance!: Offcanvas;

  dataNotes: Array<any> | any;
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
      noteID: 0,
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

  deleteNote(id: string) {
    if (confirm('Deseja realmente excluir esta nota?')) {
      this.serviceThesa.api_post('deleteNote', { noteID: id }).subscribe(
        (res) => {
          this.actionNotes.emit('OK deleteNotes');
        },
        (err) => console.error(err)
      );
    }
  }

  cancelButton() {
    this.offcanvasInstance.hide();
  }

  editNote(id: string) {
    this.serviceThesa.api_post('getNote', { noteID: id }).subscribe((res) => {
      this.dataNotes = res;
      this.formAction.patchValue({
        noteID: id,
        noteType: this.dataNotes.data.nt_prop,
        note: this.dataNotes.data.nt_content,
        language: this.dataNotes.data.nt_lang,
      });
      this.offcanvasInstance.show();
    });
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
      noteID: this.formAction.value.noteID,
      thesaID: this.thesaID,
      conceptID: this.conceptID,
      noteType: this.formAction.value.noteType,
      note: this.formAction.value.note,
      language: this.formAction.value.language,
    };

    this.serviceThesa.api_post('saveNote', dt).subscribe(
      (res) => {
        console.log("notas",res);
      },
      (err) => console.error(err)
    );
    this.offcanvasInstance.hide();
    this.actionNotes.emit('OK Notes');
  }
}
