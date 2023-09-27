import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { ThesaServiceService } from 'src/app/service/thesa-service.service';
import { LocalStorageService } from 'src/app/service/local-storage.service';

@Component({
  selector: 'thesa-th-select',
  templateUrl: './th-select.component.html',
  styleUrls: ['./th-select.component.css'],
})
export class ThSelectComponent {
  public thesa: Array<any> | any;

  constructor(
    //private route: ActivatedRoute,
    private thesaServiceService: ThesaServiceService,
    private LocalStorageService: LocalStorageService,
    private route: Router
  ) {}

  select_th(id: string) {
    this.LocalStorageService.set('th', [id]);
    this.route.navigate(['th/' + id]);
  }

  ngOnInit() {
    this.thesaServiceService.getId(0, 'thopen').subscribe(
      (res) => {
        this.thesa = res;
      },
      (error) => error
    );
  }
}
