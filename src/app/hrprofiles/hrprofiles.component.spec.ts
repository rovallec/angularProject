import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HrprofilesComponent } from './hrprofiles.component';

describe('HrprofilesComponent', () => {
  let component: HrprofilesComponent;
  let fixture: ComponentFixture<HrprofilesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HrprofilesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HrprofilesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
