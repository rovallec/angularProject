import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DailypayrollvaluesComponent } from './dailypayrollvalues.component';

describe('DailypayrollvaluesComponent', () => {
  let component: DailypayrollvaluesComponent;
  let fixture: ComponentFixture<DailypayrollvaluesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DailypayrollvaluesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DailypayrollvaluesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
