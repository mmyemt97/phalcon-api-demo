<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class PlanWeeklyExpectedRevenue extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $staff_id;
    public $revenue;
    public $week;
    public $year;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("plan_weekly_expected_revenue");
    }
}