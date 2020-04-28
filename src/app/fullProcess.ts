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