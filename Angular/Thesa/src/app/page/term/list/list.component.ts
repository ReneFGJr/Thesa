import { Component, Input } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  selector: 'app-term-list',
  templateUrl: './list.component.html',
  styleUrls: ['./list.component.css'],
})
export class ListComponent {
  @Input() public data: Array<any> | any;

  constructor(
    private router:Router
  ) {}

  NgOnInit() {}

  viewTerm(ID:string = '')
    {
      this.router.navigate(['term/'+ID])
    }
}
