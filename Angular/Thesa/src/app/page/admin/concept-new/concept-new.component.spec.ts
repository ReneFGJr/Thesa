import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ConceptNewComponent } from './concept-new.component';

describe('ConceptNewComponent', () => {
  let component: ConceptNewComponent;
  let fixture: ComponentFixture<ConceptNewComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ConceptNewComponent]
    });
    fixture = TestBed.createComponent(ConceptNewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
