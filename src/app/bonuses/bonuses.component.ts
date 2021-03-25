import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { advances, advances_acc, credits, employees_Bonuses, payments, periods } from "../process_templates";
import { employees, hrProcess, payment_methods } from '../fullProcess';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';

@Component({
  selector: 'app-bonuses',
  templateUrl: './bonuses.component.html',
  styleUrls: ['./bonuses.component.css']
})
export class BonusesComponent implements OnInit {  
  period: periods[] = [];
  actPeriod: periods = new periods;
  employees: employees[] = [];
  employees_bonuses: employees_Bonuses[] = [];
  filter: string = null;
  filterBonuses: string = '1';
  value: string = null;
  searching: boolean = false;

  constructor(private apiService: ApiService, public router: Router, private authSrv: AuthServiceService) { }

  ngOnInit() {
    this.getAllBonuses();
  }

  getAllBonuses() {
    this.apiService.getAllBonuses({ filter: this.filter, value: this.value, department: 'bonuses', filterBonuses: this.filterBonuses }).subscribe((emp: employees_Bonuses[]) => {
      this.employees_bonuses = emp;
    })
  }

  searchType() {
    if (this.searching) {
      this.searchEmployee();
    } else {
      this.getAllBonuses();
    }
  }

  cancelSearch() {
    this.getAllBonuses();
    this.searching = false;
  }

  searchEmployee() {
    this.searching = true;
    this.apiService.getAllBonuses({ filter: this.filter, value: this.value, department: 'employee', filterBonuses: this.filterBonuses }).subscribe((emp: employees_Bonuses[]) => {
      this.employees_bonuses = emp;
    })
  }

  gotoProfile(emp: employees) {
    this.router.navigate(['./accProfile', emp.idemployees]);
  }

  closePeriod() {
    let empl: employees = new employees;
    let pay: payment_methods[] = [];
    let cre: credits = new credits;
    let hrp: hrProcess = new hrProcess;
    let emp_bonuses: employees_Bonuses[] = [];

    let py: payments = new payments;
    let i = 0;
    this.apiService.getAllPeriods().subscribe((peri: periods[]) => {
      this.period = peri.filter(p => p.type_period == this.filterBonuses && p.status == '1');
      this.actPeriod = this.period[0];
      this.apiService.getAllBonuses({ filter: this.filter, value: this.value, department: 'all', filterBonuses: this.filterBonuses }).subscribe((empb: employees_Bonuses[]) => {
        emp_bonuses = empb;
        this.apiService.closePeriodBonuses(this.actPeriod).subscribe((str: string) => {
          // periodo cerrado y creado el nuevo.
          emp_bonuses.forEach(emp => {
            py.id_employee = emp.idemployees;
            this.apiService.getEmployeeId({id: emp.idemployees}).subscribe((employee: employees) => {
              empl = employee;
              this.apiService.getPaymentMethods(empl).subscribe((pay_me: payment_methods[])=> {
                pay = pay_me.filter(pm => pm.predeterm = '1');
                py = new payments;

                if (pay.length > 0) {
                  py.id_paymentmethod = pay[0].idpayment_methods;
                } else {
                  py.id_paymentmethod = '1';
                }
                
                py.id_period = this.actPeriod.idperiods;
                py.nearsol_id = emp.nearsol_id;

                cre.id_employee = emp.idemployees;
                cre.idpayments = py.idpayments;
                if (this.filterBonuses=='1') {
                  cre.type = 'Bono 14';
                } else {
                  cre.type = 'Aguinaldo';
                }
                cre.status = 'PENDING';
                cre.amount = emp.total;
                this.apiService.getAdvancesAcc(empl).subscribe((adv: advances_acc[]) => {
                  let adva: advances_acc[] = adv.filter(ad => ad.status=='PENDING');
                  if (adva.length>0) {
                    adva.forEach(a => {
                      a.status = 'PAID';
                      hrp.idhr_process = a.idhr_processes;
                      hrp.status = a.status;
                      hrp.notes = a.notes;
                      cre.amount = (Number(cre.amount) - Number(a.amount)).toString();

                      this.apiService.insertPayments(py).subscribe((_str: string) => {
                        this.apiService.insertCredits(cre).subscribe((_str: string) => {
                          // Pagos ingresados.
                          this.apiService.updatehr_process(hrp).subscribe((_str: string) => {
                            // proceso actualizado.
                          })                      
                        })
                      })
                    })
                  } else {
                    this.apiService.insertPayments(py).subscribe((_str: string) => {
                      this.apiService.insertCredits(cre).subscribe((_str: string) => {
                        // Pagos ingresados.
                      })
                    })
                  }
                })
              })
            })
          })
          if (String(str).split('|')[0]=='Info:') {
            window.alert(String(str).split('|')[1]);
          } else {
            window.alert( String(str).split('|')[0] + '\n' +
                          String(str).split('|')[1] + '\n' +
                          String(str).split('|')[2] + '\n' +
                          String(str).split('|')[3]);
          }
        })
      })
    })
  }
}
