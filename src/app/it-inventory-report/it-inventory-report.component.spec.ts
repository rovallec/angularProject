import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ItInventoryReportComponent } from './it-inventory-report.component';

describe('ItInventoryReportComponent', () => {
  let component: ItInventoryReportComponent;
  let fixture: ComponentFixture<ItInventoryReportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ItInventoryReportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ItInventoryReportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
