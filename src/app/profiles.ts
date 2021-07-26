export class profiles {
    idprofiles: string;
    tittle: string;
    first_name: string;
    second_name: string;
    first_lastname: string;
    second_lastname: string;
    day_of_birth: string;
    nationality: string;
    gender: string;
    etnia: string;
    bank: string;
    account: string;
    account_type: string;
    marital_status: string;
    dpi: string;
    nit: string;
    iggs: string;
    irtra: string;
    status: string;
    idcontact_details: string;
    id_profile: string;
    primary_phone: string;
    secondary_phone: string;
    address: string;
    city: string;
    email: string;
    idjob_histories: string;
    company: string;
    date_joining: string;
    date_end: string;
    position: string;
    birth_place: string;
    reference_name: string;
    reference_lastname: string;
    reference_position: string;
    reference_email: string;
    reference_phone: string;
    working: string;
    idprofile_details: string;
    english_level: string;
    transport: string;
    start_date: string;
    unavialable_days: string;
    marketing_campaing: string;
    first_lenguage: string;
    second_lenguage: string;
    third_lenguage: string;
    idemergency_details: string;
    emergency_first_name: string;
    emergency_second_name: string;
    emergency_first_lastname: string;
    emergency_second_lastname: string;
    phone: string;
    relationship: string;
    idmedical_details: string;
    medical_treatment: string;
    medical_prescription: string;
    ideducation_details: string;
    current_level: string;
    further_education: string;
    currently_studing: string;
    institution_name: string;
    degree: string;
    iddocuments: string;
    doc_type: string;
    doc_path: string;
    profesion:string;
    constructor() {
        this.idprofiles = null;
        this.tittle = null;
        this.first_name = null;
        this.second_name = null;
        this.first_lastname = null;
        this.second_lastname = null;
        this.day_of_birth = null;
        this.nationality = null;
        this.gender = null;
        this.etnia = null;
        this.bank = null;
        this.account = null;
        this.account_type = null;
        this.marital_status = null;
        this.dpi = null;
        this.nit = null;
        this.iggs = null;
        this.irtra = null;
        this.status = null;
        this.idcontact_details = null;
        this.id_profile = null;
        this.primary_phone = null;
        this.secondary_phone = null;
        this.address = null;
        this.city = null;
        this.email = null;
        this.idjob_histories = null;
        this.company = null;
        this.date_joining = null;
        this.date_end = null;
        this.position = null;
        this.birth_place = null;
        this.reference_name = null;
        this.reference_lastname = null;
        this.reference_position = null;
        this.reference_email = null;
        this.reference_phone = null;
        this.working = null;
        this.idprofile_details = null;
        this.english_level = null;
        this.transport = null;
        this.start_date = null;
        this.unavialable_days = null;
        this.marketing_campaing = null;
        this.first_lenguage = null;
        this.second_lenguage = null;
        this.third_lenguage = null;
        this.idemergency_details = null;
        this.emergency_first_name = null;
        this.emergency_second_name = null;
        this.emergency_first_lastname = null;
        this.emergency_second_lastname = null;
        this.phone = null;
        this.relationship = null;
        this.idmedical_details = null;
        this.medical_treatment = null;
        this.medical_prescription = null;
        this.ideducation_details = null;
        this.current_level = null;
        this.further_education = null;
        this.currently_studing = null;
        this.institution_name = null;
        this.degree = null;
        this.iddocuments = null;
        this.doc_type = null;
        this.doc_path = null;
    }
}

export class profiles_histories {
    idjob_histories: string;
    company: string;
    date_joining: string;
    date_end: string;
    position: string;
    reference_name: string;
    reference_lastname: string;
    reference_position: string;
    reference_email: string;
    reference_phone: string;
    working: string;
}

export class profiles_family {
    idaffinity_details: number;
    affinity_idfamilies: number;
    affinity_id_profile: number;
    affinity_first_name: string;
    affinity_second_name: string;
    affinity_first_last_name: string;
    affinity_second_last_name: string;
    affinity_phone: string;
    affinity_relationship: string;
    affinity_birthdate: Date;
}