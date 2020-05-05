import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HrhomeComponent } from './hrhome.component';

describe('HrhomeComponent', () => {
  let component: HrhomeComponent;
  let fixture: ComponentFixture<HrhomeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HrhomeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HrhomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
