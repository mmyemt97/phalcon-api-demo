<?php
namespace Website\Repos;

use SVCodebase\Library\UtilArr;
use Website\Constant;
use Website\Models\SalesRevenueAchievement;

class SalesRevenueAchievementRepo extends BaseRepo
{
    protected $authService;

    public function __construct()
    {
        $this->authService = new \Website\Services\AuthService();
    }

    public function getListsByDate()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $team_id = $this->request->getQuery('team_id',['int']);
        $staff_id = $this->request->getQuery('staff_id');

        $columns = [
            'date',
            //'COUNT(*) as total_achievement',
            'SUM(quantity) as total_achievement',
            'SUM(revenue * quantity) as total_revenue'
        ];
        $r = SalesRevenueAchievement::buildModel();
        $r->columns($columns);
        //$r->whereIf('team_id', '=', $team_id);
        //$r->whereArray('staff_id', $staff_id);

        $dates = $this->request->getQuery('date');
        $between = [
            ['date', '>=', 'from'],
            ['date', '<=', 'to']
        ];
        foreach ($between as $param) {
            if (isset($dates[$param[2]])) {
                $r->wheres($param[0], $param[1], date('Y-m-d', $dates[$param[2]]));
            }
        }

        $r->orderBy('date DESC');
        $r->groupBy('date');
        $result = $r->paginate($this->request->getQuery('page',['int'],1));
        return $result;
    }

    public function getListsByTeam()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $team_id = $this->request->getQuery('team_id',['int']);
        $teams = $this->authService->filterTeams($team_id);

        $time = $this->request->getQuery('date');
        $date = date('Y-m-d', $time);

        $columns = [
            'team_id',
            //'COUNT(*) as total_achievement',
            'SUM(quantity) as total_achievement',
            'SUM(revenue * quantity) as total_revenue'
        ];
        $r = SalesRevenueAchievement::buildModel();
        $r->columns($columns);
        $r->whereIf('team_id', '=', $team_id);
        $r->wheres('date', '=', $date);
        $r->orderBy('team_id DESC');
        $r->groupBy('team_id');
        $result = $r->get()->toArray();
        $result = UtilArr::keyBy('team_id',$result);

        foreach ($teams as $index => $team){
            if(isset($result[$team['id']])){
                $teams[$index]['total_achievement'] = (int) $result[$team['id']]['total_achievement'];
                $teams[$index]['total_revenue'] = (int) $result[$team['id']]['total_revenue'];
            }else{
                $teams[$index]['total_achievement'] = 0;
                $teams[$index]['total_revenue'] = 0;
            }
        }

        return $teams;
    }

    public function getListsByTeamMember()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $team_id = $this->request->getQuery('team_id',['int']);
        $staff_id = $this->request->getQuery('staff_id');
        $members = $this->authService->filterMemberByTeamId($team_id, $staff_id);

        $time = $this->request->getQuery('date');
        $date = date('Y-m-d', $time);

        $columns = [
            'staff_id',
            //'COUNT(*) as total_achievement',
            'SUM(quantity) as total_achievement',
            'SUM(revenue * quantity) as total_revenue'
        ];
        $r = SalesRevenueAchievement::buildModel();
        $r->columns($columns);
        $r->wheres('team_id', '=', $team_id);
        $r->whereArray('staff_id', $this->request->getQuery('staff_id'));
        $r->wheres('date', '=', $date);
        $r->orderBy('staff_id DESC');
        $r->groupBy('staff_id');
        $result = $r->get()->toArray();
        $result = UtilArr::keyBy('staff_id',$result);

        foreach ($members as $index => $member){
            if(isset($result[$member['id']])){
                $members[$index]['total_achievement'] = (int) $result[$member['id']]['total_achievement'];
                $members[$index]['total_revenue'] = (int) $result[$member['id']]['total_revenue'];
            }else{
                $members[$index]['total_achievement'] = 0;
                $members[$index]['total_revenue'] = 0;
            }
        }

        return $members;
    }

    public function getDetailOfMember()
    {
        $branch_code = $this->request->getQuery('branch_code');
        $staff = $this->request->getQuery('staff_id');
        $time = $this->request->getQuery('date');
        $date = date('Y-m-d', $time);

        $columns = [
            'id',
            'employer_name',
            'quantity',
            '(revenue * quantity) as total_revenue'
        ];
        $r = SalesRevenueAchievement::buildModel();
        $r->columns($columns);
        //$r->wheres('channel_code', '=', $channel_code);
        $r->whereIf('team_id', '=', $this->request->getQuery('team_id'));
        $r->wheres('staff_id','=', $staff);
        $r->wheres('date', '=', $date);
        $r->orderBy('employer_name DESC');
        //$result = $r->get()->toArray();
        $r->limit($this->request->get('per_page', ['int'], 20));
        $result = $r->paginate($this->request->get('page', ['int'], 1));

        return $result;
    }

    public function createAchievementOfMember()
    {
        $request = (array) $this->request->getJsonRawBody(true);
        $date = $request['date'];
        $datetime = (new \DateTime())->setTimestamp($date);
        $month = $datetime->format('m');
        $week = $datetime->format("W");
        $year = $datetime->format("Y");

        $channel_code = $this->auth->userChannelCode();
        $params = [
            'channel_code' => $channel_code,
            'team_id' => $request['team_id'],
            'staff_id' => $request['staff_id'],
            'date' => $datetime->format('Y-m-d'),
            'week' => $week,
            'month' => $month,
            'year' => $year,
            'employer_name' => $request['employer_name'],
            'quantity' => $request['quantity'],
            'revenue' => $request['revenue'],
            'created_source' => Constant::CREATED_SOURCE_crm,
        ];

        $model = new SalesRevenueAchievement();
        $model->create($params);
        return $model;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function updateAchievementOfMember()
    {
        $request = (array) $this->request->getJsonRawBody(true);
        $only = ['employer_name', 'quantity', 'revenue', 'achievement_id'];
        $params = UtilArr::only($request, $only);

        $data = SalesRevenueAchievement::buildModel()
            ->wheres('id', '=', (int) $params['achievement_id'])
            ->firstOrFail();

        $data->update($params);
        return $data;
    }

    /**
     * @return mixed
     * @throws \Exception
     */
    public function deleteAchievementOfMember()
    {
        $request = (array) $this->request->getJsonRawBody(true);

        $id = $request['achievement_id'];

        $data = SalesRevenueAchievement::buildModel()
            ->wheres('id', '=', $id)
            ->firstOrFail();

        return (bool) $data->delete();
    }

    public function exportAchievement()
    {
        //dữ liệu lấy ra chi tiết nhóm viên theo team_id dựa vào jwt auth
        $branch_code = $this->request->getQuery('branch_code');
        $auth = $this->auth->user();
        $team_id = $auth->team->id;
        $members = $this->authService->filterMemberByTeamId($team_id, null);
        $listMembers = array_column($members,'id');

        $columns = [
            'staff_id',
            'date',
            //'COUNT(*) as total_achievement',
            'SUM(quantity) as total_achievement',
            'SUM(revenue * quantity) as total_revenue'
        ];

        $r = SalesRevenueAchievement::buildModel();
        $r->columns($columns);
        $r->wheres('team_id', '=', $team_id);
        $r->whereArray('staff_id', $listMembers);

        $dates = $this->request->getQuery('date');
        $between = [
            ['date', '>=', 'from'],
            ['date', '<=', 'to']
        ];
        foreach ($between as $param) {
            if (isset($dates[$param[2]])) {
                $r->wheres($param[0], $param[1], date('Y-m-d', $dates[$param[2]]));
            }
        }

        $r->groupBy('date,staff_id');
        $r->orderBy('date DESC');
        $r->orderBy('staff_id DESC');
        $result = $r->get()->toArray();

        $resultJoinMember = UtilArr::joinBy($result, 'staff_id', $members, 'id');
        $resultKeyDate = UtilArr::groupBy('date', $resultJoinMember);

        return $resultKeyDate;
    }
}