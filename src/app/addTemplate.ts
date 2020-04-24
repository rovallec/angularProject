export class applyent_contact{
    idprocesses:string;
    id_role:string;
    id_profile:string;
    processName:string;
    description:string;
    prc_date:string;
    id_user:string;
    status:string;
    //---------------------------   Process Detail ------------------------//
    notes:string;
    result:string;
    method:string;
}

export class schedule_visit{
    idprocesses:string;
    id_role:string;
    id_profile:string;
    name:string;
    descritpion:string;
    prc_date:string;
    status:string;
    id_user:string;
    //---------------------------   Process Detail ------------------------//
    vehicle:string;
    plate:string;
    dateTime:string;
    color:string;
    brand:string;
}

export class preApproval{
    idprocesses:string;
    id_role:string;
    id_profile:string;
    name:string;
    descritpion:string;
    prc_date:string;
    status:string;
    id_user:string;
}