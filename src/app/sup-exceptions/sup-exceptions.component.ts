import { Component, OnInit } from '@angular/core';
import { attendences, attendences_adjustment, supervisor_survey, sup_exception } from '../process_templates';
import * as XLSX from 'xlsx';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { isNull, isNullOrUndefined } from 'util';
import { AuthServiceService } from '../auth-service.service';

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
  completed:boolean = false;

  constructor(public apiService:ApiService, public authService:AuthServiceService) { }

  ngOnInit() {
  }
  
  closeImport(){
    this.activeCheck = false;
    this.completed = false;
    this.sups = [];
  }

  completeImport(){
    let adjustments:attendences_adjustment[] = [];
    let i:number = 0;
    this.sups.forEach(sup=>{
      i = i + 1;
      if(sup.status == 'TRUE'){
        this.apiService.getSearchEmployees({dp:'all', filter:'client_id', value:sup.avaya}).subscribe((emp:employees[])=>{
          this.apiService.getAttendences({date:"= '" + sup.date + "'", id:emp[0].idemployees}).subscribe((attendance:attendences[])=>{
            let adjustment:attendences_adjustment = new attendences_adjustment;
            adjustment.id_employee = emp[0].idemployees;
            adjustment.id_user = this.authService.getAuthusr().iduser;
            adjustment.id_type = '2';
            adjustment.id_department = '28';
            adjustment.date = (new Date().getFullYear().toString()) + "-" + ((new Date().getMonth()+1).toString()) + "-" + (new Date().getDate().toString());
            adjustment.notes = "Supervisor: " + sup.supervisor + " Reason: " + sup.reason;
            adjustment.status = 'PENDING';
            adjustment.reason = 'Supervisor Exception';
            adjustment.id_attendence = attendance[0].idattendences;
            adjustment.time_before = attendance[0].worked_time;
            adjustment.time_after = (Number(sup.time) + Number(attendance[0].worked_time)).toFixed(3);
            adjustment.amount = sup.time;
            adjustment.state = "PENDING";
            this.apiService.insertAttJustification(adjustment).subscribe((str:string)=>{
              if(i == this.sups.length){
                this.completed = true;
              }
            })
          })
        })
      }
    })
  }

  importFile(event) {
    this.sups = [];
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
            if(isNullOrUndefined(emp[0])){
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
