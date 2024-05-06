import { ComponentFixture, TestBed } from '@angular/core/testing';

import { TermNewComponent } from './term-new.component';

describe('TermNewComponent', () => {
  let component: TermNewComponent;
  let fixture: ComponentFixture<TermNewComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [TermNewComponent]
    });
    fixture = TestBed.createComponent(TermNewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
