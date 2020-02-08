<?php
namespace Website\Services;

use SVCodebase\Library\Constant;
use SVCodebase\Services\BaseService;

class AuthService extends BaseService
{
    protected function apiUrl()
    {
        return 'https://auth.sieuviet-team.com';
    }

    /**
     * @param int $team_id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getMemberByTeamId(int $team_id, array $params = []): array
    {
        $url = $this->apiUrl().'/member-of-team/'.$team_id;
        return $this->get($url, $params);
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTeams(array $params = []): array
    {
        $url = $this->apiUrl().'/team';
        return $this->get($url, $params);
    }

    /**
     * @param int $team_id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getTeam(int $team_id, array $params = []): array
    {
        $teams = $this->getTeams($params);

        foreach ($teams as $team){
            if($team['id'] == $team_id){
                return $team;
            }
        }

        return [];
    }

    /**
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getCountTeam():array
    {
        $url = $this->apiUrl().'/count-team-by-staff';
        return $this->get($url);
    }

    /**
     * sử dụng cho team theo jwt
     * @param $team_id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function filterTeams($team_id)
    {
        if (!$this->checkRoleCSKH()) {
            $params = [
                'branch_code' => $this->request->getQuery('branch_code')
            ];

            $teams = ($team_id) ? [$this->getTeam($team_id, $params)] : $this->getTeams($params);

            if (empty($teams[0])) {
                //throw new \Exception('Nhóm viên không tồn tại', 404);
                return [];
            }

            return $teams;
        }

        $user = $this->auth->user();

        if ($team_id && $team_id != $user->team->id) {
            throw new \Exception(\SVCodebase\Library\ErrorMessages::PERMISSION_IS_NOT_ALLOWED,
                \SVCodebase\Library\StatusCode::PERMISSION_NOT_ALLOWED);
        }

        return [(array)$user->team];
    }

    /**
     * @param $team_id
     * @param null $staff_id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Exception
     */
    public function filterMemberByTeamId($team_id, $staff_id):array
    {
        $list_filter_staff = is_array($staff_id) ? $staff_id : [$staff_id];

        if (!$this->checkRoleCSKH()) {
            return $this->filterMember($team_id, $list_filter_staff);
        }

        if ($team_id && $team_id != $this->auth->user()->team->id) {
            throw new \Exception(\SVCodebase\Library\ErrorMessages::PERMISSION_IS_NOT_ALLOWED,
                \SVCodebase\Library\StatusCode::PERMISSION_NOT_ALLOWED);
        }

        $list_staff_id = $this->auth->dataPermissionById();

        if (empty($list_filter_staff[0])) {
            return $this->filterMember($team_id, $list_staff_id);
        }

        $list_filter_same = array_intersect($list_staff_id, $list_filter_staff);

        //tồn tại dữ liệu filter không nằm trong quyền cho phép
        if (count($list_filter_same) != count($list_filter_staff)) {
            throw new \Exception(\SVCodebase\Library\ErrorMessages::PERMISSION_IS_NOT_ALLOWED,
                \SVCodebase\Library\StatusCode::PERMISSION_NOT_ALLOWED);
        }

        return $this->filterMember($team_id, $list_filter_same);
    }

    private function checkRoleCSKH()
    {
        $cskhRole = [Constant::DIVISION_CODE_CUSTOMER_CARE_MEMBER, Constant::DIVISION_CODE_CUSTOMER_CARE_LEADER];
        return in_array($this->auth->user()->division_code, $cskhRole);
    }

    /**
     * @param $team_id
     * @param $list_staff_id
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function filterMember($team_id, array $list_staff_id):array
    {
        $params = [
            'branch_code' => $this->request->getQuery('branch_code')
        ];
        $members = $this->getMemberByTeamId($team_id, $params);
        
        if (empty($list_staff_id[0])) {
            return $members;
        }

        return array_filter($members, function($member) use ($list_staff_id){
            return in_array($member['id'], $list_staff_id);
        });
    }
}