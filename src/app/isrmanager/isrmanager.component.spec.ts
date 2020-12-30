import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { IsrmanagerComponent } from './isrmanager.component';

describe('IsrmanagerComponent', () => {
  let component: IsrmanagerComponent;
  let fixture: ComponentFixture<IsrmanagerComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ IsrmanagerComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IsrmanagerComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
