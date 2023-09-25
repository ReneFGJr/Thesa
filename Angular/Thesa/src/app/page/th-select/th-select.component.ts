import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';

@Component({
  selector: 'thesa-th-select',
  templateUrl: './th-select.component.html',
  styleUrls: ['./th-select.component.css'],
})
export class ThSelectComponent {
  public thesa: Array<any> | any;

  constructor(
    private route: ActivatedRoute,
    private thesaServiceService: ThesaServiceService
  ) {}

  select_th(id:string)
    {
      alert(id);
    }

  ngOnInit() {
    this.thesaServiceService.getId(0, 'thopen').subscribe(
      (res) => {
        this.thesa = res;
        console.log(this.thesa);
      },
      (error) => error
    );
  }
}
