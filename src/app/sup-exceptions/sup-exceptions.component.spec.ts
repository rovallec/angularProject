import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SupExceptionsComponent } from './sup-exceptions.component';

describe('SupExceptionsComponent', () => {
  let component: SupExceptionsComponent;
  let fixture: ComponentFixture<SupExceptionsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SupExceptionsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SupExceptionsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
