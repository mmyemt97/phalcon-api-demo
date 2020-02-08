<?php
namespace Website\Repos;

use Website\Models\SalesRevenueTarget;
use SVCodebase\Library\UtilArr;
use Website\Constant;
use Website\ErrorMessage;
use Website\StatusCode;

class SalesTargetTomorrowRepo extends BaseRepo
{
    protected $codes;
    protected $authService;

    public function __construct()
    {
        $this->codes = Constant::listKpiCode();
        $this->authService = new \Website\Services\AuthService();
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getLists()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $staff_id = $this->request->getQuery('staff_id');
        $tomorrow = (new \DateTime())->modify('+1 days')->format('Y-m-d');

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
        $s->wheres('date', '=', $tomorrow);
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
     */
    private function nullTarget()
    {
        $nullTarget = ['total_quantity' => 0, 'total_value' => 0];
        foreach ($this->codes as $code) {
            $nullTarget[$code.'_quantity'] = "0";
            $nullTarget[$code.'_value'] = "0";
        }
        return $nullTarget;
    }

    /**
     * using nullTarget + tranformTarget
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getDetail()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $team_id = $this->request->getQuery('team_id',['int']);
        $staff_id = $this->request->getQuery('staff_id');

        $members = $this->authService->filterMemberByTeamId($team_id, $staff_id);

        $tomorrow = (new \DateTime())->modify('+1 days')->format('Y-m-d');

        $columns = ['id AS sale_target_id', 'quantity', 'value', 'master_kpi_code', 'staff_id', 'team_id'];
        $s = SalesRevenueTarget::buildModel();
        $s->columns($columns);
        $s->wheres('team_id', '=', $team_id);
        $s->whereArray('staff_id', $staff_id);
        $s->wheres('date', '=', $tomorrow);
        $s->inWhere('master_kpi_code', $this->codes);
        $s->orderBy('master_kpi_code ASC');
        $s->orderBy('id ASC');
        $targets = $s->get()->toArray();

        $targets = UtilArr::groupBy('staff_id', $targets);

        $nullTarget = $this->nullTargetItem($team_id);

        foreach ($members as $index => $member) {
            $members[$index]['target'] = isset($targets[$member['id']]) ?
                $this->tranformTargetItem($targets[$member['id']], $nullTarget) :
                $nullTarget;
        }

        return $members;
    }

    /**
     * @param $team_id
     * @return array
     */
    private function nullTargetItem($team_id)
    {
        $item['total'] = ['quantity' => 0, 'value' => 0];

        foreach ($this->codes as $index => $code) {
            $item[$code] = [
                'sale_target_id' => "0",
                'quantity' => "0",
                'value' => "0",
                'master_kpi_code' => $code,
                'team_id' => $team_id,
            ];
        }

        return $item;
    }

    /**
     * @param $targets
     * @param $nullTarget
     * @return mixed
     */
    private function tranformTargetItem($targets, $nullTarget)
    {
        $target['total'] = ['quantity' => 0, 'value' => 0];
        foreach ($targets as $value) {
            $target[$value['master_kpi_code']] = $value;
            $target['total']['quantity'] += $value['quantity'];
            $target['total']['value'] += $value['value'];
        }
        $target += $nullTarget;
        return $target;
    }

    /**
     * @return mixed|SalesRevenueTarget
     * @throws \Exception
     */
    public function createOrUpdate()
    {
        $request = (array) $this->request->getJsonRawBody(true);
        $id = $request['sale_target_id'] ?? 0;

        return $id > 0 ? $this->update($id) : $this->create();
    }

    /**
     * @param $id
     * @return SalesRevenueTarget
     * @throws \Exception
     */
    public function update($id)
    {
        $target = SalesRevenueTarget::buildModel()
            ->wheres('id', '=', $id)
            ->firstOrFail();

        $params = $this->params();

        $check = $target->date == $params['date'] &&
                 $target->staff_id == $params['staff_id'] &&
                 $target->master_kpi_code == $params['master_kpi_code'] &&
                 //$target->channel_code == $params['channel_code'] &&
                 $target->team_id == $params['team_id'];

        if (!$check) {
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_UPDATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $target->update($params);
        return $target;
    }

    /**
     * @return SalesRevenueTarget
     * @throws \Exception
     */
    public function create()
    {
        $params = $this->params();
        $null = ['value' => 0, 'quantity' => 0];
        $params += $null;

        $check = SalesRevenueTarget::buildModel()
            ->wheres('date', '=', $params['date'])
            ->wheres('staff_id', '=', $params['staff_id'])
            ->wheres('master_kpi_code', '=', $params['master_kpi_code'])
            ->findFirst();
        if($check){
            throw new \InvalidArgumentException(ErrorMessage::INVALID_DATA_TO_CREATE, StatusCode::ERROR_CODE_DEFAULT);
        }

        $target = new SalesRevenueTarget();
        $target->create($params);
        return $target;
    }

    /**
     * render param create and update
     * @return array
     */
    private function params()
    {
        $request = $this->request->getJsonRawBody(true);
        $date = $request['date'];
        $datetime = (new \DateTime())->setTimestamp($date);
        $month = $datetime->format('m');
        $week = $datetime->format("W");
        $year = $datetime->format("Y");

        $params = [
            'date' => $datetime->format('Y-m-d'),
            'month' => $month,
            'week' => $week,
            'year' => $year,
            'team_id' => $request['team_id'],
            'staff_id' => $request['staff_id'],
            'master_kpi_code' => $request['master_kpi_code'],
            //'channel_code' => $request['channel_code'],
            'channel_code' => $this->auth->userChannelCode(),
            'created_source' => Constant::CREATED_SOURCE_crm,
        ];

        if(isset($request['value'])){
            $params['value'] = $request['value'];
        }

        if(isset($request['quantity'])){
            $params['quantity'] = $request['quantity'];
        }

        return $params;
    }
}