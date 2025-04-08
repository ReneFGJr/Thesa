import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-pref-label',
  templateUrl: './pref-label.component.html',
  styleUrl: './pref-label.component.scss',
})
export class PrefLabelComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() thesa: number = 0;

  newPrefLabel()
    {
      alert("HIDDEN")
    }
}
