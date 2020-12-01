import { Component } from '@angular/core';
import { AuthServiceService } from './auth-service.service';
import {Form} from '@angular/forms'
import { users } from './users';
import { ApiService } from './api.service';


@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.css']
})
export class AppComponent {
  title = 'main';

  autUsr:users = {
    iduser:'N/A',
    username:'N/A',
    department:'N/A',
    user_name:'N/A',
    valid:'N/A',
    password:'N/A',
    id_role:'N/A',
    signature:'N/A'
  };
  selectedOption:string = 'HOME';
  uploadFile:string = null;

  constructor(private authSrv:AuthServiceService, private apiService:ApiService){
  }

  getAuth(){
    return this.authSrv.isAuthenticated();
  }

  getSaved(){
    if(this.authSrv.isAuthenticated()){
      return this.authSrv.getAuthusr();
    }
  };
  SetSel(sel:string){
    this.selectedOption = sel;
  }

  onChange(event){
    let formData = new FormData;
    formData.append('process', 'updateSignature');
    formData.append('file1', event.target.files[0]);
    formData.append('user', this.authSrv.getAuthusr().iduser);
    formData.append('profile', 'null');
    this.apiService.insDocProc_doc(formData).subscribe((str:any)=>{

    })
  }

  getEmployeeData(){
    window.open("http://200.94.251.67/phpscripts/exportEmployeesData.php", "_blank");
  }
}
