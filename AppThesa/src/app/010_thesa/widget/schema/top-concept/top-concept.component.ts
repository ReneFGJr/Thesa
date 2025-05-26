import { Component, Input } from '@angular/core';

@Component({
  selector: 'app-top-concept',
  templateUrl: './top-concept.component.html',
  styleUrl: './top-concept.component.scss'
})
export class TopConceptComponent {
  @Input() data: any;
  topConcept: boolean = false;

  ngOnInit()
    {
      this.topConcept = this.data?.topConcept || false;
      if (this.data?.topConcept === undefined) {
        this.topConcept = false;
      }
    }
}
