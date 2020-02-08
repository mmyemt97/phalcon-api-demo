<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class PlanMonthlyExpectedRank extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $month;
    public $year;
    public $rank_type;
    public $rank;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("plan_monthly_expected_rank");
    }
}