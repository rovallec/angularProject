import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OttrackerComponent } from './ottracker.component';

describe('OttrackerComponent', () => {
  let component: OttrackerComponent;
  let fixture: ComponentFixture<OttrackerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OttrackerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OttrackerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
