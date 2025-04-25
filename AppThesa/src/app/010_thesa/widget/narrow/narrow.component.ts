import { Component, Input } from '@angular/core';

@Component({
    selector: 'app-narrow',
    templateUrl: './narrow.component.html',
    styleUrl: './narrow.component.scss',
    standalone: false
})
export class NarrowComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;

  newNarrow()
    {
      alert("NarrowComponent: newNarrow() is not implemented yet")
    }
}
