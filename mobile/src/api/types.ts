export interface ApiUser {
  user_id: number;
  login_id: string;
  name: string;
  designation: string | null;
  landline: string | null;
  mobile: string | null;
  email: string | null;
  user_role_id: number;
  user_role_name: string;
  office_id: number;
  office_name: string;
  emp_id: number;
  force_password_reset: boolean;
}

export interface DashboardCounts {
  total: number;
  in_progress: number;
  complete: number;
  closed: number;
  rejected: number;
}

export interface DashboardResponse {
  user: { name: string; office_name: string };
  counts: DashboardCounts;
}

export interface WorkSummary {
  work_id: number;
  work_name: string;
  work_type_name: string;
  work_status: number;
  work_stage_name: string;
  sanction_amount: number;
  created_at: string;
  due_date: string | null;
  office_name: string;
  area: string;
  thumbnail: string | null;
}

export interface WorkDetail extends WorkSummary {
  scheme_name: string;
  department_name: string;
  employee_name: string;
  sdo_name: string;
  latitude: string | null;
  longitude: string | null;
  total_stages: number;
}

export interface WorkStage {
  work_type_stage_id: number;
  stage_number: number;
  stage_name: string;
  percent_complete: number | null;
  recorded: boolean;
  estimated_completion_date: string | null;
  expenditure_amount: number | null;
  description: string | null;
  status_update_date: string | null;
  photos: string[];
}
