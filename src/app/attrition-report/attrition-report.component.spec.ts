import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AttritionReportComponent } from './attrition-report.component';

describe('AttritionReportComponent', () => {
  let component: AttritionReportComponent;
  let fixture: ComponentFixture<AttritionReportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AttritionReportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AttritionReportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
