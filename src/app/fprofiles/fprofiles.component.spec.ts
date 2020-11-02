import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FprofilesComponent } from './fprofiles.component';

describe('FprofilesComponent', () => {
  let component: FprofilesComponent;
  let fixture: ComponentFixture<FprofilesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FprofilesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FprofilesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
