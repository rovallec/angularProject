import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { sendmailRes } from '../process_templates';

@Component({
  selector: 'app-paystub-sendmail',
  templateUrl: './paystub-sendmail.component.html',
  styleUrls: ['./paystub-sendmail.component.css']
})
export class PaystubSendmailComponent implements OnInit {

  constructor(public apiService:ApiService) { }

  ngOnInit() {
  }

  sendMail(){
    this.apiService.sendMail({test:"Test"}).subscribe((str:sendmailRes)=>{
      window.alert(str.retunr_text);
    })
  }

}
