import { Observable } from 'rxjs';
import { Component } from '@angular/core';
import { ActivatedRoute, Router, Routes } from '@angular/router';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';

@Component({
  selector: 'thesa-main',
  templateUrl: './main.component.html',
})
export class ThesaMainComponent {
  public logo = '/assets/img/logo/logo_thesa.svg';
  public logoTXT = '/assets/img/logo/logo_thesa_txt.svg';
  public resume: Array<any> | any;

  public thesa_about: string = 'thesa_about';

  constructor(
    private route: ActivatedRoute,
    private thesaServiceService: ThesaServiceService,
    private router: Router
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

  openTh() {
    this.router.navigate(['thopen'], { relativeTo: this.route });
  }
}
