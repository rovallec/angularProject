import { Component } from '@angular/core';
import { AuthServiceService } from './auth-service.service';
import {Form} from '@angular/forms'
import { users } from './users';
import { ApiService } from './api.service';
import { ActivatedRoute } from '@angular/router';


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

  constructor(private authSrv:AuthServiceService, private apiService:ApiService, private route: ActivatedRoute){
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

  async getEmployeeData(){
    this.SetSel("EMPD");
    try {
          window.open(this.apiService.PHP_API_SERVER + "/phpscripts/exportEmployeesData.php", "_blank");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }


  async getExceptions(){
    let dt:Date = new Date();
    let start:string = null;
    let end:string = null;

    if(dt.getDate() <= 16){
      start = dt.getFullYear().toString() + "-" + (dt.getMonth() + 1).toString().padStart(2,"0") + "-01";
      end = dt.getFullYear().toString() + "-" + (dt.getMonth() + 1).toString().padStart(2,"0")  + "-15";
    }else{
      start = dt.getFullYear().toString() + "-" + (dt.getMonth() + 1).toString().padStart(2,"0") + "-16";
      let nwDate:Date = new Date(dt.getFullYear(), (dt.getMonth() + 1),0);
      end = nwDate.getFullYear().toString() + "-" + (dt.getMonth() + 1).toString().padStart(2,"0") + "-" + nwDate.getDate().toString();
    }
    this.SetSel("EXPEX");
    try {
      window.open(this.apiService.PHP_API_SERVER + "/phpscripts/exportExceptions_tk.php?start=" + start + "&end=" + end, "_blank");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  async getServicesReport(){
    this.SetSel("SERVR");
    try {
      window.open(this.apiService.PHP_API_SERVER + "/phpscripts/exportServicesF.php", "_blank");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }

  async exportAdvancesReport(){
    this.SetSel("ADVREP");
    try {
      window.open(this.apiService.PHP_API_SERVER + "/phpscripts/exportAdvancesReport.php", "_blank");
    } catch (error) {
      alert("It could not to generate the report.");
    } finally {
      await new Promise( resolve => setTimeout(resolve, 1000));
      window.confirm("The report has been generated.");
    }
  }
}
