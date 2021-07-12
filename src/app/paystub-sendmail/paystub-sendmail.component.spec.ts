import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PaystubSendmailComponent } from './paystub-sendmail.component';

describe('PaystubSendmailComponent', () => {
  let component: PaystubSendmailComponent;
  let fixture: ComponentFixture<PaystubSendmailComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PaystubSendmailComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PaystubSendmailComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
