<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class PlanMonthlyExpectedRevenue extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $staff_id;
    public $month;
    public $year;
    public $realizable_revenue;
    public $realized_revenue;
    public $old_revenue;
    public $new_revenue;
    public $note;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("plan_monthly_expected_revenue");
    }
}