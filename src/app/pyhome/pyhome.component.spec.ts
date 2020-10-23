import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PyhomeComponent } from './pyhome.component';

describe('PyhomeComponent', () => {
  let component: PyhomeComponent;
  let fixture: ComponentFixture<PyhomeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PyhomeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PyhomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
