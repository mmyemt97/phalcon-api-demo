<?php
namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use Website\Models\PlanMonthlyExpectedRank;
use Website\Constant;
use Website\ErrorMessage;
use Website\Services\AuthService;
use Website\StatusCode;

class PlanMonthlyRankRepo extends BaseRepo
{
    protected $authService;
    protected $listRankType;

    public function __construct()
    {
        $this->authService = new AuthService();
        $this->listRankType = Constant::listRankType();
    }

    /**
     * Rank được lấy theo team_id của của user đăng nhập hệ thống
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getListRank()
    {
        $total = $this->authService->getCountTeam();
        $auth = $this->auth->user();
        $team_id = $auth->team->id;

        $branch_code = $this->request->getQuery('branch_code');
        $monthYear = $this->request->getQuery('month_year');
        $dt = explode('/', $monthYear);
        $month = (int)$dt[0];
        $year = (int)$dt[1];

        $columns = ['id AS plan_monthly_rank_id', 'team_id', 'rank', 'rank_type', 'month', 'year'];
        $p = PlanMonthlyExpectedRank::buildModel();
        $p->columns($columns);
        $p->wheres('team_id', '=', $team_id);
        $p->wheres('month', '=', $month);
        $p->wheres('year', '=', $year);
        $p->inWhere('rank_type', $this->listRankType);
        $p->groupBy('rank_type');
        $p->orderBy('month ASC');
        $result = $p->get()->toArray();

        $null = [
            'plan_monthly_rank_id' => 0,
            'team_id' => $team_id,
            'rank' => 0,
            'month' => $month,
            'year' => $year,
            'rank_type' => ''
        ];

        $ranks = UtilArr::keyBy('rank_type', $result);

        foreach ($this->listRankType as $index => $type) {
            $resultRank[$index] = $ranks[$type] ?? array_merge($null, ['rank_type' => $type]);
        }

        $result = ['total' => $total, 'result' => $resultRank];

        return $result;
    }

    /**
     * @return bool
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function createOrUpdateRankByTeam()
    {
        $total = $this->authService->getCountTeam();
        $auth = $this->auth->user();
        $team_id = $auth->team->id;

        $request = (array)$this->request->getJsonRawBody(true);
        $monthYear = $request['month_year'];
        $dt = explode('/', $monthYear);
        $month = (int)$dt[0];
        $year = (int)$dt[1];

        foreach ($request['result'] as $value) {

            if (!$this->checkMaxRankByRankType($total, $value)) {
                throw new \Exception(ErrorMessage::EXCEED_MAX, StatusCode::ERROR_CODE_DEFAULT);
            }

            $id = $value['plan_monthly_rank_id'];

            $object = $id <= 0
                ? new PlanMonthlyExpectedRank()
                : PlanMonthlyExpectedRank::buildModel()->wheres('id', '=', $id)->firstOrFail();

            $object->rank = $value['rank'];
            $object->rank_type = $value['rank_type'];
            $object->month = $month;
            $object->year = $year;
            $object->channel_code = $this->auth->userChannelCode();
            $object->team_id = $team_id;
            //$object->team_id = $request['team_id'];
            $object->created_source = Constant::CREATED_SOURCE_crm;
            $object->save();
        }

        return true;
    }

    private function checkMaxRankByRankType($total, $rank)
    {
        $totalRank = Constant::wrapCountTotal($total);
        $type = $rank['rank_type'];
        return $totalRank[$type] >= (int)$rank['rank'];
    }
}