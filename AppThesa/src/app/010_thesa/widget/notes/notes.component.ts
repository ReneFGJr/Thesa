import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-notes',
  templateUrl: './notes.component.html',
  styleUrl: './notes.component.scss',
})
export class NotesComponent {
  @Input() notes: any[] = [];
  @Input() editMode: boolean = false;

  newNotes()
  {
    alert("NotesComponent: newNotes() is not implemented yet")
  }
}
