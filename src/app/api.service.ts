import { Injectable } from '@angular/core';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';

import { profiles } from './profiles';
import { process } from './process';
import { fullPreapproval, fullApplyentcontact, fullSchedulevisit, fullDoc_Proc, testRes, queryDoc_Proc, uploaded_documetns, search_parameters, new_hire, vew_hire_process, coincidences, employees, hrProcess, payment_methods } from './fullProcess';
import { process_templates, waves_template, hires_template, schedules, accounts, realTimeTrack, attendences, attendences_adjustment, vacations, leaves, disciplinary_processes, insurances, beneficiaries, terminations, reports, advances, rises, call_tracker, letters, supervisor_survey, judicials, irtra_requests, messagings, periods, deductions, debits, credits, payments, services, change_id, ot_manage, attendance_accounts } from './process_templates';

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

PHP_API_SERVER = "http://200.94.251.67/test_phpscripts";

readProfiles():Observable<profiles[]>{
  return this.httpClient.get<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/read_profiles.php`);
}

readFilteredProfiles(parameters:search_parameters[]):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/getSerchApp.php`, parameters);
}

readApproved():Observable<profiles[]>{
  return this.httpClient.get<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/read_approved.php`);
}

readFilteredProfiles_Apr(parameters:search_parameters[]):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/getSerchApr.php`, parameters);
}

readRejected():Observable<profiles[]>{
  return this.httpClient.get<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/read_rejected.php`);
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
  return this.httpClient.post<number>(`${this.PHP_API_SERVER}/phpscripts/update_status.php`,prof);
}

getCoincidences(prof:profiles){
  return this.httpClient.post<coincidences[]>(`${this.PHP_API_SERVER}/phpscripts/getCoincidences.php`, prof);
}

getProfile(prof:profiles):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/full_query.php`, prof);
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

//Edit Profile
updateProfile(prof:profiles){
  return this.httpClient.post<profiles>(`${this.PHP_API_SERVER}/phpscripts/update_profile.php`, prof);
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

getallEmployees(nm:any){
  return this.httpClient.post<employees[]>(`${this.PHP_API_SERVER}/phpscripts/getallEmployees.php`,nm);
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

getAttAdjustment(str:any){
  return this.httpClient.post<attendences_adjustment>(`${this.PHP_API_SERVER}/phpscripts/getAttAdjustment.php`, str);
}

getVacations(str:any){
  return this.httpClient.post<vacations[]>(`${this.PHP_API_SERVER}/phpscripts/getVacations.php`,str);
}

insertVacations(vacation:vacations){
  return this.httpClient.post<vacations>(`${this.PHP_API_SERVER}/phpscripts/insertVacations.php`, vacation);
}

getLeaves(str:any){
  return this.httpClient.post<leaves[]>(`${this.PHP_API_SERVER}/phpscripts/getLeaves.php`, str);
}

insertLeaves(leaves:leaves){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertLeave.php`, leaves);
}

getEmployeeId(str:any){
  return this.httpClient.post<employees>(`${this.PHP_API_SERVER}/phpscripts/getEmployeeId.php`, str);
}

getApprovers(){
  return this.httpClient.get<users[]>(`${this.PHP_API_SERVER}/phpscripts/getApprovers.php`);
}

insertDisciplinary_Request(str:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDisciplinary_Request.php`, str)
}

insertDisciplinary_Process(str:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDisciplinary_Process.php`, str)
}

getStaffPeople(){
  return this.httpClient.get<users[]>(`${this.PHP_API_SERVER}/phpscripts/getStaffPeople.php`)
}

getDisciplinaryProcesses(str:any){
  return this.httpClient.post<disciplinary_processes[]>(`${this.PHP_API_SERVER}/phpscripts/getDisciplinaryProcesses.php`, str)
}

insertDP(dp:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDP.php`, dp);
}

insertDPA(dp:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDPA.php`, dp);
}

insertDPS(dp:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDPS.php`, dp);
}

insertDPSA(dp:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDPSA.php`, dp);
}

getInsurances(str:any){
  return this.httpClient.post<insurances>(`${this.PHP_API_SERVER}/phpscripts/getInsurances.php`, str);
}

getBeneficiaries(str:any){
  return this.httpClient.post<beneficiaries[]>(`${this.PHP_API_SERVER}/phpscripts/getBeneficiaries.php`, str);
}

insertInsurances(ins:insurances){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertInsurance.php`, ins);
}

insertBeneficiaryes(str:beneficiaries){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertBeneficiary.php`, str);
}

updateInsurance(ins:insurances){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateInsurances.php`, ins)
}

getTemplates(){
  return this.httpClient.get<process[]>(`${this.PHP_API_SERVER}/phpscripts/getTemplates.php`);
}

insertProc(proc:process){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertHr_Process.php`, proc);
}

insertTerm(proc:terminations){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertTerminations.php`, proc);
}

getProcRecorded(id:any){
  return this.httpClient.post<process[]>(`${this.PHP_API_SERVER}/phpscripts/getProcRecroded.php`, id);
}

getTerm(proc:process){
  return this.httpClient.post<terminations>(`${this.PHP_API_SERVER}/phpscripts/getTerm.php`, proc);
}

insertReport(rpr:reports){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertRerpot.php`, rpr);
}

getRerpot(proc:process){
  return this.httpClient.post<reports>(`${this.PHP_API_SERVER}/phpscripts/getRerport.php`, proc);
}

insertAdvances(adv:advances){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertAdvances.php`, adv);
}

getAdvances(proc:process){
  return this.httpClient.post<advances>(`${this.PHP_API_SERVER}/phpscripts/getAdvances.php`, proc);
}

insertRise(rs:rises){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertRises.php`, rs);
}

getRises(proc:process){
  return this.httpClient.post<rises>(`${this.PHP_API_SERVER}/phpscripts/getRises.php`, proc);
}

insertCallTracker(rs:call_tracker){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertCallTracker.php`, rs);
}

getCallTracker(proc:process){
  return this.httpClient.post<call_tracker>(`${this.PHP_API_SERVER}/phpscripts/getCallTracker.php`, proc);
}

insertLetters(lt:letters){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertLetters.php`, lt);
}

getLetters(proc:process){
  return this.httpClient.post<letters>(`${this.PHP_API_SERVER}/phpscripts/getLetters.php`, proc);
}

getTermdt(emp:employees){
  return this.httpClient.post<terminations>(`${this.PHP_API_SERVER}/phpscripts/getTermdt.php`, emp);
}

insertSurvey(srv:supervisor_survey){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertSurvey.php`, srv);
}

getSurvey(proc:process){
  return this.httpClient.post<supervisor_survey>(`${this.PHP_API_SERVER}/phpscripts/getSurvey.php`, proc);
}

getfilteredAccounts(id:any){
  return this.httpClient.post<accounts[]>(`${this.PHP_API_SERVER}/phpscripts/getfilteredAccounts.php`, id);
}

insertTransfer(employee:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertTransfer.php`, employee);
}

insertJudicials(judicials:judicials){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertJudicials.php`, judicials);
}

getJudicials(proc:process){
  return this.httpClient.post<judicials>(`${this.PHP_API_SERVER}/phpscripts/getJudicials.php`, proc);
}

insertIrtra_request(irtraRquest:irtra_requests){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertIrtra_requests.php`, irtraRquest);
}

getIrtra_request(proc:process){
  return this.httpClient.post<irtra_requests>(`${this.PHP_API_SERVER}/phpscripts/getIrtra_requests.php`, proc);
}

updateIrtra(prof:profiles){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateIrtra.php`, prof);
}

updateIgss(prof:profiles){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateIgss.php`, prof);
}

insertMessagings(msg:messagings){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertMessagings.php`, msg);
}

getMessagings(proc:process){
  return this.httpClient.post<messagings>(`${this.PHP_API_SERVER}/phpscripts/getMessagings.php`, proc);
}

updateEmployee(emp:employees){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateEmployees.php`, emp);
}

updateDP(dp:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateDP.php`, dp);
}

getPeriods(){
  return this.httpClient.get<periods[]>(`${this.PHP_API_SERVER}/phpscripts/getPeriods.php`);
}

getDeductions(any:any){
  return this.httpClient.post<deductions[]>(`${this.PHP_API_SERVER}/phpscripts/getDeductions.php`,any);
}

getFilteredPeriods(any:any){
  return this.httpClient.post<periods>(`${this.PHP_API_SERVER}/phpscripts/getFilteredPeriods.php`, any);
}

getDebits(any:any){
  return this.httpClient.post<debits[]>(`${this.PHP_API_SERVER}/phpscripts/getDebits.php`, any);
}

getCredits(any:any){
  return this.httpClient.post<credits[]>(`${this.PHP_API_SERVER}/phpscripts/getCredits.php`, any);
}

getFilteredDeductions(any:any){
  return this.httpClient.post<deductions[]>(`${this.PHP_API_SERVER}/phpscripts/getFilteredDeductions.php`, any);
}

getPayments(period:periods){
  return this.httpClient.post<payments[]>(`${this.PHP_API_SERVER}/phpscripts/getPayments.php`, period);
}

getAutoAdjustments(any:any){
  return this.httpClient.post<attendences_adjustment[]>(`${this.PHP_API_SERVER}/phpscripts/getAutoAdjustments.php`, any);
}

insertCredits(credits:credits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertCredits.php`, credits);
}

insertDebits(debits:credits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDebits.php`, debits);
}

closePeriod(period:periods){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/closePeriod.php`, period);
}

getDPAtt(any:any){
  return this.httpClient.post<disciplinary_processes[]>(`${this.PHP_API_SERVER}/phpscripts/getDPAtt.php`, any);
}

getAttPeriod(any:any){
  return this.httpClient.post<attendences[]>(`${this.PHP_API_SERVER}/phpscripts/getAttendancesPeriod.php`, any);
}

insertPayment(payments:payments){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPayments.php`, payments);
}

getJudicialDiscounts(any:any){
  return this.httpClient.post<judicials[]>(`${this.PHP_API_SERVER}/phpscripts/getJudicialDiscounts.php`, any);
}

getServicesDiscounts(any:any){
  return this.httpClient.post<services[]>(`${this.PHP_API_SERVER}/phpscripts/getServicesDiscounts.php`, any);
}

updateServices(service:services){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateServices.php`, service);
}

insertPushedCredit(credit:credits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPushedCredit.php`, credit);
}

insertPushedDebit(debit:credits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPushedDebit.php`, debit);
}

updateBank(hire:hires_template){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateBank.php`, hire);
}

insertPaymentMethod(payment_methods:payment_methods){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPaymentMethod.php`, payment_methods);
}

getPushedCredits(credit:credits){
  return this.httpClient.post<credits>(`${this.PHP_API_SERVER}/phpscripts/getPushedCredits.php`, credit);
}

getPushedDebits(debit:debits){
  return this.httpClient.post<credits>(`${this.PHP_API_SERVER}/phpscripts/getPushedDebits.php`, debit);
}

getPaymentMethods(employee:employees){
  return this.httpClient.post<payment_methods[]>(`${this.PHP_API_SERVER}/phpscripts/getPaymentMethods.php`, employee);
}

getServices(any:any){
  return this.httpClient.post<services[]>(`${this.PHP_API_SERVER}/phpscripts/getServices.php`,any);
}

insertService(service:services){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertService.php`, service);
}

getFacilitesTemplate(){
  return this.httpClient.get<process_templates[]>(`${this.PHP_API_SERVER}/phpscripts/getFacilitiesTemplates.php`);
}

getPayrollTemplates(){
  return this.httpClient.get<process_templates[]>(`${this.PHP_API_SERVER}/phpscripts/getPayrollTemplates.php`);
}

insertChangeClientID(change:change_id){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertChangeClientID.php`, change)
}

insertApprovedOt(ot:ot_manage){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertApprovedOt.php`, ot);
}

getApprovedOt(ot:ot_manage){
  return this.httpClient.post<ot_manage>(`${this.PHP_API_SERVER}/phpscripts/getApprovedOt.php`, ot);
}

updateAttendances(att:attendences){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateAttendance.php`, att);
}

getAttAccounts(any:any){
  return this.httpClient.post<attendance_accounts[]>(`${this.PHP_API_SERVER}/phpscripts/getAttAccounts.php`,any);
}

getAttMissing(any:any){
  return this.httpClient.post<employees[]>(`${this.PHP_API_SERVER}/phpscripts/getAttMissing.php`, any);
}
  constructor(private httpClient:HttpClient) { }
}
