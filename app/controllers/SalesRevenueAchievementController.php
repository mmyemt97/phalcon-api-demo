<?php
namespace Website\Controllers;

use Website\Repos\SalesRevenueAchievementRepo;

class SalesrevenueachievementController extends BaseController
{
    /**
     * @var SalesRevenueAchievementRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new SalesRevenueAchievementRepo();
    }

    use SalesrevenueachievementdetailTrait;
    use SalesrevenueachievementlistTrait;
}