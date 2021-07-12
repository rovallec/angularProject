import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';

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
    this.apiService.sendMail({test:"Test"}).subscribe((str:string)=>{
      if(str === '1'){
        window.alert("SUCCESS");
      }else{
        window.alert("ERROR");
      }
    })
  }

}
