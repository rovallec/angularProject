import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AccountingPoliciesComponent } from './accounting-policies.component';

describe('AccountingPoliciesComponent', () => {
  let component: AccountingPoliciesComponent;
  let fixture: ComponentFixture<AccountingPoliciesComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AccountingPoliciesComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AccountingPoliciesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
