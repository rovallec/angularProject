import { splitAtColon } from '@angular/compiler/src/util';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, RouteConfigLoadEnd } from '@angular/router';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees, payment_methods } from '../fullProcess';
import { AuthGuard } from '../guard/auth-guard.service';
import { PeriodsComponent } from '../periods/periods.component';
import { process } from '../process';
import { attendences, attendences_adjustment, credits, debits, disciplinary_processes, judicials, leaves, ot_manage, payments, periods, services, terminations, vacations } from '../process_templates';
import { profiles } from '../profiles';

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
  cred_benefits:credits[] = [];
  vacations:vacations[] = [];
  active_payment: payments = new payments;
  newProc:boolean = false;
  insertN:string = null;
  insertNew:boolean = false;
  activeCred:credits = new credits;
  totalPayment:string = null;
  record:boolean = false;
  newPaymentMethod:boolean  = false;
  recordPaymentMethod:boolean = false;
  non_show_2:boolean = false;
  seventh:number = 0;
  absence:number = 0;
  diff:number = 0;
  attended:number = 0;
  roster:number = 0;
  daysOff:number = 0;
  totalCredits:number = 0;
  totalDebits:number = 0;
  absence_fixed:string = null;
  period:periods = new periods;
  term_date:string = null;
  leaves:leaves[] = [];
  attendances:attendences[] = [];
  total:number;

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
        this.getTerm();
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

  getTerm(){
    let end_date:string = null;
    let difference:number = null;
    let a_date:string = null;
    let b_date:string = null;
    let v_amount:number = 0;

    let cred_indemnization:credits = new credits;
    let cred_aguinaldo:credits = new credits;
    let cred_bono14:credits = new credits;
    let cred_vacations:credits = new credits;
    let cred_pendings:credits = new credits;

    this.apiService.getProcRecorded({id:this.employee.idemployees}).subscribe((pr:process[])=>{
      pr.forEach(proc=>{
        if(proc.name === "Termination"){
          end_date = proc.prc_date;
          difference = (((new Date(proc.prc_date).getFullYear()) - (new Date(this.employee.hiring_date).getFullYear())) * 12) + ((new Date(proc.prc_date).getMonth()) - (new Date(this.employee.hiring_date).getMonth()) + 1);
        }
      })
      cred_indemnization.type = "Indemnizacion Periodo del " + this.employee.hiring_date + " al " + end_date;
      cred_indemnization.amount = (((((Number(this.employee.base_payment) + Number(this.employee.productivity_payment)) /12)*14)/365)*((new Date(end_date).getTime() - new Date(this.employee.hiring_date).getTime())/(1000*3600*24))).toFixed(2);
      this.cred_benefits.push(cred_indemnization);

      if((new Date(this.employee.hiring_date).getTime() - (new Date((Number(end_date.split("-")[0])-1).toString() + "-12-01").getTime()) >= 0)){
        a_date = this.employee.hiring_date;
      }else{
        a_date = (new Date(end_date).getFullYear() - 1) + '-12-01'
      }
      cred_aguinaldo .type = "Aguinaldo Periodo del " + a_date + " al " + end_date;
      cred_aguinaldo.amount = (((Number(this.employee.base_payment) + Number(this.employee.productivity_payment))/365)*(((new Date(end_date).getTime() - (new Date(a_date).getTime())))/(1000*3600*24))).toFixed(2);
      this.cred_benefits.push(cred_aguinaldo);

      if((new Date(this.employee.hiring_date).getTime() - (new Date((Number(end_date.split("-")[0])).toString() + "-07-01").getTime()) >= 0)){
        b_date = this.employee.hiring_date;
      }else{
        b_date = (new Date(end_date).getFullYear()) + "-07-01";
      }
      cred_bono14.type = "Bono 14 Periodo del " + b_date + " al " + end_date;
      cred_bono14.amount = (((Number(this.employee.base_payment) + Number(this.employee.productivity_payment))/365)*((new Date(end_date).getTime() - new Date(b_date).getTime())/(1000*3600*24))).toFixed(2);
      this.cred_benefits.push(cred_bono14);

      this.apiService.getVacations({id:this.employee.id_profile}).subscribe((vacs:vacations[])=>{
        vacs.forEach(vacation=>{
          v_amount = v_amount + (1 * Number(vacation.count));
        })
        cred_vacations.type = "Vacaciones Periodo del " + this.employee.hiring_date + " al " + end_date + " habiendo gozado: " + v_amount;
        cred_vacations.amount = (((Number(this.employee.base_payment) + Number(this.employee.productivity_payment))/30)*(((new Date(end_date).getTime() - new Date(this.employee.hiring_date).getTime())/(1000*3600*24))/(1/(15/365)) - v_amount)).toFixed(2);
        this.cred_benefits.push(cred_vacations);
      })

      this.setPayTime(this.employee.idemployees, this.employee.id_profile);

      cred_pendings.type = "Sueldos y Salarios Pendientes de Pago";
      cred_pendings.amount = this.total.toFixed(2);
      this.cred_benefits.push(cred_pendings);
    })
  }

  completePayment(){
    this.employee.state = "PAID";
    this.employee.platform = "NONE";
    this.apiService.updateEmployee(this.employee).subscribe((str:string)=>{});
  }

  setPayTime(id_employee: string, id_profile: string) {
    let date: Date = new Date(this.term_date);
    let start: string = null;
    let end: string = null;

    if(date.getDate() > 15) {
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '16';
      end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + (new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate().toString());
    }else{
      start = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + '01';
      end = end = date.getFullYear().toString() + "-" + (date.getMonth() + 1).toString() + "-" + "15";
    }

    this.vacations = [];
    let totalCred: number = 0;
    let totalDeb: number = 0;
    let discounted: number = 0;
    let nonShowCount: number = 0;
    let activeVac: boolean = false;
    let activeLeav: boolean = false;
    let activeDp: boolean = false;
    let janp_sequence: number = 0;
    let last_seventh:boolean = true;

    let non_show1: boolean = false;
    let non_show2: boolean = false;
    this.non_show_2 = true;

    this.seventh = 0;
    this.absence = 0;
    this.diff = 0;
    this.attended = 0;
    this.roster = 0;
    this.daysOff = 0;

    this.credits = [];
    this.debits = [];

    this.period.start = start;
    this.period.end = end;
    this.period.status = '1';

    this.apiService.getSearchEmployees({ dp: 'all', filter: 'idemployees', value: id_employee }).subscribe((emp: employees[]) => {
      this.apiService.getVacations({ id: emp[0].id_profile }).subscribe((vac: vacations[]) => {
        this.apiService.getLeaves({ id: emp[0].id_profile }).subscribe((leave: leaves[]) => {
          this.apiService.getDPAtt({ id: emp[0].idemployees, date_1: this.period.start, date_2: this.period.end }).subscribe((dp: disciplinary_processes[]) => {
            this.apiService.getAttPeriod({ id: emp[0].idemployees, date_1: this.period.start, date_2: this.period.end }).subscribe((att: attendences[]) => {
              this.apiService.getAttAdjustments({ id: emp[0].idemployees }).subscribe((ad: attendences_adjustment[]) => {
                this.apiService.getCredits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((cd: credits[]) => {
                  this.apiService.getDebits({ id: emp[0].idemployees, period: this.period.idperiods }).subscribe((db: debits[]) => {
                    this.apiService.getJudicialDiscounts({ id: emp[0].idemployees }).subscribe((judicials: judicials[]) => {
                      this.apiService.getServicesDiscounts({ id: emp[0].idemployees, date: this.period.start }).subscribe((services: services[]) => {
                        let py:payments = new payments;
                        py.id_employee = emp[0].idemployees;
                        py.id_period = this.period.idperiods;
                        this.apiService.getLastSeventh(py).subscribe((pyment:payments)=>{
                          if(pyment.last_seventh == '1'){
                            last_seventh = false;
                          }
                        })
                        vac.forEach(vacc => {
                          if (vacc.status === 'PENDING') {
                            this.vacations.push(vacc);
                          }
                        })

                        this.leaves = leave;
                        non_show1 = false;
                        non_show2 = false;

                        if (att.length != 0) {
                          att.forEach(attendance => {

                            let dt: Date = new Date(attendance.date);

                            if (dt.getDay() === 0) {
                              this.non_show_2 = true;
                              if (nonShowCount == 5) {
                                discounted = discounted - 8;
                                this.absence = this.absence - 8;
                              }

                              if (janp_sequence == 5) {
                                discounted = discounted - 8;
                                this.absence = this.absence - 8;
                              }

                              janp_sequence = 0;
                              nonShowCount = 0;
                            }

                            activeDp = false;
                            activeVac = false;
                            activeLeav = false;

                            dp.forEach(disciplinary => {
                              if (disciplinary.day_1 == attendance.date || disciplinary.day_2 == attendance.date || disciplinary.day_3 == attendance.date || disciplinary.day_4 == attendance.date) {
                                this.absence = this.absence - 8;
                                discounted = discounted - 8;
                                attendance.balance = "SUSPENSION"
                                activeDp = true;
                              }
                            });

                            if (!activeDp) {
                              leave.forEach(leav => {
                                if ((new Date(leav.start)) <= (new Date(attendance.date)) && (new Date(leav.end)) >= (new Date(attendance.date))) {
                                  if (attendance.scheduled != 'OFF') {
                                    this.roster = this.roster + Number(attendance.scheduled);
                                  }
                                  activeLeav = true;
                                  if (leav.motive == 'Others Unpaid' || leav.motive == 'Leave of Absence Unpaid') {
                                    discounted = discounted - 8;
                                    this.absence = this.absence - 8;
                                    attendance.balance = 'JANP';
                                    if (attendance.scheduled != 'OFF') {
                                      janp_sequence = janp_sequence + 1;
                                    }
                                  } else {
                                    if (leav.motive == 'Maternity' || leav.motive == 'Others Paid') {
                                      this.attended = this.attended + 8;
                                      attendance.balance = 'JAP';
                                    }
                                  }
                                }
                              })
                            }

                            if (!activeDp && !activeLeav) {
                              vac.forEach(vacation => {
                                if (vacation.took_date == attendance.date) {
                                  if (attendance.scheduled != "OFF") {
                                    this.roster = this.roster + Number(attendance.scheduled);
                                    this.attended = this.attended + Number(attendance.scheduled);
                                    attendance.balance = 'VAC';
                                  } else {
                                    this.daysOff = this.daysOff + 1;
                                    attendance.balance = "OFF";
                                  }
                                  activeVac = true;
                                }
                              })
                            }

                            if (!activeLeav && !activeVac && !activeDp) {
                              if (attendance.scheduled == 'OFF') {
                                if (Number(attendance.worked_time) == 0) {
                                  this.daysOff = this.daysOff + 1;
                                  attendance.balance = "OFF";
                                } else {
                                  this.attended = this.attended + Number(attendance.worked_time);
                                  this.absence = this.absence + Number(attendance.worked_time);
                                  discounted = discounted + Number(attendance.worked_time);
                                }
                              } else {
                                this.roster = this.roster + Number(attendance.scheduled);
                                if (Number(attendance.worked_time) == 0) {
                                  if (this.non_show_2 && last_seventh) {
                                    this.absence = this.absence - 16;
                                    discounted = discounted - 16;
                                    this.seventh = this.seventh + 1;
                                    this.non_show_2 = false;
                                    attendance.balance = "NS";
                                    nonShowCount = nonShowCount + 1;
                                  } else {
                                    attendance.balance = "NS"
                                    this.absence = this.absence - 8;
                                    discounted = discounted - 8;
                                    nonShowCount = nonShowCount + 1;
                                  }
                                } else {
                                  this.attended = this.attended + Number(attendance.worked_time);
                                  this.absence = this.absence + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                  attendance.balance = (Number(attendance.worked_time) - Number(attendance.scheduled)).toFixed(2);
                                  discounted = discounted + (Number(attendance.worked_time) - Number(attendance.scheduled));
                                }
                              }
                            }
                          });

                          if (this.attended == 0) {
                            this.absence = (this.attendances.length * 8) * (-1);
                            discounted = (this.attendances.length * 8) * (-1);
                            this.seventh = 0;
                          }

                          this.attendances = att;

                          if (this.period.status == '1') {
                            let base_hour: number = Number(emp[0].base_payment) / 240;
                            let productivity_hour: number = (Number(emp[0].productivity_payment) - 250) / 240;
                            let base_credit: credits = new credits;
                            let productivity_credit: credits = new credits;
                            let decreto_credit: credits = new credits;
                            let ot_credit: credits = new credits;
                            let igss_debit: debits = new debits;

                            base_credit.type = "Salario Base";
                            productivity_credit.type = "Bonificacion Productividad";
                            decreto_credit.type = "Bonificacion Decreto";
                            igss_debit.type = "IGSS";

                            if (this.absence <= 0) {
                              base_credit.amount = (((att.length * 8) + (this.absence)) * base_hour).toFixed(2);
                              productivity_credit.amount = (((att.length * 8) + (this.absence)) * productivity_hour).toFixed(2);
                              ot_credit.amount = '0';
                              decreto_credit.amount = ((125 / 120) * ((att.length * 8) + (this.absence))).toFixed(2);
                            } else {
                              productivity_credit.amount = ((att.length * 8) * productivity_hour).toFixed(2);
                              base_credit.amount = ((att.length * 8) * base_hour).toFixed(2);
                              decreto_credit.amount = '125.00';
                              let ot: ot_manage = new ot_manage;
                              ot.id_period = this.period.idperiods;
                              ot.id_employee = emp[0].idemployees;
                              ot_credit.type = "Horas Extra Laboradas: " + this.absence;
                              if (emp[0].id_account != '13' && emp[0].id_account != '25' && emp[0].id_account != '23' && emp[0].id_account != '26' && emp[0].id_account != '12' && emp[0].id_account != '20') {
                                ot_credit.amount = ((base_hour + productivity_hour) * 2 * this.absence).toFixed(2);
                              } else {
                                ot_credit.amount = ((base_hour + productivity_hour) * 1.5 * this.absence).toFixed(2);
                              }
                              this.credits.push(ot_credit);
                            }
                            igss_debit.amount = ((Number(base_credit.amount) + Number(ot_credit.amount)) * 0.0483).toFixed(2);

                            if (base_credit.amount != 'NaN') {
                              this.credits.push(base_credit);
                              this.credits.push(productivity_credit);
                              this.credits.push(decreto_credit);
                              this.debits.push(igss_debit);
                            }

                            db.forEach(debit => {
                              totalDeb = totalDeb + Number(debit.amount);
                              this.debits.push(debit);
                            })
                            cd.forEach(credit => {
                              totalCred = totalCred + Number(credit.amount)
                              this.credits.push(credit);
                            });

                            totalCred = totalCred + Number(base_credit.amount) + Number(productivity_credit.amount) + Number(decreto_credit.amount) + Number(ot_credit.amount);
                            totalDeb = totalDeb + Number(igss_debit.amount);

                            this.apiService.getAutoAdjustments({ id: emp[0].idemployees, date: this.period.start }).subscribe((adjustments: attendences_adjustment[]) => {
                              adjustments.forEach(adjustment => {
                                let new_credit: credits = new credits;
                                new_credit.amount = (((Number(adjustment.time_after) - Number(adjustment.time_before)) * base_hour) + ((Number(adjustment.time_after) - Number(adjustment.time_before)) * productivity_hour)).toFixed(2);
                                new_credit.type = "Auto Ajuste " + adjustment.date;

                                this.credits.push(new_credit);
                                totalCred = totalCred + Number(new_credit.amount);
                              });

                              vac.forEach(vacat => {
                                if (new Date(vacat.took_date) < new Date(this.period.start) && vacat.status == 'PENDING') {
                                  let new_credit2: credits = new credits;
                                  new_credit2.amount = ((8 * base_hour) + (8 * productivity_hour)).toFixed(2);
                                  new_credit2.type = "Auto Ajuste Vacaciones " + vacat.took_date;

                                  this.credits.push(new_credit2);
                                  totalCred = totalCred + Number(new_credit2.amount);
                                }
                              })

                              services.forEach(service => {
                                if (service.status == '1') {
                                  let partial_service: debits = new debits;
                                  if (service.max == '0') {
                                    partial_service.amount = service.amount;
                                  } else {
                                    if ((Number(service.max) - (Number(service.current) + Number(service.amount))) > 0) {
                                      partial_service.amount = service.amount;
                                      service.current = (Number(service.current) + Number(service.amount)).toFixed(2);
                                    } else {
                                      partial_service.amount = (Number(service.max) - Number(service.current)).toFixed(2);
                                      service.current = service.max;
                                    }
                                  }

                                  partial_service.type = "Descuento Por Servicio " + service.name;
                                  this.debits.push(partial_service);
                                  totalDeb = totalDeb + Number(partial_service.amount);
                                }
                              })

                              judicials.forEach(judicial => {
                                if (judicial.max != judicial.current) {
                                  let partial_debit: debits = new debits;
                                  if (Number(judicial.max) - (((Number(judicial.amount) / 100) * (totalCred - totalDeb)) + Number(judicial.current)) > 0) {
                                    partial_debit.amount = ((Number(judicial.amount) / 100) * (totalCred - totalDeb)).toFixed(2);
                                    judicial.current = (Number(judicial.max) + ((Number(judicial.amount) / 100) * totalCred)).toFixed(2);
                                  } else {
                                    partial_debit.amount = (Number(judicial.max) - Number(judicial.current)).toFixed(2);
                                    judicial.current = judicial.max;
                                  }

                                  partial_debit.type = "Acuerdo Judicial";
                                  this.debits.push(partial_debit);
                                  totalDeb = totalDeb + Number(partial_debit.amount);
                                }
                              });



                              this.totalCredits = Number((totalCred).toFixed(2));
                              this.totalDebits = Number((totalDeb).toFixed(2));
                              this.absence_fixed = (this.absence).toFixed(2);
                              this.roster = Number((this.roster).toFixed(2));
                              this.attended = Number((this.attended).toFixed(2));
                              this.diff = Number((this.roster - this.attended).toFixed(2));
                            })
                          }
                        } else {
                          db.forEach(debit => {
                            totalDeb = totalDeb + Number(debit.amount);
                            this.debits.push(debit);
                          })
                          cd.forEach(credit => {
                            totalCred = totalCred + Number(credit.amount)
                            this.credits.push(credit);
                          });

                          this.totalCredits = Number((totalCred).toFixed(2));
                          this.totalDebits = Number((totalDeb).toFixed(2));
                          this.absence_fixed = (this.absence).toFixed(2);
                          this.roster = Number((this.roster).toFixed(2));
                          this.attended = Number((this.attended).toFixed(2));
                          this.diff = Number((this.roster - this.attended).toFixed(2));
                          this.total = this.totalCredits - this.totalDebits;
                        }
                      })
                    })
                  })
                })
              })
            })
          })
        })
      })
    })
  }
} 