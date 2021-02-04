import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AccprofilesPhComponent } from './accprofiles-ph.component';

describe('AccprofilesPhComponent', () => {
  let component: AccprofilesPhComponent;
  let fixture: ComponentFixture<AccprofilesPhComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AccprofilesPhComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AccprofilesPhComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
