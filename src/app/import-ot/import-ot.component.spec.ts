import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ImportOtComponent } from './import-ot.component';

describe('ImportOtComponent', () => {
  let component: ImportOtComponent;
  let fixture: ComponentFixture<ImportOtComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ImportOtComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ImportOtComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
