import { Component, ElementRef, OnInit } from '@angular/core';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { periods, paystubview, checks, checksDetails, Fecha, AccountingAccounts, credits, debits, seldebits, selcredits, checkclass, checkbooks, creditsdebitsbyemployees } from '../process_templates';

@Component({
  selector: 'app-checks',
  templateUrl: './checks.component.html',
  styleUrls: ['./checks.component.css']
})
export class ChecksComponent implements OnInit {

  constructor(public apiService: ApiService, public _sanitizer:DomSanitizer) { }

  html: SafeHtml;
  checkbooks: checkbooks[] = [];
  selectedCheckbook: checkbooks = new checkbooks;
  years: string[] = [new Date().getFullYear().toString()];
  periods: periods[] = [];
  selectedPeriodEmp: number;
  periodsByEmployee: periods[] = [];
  selectedYear: string = new Date().getFullYear().toString();
  selectedPeriod: periods = new periods;
  checks: checks[] = [];
  actualCheck: checks = new checks;
  paystubs: paystubview[] = [];
  selectedPaystub: paystubview = new paystubview;
  details: checksDetails[] = [];
  accounts: AccountingAccounts[] = [];
  employees: employees[] = [];
  actualEmployee: employees = new employees;
  credits: checkclass[] = [];
  debits: checkclass[] = [];
  totalPay: number = 0;
  totalCred: number = 0;
  creditsDebitsByEmployees: creditsdebitsbyemployees[] = [];
  selCredDebByEmp: creditsdebitsbyemployees = new creditsdebitsbyemployees;


  ngOnInit() {
    this.setPeriods();
    this.getCheckBooks();
    this.getAccounts();
    this.getemployees();
    this.periods.reverse();
  }

  getCheckBooks() {
    this.apiService.getCheckBooks().subscribe((chb: checkbooks[]) => {
      this.checkbooks = chb;
      this.selectedCheckbook = this.checkbooks[0];
      this.setCheckBook(this.checkbooks[0].account_bank);
    })
  }

  setCheckBook(sch: string) {
    this.checkbooks.forEach((chb: checkbooks) => {
      if (sch === chb.account_bank) {
        this.selectedCheckbook = chb;
        this.actualCheck = new checks;
        this.actualCheck.document = chb.next_correlative.toString();
        this.setCheck();
      }
    })
  }

  setPeriods() {
    let toPush: boolean = true;
    this.periods = [];
    this.years = [new Date().getFullYear().toString()];
    this.apiService.getPeriods().subscribe((per: periods[]) => {
      per.forEach(period => {
        toPush = true;
        this.years.forEach(yr => {
          if (yr == period.start.split("-")[0]) {
            toPush = false;
          }
        })
        if (toPush) {
          this.years.push(period.start.split("-")[0]);
        }
        this.selectedYear = this.years[0];

        if (this.selectedYear == period.start.split("-")[0]) {
          this.periods.push(period);
          this.selectedPeriod = this.periods[0];
          this.getPayments();
        }
      })
      this.years.sort();
      this.periods = this.periods.reverse();
      this.selectedPeriod = this.periods[0];
    });
  }

  setPSelection() {
    this.periods = [];
    this.apiService.getPeriods().subscribe((per: periods[]) => {
      per.forEach(period => {
        if (this.selectedYear == period.start.split("-")[0]) {
          this.periods.push(period);
        }
      })
      this.years.sort();
      this.periods = this.periods.reverse();
      this.setSelection(this.periods[0]);
    })
  }

  setSelection(period: periods) {
    this.selectedPeriod = period;
    this.selectedPeriodEmp = Number(this.selectedPeriod.idperiods);
    this.getPayments();
  }

  setAccount(adetail: checksDetails, acc: AccountingAccounts) {
    this.details.forEach((detail: checksDetails) => {
      if (detail.id_detail == adetail.id_detail) {
        detail.name = acc.name;
        detail.id_account = acc.external_id;
      }
    })
  }

  getPayments() {
    this.apiService.getPaystubDetails(this.selectedPeriod).subscribe((pst_view: paystubview[]) => {
      pst_view.forEach(pst=>{
        if(!isNullOrUndefined(pst.idpaystub_deatils)){
          pst.select = true;
          pst.ignore = true;
        }else{
          pst.select = false;
          pst.ignore = false;
        }
      })
      this.paystubs = pst_view.filter(pysf => pysf.type === 'BANK CHECK');
      this.getInfoEmployees();
    })
  }

  setpaystubSelected(pst_view:paystubview){
    this.selectedPaystub = pst_view;
    this.creditsDebitsByEmployees.forEach(credDebByEmp => {
      if ((credDebByEmp.client_id == this.selectedPaystub.client_id) && (credDebByEmp.idpayments == this.selectedPaystub.idpayments)) {
        this.getTotalCred(credDebByEmp);
        this.setCredDebByEmp(credDebByEmp);
      }
    })
    this.html = this._sanitizer.bypassSecurityTrustHtml(this.selectedPaystub.content);
  }

  setStatus(pst_view:paystubview){
    this.paystubs[this.paystubs.indexOf(pst_view)].select = !this.paystubs[this.paystubs.indexOf(pst_view)].select;
    console.log(this.paystubs[this.paystubs.indexOf(pst_view)]);
  }

  setStatusCBEmp(Emp: creditsdebitsbyemployees) {
    this.creditsDebitsByEmployees[this.creditsDebitsByEmployees.indexOf(Emp)].checked = !this.creditsDebitsByEmployees[this.creditsDebitsByEmployees.indexOf(Emp)].checked;
  }

  setCheck() {
    let fecha: Fecha = new Fecha;
    this.actualCheck.place = "Guatemala";
    this.actualCheck.date = fecha.getToday();
    this.actualCheck.value = '0.00';
    this.actualCheck.name = '';
    this.actualCheck.description = '';
    this.actualCheck.negotiable = 'NO NEGOCIABLE';
    this.actualCheck.checked = false;
    this.actualCheck.nearsol_id = '';
    this.actualCheck.client_id = '';
    this.actualCheck.id_account = '';
    this.actualCheck.document = this.selectedCheckbook.next_correlative.toString(); // check number
    this.actualCheck.bankAccount = this.selectedCheckbook.account_bank;
    this.actualCheck.printDetail = false;
  }

  checkAll() {
    this.paystubs.forEach(pay => {
      pay.select = true;
    })
  }

  unCheckAll() {
    this.paystubs.forEach(pay => {
      pay.select = false;
    })
  }

  changeVal(val) {
    this.actualCheck.checked = val.target.checked;
    if(val.target.checked == true){
      this.actualCheck.negotiable = 'NEGOCIABLE';
    }else{
      this.actualCheck.negotiable = 'NO NEGOCIABLE';
    }
  }


  checkCredit(val) {
    //this.credits.checked = val.target.checked;
    /*if(val.target.checked == true){
      this.actualCheck.negotiable = 'NEGOCIABLE';
    }else{
      this.actualCheck.negotiable = 'NO NEGOCIABLE';
    }*/
  }

  onChangeValue(event: string) {
    this.actualCheck.value = parseFloat(event).toFixed(2);
  }

  addDetail() {
    let detail: checksDetails = new checksDetails;
    detail.debits = '0.00';
    detail.credits = '0.00';
    detail.id_detail = String(this.details.length + 1);
    detail.checked = false;
    this.details.push(detail);
  }

  deleteDetail(val: boolean) {
    let i: number = 0;
    if (val === false) {
      this.details.forEach((detail: checksDetails) => {
        if (detail.checked) {
          this.details.splice(this.details.indexOf(detail), 1);
          this.deleteDetail(true);
        } else {
          val = false;
        }
      });

      this.details.forEach((detail: checksDetails) => {
        i++;
        detail.id_detail = i.toString();
      });
      }
  }

  selectedDetail(detail) {
    //this.details.checked = event.target.checked;
  }

  getAccounts() {
    this.apiService.getAccounting_Accounts().subscribe((acc) => {
      this.accounts = acc;
    })
  }

  getemployees() {
    this.apiService.getallEmployees({ department: 'NoLimitAC' }).subscribe((emp: employees[]) => {
      emp.forEach(empl => {
        if (empl.nearsol_id != null && empl.nearsol_id.trim() != '') {
          this.employees.push(empl);
        }
      })
    })
  }

  getFilteredEmployees(ANearsol_Id: string) {
    this.employees.forEach((emp) => {
      if (!isNullOrUndefined(emp.nearsol_id)) {
        if (emp.nearsol_id.toUpperCase() == ANearsol_Id.toUpperCase()) {
          this.actualEmployee = emp;
          this.actualCheck.name = this.actualEmployee.name;
          this.actualCheck.nearsol_id = this.actualEmployee.nearsol_id;
          this.actualCheck.client_id = this.actualEmployee.client_id;
          this.apiService.getPeriodsByEmployee({ id: this.actualEmployee.idemployees }).subscribe(periods => {
            this.periodsByEmployee = periods;
          })
        }
      }
    })
  }

  getCredits() {
    this.credits = [];
    this.apiService.getCredits({ id: this.actualEmployee.idemployees, period: this.selectedPeriodEmp }).subscribe((cred: credits[]) => {
      cred.forEach((credit) => {
        if ((Number(credit.amount) != 0) && (credit.status == 'PENDING')) {
          let obj: checkclass = new checkclass(true, credit);
          this.credits.push(obj);
        }
      })
      this.getTotal();
    })
  }

  getDebits() {
    this.debits = [];
    this.apiService.getDebits({ id: this.actualEmployee.idemployees, period: this.selectedPeriodEmp }).subscribe((deb: debits[]) => {
      deb.forEach((debit: debits) => {
        if ((Number(debit.amount) != 0) && (debit.status == 'PENDING')) {
          let obj: checkclass = new checkclass(true, debit);
          this.debits.push(obj);
        }
      })
      this.getTotal();
    })
  }

  searchCreditsDebits() {
    this.getCredits();
    this.getDebits();
  }

  getTotal() {
    this.totalPay = 0;
    this.credits.forEach((credit) => {
      if (credit.checked) {
        this.totalPay = this.totalPay + Number(credit.object.amount);
      }
    })

     this.debits.forEach((debit) => {
      if (debit.checked) {
        this.totalPay = this.totalPay - Number(debit.object.amount);
      }
     })

    if (this.totalPay < 0) {
      this.totalPay = 0;
    }
  }

  addCreditsDebits() {
    this.details = [];
    this.credits.forEach((credit) => {
      if (credit.checked) {
        let detail: checksDetails = new checksDetails;
        detail.id_detail = String(this.details.length + 1);
        detail.debits = '0.00';
        detail.credits = credit.object.amount;
        detail.checked = false;
        detail.id_movement = credit.object.iddebits;
        detail.movement = credit.object.type;
        this.details.push(detail);
      }
    });

    this.debits.forEach((debit) => {
      if (debit.checked) {
        let detail: checksDetails = new checksDetails;
        detail.id_detail = String(this.details.length + 1);
        detail.debits = debit.object.amount;
        detail.credits = '0.00';
        detail.checked = false;
        detail.id_movement = debit.object.iddebits;
        detail.movement = debit.object.type;
        this.details.push(detail);
      }
    });
    this.actualCheck.value = this.totalPay.toFixed(2);
  }

  addCreditsDebitsByEmp() {
    //this.selectedPaystub.liquido = this.selCredDebByEmp.total.toString();
  }

  saveCheck() {
    this.apiService.saveCheck({ head: this.actualCheck, detail: this.details }).subscribe((str: string) => {
      if (str.split("|")[0].trim() == "Success") {
        this.actualCheck.idchecks = str.split("|")[1];
        this.getCheck();
      }
    })
  }

  getCheck() {
    //console.log(this.actualCheck);
    this.apiService.getCheck({ account: this.selectedCheckbook.account_bank, document: this.actualCheck.document }).subscribe((chb: checks) => {
      this.actualCheck = chb;
      console.log(this.actualCheck);
      this.apiService.getCheckDetails(this.actualCheck).subscribe((det: checksDetails[]) => {
        this.details = det;
      })
    })
  }

  printChecks() {
    this.apiService.printChecks(this.actualCheck).subscribe((_str: string) => {
      // no hace nada.
     })
  }

  getInfoEmployees() {
    this.paystubs.forEach((paystub) => {
      let credDebByEmp: creditsdebitsbyemployees = new creditsdebitsbyemployees;
      this.employees.forEach((employee) => {
        if (employee.nearsol_id == paystub.nearsol_id) {
          credDebByEmp.idemployees = employee.idemployees;
          credDebByEmp.nearsol_id = paystub.nearsol_id;
          credDebByEmp.client_id = paystub.client_id;
          credDebByEmp.idpayments = paystub.idpayments;
          credDebByEmp.name = paystub.employee_name;
          credDebByEmp.account = paystub.account;
          credDebByEmp.check = '';
          credDebByEmp.checked = false;

          // credits
          credDebByEmp.credits = [];
          this.apiService.getCredits({ id: employee.idemployees, period: this.selectedPeriodEmp }).subscribe((cred: credits[]) => {
            cred.forEach((credit) => {
              if ((Number(credit.amount) != 0) && (credit.status == 'PENDING')) {
                let obj: checkclass = new checkclass(true, credit);
                credDebByEmp.credits.push(obj);
              }
            })
            this.getTotalCred(credDebByEmp);
          })

          // debits
          credDebByEmp.debits = [];
          this.apiService.getDebits({ id: employee.idemployees, period: this.selectedPeriodEmp }).subscribe((deb: debits[]) => {
            deb.forEach((debit: debits) => {
              if ((Number(debit.amount) != 0) && (debit.status == 'PENDING')) {
                let obj: checkclass = new checkclass(true, debit);
                credDebByEmp.debits.push(obj);
              }
            })
            this.getTotalCred(credDebByEmp);
          })
          this.getTotalCred(credDebByEmp);
          //if (credDebByEmp.total>0.00) {
            this.creditsDebitsByEmployees.push(credDebByEmp);
          //}
        } // end if
      }) // end foreach employees
    }) //end foreach paystubs
  };

  setCredDebByEmp(Avalue) {
    this.selCredDebByEmp = Avalue;
  }

  getTotalCred(AcredDeb: creditsdebitsbyemployees) {
    this.totalCred = 0;
    if (isNullOrUndefined(AcredDeb)) {
      this.selCredDebByEmp = this.selCredDebByEmp;
    } else {
      this.selCredDebByEmp = AcredDeb;
    }

    this.selCredDebByEmp.credits.forEach((credit) => {
      if (credit.checked) {
        this.totalCred = this.totalCred + Number(credit.object.amount);
      }
    })

     this.selCredDebByEmp.debits.forEach((debit) => {
      if (debit.checked) {
        this.totalCred = this.totalCred - Number(debit.object.amount);
      }
     })

    if (this.totalCred < 0) {
      this.totalCred = 0;
    }
    this.selCredDebByEmp.total = this.totalCred;
  }

  saveChecksByPeriod() {
    let fecha: Fecha = new Fecha;
    let details: checksDetails[] = [];
    let next_correlative: number = this.selectedCheckbook.next_correlative;
    this.checks = [];
    this.creditsDebitsByEmployees.forEach(cbEmp => {
      if (cbEmp.checked) {
        let check: checks = new checks;
        let i = 0;
        check.place = "Guatemala";
        check.date = fecha.getToday();
        check.value = cbEmp.total.toFixed(2);
        check.name = cbEmp.name.toUpperCase();
        check.description = 'PAGO DE PRESTACIONES LABORALES DEL ' + this.selectedPeriod.start + ' AL ' + this.selectedPeriod.end;
        check.negotiable = 'NO NEGOCIABLE';
        check.checked = cbEmp.checked;
        check.nearsol_id = cbEmp.nearsol_id;
        check.client_id = cbEmp.client_id;
        check.id_account = cbEmp.account;
        check.payment = cbEmp.idpayments;
        check.document = String(next_correlative); // check number
        check.bankAccount = this.selectedCheckbook.account_bank;
        check.printDetail = false;
        this.creditsDebitsByEmployees.forEach(d => {

          if (d.idpayments == check.payment) {
            d.credits.forEach(cred => {
              if (cred.checked == true) {
                let detail: checksDetails = new checksDetails;
                i++;
                detail.id_detail = String(i);
                detail.id_movement = cred.object.iddebits;
                detail.movement = cred.object.type;
                detail.debits = '0';
                detail.credits = cred.object.amount;
                detail.checked = true;
                details.push(detail);
              }
            })

            d.debits.forEach(deb => {
              if (deb.checked == true) {
                let detail: checksDetails = new checksDetails;
                i++;
                detail.id_detail = String(i);
                detail.id_movement = deb.object.iddebits;
                detail.movement = deb.object.type;
                detail.debits = deb.object.amount;
                detail.credits = '0';
                detail.checked = true;
                details.push(detail);
              }
            })
          }
        })
        console.log(details);
        this.apiService.saveCheck({ head: check, detail: details }).subscribe((str: string) => {
          if (str.split("|")[0].trim() == "Success") {
            check.idchecks = str.split("|")[1];
            this.checks.push(check);
            this.apiService.printChecks(check).subscribe((str: string) => {
              console.log(str);
            });
            cbEmp.check = ' Check No.: ' + check.document + ' Account: ' + check.bankAccount;
          } else {
            if (str.split("|")[0].trim() == "Error:") {
              window.confirm(str.split("|")[1] + "\n" + str.split("|")[2] + "\n" + str.split("|")[3]);
              console.log(str.split("|")[2]);
            }
          }
        })
        next_correlative++;
      } // fin IF
    }) // fin foreach
  }

  printChecksByPeriod() {
    this.checks.forEach((check) => {
      this.apiService.printChecks(check).subscribe((_str: string) => {
      // no hace nada.
      });
    })

  }

}
