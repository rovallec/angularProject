import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AcphhomeComponent } from './acphhome.component';

describe('AcphhomeComponent', () => {
  let component: AcphhomeComponent;
  let fixture: ComponentFixture<AcphhomeComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AcphhomeComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AcphhomeComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
