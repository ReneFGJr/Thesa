import { Component, Input, Output, EventEmitter } from '@angular/core';
import { SimpleChanges } from '@angular/core';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';
import {
  trigger,
  state,
  style,
  transition,
  animate,
} from '@angular/animations';

@Component({
  selector: 'app-concept',
  templateUrl: './concept.component.html',
})
export class ConceptComponent {
  @Input() public th: number = 0;
  @Input() public idT: number = 0;
  @Output() public IDEvent = new EventEmitter<number>();
  public data: Array<any> | any;
  public isVisible: boolean = false;

  constructor(private thesaServiceService: ThesaServiceService) {}

  getTerm(ID: number = 0) {
    let dt = [{}];
    let IDt = this.idT.toString();
    this.thesaServiceService.api_post('c/' + IDt, dt).subscribe((res) => {
      console.log(res);
      this.data = res;
    });
  }

  hiddenDIV() {
    this.isVisible = !this.isVisible;
  }

  copyToClipboard(val: string) {
    const selBox = document.createElement('textarea');
    selBox.style.position = 'fixed';
    selBox.style.left = '0';
    selBox.style.top = '0';
    selBox.style.opacity = '0';
    selBox.value = val;
    document.body.appendChild(selBox);
    selBox.focus();
    selBox.select();
    document.execCommand('copy');
    document.body.removeChild(selBox);
  }

  selectConcept(idT: number) {
    this.IDEvent.emit(idT);
  }

  ngOnChanges(changes: SimpleChanges) {
    this.getTerm(this.idT);
  }
}
