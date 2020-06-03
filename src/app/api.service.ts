import { Injectable } from '@angular/core';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';

import { profiles } from './profiles';
import { process } from './process';
import { fullPreapproval, fullApplyentcontact, fullSchedulevisit, fullDoc_Proc, testRes, queryDoc_Proc, uploaded_documetns, search_parameters, new_hire, vew_hire_process, coincidences, employees, hrProcess } from './fullProcess';
import { process_templates, waves_template, hires_template, schedules, accounts, realTimeTrack, attendences, attendences_adjustment } from './process_templates';

import { Observable } from 'rxjs'; 
import { users } from './users';
import { applyent_contact, schedule_visit } from './addTemplate';
import { Data } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

prof:profiles[] = [];
id_profile:number;

PHP_API_SERVER = "localhost";

readProfiles():Observable<profiles[]>{
  return this.httpClient.get<profiles[]>(`${this.PHP_API_SERVER}/minearsol/read_profiles.php`);
}

readFilteredProfiles(parameters:search_parameters[]):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/getSerchApp.php`, parameters);
}

readApproved():Observable<profiles[]>{
  return this.httpClient.get<profiles[]>(`${this.PHP_API_SERVER}/minearsol/read_approved.php`);
}

readFilteredProfiles_Apr(parameters:search_parameters[]):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/getSerchApr.php`, parameters);
}

readRejected():Observable<profiles[]>{
  return this.httpClient.get<profiles[]>(`${this.PHP_API_SERVER}/minearsol/read_rejected.php`);
}

readFilteredProfiles_Den(parameters:search_parameters[]):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/getSerchDen.php`, parameters);
}

getWaves(){
  return this.httpClient.get<waves_template[]>(`${this.PHP_API_SERVER}/phpscripts/getwaves.php`);
}

getAcconts(){
  return this.httpClient.get<accounts[]>(`${this.PHP_API_SERVER}/phpscripts/getAccounts.php`);
}

updateWave(wv:waves_template){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateWaves.php`, wv);
}

updateSchedules(sch:schedules){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateSchedules.php`, sch); 
}

insertNewSchedule(sch:schedules){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertSchedule.php`, sch);
}

insertNewWave(wv:waves_template){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertWave.php`, wv);
}

getFilteredHires(wv:waves_template){
  return this.httpClient.post<hires_template[]>(`${this.PHP_API_SERVER}/phpscripts/getHires.php`, wv);
}

getSearchProfile(srch:any){
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/getProfilesFiltered.php`, srch);
}

getFilterSchHires(sch:schedules){
  return this.httpClient.post<hires_template[]>(`${this.PHP_API_SERVER}/phpscripts/getschHires.php`, sch);
} 

getUsers(wv:waves_template){
  return this.httpClient.post<users[]>(`${this.PHP_API_SERVER}/phpscripts/getUsers.php`, wv);
}

getSchedules(wv:waves_template){
  return this.httpClient.post<schedules[]>(`${this.PHP_API_SERVER}/phpscripts/getSchedules.php`, wv);
}

changeStatus(prof:profiles):Observable<number>{
  return this.httpClient.post<number>(`${this.PHP_API_SERVER}/minearsol/update_status.php`,prof);
}

getCoincidences(prof:profiles){
  return this.httpClient.post<coincidences[]>(`${this.PHP_API_SERVER}/phpscripts/getCoincidences.php`, prof);
}

getProfile(prof:profiles):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/minearsol/full_query.php`, prof);
}

authUsr(users:users):Observable<users[]>{
  return this.httpClient.post<users[]>(`${this.PHP_API_SERVER}/phpscripts/login.php`, users);
}

insProcess(proc:process):Observable<number>{
  return this.httpClient.post<number>(`${this.PHP_API_SERVER}/phpscripts/process_add.php`, proc);
}

getProcesses(proc:process):Observable<process[]>{
  return this.httpClient.post<process[]>(`${this.PHP_API_SERVER}/phpscripts/getProcesses.php`, proc);
}

getFullprocess(actualProc:fullPreapproval):Observable<fullPreapproval[]>{
  return this.httpClient.post<fullPreapproval[]>(`${this.PHP_API_SERVER}/phpscripts/getFullprocess.php`, actualProc);
}

getFullApplyentContact(actualProc:fullPreapproval):Observable<fullApplyentcontact[]>{
  return this.httpClient.post<fullApplyentcontact[]>(`${this.PHP_API_SERVER}/phpscripts/getFullApplyentContact.php`, actualProc);
}

getFullScheduleVisit(actualProc:fullPreapproval):Observable<fullSchedulevisit[]>{
  return this.httpClient.post<fullSchedulevisit[]>(`${this.PHP_API_SERVER}/phpscripts/getFullScheduleVisit.php`, actualProc);
}

getFullTestResults(actualProc:fullPreapproval):Observable<queryDoc_Proc[]>{
  return this.httpClient.post<queryDoc_Proc[]>(`${this.PHP_API_SERVER}/phpscripts/getFullTestResults.php`, actualProc);
}

getProctemplates(queryInfo:process):Observable<process_templates[]>{
  return this.httpClient.post<process_templates[]>(`${this.PHP_API_SERVER}/phpscripts/getProcessTemplates.php`, queryInfo);
}

getDocProc(proc:queryDoc_Proc):Observable<uploaded_documetns[]>{
  return this.httpClient.post<uploaded_documetns[]>(`${this.PHP_API_SERVER}/phpscripts/getDocProc.php`, proc);
}

getNewHireProc(proc:fullPreapproval){
  return this.httpClient.post<vew_hire_process>(`${this.PHP_API_SERVER}/phpscripts/getNewHiresProc.php`, proc);
}

downloadDoc(doc:uploaded_documetns):Observable<string>{
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/download_file.php`,doc);
}

insApplyentcontact(insInfo:applyent_contact):Observable<string>{
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertApplyentContact.php`, insInfo)
}

insSchedulevisit(insInfo:schedule_visit):Observable<string>{
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertScheduleVisit.php`, insInfo);
}

insDocProc_doc(frmData):Observable<testRes>{
  return this.httpClient.post<testRes>(`${this.PHP_API_SERVER}/phpscripts/uploadFile.php`,frmData);
}

insDocProc(isnInfo:fullDoc_Proc[]):Observable<string>{
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertUploadFile.php`, isnInfo);
}

insNewHireProc(nwHire:new_hire){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertHire.php`, nwHire);
}

updateInformation(qry:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateInformation.php`, qry);
}

//RealTime Track

getFilteredParam(filter:any){
  return this.httpClient.post<string[]>(`${this.PHP_API_SERVER}/phpscripts/getValfilter.php`, filter);
}

getrealTime(rlt:realTimeTrack){
  return this.httpClient.post<realTimeTrack[]>(`${this.PHP_API_SERVER}/phpscripts/getRealTime.php`, rlt);
}

downloadRealTimeReport(rlt:realTimeTrack){
  return this.httpClient.post<realTimeTrack[]>(`${this.PHP_API_SERVER}/phpscripts/exportRealTrack.php`, rlt);
}

//Recruitment Daily Report
getfilteredWaves(flt:any){
  return this.httpClient.post<waves_template[]>(`${this.PHP_API_SERVER}/phpscripts/getfilteredWaves.php`, flt);
}

//HR

getHiresAsEmployees(flt:any){
  return this.httpClient.post<hires_template[]>(`${this.PHP_API_SERVER}/phpscripts/getHiresToEmployees.php`, flt);
}

insertEmployees(emp:employees[]){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertEmployees.php`, emp);
}

getAttendences(flt:any){
  return this.httpClient.post<attendences[]>(`${this.PHP_API_SERVER}/phpscripts/getAttendences.php`, flt);
}

insertAttendences(att:attendences[]){
  return this.httpClient.post<attendences[]>(`${this.PHP_API_SERVER}/phpscripts/insertAttendences.php`, att);
}

updateWaveState(wv:waves_template){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateWaveState.php`, wv);
}

getallEmployees(){
  return this.httpClient.get<employees[]>(`${this.PHP_API_SERVER}/phpscripts/getallEmployees.php`);
}

getallHrProcesses(){
  return this.httpClient.get<hrProcess[]>(`${this.PHP_API_SERVER}/phpscripts/getallHrProcesses.php`);
}

getSearchEmployees(str:any){
  return this.httpClient.post<employees[]>(`${this.PHP_API_SERVER}/phpscripts/getFilteredEmployees.php`, str);
}

insertAttJustification(adj:attendences_adjustment){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertAttJustification.php`, adj);
}

getAttAdjustments(str:any){
  return this.httpClient.post<attendences_adjustment[]>(`${this.PHP_API_SERVER}/phpscripts/getAttAdjustments.php`, str);
}

  constructor(private httpClient:HttpClient) { }
}
