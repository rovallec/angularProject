import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RecDailyconvergentReportComponent } from './rec-dailyconvergent-report.component';

describe('RecDailyconvergentReportComponent', () => {
  let component: RecDailyconvergentReportComponent;
  let fixture: ComponentFixture<RecDailyconvergentReportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RecDailyconvergentReportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RecDailyconvergentReportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
