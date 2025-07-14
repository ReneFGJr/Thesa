import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-concept-card',
  templateUrl: './concept-card.component.html',
  styleUrl: './concept-card.component.scss',
  standalone: false,
})
export class ConceptCardComponent {
  @Input() data: any;

  hasDefinition(): boolean {
    return (
      Array.isArray(this.data?.notes) &&
      this.data.notes.some((def:any) => def.p_name === 'Definição')
    );
  }
}
