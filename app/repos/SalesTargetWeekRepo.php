<?php
namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use Website\Models\SalesRevenueTarget;
use Website\Constant;
use Website\Services\AuthService;

class SalesTargetWeekRepo extends BaseRepo
{
    protected $codes;
    protected $authService;

    public function __construct()
    {
        $this->codes = Constant::listKpiCode();
        $this->authService = new AuthService();
    }

    public function getLists()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $staff_id = $this->request->getQuery('staff_id');
        $week = $this->request->getQuery('week', ['int'], 0);
        $year = date('Y');

        $team_id = $this->request->getQuery('team_id',['int']);
        $teams = $this->authService->filterTeams($team_id);

        $columns = ['team_id'];
        foreach ($this->codes as $code) {
            $columns[] = "SUM(IF(master_kpi_code='$code',quantity,0)) AS {$code}_quantity";
            $columns[] = "SUM(IF(master_kpi_code='$code',value,0)) AS {$code}_value";
        }
        $columns[] = "SUM(quantity) AS total_quantity";
        $columns[] = "SUM(value) AS total_value";

        $s = SalesRevenueTarget::buildModel();
        $s->columns($columns);
        $s->wheres('week', '=', $week);
        $s->wheres('year', '=', $year);
        $s->whereIf('team_id', '=', $team_id);
        //$s->whereArray('staff_id', $staff_id);
        $s->inWhere('master_kpi_code', $this->codes);
        $s->groupBy('team_id');
        $s->orderBy('team_id ASC');
        $targets = $s->get()->toArray();

        $targets = UtilArr::keyBy('team_id', $targets);

        $nullTarget = $this->nullTarget();

        foreach ($teams as $index => $team){
            $item = $targets[$team['id']] ?? [];
            $item += $nullTarget;
            $teams[$index]['target'] = $item;
        }

        return $teams;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getDetail()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $team_id = $this->request->getQuery('team_id');
        //$staff_id = $this->request->getQuery('staff_id');
        $week = $this->request->getQuery('week',['int']);
        $year = date('Y');

        $columns = ['date'];
        foreach ($this->codes as $code) {
            $columns[] = "SUM(IF(master_kpi_code='$code',quantity,0)) AS {$code}_quantity";
            $columns[] = "SUM(IF(master_kpi_code='$code',value,0)) AS {$code}_value";
        }
        $columns[] = "SUM(quantity) AS total_quantity";
        $columns[] = "SUM(value) AS total_value";
        $s = SalesRevenueTarget::buildModel();
        $s->columns($columns);
        $s->inWhere('master_kpi_code', $this->codes);
        $s->wheres('team_id', '=', $team_id);
        //$s->whereArray('staff_id',  $staff_id);
        $s->wheres('week', '=', $week);
        $s->wheres('year', '=', $year);
        $s->groupBy('date');
        $s->orderBy('date ASC');
        $targetWeek = $s->get()->toArray();
        $targetWeek = UtilArr::keyBy('date', $targetWeek);

        $start = (new \DateTime())->setISODate(date('Y'), $week);
        $end = (new \DateTime())->setISODate(date('Y'), $week)->modify('+6 days');
        $interval = new \DateInterval('P1D');
        $end->add($interval);
        $periods = new \DatePeriod($start, $interval, $end);

        $nullTarget = $this->nullTarget();

        foreach ($periods as $period) {
            $current = $period->format('Y-m-d');
            $targetWeek[$current] = $targetWeek[$current] ?? $nullTarget + ['date'=>$current];
        }

        return $targetWeek;
    }

    /**
     * @return array
     */
    private function nullTarget()
    {
        $nullTarget = ['total_quantity' => 0, 'total_value' => 0];
        foreach ($this->codes as $code) {
            $nullTarget[$code.'_quantity'] = 0;
            $nullTarget[$code.'_value'] = 0;
        }
        return $nullTarget;
    }
}