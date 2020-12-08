import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { MaquetacionComponent } from './maquetacion.component';

describe('MaquetacionComponent', () => {
  let component: MaquetacionComponent;
  let fixture: ComponentFixture<MaquetacionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ MaquetacionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(MaquetacionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
