<?php
namespace Website\Models;

use SVCodebase\Models\ModifyTrait;

class BaseModel extends \SVCodebase\Models\BaseModel
{
	public function beforeCreate()
    {
        $this->created_at = date("Y-m-d H:i:s");
        $this->updated_at = date("Y-m-d H:i:s");
    }

    public function beforeUpdate()
    {
        $this->updated_at = date("Y-m-d H:i:s");
    }
}