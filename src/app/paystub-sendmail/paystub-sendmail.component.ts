import { Component, OnInit, ViewEncapsulation } from '@angular/core';
import { isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { payments, paystubview, periods, sendmailRes } from '../process_templates';
import { DomSanitizer, SafeHtml } from '@angular/platform-browser'

@Component({
  selector: 'app-paystub-sendmail',
  templateUrl: './paystub-sendmail.component.html',
  styleUrls: ['./paystub-sendmail.component.css'],
})
export class PaystubSendmailComponent implements OnInit {

  constructor(public apiService: ApiService, public _sanitizer:DomSanitizer) { }

  html:SafeHtml;
  years: string[] = [new Date().getFullYear().toString()];
  periods: periods[] = [];
  selectedYear: string = new Date().getFullYear().toString();
  selectedPeriod: periods = new periods;
  paystubs: paystubview[] = [];
  selectedPaystub:paystubview = new paystubview;

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

  setPSelection() {
    this.periods = [];
    this.apiService.getPeriods().subscribe((per: periods[]) => {
      per.forEach(period => {
        if (this.selectedYear == period.start.split("-")[0]) {
          this.periods.push(period);
        }
      })
      this.setSelection(this.periods[0]);
    })
  }

  setSelection(period: periods) {
    this.selectedPeriod = period;
    this.getPayments();
  }

  getPayments() {
    this.apiService.getPaystubDetails(this.selectedPeriod).subscribe((pst_view: paystubview[]) => {
      pst_view.forEach(pst=>{
        if(!isNullOrUndefined(pst.idpaystub_deatils)){
          pst.select = true;
        }else{
          pst.select = false;
        }
        if(pst.nearsol_id == 'QA21'){
          console.log(pst);
        }        
      })
      this.paystubs = pst_view;
    })
  }

  setpaystubSelected(pst_view:paystubview){
    this.selectedPaystub = pst_view;
    this.html = this._sanitizer.bypassSecurityTrustHtml(this.selectedPaystub.content);
  }

  setStatus(pst_view:paystubview){
    this.paystubs[this.paystubs.indexOf(pst_view)].select = !this.paystubs[this.paystubs.indexOf(pst_view)].select
  }

  sendMails(){
    let count = 0;
    this.paystubs.forEach(py=>{
      if(py.select){
        this.apiService.sendMail({id_employee:py.idpayments}).subscribe((pstv:paystubview)=>{
          count++;
          console.log(pstv);
          console.log(count);
        });
      }
    })
  }
}