import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AttendenceImportComponent } from './attendence-import.component';

describe('AttendenceImportComponent', () => {
  let component: AttendenceImportComponent;
  let fixture: ComponentFixture<AttendenceImportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AttendenceImportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AttendenceImportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
