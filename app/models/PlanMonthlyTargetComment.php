<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class PlanMonthlyTargetComment extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $month;
    public $year;
    public $explain_note;
    public $plan_note;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("plan_monthly_target_comment");
    }
}