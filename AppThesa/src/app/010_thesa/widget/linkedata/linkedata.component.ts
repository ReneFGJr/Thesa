import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-linkedata',
  templateUrl: './linkedata.component.html',
  styleUrl: './linkedata.component.scss'
})
export class LinkedataComponent {
  @Input() notes: any[] = [];
  @Input() editMode: boolean = false;
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  @Output() actionNotes = new EventEmitter<any>();
}
