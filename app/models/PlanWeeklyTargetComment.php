<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class PlanWeeklyTargetComment extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $staff_id;
    public $week;
    public $year;
    public $comment;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("plan_weekly_target_comment");
    }
}