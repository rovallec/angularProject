import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MiprofilesComponent } from './miprofiles.component';

describe('MiprofilesComponent', () => {
  let component: MiprofilesComponent;
  let fixture: ComponentFixture<MiprofilesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MiprofilesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MiprofilesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
