import { Component, OnInit } from '@angular/core';
import { AuthServiceService } from '../auth-service.service'
import { ApiService } from '../api.service';
import { users } from '../users'
import { Router } from '@angular/router';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css']
})
export class LoginComponent implements OnInit {

  constructor(private _authService: AuthServiceService, private _router: Router, private apiService: ApiService) { }

  usr: users = {
    iduser: null,
    username:null,
    department:null,
    user_name:null,
    valid:null,
    password:null,
    id_role:null,
    signature:null
  };

  resUsr: users[] = [{
    iduser:null,
    username:null,
    department:null,
    user_name:null,
    valid:null,
    password:null,
    id_role:null,
    signature:null
  }];

  wrongAuth: boolean = false;
  inactiveAcc: boolean = false;

  ngOnInit() {
  }

  authUser() {
    this.wrongAuth = true;
    this.inactiveAcc = false;
    this._authService.changeAuth(false);
      this.apiService.authUsr(this.usr).subscribe((srsGet: users[]) => {
        this.resUsr = srsGet;
        if (this.resUsr[0].valid == "1") {
          this.wrongAuth = false;
          this._authService.changeAuth(true);
          this._authService.saveUsr(this.resUsr[0]);
          if(this.resUsr[0].department == "2"){
            this._router.navigate(['/rehome']);
          }else{
            if(this.resUsr[0].department == "4" || this.resUsr[0].department == "61"){
              this._router.navigate(['/achome']);
            }else{
              if(this.resUsr[0].department == "5" || this.resUsr[0].department == "27"){
                this._router.navigate(['/hrhome']);
              }else{
                if(this.resUsr[0].department == "28"){
                  this._router.navigate(['/pyhome']);
                }else{
                  if(this.resUsr[0].department == "29"){
                    this._router.navigate(['/fhome']);
                  }
                }
              }
            }
          }
        } else {
          if (this.resUsr[0].valid == "0") {
            this.inactiveAcc = true;
            this.wrongAuth = false;
            this._authService.changeAuth(false);
          };
        }
      });
  }

}
