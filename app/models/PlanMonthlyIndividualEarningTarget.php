<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class PlanMonthlyIndividualEarningTarget extends BaseModel
{
    public $id;
    public $branch_code;
    public $team_id;
    public $staff_id;
    public $month;
    public $year;
    public $experience_month;
    public $expected_revenue;
    public $total_salary;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("plan_monthly_individual_earning_target");
    }
}