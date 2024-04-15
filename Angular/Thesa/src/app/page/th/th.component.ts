import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';

@Component({
  selector: 'app-th',
  templateUrl: './th.component.html',
})
export class ThComponent {
  public thesa: Array<any> | any;
  public terms: Array<any> | any;
  public data: Array<any> | any;
  public id: number = 0;
  public sub: Array<any> | any;
  public idT: number = 0;

  constructor(
    private route: ActivatedRoute,
    private thesaServiceService: ThesaServiceService
  ) {}

  ngOnInit() {
    this.sub = this.route.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number

      this.thesaServiceService.getId(this.id, 'th').subscribe(
        (res) => {
          this.thesa = res;
          console.log(res);

          /************** Terms */
          console.log(this.thesa.id_th);
          let dt: Array<any> = [{}];
          this.thesaServiceService
            .api_post('terms/' + this.thesa.id_th, dt)
            .subscribe(
              (res) => {
                this.terms = res;
              },
              (error) => error
            );
        },
        (error) => error
      );
    });
  }

  updateTerm(ID:number)
    {
      this.idT = ID
    }
}
