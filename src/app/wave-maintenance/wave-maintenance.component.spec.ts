import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WaveMaintenanceComponent } from './wave-maintenance.component';

describe('WaveMaintenanceComponent', () => {
  let component: WaveMaintenanceComponent;
  let fixture: ComponentFixture<WaveMaintenanceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WaveMaintenanceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WaveMaintenanceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
