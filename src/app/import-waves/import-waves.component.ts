import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts, clients, Fecha, reporters, waves_template  } from '../process_templates';
import * as XLSX from 'xlsx';
import { isNullOrUndefined } from 'util';

@Component({
  selector: 'app-import-waves',
  templateUrl: './import-waves.component.html',
  styleUrls: ['./import-waves.component.css']
})
export class ImportWavesComponent implements OnInit {

  clients: clients[] = [];
  selectedClient: string = null;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;
  isLoading: boolean = true;
  finished: boolean = true;
  progress: number = 0;
  max_progress: number = 0;
  step: string = 'Cargando...';
  reporters: reporters[] = [];
  actualReporter: reporters = new reporters;
  waves: waves_template = new waves_template;
  showdiv: boolean = false;
  date: string = null;
  start_time: string = null;
  end_time: string = null;
  // file
  file: any;
  completed: boolean = false;
  arrayBuffer: any;
  filelist: any;
  importType: string = null;
  importString: string = null;
  importEnd: boolean = false;

  constructor(public apiServices: ApiService) { }

  ngOnInit() {    
    this.isLoading = true;    
    this.start();
  }

  start() {
    let fecha: Fecha = new Fecha;    
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })
    this.getReporter();
    this.date = fecha.today;
    this.isLoading = false;
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.selectedAccount = this.accounts[0];
    })
  }

  saveWave() {
    let error: boolean = false;
    // grabar la wave y todo el sistema.
    if (this.start_time=='') {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to record changes without established schedules.");
    }
    if (this.end_time=='') {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to record changes without established schedules.");
    }    

    if (!error) {
      // ya hechas las validaciones correspondientes envia la información a grabarse en la BD.
    }
  }

  setSchedule() {
    this.waves.trainning_schedule = this.start_time + ' - ' + this.end_time;
  }

  cancelWave(){
    // Cancelar.
  }

  setAccount(acc) {
    this.selectedAccount = acc;
    // set payments
  }

  getReporter() {
    let i = 0;
    this.apiServices.getReporter().subscribe((rep: reporters[]) => {      
      rep.forEach(reporter => {        
        this.reporters[i] = reporter;
        i++;
      })
        
    })
  }

  setReporter(rep: reporters) {
    this.actualReporter = rep;
  }

  addfile(event) {
    //this.credits = [];
    this.file = event.target.files[0];
    //let partial_credits: credits[] = [];
    let fileReader = new FileReader();
    let found: boolean = false;

    fileReader.readAsArrayBuffer(this.file);
    fileReader.onload = (e) => {
      if (!this.completed) {
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
         // let cred: credits = new credits;
          //cred.iddebits = element['Nearsol ID'];
          //cred.amount = element['Amount'];
          //partial_credits.push(cred);
        })
        let count: number = 0;
        /*this.apiServices.getPayments(this.period).subscribe((paymnts: payments[]) => {
         // partial_credits.forEach(ele => {
            this.apiServices.getSearchEmployees({ dp: 'exact', filter: 'nearsol_id', value: ele.iddebits }).subscribe((emp: employees[]) => {
              ele.type = this.importType;
              if (!isNullOrUndefined(emp[0])) {
                paymnts.forEach(py => {
                  if (py.id_employee == emp[0].idemployees) {
                    ele.idpayments = py.idpayments;
                  }
                });
                ele.notes = emp[0].name;
              } else {
                ele.notes = "ERROR";
              }
              count = count + 1;
              if (this.importType == "Bonus") {
                this.credits.push(ele);
              } else if (this.importType == "Discount") {
                let deb: debits = new debits;
                deb.iddebits = ele.iddebits;
                deb.amount = ele.amount;
                deb.date = ele.date;
                deb.id_employee = ele.id_employee;
                deb.idpayments = ele.idpayments;
                deb.notes = ele.notes;
                deb.type = ele.type;
                this.debits.push(deb);
              }

              if (count == (partial_credits.length - 1)) {
                this.importEnd = true;
                this.completed = true;
              }
            })
          })
        })*/
      }
    }
  }
}
