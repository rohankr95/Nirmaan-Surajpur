<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with the master data the app needs to
     * function, plus one login per role. Safe to re-run after migrate:fresh.
     */
    public function run()
    {
        $now = now();

        DB::table('user_roles')->insert([
            ['user_role_id' => 1, 'role_name' => 'व्यवस्थापक',  'role_name_en' => 'Admin',    'created_at' => $now, 'updated_at' => $now],
            ['user_role_id' => 2, 'role_name' => 'अधिकारी',     'role_name_en' => 'Officer',  'created_at' => $now, 'updated_at' => $now],
            ['user_role_id' => 3, 'role_name' => 'कर्मचारी',    'role_name_en' => 'Employee', 'created_at' => $now, 'updated_at' => $now],
        ]);

        // Work statuses drive every worklist filter and the dashboard columns.
        // The numeric ids are referenced directly by the stage controllers.
        DB::table('work_statuses')->insert([
            ['work_status_id' => 1,  'work_status_name' => 'अप्रारंभ',                  'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 2,  'work_status_name' => 'तकनीकी स्वीकृति हेतु प्रेषित', 'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 3,  'work_status_name' => 'तकनीकी स्वीकृत',            'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 4,  'work_status_name' => 'प्रशासकीय स्वीकृति हेतु प्रेषित', 'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 5,  'work_status_name' => 'प्रशासकीय स्वीकृत',          'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 6,  'work_status_name' => 'निविदा आमंत्रित',            'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 7,  'work_status_name' => 'कार्य आदेश जारी',           'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 8,  'work_status_name' => 'कार्य प्रारंभ',              'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 9,  'work_status_name' => 'कार्य प्रगति पर',            'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 10, 'work_status_name' => 'कार्य पूर्ण',                'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 11, 'work_status_name' => 'कार्य बंद',                 'created_at' => $now, 'updated_at' => $now],
            ['work_status_id' => 12, 'work_status_name' => 'कार्य निरस्त',               'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('location_types')->insert([
            ['location_type_id' => 1, 'location_type_name' => 'ग्रामीण', 'location_type_name_en' => 'Rural', 'created_at' => $now, 'updated_at' => $now],
            ['location_type_id' => 2, 'location_type_name' => 'नगरीय',  'location_type_name_en' => 'Urban', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('financial_years')->insert([
            ['id' => 1, 'name' => '2025-26', 'from_date' => '2025-04-01', 'to_date' => '2026-03-31', 'created_by' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['id' => 2, 'name' => '2026-27', 'from_date' => '2026-04-01', 'to_date' => '2027-03-31', 'created_by' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // --- Location hierarchy -------------------------------------------------
        DB::table('states')->insert([
            ['state_id' => 1, 'state_name' => 'छत्तीसगढ़', 'state_name_en' => 'Chhattisgarh', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('districts')->insert([
            ['district_id' => 1, 'district_name' => 'सूरजपुर', 'district_name_en' => 'Surajpur', 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('subdivisions')->insert([
            ['subdivision_id' => 1, 'subdivision_name' => 'सूरजपुर', 'subdivision_name_en' => 'Surajpur', 'district_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('parliamentary_constituencies')->insert([
            ['parliamentary_constituency_id' => 1, 'parliamentary_constituency_name' => 'सरगुजा', 'parliamentary_constituency_name_en' => 'Surguja', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('assembly_constituencies')->insert([
            ['assembly_constituency_id' => 1, 'assembly_constituency_name' => 'प्रेमनगर', 'assembly_constituency_name_en' => 'Premnagar', 'parliamentary_constituency_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['assembly_constituency_id' => 2, 'assembly_constituency_name' => 'भटगांव',  'assembly_constituency_name_en' => 'Bhatgaon',  'parliamentary_constituency_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('blocks')->insert([
            ['block_id' => 1, 'block_name' => 'सूरजपुर',   'block_name_en' => 'Surajpur',  'subdivision_id' => 1, 'district_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['block_id' => 2, 'block_name' => 'प्रेमनगर', 'block_name_en' => 'Premnagar', 'subdivision_id' => 1, 'district_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('grampanchayats')->insert([
            ['grampanchayat_id' => 1, 'grampanchayat_name' => 'केतका',  'grampanchayat_name_en' => 'Ketka',  'block_id' => 1, 'subdivision_id' => 1, 'district_id' => 1, 'assembly_constituency_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['grampanchayat_id' => 2, 'grampanchayat_name' => 'जरहाडीह', 'grampanchayat_name_en' => 'Jarhadih', 'block_id' => 2, 'subdivision_id' => 1, 'district_id' => 1, 'assembly_constituency_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('villages')->insert([
            ['village_id' => 1, 'village_name' => 'केतका',    'village_name_en' => 'Ketka',    'grampanchayat_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['village_id' => 2, 'village_name' => 'बरबसपुर',  'village_name_en' => 'Barbaspur', 'grampanchayat_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['village_id' => 3, 'village_name' => 'जरहाडीह',  'village_name_en' => 'Jarhadih',  'grampanchayat_id' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('city_types')->insert([
            ['city_type_id' => 1, 'city_type_name' => 'नगर पालिका', 'city_type_name_en' => 'Municipality', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('cities')->insert([
            ['city_id' => 1, 'city_name' => 'सूरजपुर', 'city_name_en' => 'Surajpur', 'city_type_id' => 1, 'subdivision_id' => 1, 'district_id' => 1, 'assembly_constituency_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('wards')->insert([
            ['ward_id' => 1, 'ward_no' => 1, 'ward_name' => 'वार्ड 1', 'ward_name_en' => 'Ward 1', 'city_id' => 1, 'district_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['ward_id' => 2, 'ward_no' => 2, 'ward_name' => 'वार्ड 2', 'ward_name_en' => 'Ward 2', 'city_id' => 1, 'district_id' => 1, 'state_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // --- Org ----------------------------------------------------------------
        DB::table('departments')->insert([
            ['department_id' => 1, 'department_name' => 'जिला खनिज न्यास',       'department_name_en' => 'DMF',  'created_at' => $now, 'updated_at' => $now],
            ['department_id' => 2, 'department_name' => 'लोक निर्माण विभाग',      'department_name_en' => 'PWD',  'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('offices')->insert([
            ['office_id' => 1, 'office_name' => 'जिला कार्यालय सूरजपुर', 'office_name_en' => 'District Office Surajpur', 'department_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['office_id' => 2, 'office_name' => 'उपसंभाग सूरजपुर',      'office_name_en' => 'Sub Division Surajpur',    'department_id' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('employees_designation')->insert([
            ['designation_id' => 1, 'designation_name' => 'उप अभियंता (Sub Engineer)', 'created_at' => $now, 'updated_at' => $now],
            ['designation_id' => 2, 'designation_name' => 'अनुविभागीय अधिकारी (SDO)',  'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('schemes')->insert([
            ['scheme_id' => 1, 'scheme_name' => 'डी.एम.एफ.',              'department_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['scheme_id' => 2, 'scheme_name' => 'मुख्यमंत्री ग्राम सड़क योजना', 'department_id' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('work_categories')->insert([
            ['work_category_id' => 1, 'work_category_name' => 'सड़क एवं पुल',   'created_at' => $now, 'updated_at' => $now],
            ['work_category_id' => 2, 'work_category_name' => 'पेयजल आपूर्ति', 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('work_types')->insert([
            ['work_type_id' => 1, 'work_type_name' => 'सीसी रोड निर्माण', 'work_category_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['work_type_id' => 2, 'work_type_name' => 'पेयजल पाईप लाइन', 'work_category_id' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);
        DB::table('work_type_stages')->insert([
            ['work_type_stage_id' => 1, 'work_type_stage_name' => 'नींव कार्य',       'stage_number' => 1, 'work_type_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['work_type_stage_id' => 2, 'work_type_stage_name' => 'ढांचा निर्माण',    'stage_number' => 2, 'work_type_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['work_type_stage_id' => 3, 'work_type_stage_name' => 'फिनिशिंग',        'stage_number' => 3, 'work_type_id' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['work_type_stage_id' => 4, 'work_type_stage_name' => 'खुदाई',           'stage_number' => 1, 'work_type_id' => 2, 'created_at' => $now, 'updated_at' => $now],
            ['work_type_stage_id' => 5, 'work_type_stage_name' => 'पाईप बिछाना',     'stage_number' => 2, 'work_type_id' => 2, 'created_at' => $now, 'updated_at' => $now],
        ]);

        // --- People -------------------------------------------------------------
        // Employee rows are inserted directly: the Employee model's created hook
        // auto-provisions a matching login, which we do not want for the seed.
        DB::table('employees')->insert([
            ['emp_id' => 1, 'emp_name' => 'रमेश कुमार', 'emp_mobile' => '9876543210', 'emp_email' => 'se1@nirmaan.test',  'emp_designation_id' => 1, 'office_id' => 1, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['emp_id' => 2, 'emp_name' => 'सुनीता वर्मा', 'emp_mobile' => '9876500011', 'emp_email' => 'sdo1@nirmaan.test', 'emp_designation_id' => 2, 'office_id' => 1, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['emp_id' => 3, 'emp_name' => 'अनिल साहू',  'emp_mobile' => '9876500022', 'emp_email' => 'se2@nirmaan.test',  'emp_designation_id' => 1, 'office_id' => 2, 'status' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('users')->insert([
            ['user_id' => 1, 'login_id' => 'admin',    'password' => Hash::make('admin123'),    'name' => 'प्रशासक',     'designation' => 'District Administrator', 'mobile' => '9000000001', 'email' => 'admin@nirmaan.test',    'user_role_id' => 1, 'office_id' => 1, 'emp_id' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 2, 'login_id' => 'officer',  'password' => Hash::make('officer123'),  'name' => 'कार्यालय प्रमुख', 'designation' => 'Office In-charge',        'mobile' => '9000000002', 'email' => 'officer@nirmaan.test',  'user_role_id' => 2, 'office_id' => 1, 'emp_id' => 0, 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => 3, 'login_id' => 'engineer', 'password' => Hash::make('engineer123'), 'name' => 'रमेश कुमार',   'designation' => 'Sub Engineer',            'mobile' => '9876543210', 'email' => 'se1@nirmaan.test',      'user_role_id' => 3, 'office_id' => 1, 'emp_id' => 1, 'created_at' => $now, 'updated_at' => $now],
        ]);

        $this->command->info('Seeded masters and 3 logins: admin/admin123, officer/officer123, engineer/engineer123');
    }
}
