import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AccprofilesComponent } from './accprofiles.component';

describe('AccprofilesComponent', () => {
  let component: AccprofilesComponent;
  let fixture: ComponentFixture<AccprofilesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AccprofilesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AccprofilesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
