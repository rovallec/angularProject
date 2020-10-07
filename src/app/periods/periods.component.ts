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

  constructor(public apiService:ApiService, public route:ActivatedRoute) { }

  ngOnInit() {
    this.getDeductions();
    this.apiService.getFilteredPeriods({id:this.route.snapshot.paramMap.get('id')}).subscribe((p:periods)=>{
      this.period = p;
    });
  }

  getDeductions(){
    this.apiService.getDeductions({id:this.route.snapshot.paramMap.get('id')}).subscribe((de:deductions[])=>{
      this.deductions = de;
    })
  }

  setReg(de:deductions){
    this.apiService.getAttendences({id:de.idemployees,date:"BETWEEN '"  + this.period.start + "' AND '" + this.period.end + "'"}).subscribe((att:attendences[])=>{
      this.attendances = att;
      console.log(this.attendances);
      this.selectedEmployee = true;
    })
  }

}
