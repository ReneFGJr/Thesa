import { Component, Input } from '@angular/core';

@Component({
    selector: 'app-related',
    templateUrl: './related.component.html',
    styleUrl: './related.component.scss',
    standalone: false
})
export class RelatedComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;

  newRelated()
    {
      alert("RelatedComponent: newRelated() is not implemented yet")
    }
}
