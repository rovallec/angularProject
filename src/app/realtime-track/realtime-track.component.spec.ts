import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RealtimeTrackComponent } from './realtime-track.component';

describe('RealtimeTrackComponent', () => {
  let component: RealtimeTrackComponent;
  let fixture: ComponentFixture<RealtimeTrackComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RealtimeTrackComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RealtimeTrackComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
