import { Injectable } from '@angular/core';
import { HttpClient, HttpParams, HttpHeaders } from '@angular/common/http';

import { profiles, profiles_family, profiles_histories } from './profiles';
import { process } from './process';
// tslint:disable-next-line:max-line-length
import { fullPreapproval, fullApplyentcontact, fullSchedulevisit, fullDoc_Proc, testRes, queryDoc_Proc, uploaded_documetns, search_parameters, new_hire, vew_hire_process, coincidences, employees, hrProcess, payment_methods } from './fullProcess';
import { process_templates, waves_template, hires_template, schedules, accounts, realTimeTrack, attendences, attendences_adjustment, vacations, leaves, disciplinary_processes, insurances, beneficiaries, terminations, reports, advances, rises, call_tracker, letters, supervisor_survey, judicials, irtra_requests, messagings, periods, deductions, debits, credits, payments, services, change_id, ot_manage, attendance_accounts, clients, marginalization, isr, payroll_values, advances_acc, payroll_values_gt, paid_attendances, payroll_resume, employees_Bonuses, reporters, ids_profiles, formerEmployer, accountingPolicies, AccountingAccounts, timekeeping_adjustments, policies, billing, billing_detail, sendmailRes, paystubview, contractCheck } from './process_templates';

import { Observable } from 'rxjs';
import { users } from './users';
import { applyent_contact, schedule_visit } from './addTemplate';
import { Data } from '@angular/router';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

prof:profiles[] = [];
id_profile:number;

//PHP_API_SERVER = environment.PHP_root; // Desarrollo
PHP_API_SERVER = "http://172.18.2.45";  // produccion

constructor(private httpClient:HttpClient) { }

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

getAccounts(){
  return this.httpClient.get<accounts[]>(`${this.PHP_API_SERVER}/phpscripts/getAccounts.php`);
}

getAccounting_Accounts() {
  return this.httpClient.get<AccountingAccounts[]>(`${this.PHP_API_SERVER}/phpscripts/getAccounting_Accounts.php`);
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

insertAccountingPolicies(any: any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertAccountingPolicies.php`, any);
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

getFamilies(profile:profiles):Observable<profiles_family[]>{
  return this.httpClient.post<profiles_family[]>(`${this.PHP_API_SERVER}/phpscripts/getfamilies.php`, profile);
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

exportVacationsReport(emp: employees[]) {
  return this.httpClient.post<employees[]>(`${this.PHP_API_SERVER}/phpscripts/exportVacationsReport.php`, emp);
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

getAllBonuses(nm:any) {
  return this.httpClient.post<employees_Bonuses[]>(`${this.PHP_API_SERVER}/phpscripts/getAllBonuses.php`,nm);
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

getIdEmployee(str:any){
  return this.httpClient.post<employees>(`${this.PHP_API_SERVER}/phpscripts/getIdEmployee.php`, str);
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

updateAdvances(adv:advances_acc){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateAdvances.php`, adv);
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

getAllPeriods(){
  return this.httpClient.get<periods[]>(`${this.PHP_API_SERVER}/phpscripts/getAllPeriods.php`);
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

closePeriodBonuses(period:periods) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/closePeriodBonuses.php`, period);
}

setClosePeriods(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/setClosePeriod.php`, any);
}

setCloseActualPeriods(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/setCloseActualPeriod.php`, any);
}

setCompleted(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/setCompleted.php`, any);
}

getDPAtt(any:any){
  return this.httpClient.post<disciplinary_processes[]>(`${this.PHP_API_SERVER}/phpscripts/getDPAtt.php`, any);
}

getAttPeriod(any:any){
  return this.httpClient.post<attendences[]>(`${this.PHP_API_SERVER}/phpscripts/getAttendancesPeriod.php`, any);
}

setPayment(payments:payments){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/setPayments.php`, payments);
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

getPaymentMethods(employee:any){
  return this.httpClient.post<payment_methods[]>(`${this.PHP_API_SERVER}/phpscripts/getPaymentMethods.php`, employee);
}

getServices(any:any){
  return this.httpClient.post<services[]>(`${this.PHP_API_SERVER}/phpscripts/getServices.php`,any);
}

insertService(service:services){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertService.php`, service);
}

deleteService(service: services) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/deleteService.php`, service);
}

updateService(service: services) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateService.php`, service);
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

getToValidate(){
  return this.httpClient.get<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/getValidate.php`);
}

getJobHistories(any:any){
  return this.httpClient.post<profiles_histories[]>(`${this.PHP_API_SERVER}/phpscripts/getJobHistories.php`, any);
}

insertMergeProfile(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertMergeProfile.php`, any);
}

getLastSeventh(payment:payments){
  return this.httpClient.post<payments>(`${this.PHP_API_SERVER}/phpscripts/getLastSeventh.php`, payment);
}

getOverlaps(any:any){
  return this.httpClient.post<employees[]>(`${this.PHP_API_SERVER}/phpscripts/getOverlap.php`, any);
}

getClients(){
  return this.httpClient.get<clients[]>(`${this.PHP_API_SERVER}/phpscripts/getClients.php`);
}

insertMarginalizations(margin:marginalization){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertMarginazations.php`, margin);
}

insertMarginalizationsDetails(margin:marginalization){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertMarginalizationDetails.php`, margin);
}

getMarginalizations(any:any){
  return this.httpClient.post<marginalization[]>(`${this.PHP_API_SERVER}/phpscripts/getMarginalizations.php`, any);
}

updateApproveOt(ot:ot_manage){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateApprovedOt.php`, ot);
}

updateVacations(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateVacations.php`, any);
}

updateLeaves(leave:leaves){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateLeaves.php`, leave);
}

updateSuspensions(susp:disciplinary_processes){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateSuspensions.php`, susp);
}

getIsr(period:periods){
  return this.httpClient.post<isr[]>(`${this.PHP_API_SERVER}/phpscripts/getIsr.php`, period);
}

getFilteredEmployees_ph(str:any){
  return this.httpClient.post<employees[]>(`${this.PHP_API_SERVER}/phpscripts/getFilteredEmployees_ph.php`, str);
}

getallEmployees_ph(dp:any){
  return this.httpClient.post<employees[]>(`${this.PHP_API_SERVER}/phpscripts/getallEmployees_ph.php`, dp)
}

getPeriods_ph(){
  return this.httpClient.get<periods[]>(`${this.PHP_API_SERVER}/phpscripts/getPeriods_ph.php`);
}

getCredits_ph(any:any){
  return this.httpClient.post<credits[]>(`${this.PHP_API_SERVER}/phpscripts/getCredits_ph.php`, any);
}

getDebits_ph(any:any){
  return this.httpClient.post<debits[]>(`${this.PHP_API_SERVER}/phpscripts/getDebits_ph.php`, any);
}

getPayments_ph(period:periods){
  return this.httpClient.post<payments[]>(`${this.PHP_API_SERVER}/phpscripts/getPayments_ph.php`, period);
}

insertCredits_ph(credits:credits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertCredits_ph.php`, credits);
}

insertDebits_ph(debits:debits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertDebits_ph.php`, debits);
}

insertPayrollValues(payroll:payroll_values){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPayrollValues_ph.php`,payroll);
}

getPayrollValues_ph(period:periods){
  return this.httpClient.post<payroll_values[]>(`${this.PHP_API_SERVER}/phpscripts/getPayrollValues_ph.php`, period);
}

getProfile_ph(prof:profiles):Observable<profiles[]>{
  return this.httpClient.post<profiles[]>(`${this.PHP_API_SERVER}/phpscripts/full_query_ph.php`, prof);
}

getFilteredPeriods_ph(any:any){
  return this.httpClient.post<periods>(`${this.PHP_API_SERVER}/phpscripts/getFilteredPeriods_ph.php`, any);
}

updateCredits(credits:credits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateCredits.php`, credits);
}

updateDebits(debits:debits){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateCredits.php`, debits);
}

insertPayments(payment:payments){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPayments.php`, payment);
}

updatehr_process(hrprocess:hrProcess){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updatehr_process.php`, hrprocess);
}

getHr_Processes(any:any){
  return this.httpClient.post<hrProcess>(`${this.PHP_API_SERVER}/phpscripts/getHr_Processes.php`, any);
}

getAdvancesAcc(emp:any){
  return this.httpClient.post<advances_acc[]>(`${this.PHP_API_SERVER}/phpscripts/getAdvancesAcc.php`, emp);
}

insertPayroll_values_gt(py:payroll_values_gt[]){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPayroll_values_gt.php`, py);
}

getPayroll_values_gt(period:periods){
  return this.httpClient.post<payroll_values_gt[]>(`${this.PHP_API_SERVER}/phpscripts/getPayroll_values_gt.php`, period);
}

insertPaidAttendances_gt(paid_attendances:paid_attendances[]){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPaidAttendances.php`, paid_attendances);
}

getPaidAttendances(period:periods){
  return this.httpClient.post<paid_attendances[]>(`${this.PHP_API_SERVER}/phpscripts/getPaidAttendances_gt.php`, period);
}

getTransfers(any:any){
  return this.httpClient.post<hrProcess>(`${this.PHP_API_SERVER}/phpscripts/getTransfers.php`, any);
}

updateFamily(family:profiles_family):Observable<string>{
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/update_family.php`, family);
}

delFamily(family:profiles_family):Observable<string>{
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/delete_family.php`, family);
}

createFamily(family:profiles_family):Observable<string>{
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insert_family.php`, family);
}

setRevertClosePeriods(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/setRevertPeriod.php`, any);
}

getClosingRise(any:any){
  return this.httpClient.post<rises>(`${this.PHP_API_SERVER}/phpscripts/getRises_closing.php`,any);
}

updatePeriods(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updatePeriodStatus.php`, any);
}

updateJudicials(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateJudicials.php`, any);
}

insertPayroll_resume(payroll_resume:payroll_resume[]){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertPayroll_resume.php`, payroll_resume);
}

getPayroll_resume(any:any){
  return this.httpClient.post<payroll_resume[]>(`${this.PHP_API_SERVER}/phpscripts/getPayroll_resume.php`, any);
}

updatePayroll_values(pyv:payroll_values_gt){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updatePayroll_values_gt.php`, pyv);
}

updatePaid_attendances(p_att:paid_attendances){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updatePaidAttendances.php`, p_att);
}

getReporter() {
  return this.httpClient.get<reporters[]>(`${this.PHP_API_SERVER}/phpscripts/getReporter.php`);
}

insertProfile(any:any) {
  return this.httpClient.post<ids_profiles>(`${this.PHP_API_SERVER}/phpscripts/insert_profile.php`, any);
}

insertProfileDetails(any: any) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/create_details.php`, any);
}

insertcontact(any: any) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/create_contact.php`, any);
}

insertjobhistory(any: any) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/create_job_history.php`, any);
}

getFormerEmployer() {
  return this.httpClient.get<formerEmployer[]>(`${this.PHP_API_SERVER}/phpscripts/getFormerEmployer.php`);
}

insertFormerEmployer(any: any) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertFormerEmployer.php`, any);
}

updateFormerEmployer(any: any) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/updateFormerEmployer.php`, any);
}

deleteFormerEmployer(any: any) {
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/deleteFormerEmployer.php`, any);
}

getAccountingPolicies(pol: policies) {
  if (pol.type=='false') {
    return this.httpClient.post<accountingPolicies[]>(`${this.PHP_API_SERVER}/phpscripts/getAccountingPolicies.php`, pol);
  } else {
    return this.httpClient.post<accountingPolicies[]>(`${this.PHP_API_SERVER}/phpscripts/getAccountingPoliciesMonthly.php`, pol);
  }
}

insertTkAdjustments(tk:timekeeping_adjustments){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertTkAdjustments.php`, tk);
}

getTkAdjustments(any:any){
  return this.httpClient.post<timekeeping_adjustments>(`${this.PHP_API_SERVER}/phpscripts/getTkAdjustments.php`, any);
}

getBilling(any:any){
  return this.httpClient.post<billing_detail>(`${this.PHP_API_SERVER}/phpscripts/getBilling.php`, any);
}

saveBilling(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertBilling.php`, any);
}

sendMail(any:any){
  return this.httpClient.post<paystubview>(`${this.PHP_API_SERVER}/phpscripts/sendmail.php`, any);
}

getPaystubDetails(periods:periods){
  return this.httpClient.post<paystubview[]>(`${this.PHP_API_SERVER}/phpscripts/getPaystubDetails.php`, periods);
}

sendMailTerm(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/sendmail_term.php`, any);
}

sendMailTransfer(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/sendmail_transfer.php`, any);
}

checkContract(any:any){
  return this.httpClient.post<contractCheck>(`${this.PHP_API_SERVER}/phpscripts/getCheckcontract.php`, any);
}

revertTransfer(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertCancelTransfer.php`, any);
}

revertTermination(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertCancelTerm.php`, any);
}

revertJustification(any:any){
  return this.httpClient.post<string>(`${this.PHP_API_SERVER}/phpscripts/insertCancelAdjustment.php`, any);
}

}


