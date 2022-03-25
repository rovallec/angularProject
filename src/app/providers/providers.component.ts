import { Component, OnInit } from '@angular/core';
import { isNull, isNullOrUndefined } from 'util';
import { ApiService } from '../api.service';
import { providers } from '../process_templates';

@Component({
  selector: 'app-providers',
  templateUrl: './providers.component.html',
  styleUrls: ['./providers.component.css']
})
export class ProvidersComponent implements OnInit {

  constructor(private apiService:ApiService) { }

  providers:providers[];
  filteredProviders:providers[];
  selectedProvider:providers = new providers;
  edit:boolean = false;
  searchValue:string = null;

  ngOnInit() {
    this.start();
  }

  start(){
    this.providers = [];
    this.filteredProviders =[];
    this.selectedProvider = new providers;
    this.edit = false;
    this.searchValue = null;
    this.apiService.getProviders().subscribe((prv:providers[])=>{
      this.providers = prv;
      this.filteredProviders = prv;
    })
  }

  setCurrent(prov:providers){
    this.selectedProvider = prov;
  }

  enableEdit(){
    if(this.edit){
      if(isNullOrUndefined(this.selectedProvider.idproviders)){
        this.apiService.insertProvider(this.selectedProvider).subscribe((str:string)=>{
          if(str=='1'){
            window.alert("Changes Successfully Recorder");
            document.getElementById('closeModal').click();
            this.start();
          }else{
            window.alert(str);
          }
        })
      }else{
      this.apiService.updateProvider(this.selectedProvider).subscribe((str:string)=>{
        if(str == '1'){
          window.alert("Changes Successfully Recorder");
          document.getElementById('closeModal').click();
          this.start();
        }else{
          window.alert(str);
        }
      })
    }
    }
    this.edit = !this.edit;
  }
  
  addNew(){
    this.selectedProvider = new providers;
    this.edit = true;
  }

  isNew(txt){
    return isNullOrUndefined(txt);
  }

  searchNow(){
    if(isNullOrUndefined(this.searchValue)){
      this.filteredProviders = this.providers;
    }else{
      this.filteredProviders = this.providers.filter(a=>a.cel.replace("-","").includes(this.searchValue) || a.conditions.replace("-","").includes(this.searchValue) || a.contact.replace("-","").includes(this.searchValue)
      || a.contract_end.replace("-","").includes(this.searchValue) || a.contract_start.replace("-","").includes(this.searchValue) || a.credit.replace("-","").includes(this.searchValue) || a.days.replace("-","").includes(this.searchValue)
      || a.email.replace("-","").includes(this.searchValue) || a.idproviders.replace("-","").includes(this.searchValue) || a.name.replace("-","").includes(this.searchValue) || a.no_iva.replace("-","").includes(this.searchValue) 
      || a.phone.replace("-","").includes(this.searchValue))
    }
    document.getElementById("close_modal").click();
  }

  cancelSearch(){
    this.searchValue = null;
    this.searchNow();
  }
}
