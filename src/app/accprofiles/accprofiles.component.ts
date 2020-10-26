import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouteConfigLoadEnd } from '@angular/router';
import { ApiService } from '../api.service';
import { employees, payment_methods } from '../fullProcess';
import { PeriodsComponent } from '../periods/periods.component';
import { credits, debits, payments, periods } from '../process_templates';

@Component({
  selector: 'app-accprofiles',
  templateUrl: './accprofiles.component.html',
  styleUrls: ['./accprofiles.component.css']
})
export class AccprofilesComponent implements OnInit {

  employee: employees = new employees;
  employe_id: string = null;
  profile_id: string = null;
  payment: string = null;
  payments: payments[] = [];
  credits: credits[] = [];
  debits: debits[] = [];
  payment_methods: payment_methods[] = [];
  active_payment: payments = new payments;

  constructor(public apiService: ApiService, public route: ActivatedRoute) { }

  ngOnInit() {
    let peridos: periods = new periods;

    this.employe_id = this.route.snapshot.paramMap.get('id').split(";")[0];
    this.profile_id = this.route.snapshot.paramMap.get('id').split(";")[1];
    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: this.employe_id }).subscribe((emp: employees[]) => {
      this.employee = emp[0];
    })

    peridos.idperiods = 'all';
    peridos.status = this.employe_id;

    this.apiService.getPayments(peridos).subscribe((pym: payments[]) => {
      this.payments = pym;
      this.active_payment = pym[0];
      this.setPayment();
    });
  }

  setPayment() {
    this.apiService.getDebits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((deb: credits[]) => {
      this.apiService.getCredits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((cred: credits[]) => {
        this.credits = cred;
        this.debits = deb;
      })
    })
  }
}