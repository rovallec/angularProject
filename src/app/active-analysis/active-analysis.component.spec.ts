import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ActiveAnalysisComponent } from './active-analysis.component';

describe('ActiveAnalysisComponent', () => {
  let component: ActiveAnalysisComponent;
  let fixture: ComponentFixture<ActiveAnalysisComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ActiveAnalysisComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ActiveAnalysisComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
