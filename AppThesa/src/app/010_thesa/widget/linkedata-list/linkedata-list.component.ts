import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-linkedata-list',
  standalone: false,
  templateUrl: './linkedata-list.component.html',
  styleUrl: './linkedata-list.component.scss'
})
export class LinkedataListComponent {
  @Input() linkedData: Array<any> = [];
  @Input() editMode: boolean = false;
}
