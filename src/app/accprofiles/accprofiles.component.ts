import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouteConfigLoadEnd } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, payment_methods } from '../fullProcess';
import { AuthGuard } from '../guard/auth-guard.service';
import { PeriodsComponent } from '../periods/periods.component';
import { credits, debits, payments, periods } from '../process_templates';

@Component({
  selector: 'app-accprofiles',
  templateUrl: './accprofiles.component.html',
  styleUrls: ['./accprofiles.component.css']
})
export class AccprofilesComponent implements OnInit {

  employee: employees = new employees;
  activePaymentMethod:payment_methods = new payment_methods;
  employe_id: string = null;
  payment: string = null;
  payments: payments[] = [];
  credits: credits[] = [];
  debits: debits[] = [];
  paymentMethods:payment_methods[] = [];
  payment_methods: payment_methods[] = [];
  active_payment: payments = new payments;
  newProc:boolean = false;
  insertN:string = null;
  insertNew:boolean = false;
  activeCred:credits = new credits;
  totalPayment:string = null;
  record:boolean = false;
  newPaymentMethod:boolean  = false;
  recordPaymentMethod:boolean = false;


  constructor(public apiService: ApiService, public route: ActivatedRoute, public authUser: AuthServiceService) { }

  ngOnInit() {
    this.start();
  }

  start(){
    let peridos: periods = new periods;

    this.employe_id = this.route.snapshot.paramMap.get('id');
    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: this.employe_id }).subscribe((emp: employees[]) => {
      this.apiService.getPaymentMethods(emp[0]).subscribe((pymM:payment_methods[])=>{
        this.paymentMethods = pymM;
      })
      this.employee = emp[0];
      peridos.idperiods = 'all';
      peridos.status = this.employe_id;
      this.totalPayment = (parseFloat(emp[0].productivity_payment) + parseFloat(emp[0].base_payment)).toFixed(2);
  
      this.apiService.getPayments(peridos).subscribe((pym: payments[]) => {
        this.payments = pym;
        this.active_payment = pym[0];
        this.setPayment();
      });
    })
  }

  setPayment() {
    this.apiService.getDebits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((deb: debits[]) => {
      this.apiService.getCredits({ id: this.active_payment.id_employee, period: this.active_payment.id_period }).subscribe((cred: credits[]) => {
        this.credits = cred;
        this.debits = deb;
      })
    })
  }

  cancelNewpaymentMethod(){
    this.newPaymentMethod = false;
  }

  insertNewPaymentMethod(){
    this.activePaymentMethod.id_user = this.authUser.getAuthusr().iduser;
    this.apiService.insertPaymentMethod(this.activePaymentMethod).subscribe((str:string)=>{
      this.newPaymentMethod = false;
      this.start();
    })
  }


  setNewpaymentMethod(){
    this.activePaymentMethod = new payment_methods;
    this.activePaymentMethod.id_employee = this.employee.idemployees;
    this.activePaymentMethod.predeterm = "1";
    this.activePaymentMethod.id_user = this.authUser.getAuthusr().user_name;
    this.activePaymentMethod.date = (new Date().getFullYear().toString()) + "-" + (new Date().getMonth().toString()) + "-" + (new Date().getDate().toString());
    this.newPaymentMethod = true;
  }

  newDeduction(str:string){
    this.activeCred.date = new Date().getFullYear().toString() + "-" + new Date().getMonth().toString() + "-" + new Date().getDate().toString();
    this.insertN = str;
    this.insertNew = true;
  }

  setPaymentMethod(paymentMethod:payment_methods){
    this.activePaymentMethod = paymentMethod;
    this.recordPaymentMethod = true;
  }

  insertDeduction(){
    this.activeCred.id_user = this.authUser.getAuthusr().iduser;
    this.activeCred.idpayments = this.active_payment.idpayments;
    this.activeCred.id_employee = this.employe_id;
    if(this.insertN == 'Debit'){
      this.apiService.insertDebits(this.activeCred).subscribe((str:string)=>{
        this.activeCred.iddebits = str;
        this.apiService.insertPushedDebit(this.activeCred).subscribe((str:string)=>{
          this.setPayment();
        })
      })
    }else{
      this.apiService.insertCredits(this.activeCred).subscribe((str:string)=>{
        this.activeCred.iddebits = str;
        this.apiService.insertPushedCredit(this.activeCred).subscribe((str:string)=>{
          this.setPayment();
        })
      })
    }
    this.insertNew = false;
  }

  cancelDeduction(){
    this.insertNew = false;
    this.setPayment();
  }

  setCredit(cred:credits){
    this.apiService.getPushedCredits(cred).subscribe((de:credits)=>{
      this.activeCred = de;
      this.insertNew = true;
      this.insertN = 'Credit';
      this.record = true;
    })
  }

  setDeduction(deb:debits){
      this.apiService.getPushedDebits(deb).subscribe((de:credits)=>{
        this.activeCred = de;
        this.insertNew = true;
        this.insertN = 'Debit';
        this.record = true;
      })
  }
}