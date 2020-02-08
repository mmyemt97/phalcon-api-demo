<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class PlanMonthlyWorkload extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $staff_id;
    public $month;
    public $year;
    public $old_customer_quantity;
    public $new_customer_quantity;
    public $old_call_quantity;
    public $new_call_quantity;
    public $old_email_quantity;
    public $new_email_quantity;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("plan_monthly_workload");
    }
}