import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { WfmhomeComponent } from './wfmhome.component';

describe('WfmhomeComponent', () => {
  let component: WfmhomeComponent;
  let fixture: ComponentFixture<WfmhomeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ WfmhomeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(WfmhomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
