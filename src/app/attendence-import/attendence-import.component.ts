import { Component, OnInit } from '@angular/core';
import * as XLSX from 'xlsx';
import { accounts, attendences, attendences_adjustment } from '../process_templates';
import { ApiService } from '../api.service';
import { employees } from '../fullProcess';
import { isNullOrUndefined, isNull } from 'util';
import { AuthServiceService } from '../auth-service.service';

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
  importCompleted: boolean = false;
  completed: boolean = false;
  getNewRoster:boolean = false;
  selectedAccount:string = null;


  correct: attendences[] = [];
  fail: attendences[] = [];
  apply:attendences[] = [];
  uploaded:attendences[] = [];
  accounts:accounts[] = [];

  attendences: attendences[] = [];
  constructor(private apiService: ApiService, public authService:AuthServiceService) { }

  ngOnInit() {
  }

  addfile(event) {
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let nwAtt: attendences
    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      if (!this.completed) {
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
          elem.day_off1 = "NO MATCH";
          this.apiService.getSearchEmployees({ filter: 'client_id', value: elem.client_id, dp:'4'}).subscribe((emp: employees[]) => {
            if (!isNullOrUndefined(emp[0])) {
              this.apiService.getAttendences({ date:elem.date + ";" + emp[0].idemployees , id:'NULL' }).subscribe((att: attendences[]) => {
                if (att.length > 0) {
                  elem.idattendences = att[0].idattendences;
                  elem.id_employee = att[0].id_employee;
                  elem.day_off2 = att[0].worked_time;
                  elem.day_off1 = "OMMIT";
                } else {
                  elem.day_off1 = "CORRECT";
                }
              })
              elem.id_employee = emp[0].idemployees;
              this.checkedCount = this.checkedCount + 1;
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

  setAction(att:attendences){
    this.checkedCount = this.checkedCount + 1;
    this.failCount = this.failCount - 1;
  }

  uploadAttendences() {
    this.attendences.forEach(element => {
      if (element.day_off1 == 'OMMIT') {
        this.fail.push(element);
      } else {
        if(element.day_off1 == 'APPLY'){
          this.apply.push(element);
          this.uploaded.push(element);
        }else{
          if(element.day_off1 == "CORRECT"){
            this.correct.push(element);
            this.uploaded.push(element);
          }
        }
      }
    });

    this.apply.forEach(app => {
      let adj:attendences_adjustment = new attendences_adjustment;
      adj.date = app.date;
      adj.id_attendence = app.idattendences;
      adj.id_department = '4'; 
      adj.id_employee = app.id_employee;
      adj.id_type = '2';
      adj.id_user = this.authService.getAuthusr().iduser;
      adj.notes = 'WFM Attendance correction';
      adj.reason = 'WFM Correction';
      adj.state = 'PENDING';
      adj.status = "PENDING";
      adj.time_after = app.worked_time;
      adj.time_before = app.day_off2;
      adj.amount = (parseFloat(app.worked_time) - parseFloat(app.day_off2)).toFixed(2);
      this.apiService.insertAttJustification(adj).subscribe((str:string)=>{
        this.apiService.updateAttendances(app).subscribe((str:string)=>{});
      })
    });

    this.apiService.insertAttendences(this.correct).subscribe((att: attendences[]) => {
      this.importCompleted = true;
    });
  }

  activeRoster(){
    this.getNewRoster = true;
    this.apiService.getAcconts().subscribe((acc:accounts[])=>{
      this.accounts = acc;
    })
  }

  selectAccount(event){
  }

  getRoster(){
    window.open("http://200.94.251.67/phpscripts/exportRoster.php?acc=" + this.selectedAccount, "_blank")
  }
}
