import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import * as XLSX from 'xlsx';
import { ot_manage, periods } from '../process_templates';
import { timeStamp } from 'console';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { isNull } from 'util';

@Component({
  selector: 'app-import-ot',
  templateUrl: './import-ot.component.html',
  styleUrls: ['./import-ot.component.css']
})
export class ImportOtComponent implements OnInit {

  constructor(public apiService:ApiService, public authService:AuthServiceService) { }

  period:periods = new periods;
  file: any;
  arrayBuffer: any;
  filelist: any;
  imported:boolean = false;
  ots:ot_manage[] = [];

  ngOnInit() {
    this.apiService.getPeriods().subscribe((pr:periods[])=>{
      this.period = pr[pr.length-1];

    })
  }

  saveOt(){
    this.ots.forEach(ot => {
      this.apiService.getApprovedOt(ot).subscribe((ots:ot_manage)=>{
        if(parseFloat(ots.id_employee) > 0){
          this.apiService.insertApprovedOt(ot).subscribe((str:string)=>{});
        }
      })
    });
  }

  addfile(event) {
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let num:number = 0;
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
        let ot:ot_manage = new ot_manage;
        this.apiService.getSearchEmployees({filter:'nearsol_id', value:element['NEARSOL ID'], dp:this.authService.getAuthusr().department}).subscribe((emp:employees[])=>{
          ot.id_employee = emp[0].idemployees;
          ot.amount = element['AMOUNT'];
          ot.id_period = this.period.idperiods;
          ot.name = emp[0].name;
          ot.nearsol_id = element['NEARSOL ID'];
          this.ots.push(ot);
          num = num + 1;
          if(num == sheetToJson.length){
            this.imported = true;
          }
        })
      })
    }
  }

}
