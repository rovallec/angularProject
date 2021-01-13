import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { credits, debits, deductions, payments, payroll_values, periods } from '../process_templates';
import * as XLSX from 'xlsx';
import { employees } from '../fullProcess';
import { isNull } from '@angular/compiler/src/output/output_ast';
import { AuthServiceService } from '../auth-service.service';

@Component({
  selector: 'app-periods-ph',
  templateUrl: './periods-ph.component.html',
  styleUrls: ['./periods-ph.component.css']
})
export class PeriodsPhComponent implements OnInit {

  constructor(public apiServices:ApiService, public route:ActivatedRoute, public authUser:AuthServiceService) { }

  activePeriod:periods = new periods;
  allDebits:debits[] = [];
  allCredits:credits[] = [];
  allPayments:payments[] = [];
  show_debits:debits[] = [];
  show_credits:credits[] = [];
  show_payments:credits[] = [];
  actual_show:number = 0;
  showAll:boolean = false;
  searching:boolean = false;
  importType:string = "CREDIT";
  importDescription:string = "WIDGET";
  importCurrency:string = "Php";
  showImport:boolean = false;
  rate:string = null;

  file: any;
  arrayBuffer: any;
  filelist: any;
  correct:number = 0;
  incorrect:number = 0;
  importFail:debits[] = [];
  loading:boolean = true;
  stop_show:boolean = false;
  working:boolean = false;

  last_push:boolean = true;
  show_fail:boolean = false;

  writeComplete:boolean = false;

  filter:string = 'name';
  value:string = null;

  selectedPayment:payments = new payments;
  emp_credits:credits[] = [];
  emp_debits:debits[] = [];
  total_debits:string = null;
  total_credits:string = null;

  allPayroll:payroll_values[] = [];
  errorPayroll:payroll_values[] = [];
  show_payroll:payroll_values[] = [];
  importPayroll:boolean = false;

  start_view:boolean = true;

  ngOnInit() {
    this.start();
  }

  showPayroll(){
    if(!this.last_push){
      this.actual_show = this.actual_show + 50;
    }
    let min = this.actual_show;
    let max = this.actual_show + 50;
    this.show_payroll = [];
    for (let i = min; i < max; i++) {
      if((this.allPayroll.length-1) >= i){
        this.show_payroll.push(this.allPayroll[i]);
      }else{
        this.stop_show = true;
      }
    }
    this.actual_show = max;
    this.last_push = true;
    this.start_view =false;
  }

  less_showPayroll(){
    if(this.last_push){
      this.actual_show = this.actual_show - 50;
    }
    this.stop_show = false;
    let min = this.actual_show;
    let max = this.actual_show - 50;
    this.show_payroll = [];
    for (let i = max; i < min; i++) {
      if((this.allPayroll.length - 1) >= i){
        this.show_payroll.push(this.allPayroll[i]);
      }else{
        this.stop_show = true;
      }
    }
    this.actual_show = max;
    this.last_push = false;
    this.start_view =false;
  }

  showDebits(){
    if(!this.last_push){
      this.actual_show = this.actual_show + 25;
    }
    let min = this.actual_show;
    let max = this.actual_show + 25;
    this.show_debits = [];
    for (let i = min; i < max; i++) {
      if((this.allDebits.length-1) >= i){
        this.show_debits.push(this.allDebits[i]);
      }else{
        this.stop_show = true;
      }
    }
    this.actual_show = max;
    this.last_push = true;
    this.start_view =false;
  }

  less_showDebits(){
    if(this.last_push){
      this.actual_show = this.actual_show - 25;
    }
    this.stop_show = false;
    let min = this.actual_show;
    let max = this.actual_show - 25;
    this.show_debits = [];
    for (let i = max; i < min; i++) {
      if((this.allDebits.length - 1) >= i){
        this.show_debits.push(this.allDebits[i]);
      }else{
        this.stop_show = true;
      }
    }
    this.actual_show = max;
    this.last_push = false;
    this.start_view =false;
  }

  showCredits(){
    if(!this.last_push){
      this.actual_show = this.actual_show + 25;
    }
    let min = this.actual_show;
    let max = this.actual_show + 25;
    this.show_credits = [];
    for (let i = min; i < max; i++) {
      if((this.allCredits.length - 1) >= i){
        this.show_credits.push(this.allCredits[i]);
      }else{
        this.stop_show = true;
      }
    }
    this.actual_show = max;
    this.last_push = true;
    this.start_view =false;
  }

  less_showCredits(){
    if(this.last_push){
      this.actual_show = this.actual_show - 25;
    }
    this.stop_show = false;
    let min = this.actual_show;
    let max = this.actual_show - 25;
    this.show_credits = [];
    for (let i = max; i < min; i++) {
      if((this.allCredits.length - 1) >= i){
        this.show_credits.push(this.allCredits[i]);
      }
    }
    this.actual_show = max;
    this.last_push = false;
    this.start_view =false;
  }

  start(){
    this.apiServices.getPeriods().subscribe((periods:periods[])=>{
      periods.forEach(period=>{
        if(period.idperiods = this.route.snapshot.paramMap.get('id')){
          this.activePeriod = period;
        }
      })
    })

    this.apiServices.getCredits_ph({id:'all', period:this.route.snapshot.paramMap.get('id')}).subscribe((cred:credits[])=>{
      if(!isNullOrUndefined(cred)){
        this.allCredits = cred;
        this.showCredits();
      }
    })

    this.apiServices.getDebits_ph({id:'all', period:this.route.snapshot.paramMap.get('id')}).subscribe((deb:debits[])=>{
      this.allDebits = deb;
      if(!isNullOrUndefined(deb)){
      }
    })
  }

  resetCount(emp?:string){
    this.actual_show = 0;
    this.showAll = false;
    if(!isNullOrUndefined(emp)){
      if(emp == 'CREDITS'){
        this.importType = "CREDIT";
      }else{
        if(emp == 'DEBITS'){
          this.importType = "DEBIT";
        }else{
          this.showAll = true;
          this.searching = true;
        }
      }
    }
  }

  addfile(event) {
    let failures:debits = new debits;
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    this.allCredits = [];
    this.allDebits = [];
    this.loading = true;
    this.correct = 0
    this.actual_show = 0;
    this.importFail = [];
    this.working = true;

    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      this.arrayBuffer = fileReader.result;
      var data = new Uint8Array(this.arrayBuffer);
      var arr = new Array();
      for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
      var bstr = arr.join("");
      var workbook = XLSX.read(bstr, { type: "binary" });
      var first_sheet_name = workbook.SheetNames[0];
      var worksheet = workbook.Sheets[first_sheet_name];
      let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
      sheetToJson.forEach(element => {
        this.apiServices.getFilteredEmployees_ph({filter:'nearsol_id', value:element['NEARSOL ID'], dp:'exact'}).subscribe((emp:employees[])=>{
          if(!isNullOrUndefined(emp)){
            let per:periods = new periods;
            per.idperiods = this.activePeriod.idperiods;
            per.status = emp[0].idemployees;
            per.start = 'explicit';
            this.apiServices.getPayments_ph(per).subscribe((pay:payments[])=>{
              if(!isNullOrUndefined(pay)){
                this.correct = this.correct + 1;
                if(this.importType == "CREDIT"){
                  let deduction:credits = new credits;
                  deduction.date = this.correct.toString();
                  deduction.id_employee = emp[0].idemployees;
                  deduction.id_process = emp[0].name;
                  deduction.idpayments = pay[0].idpayments;
                  deduction.type = this.importDescription;
                  deduction.id_user = emp[0].nearsol_id
                  deduction.notes = emp[0].client_id;
                  if(this.importCurrency == 'Php'){
                    deduction.amount = Number(element['AMOUNT']).toFixed(2);
                  }else{
                    deduction.amount = Number(element['AMOUNT'] * Number(this.rate)).toFixed(2);
                  }
                  this.allCredits.push(deduction);
                }else{
                  let deduction:debits = new debits;
                  deduction.date = this.correct.toString();
                  deduction.id_employee = emp[0].idemployees;
                  deduction.id_process = emp[0].name;
                  deduction.idpayments = pay[0].idpayments;
                  deduction.type = this.importDescription;
                  deduction.id_user = emp[0].nearsol_id
                  deduction.notes = emp[0].client_id;
                  if(this.importCurrency == 'Php'){
                    deduction.amount = Number(element['AMOUNT']).toFixed(2);
                  }else{
                    deduction.amount = Number(element['AMOUNT'] * Number(this.rate)).toFixed(2);
                  }
                  this.allDebits.push(deduction);
                }
              }else{
                failures = new debits;
                failures.id_process = element['NAME'];
                failures.amount = element['AMOUNT'];
                failures.date = "NO PAYMENT FOUND";
                this.incorrect = this.incorrect + 1;
                this.importFail.push(failures);
              }
              if((this.importFail.length + this.correct) == sheetToJson.length && this.loading == true){
                this.loading = false;
              }
            })
          }else{
            failures = new debits;
            failures.id_process = element['NAME'];
            failures.amount = element['AMOUNT'];
            failures.date = "NO EMPLOYEE FOUND";
            this.incorrect = this.incorrect + 1;
            this.importFail.push(failures);
          }
          if((this.importFail.length + this.correct) == sheetToJson.length && this.loading == true){
            this.loading = false;
          }
        })
      })
    }
  }

  saveImport(){
    this.actual_show = 0;
    this.stop_show = false;
    if(this.importType == 'CREDIT'){
      this.showCredits();
    }else{
      this.showDebits();
    }
    this.start_view = true;
    this.showImport = true;
  }

  showFail(){
    this.show_fail = true;
  }

  showCorrect(){
    this.show_fail = false;
  }

  cancelImport(){
    this.importPayroll = false;
    this.writeComplete = false;
    this.showImport = false;
    this.show_fail = false;
    this.actual_show = 0;
    this.showAll = false;
    this.searching = false;
    this.start();
  }

  writeImport(){
    this.writeComplete = false;
    let cnt:number = 0;
    this.stop_show = false;
    this.resetCount();
    if(this.importType == 'CREDIT'){
      this.allCredits.forEach(cred=>{
        this.apiServices.insertCredits_ph(cred).subscribe((str:string)=>{
          cnt = cnt + 1;
          if(cnt == this.allCredits.length){
            this.writeComplete = true;
            this.cancelImport();
          }
        })
      })
    }else{
      this.allDebits.forEach(deb=>{
        this.apiServices.insertDebits_ph(deb).subscribe((str:string)=>{
          cnt = cnt + 1;
          if(cnt == (this.allDebits.length - 1)){
            this.writeComplete = true;
            this.cancelImport();
          }
        })
      })
    }
  }

  setModal(){
    this.importType = "CREDIT";
  }

  searchEmployee(){
    this.allPayments = [];
    this.apiServices.getFilteredEmployees_ph({filter:this.filter, value:this.value, dp:this.authUser.getAuthusr().department}).subscribe((emp:employees[]) =>{
      if(!isNullOrUndefined(emp)){
        emp.forEach(employee=>{
          let p:periods = new periods;
          p.idperiods = this.activePeriod.idperiods;
          p.start = 'explicit';
          p.status = employee.idemployees;
          this.apiServices.getPayments_ph(p).subscribe((py:payments[])=>{
            if(!isNullOrUndefined(py)){
              let pay:payments = new payments;
              pay = py[0];
              pay.nearsol_id = employee.nearsol_id;
              pay.client_id = employee.client_id;
              pay.total = (Number(pay.credits) - Number(pay.debits)).toFixed(2);
              this.allPayments.push(pay);
            }
          })
        })
      }
    })
    this.searching = true;
  }

  setPayment(pay:payments){
    this.selectedPayment = pay;
    this.apiServices.getCredits_ph({id:pay.id_employee, period:this.activePeriod.idperiods}).subscribe((cred:credits[])=>{
      this.apiServices.getDebits_ph({id:pay.id_employee, period:this.activePeriod.idperiods}).subscribe((deb:debits[])=>{
        this.emp_debits = deb;
        this.emp_credits = cred;
        deb.forEach(debs=>{
          this.total_debits = (Number(debs.amount) + Number(this.total_debits)).toFixed(2);
        })
        cred.forEach(creds=>{
          this.total_credits = (Number(creds.amount) + Number(this.total_credits)).toFixed(2);
        })
      })
    })
  }

  setPayroll_values(event){
    this.allPayroll = [];
    this.errorPayroll = [];
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    this.loading = true;
    this.correct = 0
    this.actual_show = 0;
    this.working = true;

    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      this.arrayBuffer = fileReader.result;
      var data = new Uint8Array(this.arrayBuffer);
      var arr = new Array();
      for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
      var bstr = arr.join("");
      var workbook = XLSX.read(bstr, { type: "binary" });
      var first_sheet_name = workbook.SheetNames[0];
      var worksheet = workbook.Sheets[first_sheet_name];
      let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
      sheetToJson.forEach(element => {
        this.apiServices.getFilteredEmployees_ph({filter:"nearsol_id", value:element['ID'], dp:this.authUser.getAuthusr().department}).subscribe((emp:employees[])=>{
          if(!isNullOrUndefined(emp[0])){
            this.correct = this.correct + 1;
            let payroll_value:payroll_values = new payroll_values;
            payroll_value.idpayroll_values = this.correct.toString();
            payroll_value.absences = Number(element['Absences']).toFixed(2);
            payroll_value.discount = Number(element['Hours to Discount']).toFixed(2);
            payroll_value.night_hours = Number(element['Night Hours']).toFixed(2);
            payroll_value.ot_regular = Number(element['Regular OT']).toFixed(2);
            payroll_value.ot_rdot = Number(element['RDOT Regular']).toFixed(2);
            payroll_value.ot_holiday = Number(element['OT In HLD']).toFixed(2);
            payroll_value.holiday_regular = Number(element['Regular Holiday']).toFixed(2);
            payroll_value.holiday_special = Number(element['Special Holiday']).toFixed(2);

            payroll_value.name = emp[0].name;
            payroll_value.supervisor = emp[0].user_name;
            payroll_value.nearsol_id = emp[0].nearsol_id;
            payroll_value.client_id = emp[0].client_id;
            payroll_value.id_employee = emp[0].idemployees;
            payroll_value.id_period = this.activePeriod.idperiods;
            this.allPayroll.push(payroll_value);
          }else{
            let payroll_value:payroll_values = new payroll_values;
            payroll_value.client_id = element['Avaya'];
            payroll_value.nearsol_id = element['ID'];
            payroll_value.name = element['Full Name'];
            payroll_value.supervisor = element['Supervisor'];
            payroll_value.absences = "NOT EMPLOYEE MATCH WITH NEARSOL'S ID";
            this.errorPayroll.push(payroll_value);
          }
          if((this.correct + this.errorPayroll.length) == (sheetToJson.length - 1)){
            this.loading = false;
            this.showPayroll();
            this.start_view = true;
            this.working = false;
          }
        })
      })
    }
  }

  setPayrollValues(){
    this.cancelImport();
    this.importPayroll = true;
  }

  writePayrollValues(){
    this.working = true;
    this.loading = true;
    let cnt:number = 0;

    this.allPayroll.forEach(pay=>{
      this.apiServices.insertPayrollValues(pay).subscribe((str:string)=>{
        cnt = cnt + 1;
        if(cnt == (this.allPayroll.length)){
          this.working = false;
          this.loading = false;
        }  
      })
    })
  }
}
