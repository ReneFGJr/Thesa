import { ThesaServiceService } from './../../service/thesa-service.service';
import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';


@Component({
  selector: 'app-concept-view',
  templateUrl: './view.component.html',
  styleUrls: ['./view.component.css'],
})
export class ViewConceptComponent {
  public type: string = 'NA';
  public thesa: Array<any> | any;
  public data: Array<any> | any;
  public sub: Array<any> | any;
  public id: number = 0;

  constructor(
    private route: ActivatedRoute,
    private thesaServiceService: ThesaServiceService
  ) {}

  ngOnInit() {
    this.sub = this.route.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number

      this.thesaServiceService.getId(this.id, 'c').subscribe(
        (res) => {
          this.data = res;
          this.type = this.data.class;
          console.log(this.data);

          this.thesaServiceService.getId(this.data.c_th, 'th').subscribe(
            (res) => {
              this.thesa = res;
              console.log(this.thesa);
            },
            (error) => error
          );
        },
        (error) => error
      );
    });
  }
}
