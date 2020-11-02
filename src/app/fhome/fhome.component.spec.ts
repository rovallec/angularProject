import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { FhomeComponent } from './fhome.component';

describe('FhomeComponent', () => {
  let component: FhomeComponent;
  let fixture: ComponentFixture<FhomeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ FhomeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(FhomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
