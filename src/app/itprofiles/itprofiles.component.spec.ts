import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ItprofilesComponent } from './itprofiles.component';

describe('ItprofilesComponent', () => {
  let component: ItprofilesComponent;
  let fixture: ComponentFixture<ItprofilesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ItprofilesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ItprofilesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
