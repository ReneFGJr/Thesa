import { Component, EventEmitter, Input, Output } from '@angular/core';

@Component({
  selector: 'app-concept-show-concepts',
  templateUrl: './concept-show-concepts.component.html'
})
export class ConceptShowConceptsComponent {
  @Input() data: any = [];
  @Input() editMode: boolean = true;
  @Output() actionAC: EventEmitter<any> = new EventEmitter<any>();
  action(ev: Event) {
    this.actionAC.emit(ev);
  }
}
