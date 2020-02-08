<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class CommissionConfig extends BaseModel
{
    public $id;
    public $brand_code;
    public $team_role;
    public $experience_month_from;
    public $experience_month_to;
    public $realized_revenue_from;
    public $realized_revenue_to;
    public $commission;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("commission_config");
    }
}