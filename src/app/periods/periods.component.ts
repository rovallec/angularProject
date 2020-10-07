import { NumberFormatStyle } from '@angular/common';
import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ApiService } from '../api.service';
import { attendences, credits, debits, deductions, periods } from '../process_templates';

@Component({
  selector: 'app-periods',
  templateUrl: './periods.component.html',
  styleUrls: ['./periods.component.css']
})


export class PeriodsComponent implements OnInit {

  selectedEmployee:boolean = false;
  attendances:attendences[] = [];
  deductions:deductions[] = [];
  debits:debits[] = [];
  credits:credits[] = [];
  period:periods = new periods;
  daysOff:number = 0;
  roster:number = 0;
  attended:number = 0;
  diff:number = 0;
  totalDebits:number = 0;
  totalCredits:number = 0;

  constructor(public apiService:ApiService, public route:ActivatedRoute) { }

  ngOnInit() {
    this.getDeductions();
    this.apiService.getFilteredPeriods({id:this.route.snapshot.paramMap.get('id')}).subscribe((p:periods)=>{
      this.period = p;
    });
    this.daysOff = 0;
    this.roster = 0;
    this.attended = 0;
    this.diff= 0;
    this.totalDebits = 0;
    this.totalCredits = 0;
  }

  getDeductions(){
    this.apiService.getDeductions({id:this.route.snapshot.paramMap.get('id')}).subscribe((de:deductions[])=>{
      this.deductions = de;
    })
  }

  setReg(de:deductions){
    this.apiService.getAttendences({id:de.idprofiles,date:"BETWEEN '"  + this.period.start + "' AND '" + this.period.end + "'"}).subscribe((att:attendences[])=>{
      this.attendances = att;
      this.attendances.forEach(element => {
        if(element.scheduled == 'OFF'){
          element.balance = '0';
          this.roster = this.roster + 8;
          this.attended = this.attended + 8;
          this.daysOff = this.daysOff + 1;
        }else{
          element.balance = ((parseFloat(element.worked_time) - parseFloat(element.scheduled))).toString()
          this.attended = this.attended + parseFloat(element.worked_time);
          this.roster = this.roster + parseFloat(element.scheduled);
          this.diff = this.diff + parseFloat(element.balance)
        }
      });

      this.apiService.getDebits({id:de.idemployees, period:this.period.idperiods}).subscribe((db:debits[])=>{
        this.debits = db;
        this.debits.forEach(element => {
          this.totalDebits = this.totalDebits + parseFloat(element.amount)
        });
      });

      this.apiService.getCredits({id:de.idemployees, period:this.period.idperiods}).subscribe((cd:credits[])=>{
        this.credits = cd;
        this.credits.forEach(el => {
          this.totalCredits = this.totalCredits + parseFloat(el.amount);
        })
      })
      this.selectedEmployee = true;
    })
  }

}
