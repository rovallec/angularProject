import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { DpMaintenanceComponent } from './dp-maintenance.component';

describe('DpMaintenanceComponent', () => {
  let component: DpMaintenanceComponent;
  let fixture: ComponentFixture<DpMaintenanceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ DpMaintenanceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(DpMaintenanceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
