import { waves_template } from './process_templates';
import { users } from './users';
import { process } from './process';
import { Identifiers } from '@angular/compiler';

export class fullPreapproval{
    idprocesses:string;
    process_name:string;
    description:string;
    prc_date:string;
    role_name:string;
    user_name:string;
    id_profile:string;
}
export class fullApplyentcontact{
    idprocesses:string;
    role:string;
    profile:string;
    processName:string;
    description:string;
    prc_date:string;
    user:string;
    status:string;
    notes:string;
    result:string;
    method:string;
}

export class fullSchedulevisit{
    idprocesses:string;
    id_role:string;
    id_profile:string;
    name:string;
    description:string;
    prc_date:string;
    status:string;
    id_user:string;
    vehicle:string;
    plate:string;
    dateTime:string;
    attendance:string;
    color:string;
    brand:string;
}

export class fullDoc_Proc{
    idprocesses:string;
    id_role:string;
    id_profile:string;
    name:string;
    description:string;
    prc_date:string;
    status:string;
    id_user:string;
    iddocuments:string;
    doc_res:string;
    doc_type:any;
    doc_path:string;
    result:string;
    notes:string;
    source:string;
    post:string;
    referrer:string;
    about:string;
}

export class queryDoc_Proc{
    idprocesses:string;
    id_role:string;
    id_profile:string;
    name:string;
    description:string;
    prc_date:string;
    username:string;
    status:string;
    results:string;
    notes:string;
    english_test:string;
    typing_test:string;
    psicometric_test:string;
    IGSS:string;
    IRTRA:string;
    source:string;
    post:string;
    referrer:string;
    about:string;
}


export class testRes{
    EnglishTest:string;
    TypingTest:string;
    PsicometricTest:string;
    PoliceRecrods:string;
    CriminalRecords:string;
    UtilityBill:string;
    CityTax:string;
    IGSS:string;
    IRTRA:string
    doc1:string;
    doc2:string;
    doc3:string
}

export class uploaded_documetns{
    iddocuments:string;
    id_profile:string;
    id_process:string;
    doc_type:string;
    doc_path:string;
    active:string;
}

export class search_parameters{
    filter:string;
    value:string
}

export class new_hire{
    process:process;
    wave:waves_template;
    reporter:users;
    notes:string;
    nearsol_id:string;
    id_schedule:string;
    schedule_count:string;
    next_state:string;
}

export class vew_hire_process{
    user:string;
    notes:string;
    prc_date:string;
    nearsol_id:string;
    wave:string;
    reports_to:string;
}

export class coincidences{
    id:string;
    name:string;
    status:string;

    constructor(){
        this.id = null;
        this.name = null;
        this.status = null;
    }
}

export class employees{
    id_profile:string;
    idemployees:string;
    id_hire:string;
    id_account:string;
    reporter:string;
    client_id:string;
    hiring_date:string;
    job:string;
    base_payment:string;
    productivity_payment:string;
    state:string;
    id_user:string;
    id_department:string;
    name:string;
    account:string;
    platform:string;
    nearsol_id:string;
    user_name:string;
    constructor(){
        this.id_profile = null;
        this.idemployees = null;
        this.id_hire = null;
        this.id_account = null;
        this.reporter = null;
        this.client_id = null;
        this.hiring_date = null;
        this.job = null;
        this.base_payment = null;
        this.productivity_payment = null;
        this.state = null;
        this.name = null;
        this.account = null;
        this.platform = null;
        this.nearsol_id = null;
    }
}

export class hrProcess{
    idhr_process:string;
    id_user:string;
    user_name:string;
    id_employee:string;
    employee:string;
    id_type:string;
    name:string;
    id_department:string;
    date:string;
    notes:string;
    status:string;
    constructor(){
        this.idhr_process = null;
        this.id_user = null;
        this.user_name = null;
        this.id_employee = null;
        this.employee = null;
        this.id_type = null;
        this.name = null;
        this.id_department = null;
        this.date = null;
        this.notes = null;
        this.status = null;
    }
}

export class payment_methods{
    idpayment_methods:string;
    id_employee:string;
    type:string
    number:string
    bank:string;
    predeterm:string;
    //process
    id_process: string;
    id_user: string;
    date: string;
    notes: string;
    constructor(){
        this.idpayment_methods = null;
        this.id_employee = null;
        this.type = null;
        this.number = null;
        this.bank = null;
        this.predeterm = null;
        this.id_process = null;
        this.id_user = null;
        this.id_employee = null;
        this.date = null;
        this.notes = null;
    }
}