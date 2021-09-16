import { Component, OnInit } from '@angular/core';
import * as XLSX from 'xlsx';
import { accounts, attendences, attendences_adjustment, sup_exception, tk_import, tk_upload } from '../process_templates';
import { ApiService } from '../api.service';
import { employees, fullDoc_Proc, testRes } from '../fullProcess';
import { isNullOrUndefined, isNull } from 'util';
import { AuthServiceService } from '../auth-service.service';
import { DatePipe } from '@angular/common';

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
  addDoc_proc: fullDoc_Proc[] = [new fullDoc_Proc];
  showReg: boolean = false;
  todayDate: Date = new Date();
  selectedStart: string = null;
  selectedEnd: string = null;
  imports: tk_upload[] = [];
  selectedImp: tk_upload = new tk_upload;
  reapetedEmployees: employees[] = [];
  repeatedExp: employees[] = [];

  constructor(private apiService: ApiService, public authService: AuthServiceService, public datepipe: DatePipe) { }

  ngOnInit() {
    this.selectedStart = this.datepipe.transform(this.todayDate, 'yyyy-MM-dd');
    this.selectedEnd = this.datepipe.transform(this.todayDate, 'yyyy-MM-dd');
  }

  addfile(event) {
    this.addDoc_proc[0].doc_path = event.target.files[0];
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
            nwAtt.schedule_fix = Number(element['Schedule FIX']).toFixed();
            nwAtt.time_in_aux0 = Number(element['Time in Aux 0']).toFixed();
            nwAtt.time_in_systems_issues = Number(element['Time in System Issues']).toFixed();
            nwAtt.time_in_lunch = Number(element['Time in lunch']).toFixed();
            nwAtt.break_abuse = Number(element['Break Abuse']).toFixed();
            nwAtt.exceptions_meeting_feedback = Number(element['Exceptions Meeting & feedback']).toFixed();
            nwAtt.exceptions_offline_training = Number(element['Exceptions offline Training']).toFixed();
            nwAtt.systems_issues_by_sup = Number(element['System Issues by Sup']).toFixed();
            nwAtt.floor_support = Number(element['Floor Support']).toFixed();
            nwAtt.time_training = Number(element['TIME TRAINING']).toFixed();
            let arr_exp:string[] = ['Schedule FIX', 'Time in Aux 0', 'Time in System Issues',	'Time in lunch', 'Break Abuse',	'Exceptions Meeting & feedback', 'Exceptions offline Training',
                                    'System Issues by Sup', 'Floor Support',  'TIME TRAINING'];

            for (let exp = 0; exp < 10; exp++) {
              let excp_sup:sup_exception = new sup_exception;
              excp_sup.avaya = element['Client ID'];
              excp_sup.date = element['Date'];
              excp_sup.name = element['Name'];
              excp_sup.reason = arr_exp[exp];
              excp_sup.supervisor = "ATTENDANCE";
              excp_sup.time = Number(element[arr_exp[exp]]).toFixed(5);
              excp_sup.id_type = (exp + 1).toFixed(0);
              this.apiService.getSearchEmployees({ filter: 'client_id', value: excp_sup.avaya, dp: 'exact', rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
                if (isNull(emp)) {
                  excp_sup.status = 'FALSE';
                } else {
                  excp_sup.status = 'TRUE';
                }
              emp = emp.sort((a, b) => Number(b.active) - Number(a.active)).sort((c, d) => Number(d.idemployees) - Number(c.idemployees));
                if (emp.length > 1) {
                  excp_sup.duplicated = '1';
                  emp.forEach(toPush => {
                    this.repeatedExp.push(toPush);
                  })
                } else {
                  excp_sup.duplicated = '0';
                }
                excp_sup.id_employee = emp[0].idemployees;
                this.sups.push(excp_sup);
              })
            }


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
          this.apiService.getSearchEmployees({ filter: 'client_id', value: elem.client_id, dp: 'exact', rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
            emp = emp.sort((a, b) => Number(b.active) - Number(a.active)).sort((c, d) => Number(d.idemployees) - Number(c.idemployees))
            if (emp.length > 1) {
              emp.forEach(reapeted => {
                this.reapetedEmployees.push(reapeted);
              })
              elem.id_wave = "1";
            } else {
              elem.id_wave = "0";
            }
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
          let duplicated: number = 0;
          let count: number = 1;
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
              supervisors.id_type = element['TYPE'];
              this.apiService.getSearchEmployees({ dp: 'all', filter: 'client_id', value: supervisors.avaya, rol: this.authService.getAuthusr().id_role }).subscribe((emp: employees[]) => {
                if (isNull(emp)) {
                  supervisors.status = 'FALSE';
                } else {
                  supervisors.status = 'TRUE';
                }
                emp = emp.sort((a, b) => Number(b.active) - Number(a.active)).sort((c, d) => Number(d.idemployees) - Number(c.idemployees));
                if (emp.length > 1) {
                  supervisors.duplicated = '1';
                  emp.forEach(toPush => {
                    this.repeatedExp.push(toPush);
                  })
                } else {
                  supervisors.duplicated = '0';
                }
                supervisors.name = emp[0].name;
                supervisors.id_employee = emp[0].idemployees;
                count++;
                this.sups.push(supervisors);
                let temp_sups: sup_exception[] = [];
                this.sups.forEach(element => {
                  let add: boolean = true;
                  temp_sups.forEach(temp => {
                    if (temp.avaya == element.avaya && temp.date == element.date && temp.time == element.time) {
                      add = false;
                      duplicated++;
                    }
                  })
                  if (add) {
                    temp_sups.push(element);
                  }
                })
                this.sups = temp_sups;
                if (count > sheetToJson_2.length) {
                  if (duplicated > 0) {
                    window.alert("Automaticly deleted " + (duplicated) + " duplicated Exceptions");
                  }
                }
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
      this.apiService.getAttAdjustments({ id: "id|p;" + app.id_employee + "|'" + app.date + "' AND '" + app.date + "'" }).subscribe((adjust: attendences_adjustment[]) => {
        if (!isNullOrUndefined(adjust)) {
          adjust.forEach(adjustment => {
            if (adjustment.id_department == '27' || adjustment.id_department == '5') {
              app.worked_time = (Number(app.worked_time) + Number(adjustment.amount)).toFixed(3);
            }
          })
        }
        let adj: attendences_adjustment = new attendences_adjustment;
        adj.date = (new Date().getFullYear().toString() + "-" + (new Date().getMonth() + 1).toString() + "-" + (new Date().getDate().toString()));
        adj.id_attendence = app.idattendences;
        adj.id_department = '28';
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
      })
    })

    if (this.fail.length + this.correct.length + this.apply.length == this.attendences.length) {
      this.apiService.insertTkimport().subscribe((str: tk_upload) => {
        let formData = new FormData;
        formData.append('profile', 'NULL');
        formData.append('process', 'tk_exception');
        formData.append('file1', this.addDoc_proc[0].doc_path);
        formData.append('user', str.path);
        this.correct.forEach(corr => {
          corr.tk_imp = str.idtk_import;
        })
        this.apiService.insDocProc_doc(formData).subscribe((tst: testRes) => {
          this.apiService.insertAttendences(this.correct).subscribe((att: attendences[]) => {
            this.importCompleted = true;
            //TAB 2
            let adjustments: attendences_adjustment[] = [];
            let i: number = 0;
            this.sups.forEach(sup => {
              i = i + 1;
              if (sup.status == 'TRUE') {
                this.apiService.getAttendences({ date: "= '" + sup.date + "'", id: sup.id_employee }).subscribe((attendance: attendences[]) => {
                  let adjustment: attendences_adjustment = new attendences_adjustment;
                  let adjust_time:boolean = true;
                  let adjust_sch:boolean = false;
                  adjustment.id_import = str.idtk_import;
                  adjustment.id_employee = sup.id_employee;
                  adjustment.id_user = this.authService.getAuthusr().iduser;
                  adjustment.id_type = '2';
                  adjustment.id_department = '28';
                  adjustment.date = (new Date().getFullYear().toString()) + "-" + ((new Date().getMonth() + 1).toString()) + "-" + (new Date().getDate().toString());
                  adjustment.notes = "Supervisor: " + sup.supervisor + " Reason: " + sup.reason;
                  adjustment.status = 'PENDING';
                  switch (sup.id_type) {
                    case '1':
                      adjustment.reason = 'Schedule FIX';
                      adjust_time = false;
                      adjust_sch = true;
                      break;
                    case '2':
                      adjustment.reason = 'Time in Aux 0';
                      adjust_time = true;
                      break;
                    case '3':
                      adjustment.reason = 'Time In System Issues';
                      adjust_time = true
                      break;
                    case '4':
                      adjustment.reason = 'Time in lunch';
                      if(Number(sup.time) > 1){
                        adjust_time = true;
                      }
                      break;
                    case '5':
                      adjustment.reason = 'Break Abuse';
                      adjust_time = true;
                      break;
                    case '6':
                      adjustment.reason = 'Exceptions Meeting & feedback';
                      adjust_time = true;
                      break;
                    case '7':
                      adjustment.reason = 'Exceptions offline Training';
                      adjust_time = true;
                      break;
                    case '8':
                      adjustment.reason = 'System Issues by Sup';
                      adjust_time = true;
                      break;
                    case '9':
                      adjustment.reason = 'Floor Support';
                      adjust_time = true;
                      break;
                    case '10':
                      adjustment.reason = 'TIME TRAINING';
                      adjust_time = true;
                      break;
                    case '11':
                      adjustment.reason = 'CCR Productive';
                      adjust_time = true;
                      break;
                  }
                  adjustment.id_attendence = attendance[0].idattendences;
                  if(adjust_time){
                    adjustment.time_before = attendance[0].worked_time;
                    adjustment.time_after = (Number(sup.time) + Number(attendance[0].worked_time)).toFixed(3);
                    adjustment.amount = sup.time;
                  }
                  adjustment.state = "PENDING";
                  if(adjust_sch){
                    adjustment.time_after = sup.time;
                    adjustment.time_before = attendance[0].scheduled;
                    if(attendance[0].scheduled != 'OFF'){
                      adjustment.amount = (Number(attendance[0].scheduled) - Number(sup.time)).toFixed(2);
                    }else{
                      adjustment.amount = (Number(sup.time)).toFixed(2);
                    }
                    this.apiService.insertScheduleFix(adjustment).subscribe((str:string)=>{
                      if (i == this.sups.length) {
                        this.completed = true;
                      }
                    })
                  }else{
                    this.apiService.insertAttJustification(adjustment).subscribe((str: string) => {
                      if (i == this.sups.length) {
                        this.completed = true;
                      }
                    })
                  }
                })
              }
            })
          });
        })
      })
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

  toggleReg() {
    let element: HTMLElement = document.getElementById('nav-attendence-tab');
    element.click();
    this.showReg = !this.showReg;
    this.imports = [];
    this.selectedImp = new tk_upload;
    this.attendences = [];
    this.sups = [];
    if (this.showReg) {
      this.getTk_upload();
    }
  }

  setStart(event) {
    this.selectedStart = event;
    this.getTk_upload();
  }

  setEnd(event) {
    this.selectedEnd = event;
    this.getTk_upload();
  }

  getTk_upload() {
    this.apiService.getTkUploads({ date: ' BETWEEN ' + "'" + this.selectedStart + "'" + " AND " + "'" + this.selectedEnd + "'" }).subscribe((imp: tk_upload[]) => {
      this.imports = imp;
    });
  }

  getImport_tk(imp: tk_upload) {
    this.selectedImp = imp;
    window.open("http://172.18.2.45/uploads/" + imp.path, "_blank");
  }

  setImport(imp: tk_upload) {
    this.sups = [];
    this.selectedImp = imp;
    this.apiService.getAttendences({ id: "IMPORT", date: imp.idtk_import }).subscribe((att: attendences[]) => {
      this.attendences = att;
      this.apiService.getUploadedAdjustments({ id: imp.idtk_import }).subscribe((adj: attendences_adjustment[]) => {
        adj.forEach(adjustment => {
          let sup: sup_exception = new sup_exception;
          sup.avaya = adjustment.id_attendence;
          sup.date = adjustment.attendance_date;
          sup.name = adjustment.name;
          sup.notes = adjustment.notes;
          sup.reason = adjustment.reason;
          sup.status = adjustment.status;
          sup.time = adjustment.amount;
          this.sups.push(sup);
        })
      })
      let element: HTMLElement = document.getElementById('nav-attendence-tab');
      element.click();
    })
  }

  sortAttendances() {
    return this.attendences.sort((a, b) => Number(b.id_wave) - Number(a.id_wave));
  }

  sortExceptions() {
    return this.sups.sort((a, b) => Number(b.duplicated) - Number(a.duplicated));
  }
  getReapeted(att: attendences) {
    return this.reapetedEmployees.filter((a, b) => this.reapetedEmployees.findIndex(item => item.name == a.name) === b).filter(c => c.client_id == att.client_id);
  }

  getRepeatedExp(exp: sup_exception) {
    return this.repeatedExp.filter((a, b) => this.repeatedExp.findIndex(item => item.name == a.name) === b).filter(c => c.client_id == exp.avaya);
  }

  setExpReapeted(sup: sup_exception, rep: string) {
    sup.id_employee = rep;
  }

  setEmployeeRepeated(attnd: attendences, rep: string) {
    this.apiService.getAttendences({ date: attnd.date + ";" + rep, id: 'NULL' }).subscribe((att: attendences[]) => {
      if (att.length > 0) {
        attnd.idattendences = att[0].idattendences;
        attnd.id_employee = att[0].id_employee;
        attnd.day_off2 = att[0].worked_time;
        attnd.day_off1 = "OMMIT";
      } else {
        attnd.day_off1 = "CORRECT";
      }
    })
    attnd.id_employee = rep;
  }
}
