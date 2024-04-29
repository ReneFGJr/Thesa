import { Component, Input, Output, EventEmitter } from '@angular/core';
import { FormControl } from '@angular/forms';
import { Observable } from 'rxjs';
import { map, startWith, tap } from 'rxjs/operators';
import { AsyncPipe } from '@angular/common';

@Component({
  selector: 'app-search-term',
  templateUrl: './search-term.component.html',
})
export class SearchTermComponent {
  @Output() public IDEvent = new EventEmitter<number>();
  @Input() options: any[] = []; // Array de opções passado pelo componente pai
  //options: string[] = ['One', 'Two', 'Three'];
  myControl = new FormControl();
  filteredOptions: Observable<any[]> | any;
  isEmpty:boolean = false
  max: string = '350px'

  ngOnInit() {
    this.filteredOptions = this.myControl.valueChanges.pipe(
      startWith(''),
      map((value) => this.filter(value || '')),
      tap(filtered => this.isEmpty = filtered.length === 0)
    );
  }

  private filter(value: string): any[] {
    const filterValue = value.toLowerCase();
    let rsp = this.options.filter(
      (option) => option.Term.toLowerCase().includes(filterValue),
    );
    return rsp
  }

  viewTerm(ID: number = 0) {
    this.IDEvent.emit(ID);
  }
}
