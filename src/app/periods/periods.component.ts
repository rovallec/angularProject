import { NumberFormatStyle } from '@angular/common';
import { formattedError, IfStmt, ThrowStmt } from '@angular/compiler';
import { Route } from '@angular/compiler/src/core';
import { Component, ComponentFactoryResolver, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { Console, timeStamp } from 'console';
import { AttachSession } from 'protractor/built/driverProviders';
import { parse } from 'querystring';
import { isNull, isNullOrUndefined, isUndefined } from 'util';
import { schedule_visit } from '../addTemplate';
import { ApiService } from '../api.service';
import { employees, hrProcess } from '../fullProcess';
import { attendences, attendences_adjustment, credits, debits, deductions, disciplinary_processes, judicials, leaves, ot_manage, payments, periods, services, vacations, isr, accounts, payroll_values, payroll_values_gt, terminations, rises } from '../process_templates';
import * as XLSX from 'xlsx';
import { Observable, of } from 'rxjs';
import { promise } from 'protractor';
import { resolve } from 'url';
import { DH_NOT_SUITABLE_GENERATOR } from 'constants';
import { runInThisContext } from 'vm';
import { JitCompilerFactory } from '@angular/platform-browser-dynamic';
import { process } from '../process';


@Component({
  selector: 'app-periods',
  templateUrl: './periods.component.html',
  styleUrls: ['./periods.component.css']
})


export class PeriodsComponent implements OnInit {
  completed: boolean = false;
  file: any;
  arrayBuffer: any;
  filelist: any;
  importEnd: boolean = false;
  selectedEmployee: boolean = false;
  attendances: attendences[] = [];
  deductions: deductions[] = [];
  debits: debits[] = [];
  credits: credits[] = [];
  vacations: vacations[] = [];
  leaves: leaves[] = [];
  employees: employees[] = [];
  payments: payments[] = [];
  global_credits: credits[] = [];
  global_debits: debits[] = [];
  global_judicials: judicials[] = [];
  global_services: services[] = [];
  backUp_payments: payments[];
  selected_accounts: string = 'GET ALL';
  period: periods = new periods;
  daysOff: number = 0;
  roster: number = 0;
  attended: number = 0;
  diff: number = 0;
  absence: number = 0;
  totalDebits: number = 0;
  totalCredits: number = 0;
  seventh: number = 0;
  progress: number = 0;
  filter: string = 'name';
  absence_fixed: string = null;
  value: string = null;
  ded: boolean = true;
  non_show_2: boolean = false;
  showPayments: boolean = false;
  searchClosed: boolean = false;
  importActive: boolean = false;
  working: boolean = false;
  count_payments: number = 0;
  importType: string = null;
  importString: string = null;
  loading: boolean = false;
  isrs: isr[] = [];
  accounts: accounts[] = [];
  payroll_values: payroll_values_gt[] = [];

  constructor(public apiService: ApiService, public route: ActivatedRoute) { }

  ngOnInit() {
    this.start();
  }

  start() {
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc;
    })
    this.getDeductions();
    this.apiService.getFilteredPeriods({ id: this.route.snapshot.paramMap.get('id') }).subscribe((p: periods) => {
      this.period = p;
    });
    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
  }

  getDeductions() {
    this.apiService.getDeductions({ id: this.route.snapshot.paramMap.get('id') }).subscribe((de: deductions[]) => {
      this.deductions = de;
    })
  }

  cancelSearch() {
    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff = 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
    this.getDeductions();
    this.selectedEmployee = false;
  }

  searchEmployee() {
    if (this.ded) {
      this.apiService.getFilteredDeductions({ id: this.period.idperiods, filter: this.filter, value: this.value }).subscribe((db: deductions[]) => {
        this.deductions = db;
      })
    } else {
      this.apiService.getSearchEmployees({ dp: 'all', filter: this.filter, value: this.value }).subscribe((emp: employees[]) => {
        this.employees = emp;
      })
    }
  }

  showAll() {
    this.apiService.getallEmployees({ department: 'all' }).subscribe((emp: employees[]) => {
      this.employees = emp;
    })
    this.ded = false;
  }


  closePeriod() {
    this.payments = [];
    let cnt: number = 0;
    this.showPayments = false;
    this.apiService.getPayroll_values_gt(this.period).subscribe((pv: payroll_values_gt[]) => {
      pv.forEach(payroll_value => {
        let is_trm: boolean = false;
        let py: payments = new payments;
        this.apiService.getSearchEmployees({ dp: "exact", filter: "idemployees", value: payroll_value.id_employee }).subscribe((emp: employees[]) => {
          this.apiService.getTermdt(emp[0]).subscribe((trm: terminations) => {
            if (!isNullOrUndefined(trm.valid_from)) {
              is_trm = true;
              if (new Date(trm.valid_from).getTime() >= new Date(this.period.start).getTime()) {
                py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - (((new Date(this.period.end).getTime()) - (new Date(trm.valid_from).getTime())) / (1000 * 3600 * 24) + 1)).toFixed(2);
              }
            } else {
              py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - Number(payroll_value.seventh)).toFixed(2);
            }
            let base_salary: number = Number(emp[0].base_payment)/(240);
            let productivity_salary: number = 0;

            this.apiService.getClosingRise({id_employee:emp[0].idemployees, start:this.period.start, end:this.period.end}).subscribe((rises:rises)=>{
              if(!isNullOrUndefined(rises.effective_date)){
                productivity_salary = ((Number(rises.old_salary) - Number(emp[0].base_payment) - 250)/30) * (((new Date(rises.effective_date).getTime() - new Date(this.period.start).getTime())/(1000*3600*24)));
                productivity_salary = (productivity_salary + ((Number(emp[0].productivity_payment) - 250) * ((new Date(this.period.end).getTime() - new Date(rises.effective_date).getTime())/(1000*3600*24))))/(240);
              }else{
                productivity_salary = (Number(emp[0].productivity_payment) - 250)/240
              }
            

            this.apiService.getTransfers({ id_employee: emp[0].idemployees, start: this.period.start, end: this.period.end }).subscribe((trns: hrProcess) => {
              if (!isNullOrUndefined(trns)) {
                if (new Date(trns.date).getTime() >= new Date(this.period.start).getTime()) {
                  if (payroll_value.id_account == emp[0].id_account) {
                    py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - (((new Date(trns.date).getTime() - (new Date(this.period.start).getTime()))) / (1000 * 3600 * 24))).toFixed(2);
                  } else {
                    py.days = (((Number(payroll_value.discounted_hours) + 120) / 8) - Number(payroll_value.discounted_days) - (((new Date(this.period.end).getTime()) - (new Date(trns.date).getTime())) / (1000 * 3600 * 24))).toFixed(2);
                  }
                }
              }
              py.id_employee = payroll_value.id_employee;
              py.idpayments = payroll_value.id_payment;
              py.id_period = payroll_value.id_period;
              py.nearsol_id = payroll_value.nearsol_id;
              py.ot_hours = payroll_value.ot_hours;
              py.productivity_complete = emp[0].productivity_payment;
              py.productivity_hours = (Number(py.days) * 8).toFixed(2);
              py.seventh = payroll_value.seventh;
              py.account = payroll_value.account_name;
              py.base_complete = emp[0].base_payment;
              py.base_hours = (Number(py.days) * 8).toFixed(2);
              py.client_id = payroll_value.client_id;
              py.employee_name = emp[0].name;
              py.holidays_hours = payroll_value.holidays_hours;
              py.idpayroll_values = payroll_value.idpayroll_values;
              py.holidays = (Number(payroll_value.holidays_hours) * (base_salary + productivity_salary) * 2).toFixed(2);
              py.base = (Number(base_salary) * Number(py.base_hours)).toFixed(2);
              py.productivity = (Number(productivity_salary) * Number(py.productivity_hours)).toFixed(2);
              if(emp[0].id_account != '2' && emp[0].id_account != '4' && emp[0].id_account != '5' && emp[0].id_account != '25' && emp[0].id_account != '26' && emp[0].id_account != '27' && emp[0].id_account != '29' && emp[0].id_account != '33' && emp[0].id_account != '35' && emp[0].id_account != '20'){
                py.ot = ((Number(base_salary) + Number(productivity_salary)) * Number(py.ot_hours) * 2).toFixed(2)
              }else{
                py.ot = ((Number(base_salary) + Number(productivity_salary)) * Number(py.ot_hours) * 1.5).toFixed(2)
              }
              this.payments.push(py);
              cnt = cnt + 1;
              if (cnt == (pv.length - 1)) {
                this.showPayments = true;
                this.importActive = false;
              }
            })
          })
        })
      })
    })
    })
  }

  searchCloseEmployee() {
    let partial_payments: payments[] = [];
    this.showPayments = false;
    if (!this.searchClosed) {
      this.backUp_payments = this.payments;
    }
    this.payments.forEach(pay => {
      if (pay.employee_name.includes(this.value)) {
        partial_payments.push(pay);
      }
    })
    this.payments = partial_payments;
    this.searchClosed = false;
    this.showPayments = true;
  }


  cancelCloseSearch() {
    this.showPayments = false;
    this.searchClosed = true;
    this.payments = this.backUp_payments;
    this.showPayments = true;
  }

  closeClose() {
    this.searchClosed = true;
    this.start();
    this.ded = true;
    this.showPayments = false;
  }

  proceedClosePeriod() {
    let cnt: number = 0;
    let failed: payments[] = [];

    return this.payments.forEach(pay => {
      this.apiService.setPayment(pay).subscribe((str: string) => { // Inserta los pagos ya calculados.
        if (str != '1') {
          failed.push(pay);
        }
        cnt = cnt + 1;
        if (cnt == this.payments.length - 1) {
          this.apiService.setClosePeriods({ id_period: this.period.idperiods }).subscribe((str: string) => { // ejecuta proceso de Cierre de período. CLOSE_PERIODS
            if (str.split("|")[0] == 'Info:') {
              window.alert(str.split("|")[0] + "\n" + str.split("|")[1]);
              this.loading = false;
              this.start();

            } else {
              window.alert("An error has occured:\n" + str.split("|")[0] + "\n" + str.split("|")[1] + str.split("|")[2] + "\n" + str.split("|")[3]);
              this.loading = false;
            }
          }); //Fin del if.
        }
        return str;
      })
    }
    );
  };

  revertClosePeriod() {
    this.apiService.setRevertClosePeriods({ id_period: this.period.idperiods }).subscribe((str: string) => { // ejecuta proceso de Cierre de período. CLOSE_PERIODS
      if (str.split("|")[0] == 'Info:') {
        window.alert(str.split("|")[0] + "\n" + str.split("|")[1]);
        this.loading = false;
        this.start();

      } else {
        window.alert("An error has occured:\n" + str.split("|")[0] + "\n" + str.split("|")[1] + str.split("|")[2] + "\n" + str.split("|")[3]);
        this.loading = false;
      }
    }); //Fin del if.
  }

  getHome() {
    window.open("./", "_self");
  }

  completePeriod() {
    this.loading = true;
    this.pushDeductions('credits', this.global_credits);
    this.pushDeductions('debits', this.global_debits);
    this.global_services.forEach(service => {
      if (Number(service.max) === Number(service.current)) {
        service.status = '0';
      }
      this.apiService.updateServices(service).subscribe((str: string) => { });
    });

    this.start();
    let respuesta: any;
    respuesta = this.proceedClosePeriod();
  }

  setPayTime(id_employee: string, id_profile: string) {

  }

  activeImport() {
    this.importActive = true;
  }

  completeImport() {
    if (this.importType == 'Bonus') {
      this.pushDeductions('credits', this.credits);
    } else {
      this.pushDeductions('debits', this.debits);
    }
    this.completed = false;
    this.importActive = false;
    this.showPayments = true;
  }

  addfile(event) {
    this.credits = [];
    this.file = event.target.files[0];
    let partial_credits: credits[] = [];
    let fileReader = new FileReader();
    let found: boolean = false;

    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      if (!this.completed) {
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
          let cred: credits = new credits;
          cred.iddebits = element['Nearsol ID'];
          cred.amount = element['Amount'];
          partial_credits.push(cred);
        })
        let count: number = 0;
        this.apiService.getPayments(this.period).subscribe((paymnts: payments[]) => {
          partial_credits.forEach(ele => {
            this.apiService.getSearchEmployees({ dp: 'exact', filter: 'nearsol_id', value: ele.iddebits }).subscribe((emp: employees[]) => {
              ele.type = this.importType;
              if (!isNullOrUndefined(emp[0])) {
                paymnts.forEach(py => {
                  if (py.id_employee == emp[0].idemployees) {
                    ele.idpayments = py.idpayments;
                  }
                });
                ele.notes = emp[0].name;
              } else {
                ele.notes = "ERROR";
              }
              count = count + 1;
              if (this.importType == "Bonus") {
                this.credits.push(ele);
              } else if (this.importType == "Discount") {
                let deb: debits = new debits;
                deb.iddebits = ele.iddebits;
                deb.amount = ele.amount;
                deb.date = ele.date;
                deb.id_employee = ele.id_employee;
                deb.idpayments = ele.idpayments;
                deb.notes = ele.notes;
                deb.type = ele.type;
                this.debits.push(deb);
              }

              if (count == (partial_credits.length - 1)) {
                this.importEnd = true;
                this.completed = true;
              }
            })
          })
        })
      }
    }
  }

  pushDeductions(str: string, credits?: credits[], debits?: debits[]) {
    if (str == 'debits') {
      credits.forEach(cred => {
        this.apiService.insertDebits(cred).subscribe((str: string) => { });
      });
    } else {
      if (str == 'credits') {
        credits.forEach(cred => {
          this.apiService.insertCredits(cred).subscribe((str: string) => { });
        })
      }
    }
  }

  setType() {
    if (this.importType == 'ISR') {
      this.importString = 'Mensual';
    }
  }

  exportPayrollReport() {
    window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportNominaReport.php?AID_Period=" + this.period.idperiods, "_self");
  }

  exportBankReport() {
    window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportBankReport.php?AID_Period=" + this.period.idperiods, "_self")
  }

  setAccount(str: string) {
    this.selected_accounts = str;
  }

  exportBilling() {
    if (this.selected_accounts == "GET ALL") {
      this.selected_accounts = this.accounts[0].idaccounts;
      this.accounts.forEach((acc) => {
        this.selected_accounts = this.selected_accounts + "," + acc.idaccounts;
      })
    }
    let end: Date = new Date(Number(this.period.start.split("-")[0]), Number(this.period.start.split("-")[1]), 0);
    window.open("./../phpscripts/exportBilling.php?start=" + (this.period.start.split("-")[0] + "-" + (Number(this.period.start.split("-")[1])).toString().padStart(2, "0") + "-" + "01") + "&end=" + (end.getFullYear().toString() + "-" + (end.getMonth() + 1).toString().padStart(2, "0") + "-" + end.getDate().toString()) + "&account=" + this.selected_accounts, "_self")
  }

  setAccountingPolicy() {
    // Se encarga de generar la póliza contable al período en curso si no existiera.
    window.open(`${this.apiService.PHP_API_SERVER}` + "/phpscripts/exportAccountingPolicyReport.php?AID_Period=" + this.period.idperiods, "_blank")
  }
}
