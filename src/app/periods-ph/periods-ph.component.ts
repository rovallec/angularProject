import { Route } from '@angular/compiler/src/core';
import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { credits, debits, deductions, payments, periods } from '../process_templates';
import * as XLSX from 'xlsx';
import { employees } from '../fullProcess';

@Component({
  selector: 'app-periods-ph',
  templateUrl: './periods-ph.component.html',
  styleUrls: ['./periods-ph.component.css']
})
export class PeriodsPhComponent implements OnInit {

  constructor(public apiServices:ApiService, public route:ActivatedRoute) { }

  activePeriod:periods = new periods;
  allDebits:debits[] = [];
  allCredits:credits[] = [];
  show_debits:debits[] = [];
  show_credits:credits[] = [];
  actual_show:number = 0;
  showAll:boolean = false;
  searching:boolean = false;
  importType:string = "CREDIT";
  importDescription:string = "WIDGET";
  importCurrency:string = "$";
  showImport:boolean = false;

  file: any;
  arrayBuffer: any;
  filelist: any;
  correct:number = 0;
  incorrect:number = 0;
  importFail:debits[] = [];
  show_deductions:any[] = [];

  ngOnInit() {
    this.start();
  }

  showDebits(){
    let min = this.actual_show;
    let max = this.actual_show + 20;
    this.show_debits = [];
    for (let i = min; i < max; i++) {
      if(this.allDebits.length >= i){
        this.show_debits.push(this.allDebits[i]);
      }
    }
    this.show_deductions = this.show_debits;
  }

  showCredits(){
    let min = this.actual_show;
    let max = this.actual_show + 20;
    this.show_credits = [];
    for (let i = min; i < max; i++) {
      if(this.allCredits.length >= i){
        this.show_credits.push(this.allCredits[i]);
      }
    }
    this.show_deductions = this.show_credits;
  }

  start(){
    this.apiServices.getPeriods().subscribe((periods:periods[])=>{
      periods.forEach(period=>{
        if(period.idperiods = this.route.snapshot.paramMap.get('id')){
          this.activePeriod = period;
        }
      })
    })

    this.apiServices.getCredits_ph({id:'all', period:this.activePeriod.idperiods}).subscribe((cred:credits[])=>{
      this.allCredits = cred;
    })

    this.apiServices.getDebits_ph({id:'all', period:this.activePeriod.idperiods}).subscribe((deb:debits[])=>{
      this.allDebits = deb;
      if(!isNullOrUndefined(deb)){
        this.showDebits();
      }
    })
  }

  resetCount(emp?:string){
    this.actual_show = 0;
    if(!isNullOrUndefined(emp)){
      this.showAll = true;
    }else{
      this.showAll = false;
    }
  }

  addfile(event) {
    let failures:debits = new debits;
    let deduction:any;
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    this.allCredits = [];
    this.allDebits = [];

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
                console.log(pay);
                this.correct = this.correct + 1;
                if(this.importType == "CREIDT"){
                  deduction = new credits;
                }else{
                  deduction = new debits;
                }
                deduction.id_employee = emp[0].idemployees;
                deduction.id_process = emp[0].name;
                deduction.idpayments = pay[0].idpayments;
                deduction.type = this.importDescription;
                deduction.id_user = emp[0].nearsol_id
                deduction.notes = emp[0].client_id;
                deduction.amount = element['AMOUNT'];
                if(this.importType == "CREIDT"){
                  this.allCredits.push(deduction);
                }else{
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
            })
          }else{
            failures = new debits;
            failures.id_process = element['NAME'];
            failures.amount = element['AMOUNT'];
            failures.date = "NO EMPLOYEE FOUND";
            this.incorrect = this.incorrect + 1;
            this.importFail.push(failures);
          }
        })
      })
    }
  }

  saveImport(){
    this.actual_show = 0;
    this.showImport = true;
    if(this.importType == "CREDIT"){
      this.showCredits();
    }else{
      this.showDebits();
    }
  }
}
