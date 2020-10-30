import { Component, OnInit } from '@angular/core';
import { supervisor_survey, sup_exception } from '../process_templates';
import * as XLSX from 'xlsx';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { isNull } from 'util';

@Component({
  selector: 'app-sup-exceptions',
  templateUrl: './sup-exceptions.component.html',
  styleUrls: ['./sup-exceptions.component.css']
})
export class SupExceptionsComponent implements OnInit {

  sups: sup_exception[] = [];
  activeCheck: boolean = false;
  file: any;
  arrayBuffer: any;
  filelist: any;

  constructor(public apiService:ApiService) { }

  ngOnInit() {
  }

  importFile(event) {
    this.activeCheck = true;
    let end:number = 0;
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      this.arrayBuffer = fileReader.result;
      var data = new Uint8Array(this.arrayBuffer);
      var arr = new Array();
      let nm: string = null;
      for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
      var bstr = arr.join("");
      var workbook = XLSX.read(bstr, { type: "binary" });
      var first_sheet_name = workbook.SheetNames[0];
      var worksheet = workbook.Sheets[first_sheet_name];
      let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
      sheetToJson.forEach(element => {
        try {
          let supervisors:sup_exception = new sup_exception;
          supervisors.avaya = element['AVAYA'];
          supervisors.date = element['DATE'];
          supervisors.name = element['NAME'];
          supervisors.notes = element['NOTES'];
          supervisors.reason = element['REASON'];
          supervisors.supervisor = element['SUPERVISOR'];
          supervisors.time = element['TIME'];
          this.apiService.getSearchEmployees({dp:'all', filter:'client_id', value:supervisors.avaya}).subscribe((emp:employees[])=>{
            if(isNull(emp)){
              supervisors.status = 'FALSE';
            }else{
              supervisors.status = 'TRUE';
            }
            this.sups.push(supervisors);
          })
        } catch (error) {

        }
      })
    }
  }

}
