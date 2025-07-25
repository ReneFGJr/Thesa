import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-skos-exactmath',
  standalone: false,
  templateUrl: './skos-exactmath.component.html',
  styleUrl: './skos-exactmath.component.scss',
})
export class SkosExactmathComponent {
  @Input() data: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() thesaID: number = 0;
  @Input() conceptID: number = 0;
  @Output() actionLD = new EventEmitter<any>();

  newLinkedData(ev: Event) {
    {
      this.actionLD.emit('exactMatch');
    }
  }
}
