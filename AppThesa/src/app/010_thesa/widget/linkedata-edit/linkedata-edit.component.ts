import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-linkedata-edit',
  standalone: false,
  templateUrl: './linkedata-edit.component.html',
  styleUrl: './linkedata-edit.component.scss'
})
export class LinkedataEditComponent {
  @Input() conceptID: number = 0;
  @Input() thesaID: number = 0;
  @Output() actionAC: EventEmitter<any> = new EventEmitter<any>();
}
