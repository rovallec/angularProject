import { Component, OnInit } from '@angular/core';
import { isNull } from 'util';

@Component({
  selector: 'app-active-analysis',
  templateUrl: './active-analysis.component.html',
  styleUrls: ['./active-analysis.component.css']
})
export class ActiveAnalysisComponent implements OnInit {
  minDate: string = null;
  todayDate = new Date();
  maxDate: string = this.todayDate.getFullYear().toString() + "-" + (this.todayDate.getMonth() + 1).toString().padStart(2, "0") + "-" + (this.todayDate.getUTCDate() - 1).toString();
  dateFrom: string = null;
  dateTo: string = null;
  accountAdd: string = null;
  isExportable: boolean = false;

  constructor() { }

  ngOnInit() {
  }
  setFrom(str: string) {
    this.minDate = str;
    this.dateFrom = str;
    if (isNull(this.dateFrom) || isNull(this.dateTo)) {
      this.isExportable = false;
    } else {
      this.isExportable = true;
    }
  }

  setTo(str: string) {
    this.maxDate = str;
    this.dateTo = str;
    if (isNull(this.dateFrom) || isNull(this.dateTo)) {
      this.isExportable = false;
    } else {
      this.isExportable = true;
    }
  }

  async generateReprot() {
    if (!this.isExportable) {
      alert("Missing information to generate the report");
    } else {
      try {
        window.open("http://172.18.2.45/phpscripts/exportAgentAnalysis.php", "_blank");
      } catch (error) {
        alert("It could not to generate the report.");
      }
      finally {
        await new Promise( resolve => setTimeout(resolve, 1000));
        //new Promise( resolve => setTimeout(resolve, 2000) );
        window.confirm("The report has been generated.");
      }
    }
  }
}
