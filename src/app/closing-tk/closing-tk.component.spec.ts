import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ClosingTkComponent } from './closing-tk.component';

describe('ClosingTkComponent', () => {
  let component: ClosingTkComponent;
  let fixture: ComponentFixture<ClosingTkComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ClosingTkComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ClosingTkComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
