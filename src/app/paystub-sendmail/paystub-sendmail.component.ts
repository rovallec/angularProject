import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { payments, paystubview, periods, sendmailRes } from '../process_templates';

@Component({
  selector: 'app-paystub-sendmail',
  templateUrl: './paystub-sendmail.component.html',
  styleUrls: ['./paystub-sendmail.component.css']
})
export class PaystubSendmailComponent implements OnInit {

  constructor(public apiService: ApiService) { }

  years: string[] = [new Date().getFullYear().toString()];
  periods: periods[] = [];
  selectedYear: string = new Date().getFullYear().toString();
  selectedPeriod: periods = new periods;
  paystubs: paystubview[] = [];

  ngOnInit() {
    this.setPeriods()
  }

  setPeriods() {
    let toPush: boolean = true;
    this.periods = [];
    this.years = [new Date().getFullYear().toString()];
    this.apiService.getPeriods().subscribe((per: periods[]) => {
      console.log(per);
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
          this.getPayments();
        }
      })
    });
  }

  getPayments() {
    this.apiService.getPaystubDetails(this.selectedPeriod).subscribe((pst_view: paystubview[]) => {
      this.paystubs = pst_view;
    })
  }

  sendMail() {
    this.apiService.sendMail({ test: "Test" }).subscribe((str: string) => {
      window.alert(str);
    })
  }

}
