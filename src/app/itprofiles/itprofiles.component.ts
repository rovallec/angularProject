import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { ActivatedRoute } from '@angular/router';
import { AuthServiceService } from '../auth-service.service';
import { employees } from '../fullProcess';
import { profiles } from '../profiles';
import { alertModal, assets, asset_movements, movement_types } from '../process_templates';
import { isNullOrUndefined } from 'util';

@Component({
  selector: 'app-itprofiles',
  templateUrl: './itprofiles.component.html',
  styleUrls: ['./itprofiles.component.css']
})
export class ItprofilesComponent implements OnInit {

  constructor(private apiService:ApiService, private authUser:AuthServiceService, private route:ActivatedRoute) { }

  employee:employees = new employees;
  profile:profiles = new profiles;
  assigned_assets:asset_movements[] = [];
  shown_asset:asset_movements = new asset_movements;
  disable_add:boolean = false;
  movement_options:movement_types[] = [];
  unallocatedAssets:assets[] = [];
  assets_filtered:assets[] = [];
  assetSearch:string = null;
  assigned_asset:assets = new assets;
  alert:alertModal = new alertModal;
  unassignReason:string = '1';

  ngOnInit() {
    this.start();
  }

  start(){
    this.employee = new employees;
    this.profile = new profiles;
    this.assigned_assets = [];
    this.shown_asset = new asset_movements;
    this.disable_add = false;
    this.movement_options = [];
    this.unallocatedAssets = [];
    this.assets_filtered = [];
    this.assetSearch = null;
    this.assigned_asset = new assets;
    this.unassignReason = '1';

    this.apiService.getMovment_types().subscribe((mvmt:movement_types[])=>{
      this.movement_options = mvmt
    })

    this.apiService.getUnallocatedAssets().subscribe((assets:assets[])=>{
      this.unallocatedAssets = assets;
      this.assets_filtered = assets;
    })

    this.apiService.getSearchEmployees({dp:'all', filter:'idemployees', value:this.route.snapshot.paramMap.get('id'), rol:this.authUser.getAuthusr().id_role}).subscribe((emp:employees[])=>{
      this.employee = emp[0]
      let prof:profiles = new profiles;
      prof.idprofiles = emp[0].id_profile;
      this.apiService.getAssignedAssets({id:emp[0].idemployees}).subscribe((ass:asset_movements[])=>{
        this.assigned_assets = ass;
      })
      this.apiService.getProfile(prof).subscribe((profile:profiles[])=>{
        this.profile = profile[0];
      })
    })
  }

  applySelection(asset:asset_movements){
    this.disable_add = false;
    asset.active_selection = !asset.active_selection;
    this.assigned_assets.forEach(ass=>{
      if(ass.active_selection){
        this.disable_add = true;
      }
    })
  }

  showAsset(asset:asset_movements){
    this.shown_asset = asset;
    let element = document.getElementById('shownAssetBtn');
    element.click();
  }

  addAssignation(){
    this.shown_asset = new asset_movements;
    this.shown_asset.user_name = this.authUser.getAuthusr().user_name;
    this.shown_asset.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
  }

  filterAssets(){
    this.assets_filtered = this.unallocatedAssets.filter(a=>a.code.includes(this.assetSearch) || a.serial.includes(this.assetSearch) || a.model.includes(this.assetSearch) || a.brand.includes(this.assetSearch));
    this.assets_filtered.forEach(ass=>{
      ass.selected = false;
    })
  }

  activeSelection(asset:assets){
    this.assets_filtered.forEach(ass=>{
      if(ass.idassets != asset.idassets){
        ass.selected = false;
      }
    })
    asset.selected = !asset.selected;
    if(asset.selected){
      this.assigned_asset = asset;
    }else{
      this.assigned_asset = new assets;
    }
  }

  saveAssignation(){
    if(!isNullOrUndefined(this.assigned_asset.idassets)){
      let set_movement:asset_movements = new asset_movements;
      set_movement.idassets = this.assigned_asset.idassets;
      set_movement.idemployees = this.employee.idemployees;
      set_movement.movement = this.shown_asset.movement;
      set_movement.model = this.assigned_asset.movement
      set_movement.user_name = this.authUser.getAuthusr().iduser;
      set_movement.date = new Date().getFullYear() + "-" + (new Date().getMonth() + 1) + "-" + new Date().getDate();
      set_movement.notes = this.shown_asset.notes;
      set_movement.movement_status = '1';
      this.apiService.insertAssetAssignation(set_movement).subscribe((str:string)=>{
        if(str == '1'){
          this.alert.header = "Assignation Completed";
          this.alert.content = "Your assignation has been successfuly completed";
          let element = document.getElementById('dispay_alert');
          element.click();
        }else{
          this.alert.header = "Error trying to assign";
          this.alert.content = str;
          let element = document.getElementById('dispay_alert');
          element.click();
        }
        this.start();
      })
    }else{
      this.alert.header = "Error trying to assign";
      this.alert.content = "There's no asset selected to be assigned";
      let element = document.getElementById('dispay_alert');
      element.click();
    }
  }

  applyUnassignation(){
    let max = this.assigned_assets.filter(a=>a.active_selection).length;
    let cnt = 0;
    let st = '1';

    this.assigned_assets.forEach(asset=>{
      if(asset.active_selection){
        this.apiService.insertUnassignation({idassets:asset.idassets}).subscribe((str:string)=>{
          if(str != '1'){
            st = str;
          }
          cnt++;
          if(cnt == max){
            if(st == '1'){
              this.alert.header = "Assets Unassigned";
              this.alert.content = "Assets Successfully Unassigned";
            }else{
              this.alert.header = "Error Trying to Unissigning";
              this.alert.content = str;
            }
            let element = document.getElementById('dispay_alert');
            element.click();
            this.start();
          }
        })
      }
    })
  }
}
