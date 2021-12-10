import { Component, OnInit } from '@angular/core';
import { ApiService } from '../api.service';
import { accounts, clients, Fecha, reporters, waves_template, full_profiles, schedules, ids_profiles, job_histories } from '../process_templates';
import * as XLSX from 'xlsx';
import { isNullOrUndefined } from 'util';
import { THIS_EXPR } from '@angular/compiler/src/output/output_ast';
import { profiles_family, profiles_histories } from '../profiles';
import { AppComponent } from '../app.component';
import { AuthServiceService } from '../auth-service.service';
import { ActivatedRoute, Router } from '@angular/router';

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
  allReporters: reporters[] = [];
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
  any: any = null;
  code: string = ''; // nuevo código que se le asignará a los agentes.
  correlative: number = 0;

  constructor(public apiServices: ApiService, public router: Router) { }

  ngOnInit() {
    this.start();
  }

  start() {
    let fecha: Fecha = new Fecha;
    this.waves = new waves_template;
    this.days_off = null;
    this.start_time = null;
    this.end_time = null;
    this.date = fecha.today;
    this.isLoading = false;
    this.fullprofiles = [];
    this.apiServices.getClients().subscribe((cls: clients[]) => {
      this.getReporter();
      this.clients = cls;
      this.selectedClient = cls[0].idclients;
      this.setClient(this.selectedClient);
    })

    this.any = null;
    this.arrayBuffer = null;
    this.filelist = null;
    this.file = null;
  }

  setClient(cl: string) {
    this.accounts = [];
    this.apiServices.getAccounts().subscribe((acc: accounts[]) => {
      this.accounts = acc.filter(account => account.id_client == cl );
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
    let i = 0;
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
              let j = 0;
              element.job_history.forEach(_job => {
                element.job_history[j].id_profile = element.id_profile;
                j++;
              })
              this.apiServices.insertProfileDetails(element).subscribe((_str: string) => {
                this.apiServices.insertcontact(element.contact_detail).subscribe(async (_str: string) => {
                  for await (const fam of element.family) {
                    this.apiServices.createFamily(fam).subscribe((fstr: string) => {
                      if (String(fstr).split("|")[0]=="1"){
                        element.state = 'Saved';
                        //window.alert("Action successfuly recorded.");
                      } else {
                        //element.state = "An error has occured:\n" + String(fstr).split("|")[1];
                        error = true;
                        element.state = 'Error';
                        element.error_message = String(fstr).split("|")[0];
                        console.log(String(fstr).split("|")[0]);
                      }
                      this.apiServices.insertjobhistory(element.job_history).subscribe((str: string) => {
                        if (str != '') {
                          error = true;
                        };
                      })
                    })
                  }
                })
              })
              if (error==false) {
                element.state = 'Saved';
              }
              i++;
              if ((i>=this.fullprofiles.length) && (error==false)) {
                window.alert("Profiles successfuly created");
                this.apiServices.updateCorrelativeAccount({ employee: element.employee.idemployees, account: element.employee.id_account, client_id: element.employee.client_id, correlative: this.correlative }).subscribe((str: string) => {
                })
              }
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
    window.alert('Limpiando...');
    this.start();
  }

  setAccount(acc) {
    let corr: string = '';
    this.selectedAccount = acc;
    corr = this.selectedAccount.correlative.slice(0, this.selectedAccount.correlative.length-2);
    corr = (Number(corr) + 1).toString();
    this.correlative = Number(corr) * 100;
    this.code = this.apiServices.getCode(this.selectedAccount.prefix, corr, 6);
    this.waves.prefix = this.code;
    this.reporters = this.apiServices.filterReporter(this.allReporters, this.selectedAccount.idaccounts);
  }

  getReporter() {
    let i = 0;
    this.apiServices.getReporter().subscribe((rep: reporters[]) => {
      this.reporters = rep;
      this.allReporters = rep;
      /*rep.forEach(reporter => {
        this.allReporters[i] = reporter;
        this.reporters[i] = reporter;
        i++;
      })*/
    })
  }

  setReporter(AidUser: string) {
    this.reporters.forEach(rep => {
      if (rep.idUser == AidUser) {
        this.actualReporter = rep;
      }
    })
  }

  validateEmptyStr(Adata: string): string{
    if (isNullOrUndefined(Adata)) {
      Adata = '';
    }
    return Adata;
  }

  corrigeDatos(Adata: string): string{
    Adata = String(Adata).toUpperCase().trim();
    Adata = Adata.replace('-', '');
    Adata = Adata.replace(' ', '');
    Adata = Adata.replace('_', '');
    Adata = Adata.replace('.', '');
    return Adata;
  }

  replazeZone(Adata: string): string{
    Adata = String(Adata).toUpperCase().trim();
    Adata = Adata.replace('zone', '');
    Adata = Adata.replace('zona', '');
    Adata = Adata.replace(':', '');
    Adata = Adata.replace(' ', '');
    return Adata;
  }

  validateProfile(Aprofile: full_profiles) {
    let isTrue: boolean = false;
    let states: string[] = ['SOLTERO', 'SOLTERA', 'CASADO', 'CASADA', 'VIUDO', 'VIUDA', 'SEPARADO', 'SEPARADA', 'DIVORCIADO', 'DIVORCIADA'];

    Aprofile.first_name = Aprofile.first_name.toUpperCase();
    Aprofile.second_name = Aprofile.second_name.toUpperCase();
    Aprofile.first_lastname = Aprofile.first_lastname.toUpperCase();
    Aprofile.second_lastname = Aprofile.second_lastname.toUpperCase();

    states.forEach(s => {
      if (Aprofile.marital_status.toUpperCase() == s) {
        isTrue = true;
      }
    })

    if (isTrue == false) {
      Aprofile.state = 'Error';
      Aprofile.error_message = 'The marital status must be entered in Spanish. \n The allowed values ar the following: \n' +
                          'Soltero(a), Casado(a), Viudo(a), Separado(a), Divorciado(a).';
    }
  }

  addfile(event) {
    let fecha: Fecha = new Fecha;
    let ldate: Date = new Date;
    this.file = event.target.files[0];
    let fileReader = new FileReader();
    let profilef: full_profiles = new full_profiles;
    let count: number = 0;
    let address: string = '';

    this.waves.id_account = this.selectedAccount.idaccounts;
    this.waves.starting_date = fecha.today;
    this.waves.end_date = fecha.today;
    this.waves.ops_start = this.date;
    this.waves.state = '0,0,0,0';
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
          let num: string = '1';
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
          this.waves.hires = this.max_progress.toString();
          this.schedule.days_off = this.days_off;
          this.schedule.actual_count = this.waves.max_recriut;
          this.schedule.max_count = this.waves.max_recriut;
          this.schedule.state = '0';
          this.finished = false;
          this.isLoading = true;


          sheetToJson.forEach(element => {
            profilef = new full_profiles;
            let fam: profiles_family = new profiles_family;
            let job: job_histories = new job_histories;
            if (count < 10) {
              num = '0' + count.toString();
            } else {
              num = count.toString();
            }
            profilef.No = num;
            profilef.wave = this.waves;
            profilef.state = 'Loaded';
            profilef.schedule = this.schedule;
            profilef.nearsol_id = profilef.wave.prefix + profilef.No;
            // profiles
            element['day_of_birth'] = fecha.transform(fecha.dateExcel(element['day_of_birth']));
            console.log(element['day_of_birth']);
            profilef.tittle = this.validateEmptyStr(element['tittle']);
            profilef.first_name = this.validateEmptyStr(element['first_name']);
            profilef.second_name = this.validateEmptyStr(element['second_name']);
            profilef.first_lastname = this.validateEmptyStr(element['first_lastname']);
            profilef.second_lastname = this.validateEmptyStr(element['second_lastname']);
            profilef.day_of_birth = this.validateEmptyStr(element['day_of_birth']);
            profilef.nationality = this.validateEmptyStr(element['nationality']);
            profilef.marital_status = this.validateEmptyStr(element['marital_status']);
            profilef.dpi = this.corrigeDatos(this.validateEmptyStr(element['dpi']));
            profilef.nit = this.corrigeDatos(this.validateEmptyStr(element['nit']));
            profilef.igss = this.corrigeDatos(this.validateEmptyStr(element['igss']));
            profilef.irtra = this.corrigeDatos(this.validateEmptyStr(element['irtra']));
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
            profilef.contact_detail.primary_phone = this.corrigeDatos(this.validateEmptyStr(element['primary_phone']));
            profilef.contact_detail.secondary_phone = this.corrigeDatos(this.validateEmptyStr(element['SecondaryPhone']));
            address = this.validateEmptyStr(element['First Line']) + ', ' + this.validateEmptyStr(element['District'])
                      + ', Zona: ' + this.replazeZone(this.validateEmptyStr(element['Zone']));
            profilef.contact_detail.address = address;
            profilef.contact_detail.city = this.validateEmptyStr(element['City']);
            profilef.contact_detail.email = this.validateEmptyStr(element['email']);
            // emergency_details
            profilef.emergency_first_name = this.validateEmptyStr(element['e_first_name']);
            profilef.emergency_second_name = this.validateEmptyStr(element['e_second_name']);
            profilef.emergency_first_lastname = this.validateEmptyStr(element['e_first_lastname']);
            profilef.emergency_second_lastname = this.validateEmptyStr(element['e_second_lastname']);
            profilef.emergency_phone = this.corrigeDatos(this.validateEmptyStr(element['e_phone']));
            profilef.emergency_relationship = this.validateEmptyStr(element['e_relationship']);
            // medical_details
            profilef.medical_treatment = this.validateEmptyStr(element['medical_treatment']);
            profilef.medical_prescription = this.validateEmptyStr(element['medical_prescription']);
            // families
            if (this.isEmpty(element['f1_first_name']) && this.isEmpty(element['f1_second_name']) && this.isEmpty(element['f1_first_last_name']) && this.isEmpty(element['f1_second_last_name'])) {
              //
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f1_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f1_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f1_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f1_second_last_name']);
              fam.affinity_phone = this.corrigeDatos(this.validateEmptyStr(element['f1_phone']));
              fam.affinity_relationship = this.validateEmptyStr(element['f1_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f1_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }

            if (this.isEmpty(element['f2_first_name']) && this.isEmpty(element['f2_second_name']) && this.isEmpty(element['f2_first_last_name']) && this.isEmpty(element['f2_second_last_name'])) {
              // no hacer nada.
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f2_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f2_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f2_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f2_second_last_name']);
              fam.affinity_phone = this.corrigeDatos(this.validateEmptyStr(element['f2_phone']));
              fam.affinity_relationship = this.validateEmptyStr(element['f2_relationship']);
              fam.affinity_relationship = this.validateEmptyStr(element['f1_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f2_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }

            if (this.isEmpty(element['f3_first_name']) && this.isEmpty(element['f3_second_name']) && this.isEmpty(element['f3_first_last_name']) && this.isEmpty(element['f3_second_last_name'])) {
              // no hacer nada.
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f3_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f3_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f3_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f3_second_last_name']);
              fam.affinity_phone = this.corrigeDatos(this.validateEmptyStr(element['f3_phone']));
              fam.affinity_relationship = this.validateEmptyStr(element['f3_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f3_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }

            if (this.isEmpty(element['f4_first_name']) && this.isEmpty(element['f4_second_name']) && this.isEmpty(element['f4_first_last_name']) && this.isEmpty(element['f4_second_last_name'])) {
              // no hacer nada.
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f4_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f4_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f4_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f4_second_last_name']);
              fam.affinity_phone = this.corrigeDatos(this.validateEmptyStr(element['f4_phone']));
              fam.affinity_relationship = this.validateEmptyStr(element['f4_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f4_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }

            if (this.isEmpty(element['f5_first_name']) && this.isEmpty(element['f5_second_name']) && this.isEmpty(element['f5_first_last_name']) && this.isEmpty(element['f5_second_last_name'])) {
              // no hacer nada.
            } else {
              fam.affinity_first_name = this.validateEmptyStr(element['f5_first_name']);
              fam.affinity_second_name = this.validateEmptyStr(element['f5_second_name']);
              fam.affinity_first_last_name = this.validateEmptyStr(element['f5_first_last_name']);
              fam.affinity_second_last_name = this.validateEmptyStr(element['f5_second_last_name']);
              fam.affinity_phone = this.corrigeDatos(this.validateEmptyStr(element['f5_phone']));
              fam.affinity_relationship = this.validateEmptyStr(element['f5_relationship']);
              ldate = new Date(this.validateEmptyStr(element['f5_birthdate']));
              fam.affinity_birthdate = new Date(fecha.transform(ldate));
              profilef.family.push(fam);
            }
            // job_histories
            if (this.isEmpty(element['jh1_company']) && this.isEmpty(element['jh1_date_joining']) &&
              this.isEmpty(element['jh1_date_end']) && this.isEmpty(element['jh1_reference_name']) &&
              this.isEmpty(element['jh1_reference_phone'])) {
              // no hacer nada.
            } else {
              job.company = this.validateEmptyStr(element['jh1_company']);
              ldate = new Date(this.validateEmptyStr(element['jh1_date_joining']));
              job.date_joining = fecha.transform(ldate);
              ldate = new Date(this.validateEmptyStr(element['jh1_date_end']));
              job.date_end = fecha.transform(ldate);
              job.position = this.validateEmptyStr(element['jh1_position']);
              job.reference_name = this.validateEmptyStr(element['jh1_reference_name']);
              job.reference_lastname = this.validateEmptyStr(element['jh1_reference_lastname']);
              job.reference_position = this.validateEmptyStr(element['jh1_reference_position']);
              job.reference_mail = this.validateEmptyStr(element['jh1_reference_mail']);
              job.reference_phone = this.corrigeDatos(this.validateEmptyStr(element['jh1_reference_phone']));
              job.working = this.validateEmptyStr(element['jh1_working']);
              profilef.job_history.push(job);
            }

            if (this.isEmpty(element['jh2_company']) && this.isEmpty(element['jh2_date_joining']) &&
              this.isEmpty(element['jh2_date_end']) && this.isEmpty(element['jh2_reference_name']) &&
              this.isEmpty(element['jh2_reference_phone'])) {
              // no hacer nada.
            } else {
              job = new job_histories;
              job.company = this.validateEmptyStr(element['jh2_company']);
              ldate = new Date(this.validateEmptyStr(element['jh2_date_joining']));
              job.date_joining = fecha.transform(ldate);
              ldate = new Date(this.validateEmptyStr(element['jh2_date_end']));
              job.date_end = fecha.transform(ldate);
              job.position = this.validateEmptyStr(element['jh2_position']);
              job.reference_name = this.validateEmptyStr(element['jh2_reference_name']);
              job.reference_lastname = this.validateEmptyStr(element['jh2_reference_lastname']);
              job.reference_position = this.validateEmptyStr(element['jh2_reference_position']);
              job.reference_mail = this.validateEmptyStr(element['jh2_reference_mail']);
              job.reference_phone = this.corrigeDatos(this.validateEmptyStr(element['jh2_reference_phone']));
              job.working = this.validateEmptyStr(element['jh2_working']);
              profilef.job_history.push(job);
            }

            if (this.isEmpty(element['jh3_company']) && this.isEmpty(element['jh3_date_joining']) &&
              this.isEmpty(element['jh3_date_end']) && this.isEmpty(element['jh3_reference_name']) &&
              this.isEmpty(element['jh3_reference_phone'])) {
              // no hacer nada.
            } else {
              job = new job_histories;
              job.company = this.validateEmptyStr(element['jh3_company']);
              ldate = new Date(this.validateEmptyStr(element['jh3_date_joining']));
              job.date_joining = fecha.transform(ldate);
              ldate = new Date(this.validateEmptyStr(element['jh3_date_end']));
              job.date_end = fecha.transform(ldate);
              job.position = this.validateEmptyStr(element['jh3_position']);
              job.reference_name = this.validateEmptyStr(element['jh3_reference_name']);
              job.reference_lastname = this.validateEmptyStr(element['jh3_reference_lastname']);
              job.reference_position = this.validateEmptyStr(element['jh3_reference_position']);
              job.reference_mail = this.validateEmptyStr(element['jh3_reference_mail']);
              job.reference_phone = this.corrigeDatos(this.validateEmptyStr(element['jh3_reference_phone']));
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
            profilef.state = 'Loaded';
            this.validateProfile(profilef);
            this.fullprofiles.push(profilef);
            count++;
            this.progress = count;
            this.isLoading = count >= this.max_progress;
            this.finished = !this.isLoading;
          })

          if (count == (this.max_progress)) {
            this.importEnd = true;
            this.completed = true;
            this.correlative = this.correlative + Number(num);
          }
        }
      }
    }
    catch (exception) {
      console.log("Ocurrió un Error: " + exception);
      profilef.state = 'Error';
      profilef.error_message = exception;
    }
    finally {
      this.isLoading = false;
    }
  }
}



