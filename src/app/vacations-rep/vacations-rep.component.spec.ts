import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { VacationsRepComponent } from './vacations-rep.component';

describe('VacationsRepComponent', () => {
  let component: VacationsRepComponent;
  let fixture: ComponentFixture<VacationsRepComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ VacationsRepComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(VacationsRepComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
