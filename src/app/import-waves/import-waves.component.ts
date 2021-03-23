import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts, clients, Fecha, reporters, waves_template, full_profiles, schedules, ids_profiles } from '../process_templates';
import * as XLSX from 'xlsx';
import { isNullOrUndefined } from 'util';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { profiles_family, profiles_histories } from '../profiles';
import { exception } from 'console';

@Component({
  selector: 'app-import-waves',
  templateUrl: './import-waves.component.html',
  styleUrls: ['./import-waves.component.css']
})
export class ImportWavesComponent implements OnInit {
  waves: waves_template = new waves_template;
  schedule: schedules = new schedules;
  clients: clients[] = [];
  selectedClient: string = null;
  accounts: accounts[] = [];
  selectedAccount: accounts = new accounts;
  isLoading: boolean = true;
  finished: boolean = true;
  progress: number = 0;
  max_progress: number = 0;
  step: string = 'Cargando...';
  reporters: reporters[] = [];
  actualReporter: reporters = new reporters;
  showdiv: boolean = false;
  date: string = null;
  start_time: string = null;
  end_time: string = null;
  days_off: string = null;  
  // file
  file: any;
  completed: boolean = false;
  arrayBuffer: any;
  filelist: any;
  importType: string = null;
  importString: string = null;
  importEnd: boolean = false;
  fullprofiles: full_profiles[] = [];

  constructor(public apiServices: ApiService) { }

  ngOnInit() {
    this.start();
  }

  start() {
    let fecha: Fecha = new Fecha;
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })
    this.getReporter();
    this.date = fecha.today;    
    this.isLoading = false;
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      acc.forEach(account => {
        if (account.id_client == cl) {
          this.accounts.push(account);
        }
      });
      this.selectedAccount = this.accounts[0];
    })
  }

  isEmpty(str: string) {
    if ((str=='')||(isNullOrUndefined(str))) {
      return true;
    } else {
      return false;
    }    
  }

  saveWave() {
    let error: boolean = false;
    // grabar la wave y todo el sistema.
    if (this.isEmpty(this.start_time)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to record changes without established start schedules.");
    }
    if (this.isEmpty(this.end_time)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to record changes without established end schedules.");
    }
    if (this.isEmpty(this.date)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to save changes without the date.");
    }
    if (this.isEmpty(this.actualReporter.username)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to record changes without established reporter.");
    }
    if (this.isEmpty(this.waves.name)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to save changes without the name of the wave.");
    }
    if (this.isEmpty(this.waves.prefix)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to save changes without the prefix of the wave.");
    }
    if (this.isEmpty(this.waves.job)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to save changes without the job of the wave.");
    }
    if (this.isEmpty(this.days_off)) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to save changes without the days off.");
    }
    if (this.fullprofiles.length==0) {
      error = true;
      window.alert("An error has occured:\n" + "It is not possible to save changes without profiles to create.");
    }

    if (!error) {
      // ya hechas las validaciones correspondientes envia la información a grabarse en la BD.

      this.apiServices.insertNewWave(this.waves).subscribe((idwave: string) => {
        this.waves.idwaves = idwave;        
        this.schedule.id_wave = this.waves.idwaves;
        this.apiServices.insertNewSchedule(this.schedule).subscribe((idschedules: string) => {
          this.schedule.idschedules = idschedules;
          this.fullprofiles.forEach(element => {
            element.wave = this.waves;
            element.schedule = this.schedule;
            element.id_wave = this.waves.idwaves;
            element.id_schedule = this.schedule.idschedules;
            element.id_user = this.actualReporter.idUser;
            element.reports_to = this.actualReporter.idUser;
            this.apiServices.insertProfile(element).subscribe((ids: ids_profiles) => {
              element.idprofiles = ids[0];
              element.id_profile = ids[0];
              element.contact_detail.id_profile = ids[0];
              element.employee.id_profile = ids[0];
              this.apiServices.insertProfileDetails(element).subscribe((_str: string) => {
                this.apiServices.insertcontact(element.contact_detail).subscribe((_str: string) => {
                  element.family.forEach(fam => {
                    this.apiServices.createFamily(fam).subscribe((_str: string) => {
                      element.job_history.forEach(job => {
                        this.apiServices.insertjobhistory(job).subscribe((_str: string) => {
                          window.alert("All documents saved correctly.");
                        })
                      })
                    })
                  })
                })
              })
            })
          })
        })
      })
    }
  }

  setSchedule() {
    this.schedule.start_time = this.start_time;
    this.schedule.end_time = this.end_time;
    this.schedule.days_off = this.days_off;
    this.waves.trainning_schedule = this.start_time + ' - ' + this.end_time;
    this.schedule.schedule_name = 'REF';

  }

  cancelWave() {
    // Cancelar.
  }

  setAccount(acc) {
    this.selectedAccount = acc;
    // set payments
  }

  getReporter() {
    let i = 0;
    this.apiServices.getReporter().subscribe((rep: reporters[]) => {
      rep.forEach(reporter => {
        this.reporters[i] = reporter;
        i++;
      })
    })
  }

  setReporter(reporters: reporters) {
    this.actualReporter = reporters;
  }

  validateEmptyStr(Adata: string): string{
    if (isNullOrUndefined(Adata)) {
      Adata = '';
    }
    return Adata;
  }

  addfile(event) {
    let fecha: Fecha = new Fecha;    
    let ldate: Date = new Date;
    this.file = event.target.files[0];

    let fileReader = new FileReader();
    let profilef: full_profiles = new full_profiles;
    let count: number = 0;
    
    this.waves.id_account = this.selectedAccount.idaccounts;
    this.waves.starting_date = fecha.today;
    this.waves.end_date = fecha.today;
    this.waves.ops_start = this.date;
    this.waves.state = '1,1,1,1';
    // se setean valores default ya que no afecta el salario del empleado. Solo se usa para crear la Wave;
    this.waves.base_payment = '2825.10';
    this.waves.productivity_payment = '2174.90';
    this.waves.max_recriut = '0';
    this.waves.hires = '0';

    try {
      fileReader.readAsArrayBuffer(this.file);
      fileReader.onload = (e) => {
        if (!this.completed) {
          this.arrayBuffer = fileReader.result;
          let num: string = '';
          var data = new Uint8Array(this.arrayBuffer);
          var arr = new Array();
          for (var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
          var bstr = arr.join("");
          var workbook = XLSX.read(bstr, { type: "binary" });
          var first_sheet_name = workbook.SheetNames[0];
          var worksheet = workbook.Sheets[first_sheet_name];
          let sheetToJson = XLSX.utils.sheet_to_json(worksheet, { raw: true });
          this.max_progress = sheetToJson.length;
          this.waves.max_recriut = this.max_progress.toString();
          this.waves.hires = this.waves.max_recriut;
          this.schedule.days_off = this.days_off;
          this.schedule.actual_count = this.waves.max_recriut;
          this.schedule.max_count = this.waves.max_recriut;
          this.schedule.state = '0';
          this.finished = false;
          this.isLoading = true;


          sheetToJson.forEach(element => {
            profilef = new full_profiles;
            let fam: profiles_family = new profiles_family;
            let job: profiles_histories = new profiles_histories;
            if (count < 10) {
              num = '0' + count.toString();
            } else {
              num = count.toString();
            }            
            profilef.No = num;
            profilef.wave = this.waves;
            profilef.loaded = 'false';
            profilef.schedule = this.schedule;
            profilef.nearsol_id = profilef.wave.prefix + profilef.No;            
            // profiles
            profilef.tittle = this.validateEmptyStr(element['tittle']);
            profilef.first_name = this.validateEmptyStr(element['first_name']);
            profilef.second_name = this.validateEmptyStr(element['second_name']);
            profilef.first_lastname = this.validateEmptyStr(element['first_lastname']);
            profilef.second_lastname = this.validateEmptyStr(element['second_lastname']);
            ldate = new Date(this.validateEmptyStr(element['day_of_birth']));
            profilef.day_of_birth = fecha.transform(ldate);
            profilef.nationality = this.validateEmptyStr(element['nationality']);
            profilef.marital_status = this.validateEmptyStr(element['marital_status']);
            profilef.dpi = this.validateEmptyStr(element['dpi']);
            profilef.nit = this.validateEmptyStr(element['nit']);
            profilef.igss = this.validateEmptyStr(element['igss']);
            profilef.irtra = this.validateEmptyStr(element['irtra']);
            profilef.gender = this.validateEmptyStr(element['gender']);
            profilef.etnia = this.validateEmptyStr(element['etnia']);
            profilef.profesion = this.validateEmptyStr(element['profesion']);
            profilef.birth_place = this.validateEmptyStr(element['birth_place']);
            profilef.account_type = this.validateEmptyStr(element['account_type']);
            profilef.account = this.validateEmptyStr(element['account']);
            profilef.bank = this.validateEmptyStr(element['bank']);
            // employees
            profilef.employee.job = this.validateEmptyStr(element['job']);
            profilef.employee.base_payment = this.validateEmptyStr(element['base_payment']);
            profilef.employee.productivity_payment = this.validateEmptyStr(element['productivity_payment']);
            profilef.employee.platform = this.validateEmptyStr(element['platform']);
            profilef.employee.hiring_date = this.date;
            profilef.employee.reporter = this.actualReporter.idUser;
            profilef.employee.client_id = profilef.nearsol_id;
            profilef.employee.id_account = this.selectedAccount.idaccounts;
            // contact_details            
            profilef.contact_detail.primary_phone = this.validateEmptyStr(element['primary_phone']);
            profilef.contact_detail.secondary_phone = this.validateEmptyStr(element['secondary_phone']);
            profilef.contact_detail.address = this.validateEmptyStr(element['address']);
            profilef.contact_detail.city = this.validateEmptyStr(element['city']);
            profilef.contact_detail.email = this.validateEmptyStr(element['email']);
            // emergency_details
            profilef.emergency_first_name = this.validateEmptyStr(element['e_first_name']);
            profilef.emergency_second_name = this.validateEmptyStr(element['e_second_name']);
            profilef.emergency_first_lastname = this.validateEmptyStr(element['e_first_lastname']);
            profilef.emergency_second_lastname = this.validateEmptyStr(element['e_second_lastname']);
            profilef.emergency_phone = this.validateEmptyStr(element['e_phone']);
            profilef.emergency_relationship = this.validateEmptyStr(element['e_relationship']);
            // medical_details
            profilef.medical_treatment = this.validateEmptyStr(element['medical_treatment']);
            profilef.medical_prescription = this.validateEmptyStr(element['medical_prescription']);
            // families
            if ((element['f1_first_name'] == '') && (element['f1_second_name'] == '') && (element['f1_first_last_name'] == '') && (element['f1_second_last_name'] == '')) {
              return;
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f1_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f1_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f1_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f1_second_last_name']);
              fam.affinity_phone = this.validateEmptyStr(element['f1_phone']);
              fam.affinity_relationship = this.validateEmptyStr(element['f1_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f1_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }
            
            if ((element['f2_first_name'] == '') && (element['f2_second_name'] == '') && (element['f2_first_last_name'] == '') && (element['f2_second_last_name'] == '')) {
              return;
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f2_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f2_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f2_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f2_second_last_name']);
              fam.affinity_phone = this.validateEmptyStr(element['f2_phone']);
              fam.affinity_relationship = this.validateEmptyStr(element['f2_relationship']);
              fam.affinity_relationship = this.validateEmptyStr(element['f1_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f2_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }
            
            if ((element['f3_first_name'] == '') && (element['f3_second_name'] == '') && (element['f3_first_last_name'] == '') && (element['f3_second_last_name'] == '')) {
              return;
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f3_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f3_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f3_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f3_second_last_name']);
              fam.affinity_phone = this.validateEmptyStr(element['f3_phone']);
              fam.affinity_relationship = this.validateEmptyStr(element['f3_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f3_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }
            
            if ((element['f4_first_name'] == '') && (element['f4_second_name'] == '') && (element['f4_first_last_name'] == '') && (element['f4_second_last_name'] == '')) {
              return;
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f4_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f4_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f4_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f4_second_last_name']);
              fam.affinity_phone = this.validateEmptyStr(element['f4_phone']);
              fam.affinity_relationship = this.validateEmptyStr(element['f4_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f4_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }
            
            if ((element['f5_first_name'] == '') && (element['f5_second_name'] == '') && (element['f5_first_last_name'] == '') && (element['f5_second_last_name'] == '')) {
              return;
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f5_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f5_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f5_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f5_second_last_name']);
              fam.affinity_phone = this.validateEmptyStr(element['f5_phone']);
              fam.affinity_relationship = this.validateEmptyStr(element['f5_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f5_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }            
            // job_histories
            if ((element['jh1_company'] == '') && (element['jh1_date_joining'] = '') && (element['jh1_date_end'] == '') &&
              (element['jh1_reference_name'] == '') && (element['jh1_reference_phone'] == '')) {
              return;
            } else {
              job.company = this.validateEmptyStr(element['jh1_company']);
              ldate = new Date(this.validateEmptyStr(element['jh1_date_joining']));
              job.date_joining = fecha.transform(ldate);
              ldate = new Date(this.validateEmptyStr(element['jh1_date_end']));
              job.date_end = fecha.transform(ldate);
              job.date_end = this.validateEmptyStr(element['jh1_date_end']);
              job.position = this.validateEmptyStr(element['jh1_position']);
              job.reference_name = this.validateEmptyStr(element['jh1_reference_name']);
              job.reference_lastname = this.validateEmptyStr(element['jh1_reference_lastname']);
              job.reference_position = this.validateEmptyStr(element['jh1_reference_position']);
              job.reference_email = this.validateEmptyStr(element['jh1_reference_email']);
              job.reference_phone = this.validateEmptyStr(element['jh1_reference_phone']);
              job.working = this.validateEmptyStr(element['jh1_working']);
              profilef.job_history.push(job);
            }
            
            if ((element['jh2_company'] == '') && (element['jh2_date_joining'] = '') && (element['jh2_date_end'] == '') &&
              (element['jh2_reference_name'] == '') && (element['jh2_reference_phone'] == '')) {
              return;
            } else {
              job = new profiles_histories;
              job.company = this.validateEmptyStr(element['jh2_company']);
              ldate = new Date(this.validateEmptyStr(element['jh2_date_joining']));
              job.date_joining = fecha.transform(ldate);
              ldate = new Date(this.validateEmptyStr(element['jh2_date_end']));
              job.date_end = fecha.transform(ldate);
              job.position = this.validateEmptyStr(element['jh2_position']);
              job.reference_name = this.validateEmptyStr(element['jh2_reference_name']);
              job.reference_lastname = this.validateEmptyStr(element['jh2_reference_lastname']);
              job.reference_position = this.validateEmptyStr(element['jh2_reference_position']);
              job.reference_email = this.validateEmptyStr(element['jh2_reference_email']);
              job.reference_phone = this.validateEmptyStr(element['jh2_reference_phone']);
              job.working = this.validateEmptyStr(element['jh2_working']);
              profilef.job_history.push(job);
            }
            
            if ((element['jh3_company'] == '') && (element['jh3_date_joining'] = '') && (element['jh3_date_end'] == '') &&
              (element['jh3_reference_name'] == '') && (element['jh3_reference_phone'] == '')) {
              return;
            } else {
              job = new profiles_histories;
              job.company = this.validateEmptyStr(element['jh3_company']);
              ldate = new Date(this.validateEmptyStr(element['jh3_date_joining']));
              job.date_joining = fecha.transform(ldate);
              ldate = new Date(this.validateEmptyStr(element['jh3_date_end']));
              job.date_end = fecha.transform(ldate);
              job.position = this.validateEmptyStr(element['jh3_position']);
              job.reference_name = this.validateEmptyStr(element['jh3_reference_name']);
              job.reference_lastname = this.validateEmptyStr(element['jh3_reference_lastname']);
              job.reference_position = this.validateEmptyStr(element['jh3_reference_position']);
              job.reference_email = this.validateEmptyStr(element['jh3_reference_email']);
              job.reference_phone = this.validateEmptyStr(element['jh3_reference_phone']);
              job.working = this.validateEmptyStr(element['jh3_working']);
              profilef.job_history.push(job);
            }            
            // education_details
            profilef.current_level = this.validateEmptyStr(element['current_level']);
            profilef.further_education = this.validateEmptyStr(element['further_education']);
            profilef.currently_studing = this.validateEmptyStr(element['currently_studing']);
            profilef.institution_name = this.validateEmptyStr(element['institution_name']);
            profilef.degree = this.validateEmptyStr(element['degree']);
            // marketing_details
            profilef.sourse = this.validateEmptyStr(element['sourse']);
            profilef.post = this.validateEmptyStr(element['post']);
            profilef.refer = this.validateEmptyStr(element['refer']);
            profilef.about = this.validateEmptyStr(element['about']);
            // profile_details
            profilef.english_level = this.validateEmptyStr(element['english_level']);
            profilef.transport = this.validateEmptyStr(element['transport']);
            profilef.start_date = this.date;
            profilef.unavialable_days = this.days_off;
            profilef.marketing_campaing = profilef.post;
            profilef.first_language = this.validateEmptyStr(element['first_language']);
            profilef.second_language = this.validateEmptyStr(element['second_language']);
            profilef.third_language = this.validateEmptyStr(element['third_language']);
            // processes
            profilef.name = this.validateEmptyStr(element['name']);
            profilef.description = this.validateEmptyStr(element['description']);
            // ************************************************************************ \\
            // ************************************************************************ \\
            // *pendiente implementar la consulta para que cambie el valor por el id. * \\
            // ************************************************************************ \\
            // ************************************************************************ \\
            profilef.id_userpr = this.validateEmptyStr(element['id_user']);
            // services
            profilef.amount = this.validateEmptyStr(element['amount']);
            profilef.loaded = 'true';
            this.fullprofiles.push(profilef);
            count++;
            this.progress = count;
            this.isLoading = count >= this.max_progress;
            this.finished = !this.isLoading;
          })

          if (count == (this.max_progress)) {
            this.importEnd = true;
            this.completed = true;
            console.log(profilef);
          }
        }
      }
    }
    catch (exception) {
      console.log("Ocurrió un Error: " + exception);
      profilef.loaded = exception;
      this.isLoading = false;
      this.finished = true;
      this.completed = false;
      this.importEnd = false;
    }
    finally {
      this.isLoading = false;
      /*this.finished = true;
      this.completed = true;
      this.importEnd = true;*/
    }
  }
}



