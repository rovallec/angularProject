import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CalllistreportComponent } from './calllistreport.component';

describe('CalllistreportComponent', () => {
  let component: CalllistreportComponent;
  let fixture: ComponentFixture<CalllistreportComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CalllistreportComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CalllistreportComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
