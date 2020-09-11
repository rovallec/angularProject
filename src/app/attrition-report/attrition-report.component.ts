import { Component, OnInit } from '@angular/core';
import { isNull } from 'util';

@Component({
  selector: 'app-attrition-report',
  templateUrl: './attrition-report.component.html',
  styleUrls: ['./attrition-report.component.css']
})
export class AttritionReportComponent implements OnInit {
  minDate:string = null;
  todayDate = new Date();
  maxDate:string = this.todayDate.getFullYear().toString() + "-" +  (this.todayDate.getMonth() + 1).toString().padStart(2,"0") + "-" +  (this.todayDate.getUTCDate()-1).toString();
  dateFrom:string = null;
  dateTo:string = null;
  accountAdd:string = null;
  isExportable:boolean = false;

  constructor() { }

  ngOnInit() {
  }

  setFrom(str:string){
    this.minDate = str;
    this.dateFrom = str;
    if(isNull(this.dateFrom) || isNull(this.dateTo)){
      this.isExportable = false;
    }else{
      this.isExportable = true;
    }
  }

  setTo(str:string){
    this.maxDate = str;
    this.dateTo = str;
    if(isNull(this.dateFrom) || isNull(this.dateTo)){
      this.isExportable = false;
    }else{
      this.isExportable = true;
    }
  }

  generateReprot(){
    if(!this.isExportable){
      alert("Missing information to generate the report");
    }else{
      window.open("http://200.94.251.67/phpscripts/exportAttritionReport.php?date=" + this.dateFrom + " AND " + this.dateTo, "_blank")
    }
    }
}
