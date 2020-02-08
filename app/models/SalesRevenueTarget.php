<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class SalesRevenueTarget extends BaseModel
{
    public $id;
    public $channel_code;
    public $team_id;
    public $staff_id;
    public $master_kpi_code;
    public $date;
    public $quantity;
    public $value;
    public $week;
    public $month;
    public $year;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("sales_revenue_target");
    }
}