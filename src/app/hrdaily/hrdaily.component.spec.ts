import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HrdailyComponent } from './hrdaily.component';

describe('HrdailyComponent', () => {
  let component: HrdailyComponent;
  let fixture: ComponentFixture<HrdailyComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HrdailyComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HrdailyComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
