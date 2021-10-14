import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RostermaintenanceComponent } from './rostermaintenance.component';

describe('RostermaintenanceComponent', () => {
  let component: RostermaintenanceComponent;
  let fixture: ComponentFixture<RostermaintenanceComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RostermaintenanceComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RostermaintenanceComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
