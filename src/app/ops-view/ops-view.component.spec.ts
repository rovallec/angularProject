import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { OpsViewComponent } from './ops-view.component';

describe('OpsViewComponent', () => {
  let component: OpsViewComponent;
  let fixture: ComponentFixture<OpsViewComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ OpsViewComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(OpsViewComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
