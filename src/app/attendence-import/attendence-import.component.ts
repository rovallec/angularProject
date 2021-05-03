import { Component, OnInit } from '@angular/core';
import * as XLSX from 'xlsx';
import { accounts, attendences, attendences_adjustment, sup_exception } from '../process_templates';
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
  getNewRoster: boolean = false;
  selectedAccount: string = null;


  correct: attendences[] = [];
  fail: attendences[] = [];
  apply: attendences[] = [];
  uploaded: attendences[] = [];
  accounts: accounts[] = [];
  sups: sup_exception[] = [];

  attendences: attendences[] = [];
  constructor(private apiService: ApiService, public authService: AuthServiceService) { }

  ngOnInit() {
  }

  addfile(event) {
    this.sups = [];
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
        var first_sheet_name_2 = workbook.SheetNames[1];
        var worksheet = workbook.Sheets[first_sheet_name];
        //TAB 1
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
              nwAtt.scheduled = parseFloat(element['Scheduled']).toFixed(3);
            }
            nwAtt.worked_time = parseFloat(element['Worked']).toFixed(3);
            if (nwAtt.scheduled == 'OFF') {
              nwAtt.balance = nwAtt.worked_time;
            } else {
              nwAtt.balance = parseFloat((parseFloat(nwAtt.worked_time) - parseFloat(nwAtt.scheduled)).toString()).toFixed(3);
            }
            this.attendences.push(nwAtt);
          } catch (error) {

          }
        });
        let att: attendences[] = [];

        this.attendences.forEach(elem => {
          elem.day_off1 = "NO MATCH";
          this.apiService.getSearchEmployees({ filter: 'client_id', value: elem.client_id, dp: 'exact' }).subscribe((emp: employees[]) => {
            if (!isNullOrUndefined(emp[0])) {
              this.apiService.getAttendences({ date: elem.date + ";" + emp[0].idemployees, id: 'NULL' }).subscribe((att: attendences[]) => {
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

        try {
          //TAB 2
          var worksheet_2 = workbook.Sheets[first_sheet_name_2];
          let sheetToJson_2 = XLSX.utils.sheet_to_json(worksheet_2, { raw: true });
          sheetToJson_2.forEach(element => {
            try {
              let supervisors: sup_exception = new sup_exception;
              supervisors.avaya = element['AVAYA'];
              supervisors.date = element['DATE'];
              supervisors.name = element['NAME'];
              supervisors.notes = element['NOTES'];
              supervisors.reason = element['REASON'];
              supervisors.supervisor = element['SUPERVISOR'];
              supervisors.time = element['TIME'];
              this.apiService.getSearchEmployees({ dp: 'all', filter: 'client_id', value: supervisors.avaya }).subscribe((emp: employees[]) => {
                if (isNull(emp)) {
                  supervisors.status = 'FALSE';
                } else {
                  supervisors.status = 'TRUE';
                }
                this.sups.push(supervisors);
              })
            } catch (error2) {
  
            }
          })
        } catch (error) {
          
        }
        this.attendences = att;
        this.failCount = this.attendences.length - this.checkedCount;
        this.completed = true;
      }
    }
  }

  setAction(att: attendences) {
    this.checkedCount = this.checkedCount + 1;
    this.failCount = this.failCount - 1;
  }

  uploadAttendences() {
    //TAB 1
    this.attendences.forEach(element => {
      if (element.day_off1 == 'OMMIT') {
        this.fail.push(element);
      } else {
        if (element.day_off1 == 'APPLY') {
          this.apply.push(element);
          this.uploaded.push(element);
        } else {
          if (element.day_off1 == "CORRECT") {
            this.correct.push(element);
            this.uploaded.push(element);
          }
        }
      }
    });

    this.apply.forEach(app => {
      let adj: attendences_adjustment = new attendences_adjustment;
      adj.date = (new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString() + "-" + (new Date().getDate().toString()));
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
      this.apiService.insertAttJustification(adj).subscribe((str: string) => {
        this.apiService.updateAttendances(app).subscribe((str: string) => { });
      })
    });

    if(this.correct.length > 0){
      this.apiService.insertAttendences(this.correct).subscribe((att: attendences[]) => {
        this.importCompleted = true;
        //TAB 2
        let adjustments: attendences_adjustment[] = [];
        let i: number = 0;
        this.sups.forEach(sup => {
          i = i + 1;
          if (sup.status == 'TRUE') {
            this.apiService.getSearchEmployees({ dp: 'all', filter: 'client_id', value: sup.avaya }).subscribe((emp: employees[]) => {
              this.apiService.getAttendences({ date: "= '" + sup.date + "'", id: emp[0].idemployees }).subscribe((attendance: attendences[]) => {
                let adjustment: attendences_adjustment = new attendences_adjustment;
                adjustment.id_employee = emp[0].idemployees;
                adjustment.id_user = this.authService.getAuthusr().iduser;
                adjustment.id_type = '2';
                adjustment.id_department = '28';
                adjustment.date = (new Date().getFullYear().toString()) + "-" + ((new Date().getMonth() + 1).toString()) + "-" + (new Date().getDate().toString());
                adjustment.notes = "Supervisor: " + sup.supervisor + " Reason: " + sup.reason;
                adjustment.status = 'PENDING';
                adjustment.reason = 'Supervisor Exception';
                adjustment.id_attendence = attendance[0].idattendences;
                adjustment.time_before = attendance[0].worked_time;
                adjustment.time_after = (Number(sup.time) + Number(attendance[0].worked_time)).toFixed(3);
                adjustment.amount = sup.time;
                adjustment.state = "PENDING";
                this.apiService.insertAttJustification(adjustment).subscribe((str: string) => {
                  if (i == this.sups.length) {
                    this.completed = true;
                  }
                })
              })
            })
          }
        })
      });
    }
  }

  activeRoster() {
    this.getNewRoster = true;
    this.apiService.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc;
    })
  }

  selectAccount(event) {
  }

  getRoster() {
    window.open("http://172.18.2.45/phpscripts/exportRoster.php?acc=" + this.selectedAccount, "_blank")
  }
}
