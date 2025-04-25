import { Component, Input } from '@angular/core';

@Component({
    selector: 'app-broader',
    templateUrl: './broader.component.html',
    styleUrl: './broader.component.scss',
    standalone: false
})
export class BroaderComponent {
  @Input() terms: Array<any> | any;
  @Input() editMode: boolean = false;
}
