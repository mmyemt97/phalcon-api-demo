//Link có filter staff_id trong bộ lọc có thể truyền staff_id=1 or staff_id[]=1 đều được

BRD_CSKH_Mục tiêu ngày hôm sau
https://docs.google.com/spreadsheets/d/1WImeD4eCF6X_6y4aAtLGbt7wVZaRi5Av1GB_00lCeaI/edit?usp=drive_web&ouid=108248646064072273982

//week
http://plan.test.com/planning/sales-target-week?branch_code=tvn.south&week=16
http://plan.test.com/planning/sales-target-week?branch_code=tvn.south&week=16&team_id=1

http://plan.test.com/planning/sales-target-week/detail?branch_code=tvn.south&week=16&team_id=1

//tomorrow list
http://plan.test.com/planning/sales-target-tomorrow?branch_code=tvn.south
http://plan.test.com/planning/sales-target-tomorrow?branch_code=tvn.south&team_id=1

//tomorow detail
http://plan.test.com/planning/sales-target-tomorrow/detail?branch_code=tvn.south&team_id=1
http://plan.test.com/planning/sales-target-tomorrow/detail?branch_code=tvn.south&team_id=1&staff_id[]=2

//tomorrow create or update target
http://plan.test.com/planning/sales-target-tomorrow-save 
['sale_target_id','branch_code','team_id','staff_id','master_kpi_code','date','value' or 'quantity'],

BRD_CSKH_Doanh thu hằng ngày
https://docs.google.com/spreadsheets/d/1LaWwruP0d53wrJL4Y-Y7lNHcqc6fgC_W7YatU4h9_lE/edit#gid=428990029

//danh sách ngày
http://plan.test.com//planning/sales-revenue-achievement?branch_code=tvn.south
http://plan.test.com//planning/sales-revenue-achievement?branch_code=tvn.south&date=1556001642

//danh sách team theo ngày
http://plan.test.com/planning/sales-revenue-achievement/team?branch_code=tvn.south&date=1556001642
http://plan.test.com/planning/sales-revenue-achievement/team?branch_code=tvn.south&date=1556001642&team_id=1

//danh sách member theo ngày theo team
http://plan.test.com/planning/sales-revenue-achievement/team-member?branch_code=tvn.south&date=1556001642&team_id=1
http://plan.test.com/planning/sales-revenue-achievement/team-member?branch_code=tvn.south&date=1556001642&team_id=1&staff_id[]=2

//chi tiết member theo ngày theo staff_id
http://plan.test.com/planning/sales-revenue-achievement/team-member-detail?branch_code=tvn.south&date=1555902733&staff_id=2

//tạo mới achivement by member
http://plan.test.com/planning/sales-revenue-achievement/achievement-create
//parram để tạo mới
$rules = [
    'branch_code' => 'required',
    'team_id' => 'required|digit_min:1',
    'staff_id' => 'required|digit_min:1',
    'date' => 'required|timestamp',
    'employer_name' => 'required|max:200',
    'quantity' => 'required|digit_min:1',
    'revenue' => 'required|digit_min:1000',
];
        
//cập nhật achivement by member
http://plan.test.com/planning/sales-revenue-achievement/achievement-update
//parram để cập nhật
$rules = [
    'employer_name' => 'max:200',
    'quantity' => 'digit_min:1',
    'revenue' => 'digit_min:1000',
    'achievement_id' => 'required|digit_min:1'
];

//xóa achivement by member
http://plan.test.com/planning/sales-revenue-achievement/achievement-delete

//xuất excel

//report

BRD_CSKH_Kế hoạch trong tuần
https://docs.google.com/spreadsheets/d/1BPyZve_cPi0Cyu9urvjNyGJhuSePViIkroecDTGAyrU/edit#gid=1337157943

//danh sách thống kê theo team
http://plan.test.com/planning/plan-weekly-expected-revenue?branch_code=tvn.south&week_year=16/2019
http://plan.test.com/planning/plan-weekly-expected-revenue?branch_code=tvn.south&week_year=16/2019&team_id=1

//danh sách thống kê theo thành viên
http://plan.test.com/planning/plan-weekly-expected-revenue/detail?branch_code=tvn.south&week_year=16/2019&team_id=1
http://plan.test.com/planning/plan-weekly-expected-revenue/detail?branch_code=tvn.south&week_year=16/2019&team_id=1&staff_id[]=4

//chi tiết comment
http://plan.test.com/planning/plan-weekly-expected-revenue/comment

//tạo comment
http://plan.test.com/planning/plan-weekly-expected-revenue/comment
['branch_code','comment','week_year']

//report
http://plan.test.com/planning/plan-weekly-expected-revenue/report

BRD_CSKH_Kế hoạch trong tháng
https://docs.google.com/spreadsheets/d/1HNoX3pWK6ywzCUcRYGS9V1cBXtmm_Cl8tQ-rEM_Ksyo/edit#gid=861409425

//doanh số dự kiến
//danh sách thống kê theo team
http://plan.test.com/planning/plan-monthly-expected-revenue?branch_code=tvn.south&month_year=4/2019
http://plan.test.com/planning/plan-monthly-expected-revenue?branch_code=tvn.south&month_year=4/2019&team_id=1

//danh sách thống kê theo thành viên
http://plan.test.com/planning/plan-monthly-expected-revenue/detail?branch_code=tvn.south&month_year=4/2019&team_id=1
http://plan.test.com/planning/plan-monthly-expected-revenue/detail?branch_code=tvn.south&month_year=4/2019&team_id=1&staff_id[]=4

//tạo + sửa doanh thu + note theo thành viên, id = 0 là tạo
http://plan.test.com/planning/plan-monthly-expected-revenue/save
['branch_code','month_year','team_id','staff_id','plan_monthly_expected_revenue_id','old_revenue' or 'new_revenue' or 'note']

//khối lượng công việc
//danh sách thống kê theo team
http://plan.test.com/planning/plan-monthly-workload?branch_code=tvn.south&month_year=5/2019
http://plan.test.com/planning/plan-monthly-workload?branch_code=tvn.south&month_year=5/2019&team_id=1

//danh sách thống kê theo thành viên
http://plan.test.com/planning/plan-monthly-workload/detail?branch_code=tvn.south&month_year=5/2019&team_id=1
http://plan.test.com/planning/plan-monthly-workload/detail?branch_code=tvn.south&month_year=5/2019&team_id=1&staff_id[]=4

//tạo + sửa khối lượng công việc theo thành viên, id = 0 là tạo
http://plan.test.com/planning/plan-monthly-workload/save
['branch_code','week_year','team_id','staff_id','plan_workload_id']
1 trong các value bên dưới
['old_customer_quantity','new_customer_quantity','old_call_quantity','new_call_quantity', 'old_email_quantity','new_email_quantity']

//thống kê rank
//danh sách thứ hạng
http://plan.test.com/planning/planning/plan-monthly-rank

//tạo + sửa thứ hạng
http://plan.test.com/planning/plan-monthly-rank/save
//cần post đồng thời nguyên result 3 array như yêu cầu mới qua validate, plan_monthly_rank_id = 0 là tạo
$rules = [
    'branch_code' => 'required',
    'month_year' => 'required|month_year',
    'team_id' => 'required|digit_min:1',
    'result' => 'required|isArray'
];

$listRankType = implode(',', PlanningConstant::listRankType());

for ($i = 0; $i < 3; $i++) {
    $rules['result.' . $i . '.plan_monthly_rank_id'] = "required|digit_min:0";
    $rules['result.' . $i . '.rank_type'] = "required|inclusionIn:$listRankType";
    $rules['result.' . $i . '.rank'] = 'required|digit_min:0';
}

//mục tiêu thu nhập từng cá nhân
//danh sách thống kê theo team
http://plan.test.com/planning/plan-monthly-individual-earning-target?branch_code=tvn.south&month_year=5/2019
http://plan.test.com/planning/plan-monthly-individual-earning-target?branch_code=tvn.south&month_year=5/2019&team_id=1

//danh sách thống kê theo thành viên
http://plan.test.com/planning/plan-monthly-individual-earning-target/detail?branch_code=tvn.south&month_year=5/2019&team_id=1
http://plan.test.com/planning/plan-monthly-individual-earning-target/detail?branch_code=tvn.south&month_year=5/2019&team_id=1&staff_id[]=2

//tạo + sửa mục tiêu
http://plan.test.com/planning/plan-monthly-individual-earning-target/save
$rules = [
    'branch_code' => 'required',
    'month_year' => 'required|month_year',
    'team_id' => 'required|digit_min:1',
    'staff_id' => 'required|digit_min:0',
    'expected_revenue' => 'required|digit_min:1000'
];

//Lý giải doanh thu tháng sau
//chi tiết bình luận theo week_year
http://plan.test.com/planning/plan-monthly-target-comment

//tạo + sửa bình luận 
http://plan.test.com/planning/plan-monthly-target-comment/save
$rules = [
    'branch_code' => 'required',
    'plan_monthly_target_commnent_id' => 'required|digit_min:0',
    'month_year' => 'required|month_year',
    'team_id' => 'digit_min:1',
    'explain_note' => 'max:255',
    'plan_note' => 'max:255',
];
BRD_CSKH_Theo dõi tiến độ
https://docs.google.com/spreadsheets/d/1qP3CP2LbdoWskGzlKMjlK5_6Vm3OpPxorE5ZwPsuLzI/edit#gid=249219106

Tiến độ theo tháng list team
http://plan.test.com/planning/follow-progress-month/list-team?branch_code=1&month_year=5/2019
filter team_id :
http://plan.test.com/planning/follow-progress-month/list-team?branch_code=1&month_year=5/2019&team_id=1

Tiến độ theo tháng list staff
http://plan.test.com/planning/follow-progress-month/list-staff?branch_code=1&month_year=5/2019&team_id=1
filter staff_id:
http://plan.test.com/planning/follow-progress-month/list-staff?branch_code=1&month_year=5/2019&team_id=1&staff_id[]=2

Tiến độ theo tuần của tháng

list team:
http://plan.test.com/planning/follow-progress-week/list-team?branch_code=1&month_year=5/2019
filter team_id:
http://plan.test.com/planning/follow-progress-week/list-team?branch_code=1&month_year=5/2019&team_id=1

list week:
http://plan.test.com/planning/follow-progress-week/list-week?branch_code=1&month_year=5/2019&team_id=1
filter week_year:
http://plan.test.com/planning/follow-progress-week/list-week?branch_code=1&month_year=5/2019&team_id=1&week_year=19/2019

list member:
http://plan.test.com/planning/follow-progress-week/list-staff?branch_code=1&team_id=1&week_year=19/2019
filter staff_id:
http://plan.test.com/planning/follow-progress-week/list-staff?branch_code=1&team_id=1&week_year=19/2019&staff_id[]=2
