import { Component, OnInit } from '@angular/core';
import * as XLSX from 'xlsx';
import { attendences } from '../process_templates';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { isNullOrUndefined, isNull } from 'util';

@Component({
  selector: 'app-attendence-import',
  templateUrl: './attendence-import.component.html',
  styleUrls: ['./attendence-import.component.css']
})
export class AttendenceImportComponent implements OnInit {
  file: any;
  arrayBuffer: any;
  filelist: any;
  failCount: number = 0;
  checkedCount: number = 0;
  importCompleted:boolean = false;
  completed:boolean = false;

  correct:attendences[] = [];
  fail:attendences[] = [];

  attendences: attendences[] = [];
  constructor(private apiService: ApiService) { }

  ngOnInit() {
  }

  addfile(event) {
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let nwAtt: attendences
    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      if(!this.completed){
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
            nm = element['Name'];
            nwAtt = new attendences;
            nwAtt.date = element['Date'];
            nwAtt.client_id = element['Client ID'];
            nwAtt.first_name = nm.split(" ")[0];
            nwAtt.second_name = nm.split(" ")[1];
            nwAtt.first_lastname = nm.split(" ")[2];
            nwAtt.second_lastname = nm.split(" ")[3];
  
            if (element['Scheduled'] == 'OFF') {
              nwAtt.scheduled = 'OFF';
            } else {
              nwAtt.scheduled = parseFloat(element['Scheduled']).toFixed(2);
            }
            nwAtt.worked_time = parseFloat(element['Worked']).toFixed(2);
            if (nwAtt.scheduled == 'OFF') {
              nwAtt.balance = nwAtt.worked_time;
            } else {
              nwAtt.balance = parseFloat((parseFloat(nwAtt.worked_time) - parseFloat(nwAtt.scheduled)).toString()).toFixed(2);
            }
            this.attendences.push(nwAtt);
          } catch (error) {
  
          }
        });
        let att: attendences[] = [];
  
        this.attendences.forEach(elem => {
          elem.day_off1 = "FAIL";
          this.apiService.getSearchEmployees({ filter: 'client_id', value: elem.client_id }).subscribe((emp: employees[]) => {          
            if (!isNullOrUndefined(emp[0])) {
              this.apiService.getAttendences({id:emp[0].idemployees, date:elem.date}).subscribe((att:attendences[])=>{
                console.log(att);
                if(isNullOrUndefined(att)){
                  elem.day_off1 = "FAIL";
                }else{
                  elem.day_off1 = "CORRECT";
                }
              })
              elem.id_employee = emp[0].idemployees;
              this.checkedCount++;
            }
          })
          att.push(elem);
        });
  
        this.attendences = att;
        this.failCount = this.attendences.length - this.checkedCount;
        this.completed = true;
      }
    }
  }

  uploadAttendences(){
    this.attendences.forEach(element => {
      if(element.day_off1 == 'FAIL'){
        this.fail.push(element);
      }else{
        this.correct.push(element);
      }
    });

    this.apiService.insertAttendences(this.correct).subscribe((att:attendences[])=>{
      this.importCompleted = true;
    });
  }
}
