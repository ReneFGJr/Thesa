import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-concept-show-terms',
  templateUrl: './concept-show-terms.component.html',
})
export class ConceptShowTermsComponent {
  @Input() data: Array<any> | any;
  @Input() editMode: boolean = false;
  @Output() actionAC: EventEmitter<any> = new EventEmitter<any>();
  action(ev: Event) {
    console.log("#4-actionupdate",ev)
    this.actionAC.emit(ev);
  }
}
