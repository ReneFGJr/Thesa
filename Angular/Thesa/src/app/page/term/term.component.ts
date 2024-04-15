import { Component, Input, SimpleChanges } from '@angular/core';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';

@Component({
  selector: 'thesa-term',
  templateUrl: './term.component.html',
})
export class TermComponent {
  @Input() public th: number = 0;
  @Input() public idT: number = 0;
  public data: Array<any> | any;

  constructor(
    private thesaServiceService: ThesaServiceService
  ) {}

  ngOnInit() {
    console.log('++++++++++++++TERM+');
    console.log(this.idT);
  }

  getTerm(ID:number = 0) {
    let dt = [{}]
    let IDt = this.idT.toString()
    this.thesaServiceService.api_post('t/'+IDt,dt).subscribe((res) => {
      console.log(res)
      this.data = res
    });
  }

  ngOnChanges(changes: SimpleChanges) {
    this.getTerm(this.idT)
  }
}
