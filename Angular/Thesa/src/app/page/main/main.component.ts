import { Observable } from 'rxjs';
import { Component } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';

@Component({
  selector: 'thesa-main',
  templateUrl: './main.component.html'
})
export class ThesaMainComponent {
  public logo = '/assets/img/logo/logo_thesa.svg';
  public resume: Array<any> | any;

  constructor(
    private route: ActivatedRoute,
    private thesaServiceService: ThesaServiceService
  ) {}

  ngOnInit() {
    this.thesaServiceService.getId(0, 'resume').subscribe(
      (res) => {
        this.resume = res;
        console.log(this.resume);
      },
      (error) => error
    );
  }
}
