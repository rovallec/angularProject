import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { ApiService } from '../api.service';
import { attendences, credits, debits, deductions } from '../process_templates';

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
  credits:credits[] = []

  constructor(public apiService:ApiService, public route:ActivatedRoute) { }

  ngOnInit() {
  }

  getDeductions(){
    this.apiService.getDeductions({id:this.route.snapshot.paramMap.get('id')}).subscribe((de:deductions[])=>{
      this.deductions = de;
    })
  }

  setReg(id:string){
    this.selectedEmployee = true;
  }

}
