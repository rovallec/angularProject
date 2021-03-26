import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { Router } from '@angular/router';
import { advances, advances_acc, credits, employees_Bonuses, payments, periods } from "../process_templates";
import { employees, hrProcess, payment_methods } from '../fullProcess';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { isNullOrUndefined } from 'util';

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
  loading: boolean = false;
  step: string = 'Creando cargos...';
  max: number = 0;
  progress: number = 0;

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
    this.loading = true;
    try {
      this.apiService.getAllPeriods().subscribe((peri: periods[]) => {
        this.period = peri.filter(p => p.type_period == this.filterBonuses && p.status == '1');
        this.actPeriod = this.period[0];
        this.apiService.getAllBonuses({ filter: this.filter, value: this.value, department: 'all', filterBonuses: this.filterBonuses }).subscribe((empb: employees_Bonuses[]) => {
          emp_bonuses = empb;
          this.max = emp_bonuses.length;
          this.progress = 0;

          emp_bonuses.forEach(emp => {
            this.progress = this.progress + 1;
            py.id_employee = emp.idemployees;
            this.apiService.getEmployeeId({ id: emp.idemployees }).subscribe((employee: employees) => {
              empl = employee;
              if ((isNullOrUndefined(empl)==false) && (isNullOrUndefined(empl.idemployees)==false)) {
                this.apiService.getPaymentMethods(empl).subscribe((pay_me: payment_methods[]) => {
                  pay = pay_me.filter(pm => pm.predeterm = '1');
                  this.apiService.getAdvancesAcc({idemployees: emp.idemployees}).subscribe((adv: advances_acc[]) => {
                    let adva: advances_acc[] = adv.filter(ad => ad.status == 'PENDING');
                    py = new payments;

                    if (pay.length > 0) {
                      py.id_paymentmethod = pay[0].idpayment_methods;
                    } else {
                      py.id_paymentmethod = '1';
                    }
    
                    py.id_period = this.actPeriod.idperiods;
                    py.nearsol_id = emp.nearsol_id;
                    py.id_employee = emp.idemployees;
                    py.credits = emp.total;
                    cre.id_employee = emp.idemployees;
                    cre.idpayments = py.idpayments;
                    if (this.filterBonuses == '1') {
                      cre.type = 'Bono 14';
                    } else {
                      cre.type = 'Aguinaldo';
                    }
                    cre.status = 'PENDING';
                    cre.amount = emp.total;
                    py.credits = cre.amount;
                    if (adva.length > 0) {
                      adva.forEach(a => {
                        a.status = 'PAID';
                        hrp.idhr_process = a.idhr_processes;
                        hrp.status = a.status;
                        hrp.notes = a.notes;
                        cre.amount = (Number(cre.amount) - Number(a.amount)).toString();
                        py.credits = cre.amount;
                        this.apiService.insertPayments(py).subscribe((str: string) => {
                          py.idpayments = str;
                          cre.idpayments = py.idpayments;
                          this.apiService.insertCredits(cre).subscribe((_str: string) => {
                            // Pagos ingresados.
                            this.apiService.updatehr_process(hrp).subscribe((_str: string) => {
                              // proceso actualizado.
                            })
                          })
                        })
                      })
                    } else {
                      this.apiService.insertPayments(py).subscribe((str: string) => {
                        py.idpayments = str;
                        cre.idpayments = py.idpayments;
                        this.apiService.insertCredits(cre).subscribe((_str: string) => {
                          // Pagos ingresados.
                        })
                      })
                    }
                  })
                })
              }
            })
            if (this.progress >= emp_bonuses.length) {
              this.apiService.closePeriodBonuses(this.actPeriod).subscribe((str: string) => {
                if (String(str).split('|')[0] == 'Info:') {
                  console.log("Proceso finalizado correctamente.");
                  window.alert(String(str).split('|')[1]);
                } else {
                  console.log("No fue posible cerrar el período.");
                  window.alert(String(str).split('|')[0] + '\n' +
                    String(str).split('|')[1] + '\n' +
                    String(str).split('|')[2] + '\n' +
                    String(str).split('|')[3]);
                }
              })
            }
          })
        })
      })
    } finally {
      this.loading = false;
    }
  }
}
