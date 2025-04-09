import { Component, Input } from '@angular/core';
import { FormArray, FormControl } from '@angular/forms';

@Component({
  selector: 'app-term-alt-label',
  templateUrl: './alt-label.component.html',
})
export class AltLabelComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
  @Input() termID: number = 0;
  formAction: any;

  onCheckboxChange(event: any) {
    const termsArray: FormArray = this.formAction.get('terms') as FormArray;

    if (event.target.checked) {
      termsArray.push(new FormControl(event.target.value));
    } else {
      const index = termsArray.controls.findIndex(
        (x) => x.value === event.target.value
      );
      if (index >= 0) {
        termsArray.removeAt(index);
      }
    }
  }
}
