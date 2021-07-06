import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ItdashboardComponent } from './itdashboard.component';

describe('ItdashboardComponent', () => {
  let component: ItdashboardComponent;
  let fixture: ComponentFixture<ItdashboardComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ItdashboardComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ItdashboardComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
