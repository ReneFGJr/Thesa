import { ComponentFixture, TestBed } from '@angular/core/testing';

import { WordcountComponent } from './wordcount.component';

describe('WordcountComponent', () => {
  let component: WordcountComponent;
  let fixture: ComponentFixture<WordcountComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [WordcountComponent]
    });
    fixture = TestBed.createComponent(WordcountComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
