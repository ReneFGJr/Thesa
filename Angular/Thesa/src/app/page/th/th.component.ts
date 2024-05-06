import { Component, Input } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';

@Component({
  selector: 'app-th',
  templateUrl: './th.component.html',
})
export class ThComponent {
  @Input() public idT: number = 0;
  @Input() public action: string = '';

  public thesa: Array<any> | any;
  public terms: Array<any> | any;
  public data: Array<any> | any;
  public id: number = 0;
  public sub: Array<any> | any;

  constructor(
    private route: ActivatedRoute,
    private thesaServiceService: ThesaServiceService,
    private router: Router
  ) {}

  actionMenu(act:string='')
    {
      /* Zero o termo */
      this.idT = 0
      this.action = act;
    }

  ngOnInit() {
    this.sub = this.route.params.subscribe((params) => {
      this.id = +params['id']; // (+) converts string 'id' to a number

      this.thesaServiceService.getId(this.id, 'th').subscribe(
        (res) => {
          this.thesa = res;
          console.log(res);

          /************** Terms */
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

  updateTerm(ID: number) {
    this.idT = ID;
  }
}
