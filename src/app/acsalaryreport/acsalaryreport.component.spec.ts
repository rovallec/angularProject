import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AcsalaryreportComponent } from './acsalaryreport.component';

describe('AcsalaryreportComponent', () => {
  let component: AcsalaryreportComponent;
  let fixture: ComponentFixture<AcsalaryreportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AcsalaryreportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AcsalaryreportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
