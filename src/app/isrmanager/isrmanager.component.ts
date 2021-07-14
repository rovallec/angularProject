import { Component, OnInit } from '@angular/core';
import { isNullOrUndefined } from 'util';
import * as XLSX from 'xlsx';
import { ApiService } from '../api.service';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { debits, isr, payments, periods } from '../process_templates';

@Component({
  selector: 'app-isrmanager',
  templateUrl: './isrmanager.component.html',
  styleUrls: ['./isrmanager.component.css']
})
export class IsrmanagerComponent implements OnInit {

  periods: periods[] = [];
  selectedPeriod: periods = new periods;
  years: string[] = [new Date().getFullYear().toString()];
  selectedYear: string = new Date().getFullYear().toString();
  isrs: isr[] = [];
  temp_isr: isr[] = [];
  file: any;
  arrayBuffer: any;
  filelist: any;
  completed: boolean = false;

  constructor(public apiServices: ApiService, public authSrv:AuthServiceService) { }

  ngOnInit() {
    this.setPeriods();
  }

  setPeriods() {
    let toPush: boolean = true;
    this.periods = [];
    this.years = [new Date().getFullYear().toString()];
    this.apiServices.getPeriods().subscribe((per: periods[]) => {
      per.forEach(period => {
        toPush = true;
        this.years.forEach(yr => {
          if (yr == period.start.split("-")[0]) {
            toPush = false;
          }
        })
        if (toPush) {
          this.years.push(period.start.split("-")[0]);
        }
        this.selectedYear = this.years[0];

        if (this.selectedYear == period.start.split("-")[0]) {
          this.periods.push(period);
          this.selectedPeriod = this.periods[0];
          this.getIsr();
        }
      })
    });
  }

  setPSelection() {
    this.periods = [];
    this.apiServices.getPeriods().subscribe((per: periods[]) => {
      per.forEach(period => {
        if (this.selectedYear == period.start.split("-")[0]) {
          this.periods.push(period);
          this.getIsr();
        }
      })
      this.setSelection(this.periods[0]);
    })
  }

  getIsr() {
    this.apiServices.getIsr(this.selectedPeriod).subscribe((isrs: isr[]) => {
      this.isrs = isrs;
    })
  }

  setSelection(period: periods) {
    this.selectedPeriod = period;
    this.getIsr();
  }

  getIsrReport() {
    window.open("http://172.18.2.45/phpscripts/exportIsrReport.php?start=" + this.selectedPeriod.start + "&end=" + this.selectedPeriod.end, "_blank");
  }

  getTermReport() {
    window.open("http://172.18.2.45/phpscripts/exportIsrReport_term.php?start=" + this.selectedPeriod.start + "&end=" + this.selectedPeriod.end, "_blank");
  }

  setIsrReport() {
    this.isrs = this.temp_isr;
  }

  addfile(event) {
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let new_isr: isr = new isr;

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
        let temp_nit: string = null;
        temp_nit = element['NIT Empleado'].toString();
        temp_nit = temp_nit.substr(1, temp_nit.length - 2) + "%" + temp_nit.charAt(temp_nit.length - 1) + "%";
        this.apiServices.getSearchEmployees({ filter: 'nit', value: temp_nit, dp: '4', rol:this.authSrv.getAuthusr().id_role }).subscribe((employees: employees[]) => {
          if (!isNullOrUndefined(employees)) {
            new_isr.nearsol_id = employees[0].nearsol_id;
            new_isr.idemployees = employees[0].idemployees;
            new_isr.name = employees[0].name;
            new_isr.nit = element['NIT Empleado'];
            new_isr.gross_income = element['Renta Bruta'];
            new_isr.taxable_income = element['Renta Imponible'];
            new_isr.anual_tax = element['Impuesto Anual'];
            new_isr.other_retentions = element['Otras Retenciones'];
            new_isr.accumulated = element['Retenciones Practicadas'];
            new_isr.expected = element['Impuesto a Retener'];
            new_isr.amount = Number(element['Retención Mensual']).toFixed(2);
            this.temp_isr.push(new_isr);
          }
        });
      })
    }
    this.completed = true;
  }

  setIsr() {
    this.isrs.forEach(single_isr => {
      let deb: debits = new debits;
      let p: periods = new periods;
      let cnt: string = null;

      p = this.selectedPeriod;
      p.status = single_isr.idemployees;
      cnt = p.start;
      p.start = "explicit";
      this.apiServices.getPayments(p).subscribe((pay: payments[]) => {
        if (!isNullOrUndefined(pay)) {
          pay.forEach(p => {
            if (p.id_period == this.selectedPeriod.idperiods) {
              console.log(p);
              console.log(this.selectedPeriod);
              deb.id_employee = single_isr.idemployees;
              deb.idpayments = p.idpayments;
              deb.amount = single_isr.amount;
              deb.type = "ISR";
              this.apiServices.insertDebits(deb).subscribe((str: string) => {
              })
            }
          })
        }
      })
      p.start = cnt;
    })
    window.alert("Import Finished");
    this.ngOnInit();
  }
}
