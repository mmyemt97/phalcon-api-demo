<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class BaseSalary extends BaseModel
{
    public $id;
    public $brand_code;
    public $booking_box_id;
    public $experience_month_from;
    public $experience_month_to;
    public $based_salary;
    use ModifyTrait;

    public function initialize()
    {
        $this->setSource("base_salary");
    }
}