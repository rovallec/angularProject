import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PyprofilesComponent } from './pyprofiles.component';

describe('PyprofilesComponent', () => {
  let component: PyprofilesComponent;
  let fixture: ComponentFixture<PyprofilesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PyprofilesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PyprofilesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
