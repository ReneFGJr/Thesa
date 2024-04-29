import { Component, Input, Output, EventEmitter } from '@angular/core';
import { FormControl } from '@angular/forms';
import { Observable } from 'rxjs';
import { map, startWith } from 'rxjs/operators';
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

  ngOnInit() {
    this.filteredOptions = this.myControl.valueChanges.pipe(
      startWith(''),
      map((value) => this.filter(value || ''))
    );
  }

  private filter(value: string): any[] {
    const filterValue = value.toLowerCase();
    return this.options.filter((option) =>
      option.Term.toLowerCase().includes(filterValue)
    );
  }

  viewTerm(ID: number = 0) {
    this.IDEvent.emit(ID);
  }
}
