import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { assets, asset_types } from '../process_templates';

@Component({
  selector: 'app-it-inventory-report',
  templateUrl: './it-inventory-report.component.html',
  styleUrls: ['./it-inventory-report.component.css']
})
export class ItInventoryReportComponent implements OnInit {

  constructor(private apiService:ApiService) { }

  asset_types:asset_types[] = [];
  unallocatedAssets:assets[] = [];
  allocatedAssets:assets[] = [];
  current_types:asset_types[] = [];

  ngOnInit() {
    this.asset_types = [];
    this.unallocatedAssets = [];
    this.allocatedAssets = [];
    this.current_types = [];
    this.start();
  }

  start(){
    this.apiService.getAssetTypes().subscribe((ass_types:asset_types[])=>{
      this.asset_types = ass_types;
      this.setCurrent(1);
    })
    this.apiService.getUnallocatedAssets().subscribe((ass:assets[])=>{
      this.unallocatedAssets = ass;
      this.setCurrent(1);
    })
    this.apiService.getAllocatedAssets().subscribe((assets:assets[])=>{
      this.allocatedAssets = assets;
      this.setCurrent(1);
    })
  }

  setCurrent(n:number){
    this.current_types = [];
    if(n == 1){
      this.asset_types.forEach(type=>{
        let c:asset_types = new asset_types;
        c.name = type.name;
        c.count = this.unallocatedAssets.filter(a => a.type_name == c.name).length.toFixed(0);
        this.current_types.push(c);
      })
    }else if(n == 2){
      this.asset_types.forEach(type=>{
        let c:asset_types = new asset_types;
        c.name = type.name;
        c.count = this.allocatedAssets.filter(a => a.type_name == c.name).length.toFixed(0);
        this.current_types.push(c);
      })
    }
  }

}
