import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PeriodsPhComponent } from './periods-ph.component';

describe('PeriodsPhComponent', () => {
  let component: PeriodsPhComponent;
  let fixture: ComponentFixture<PeriodsPhComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PeriodsPhComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PeriodsPhComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
