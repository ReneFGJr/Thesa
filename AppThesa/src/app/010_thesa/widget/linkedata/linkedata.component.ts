import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-linkedata',
  templateUrl: './linkedata.component.html',
  styleUrl: './linkedata.component.scss',
})
export class LinkedataComponent {
  @Input() data: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  @Output() actionLD = new EventEmitter<any>();

  newLinkedData(ev: Event) {
    {
      this.actionLD.emit('linkeddata');
    }
  }
}
