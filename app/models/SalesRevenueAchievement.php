<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class SalesRevenueAchievement extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $staff_id;
    public $date;
    public $revenue;
    public $quantity;
    public $employer_name;
    public $week;
    public $month;
    public $year;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("sales_revenue_achievement");
    }
}