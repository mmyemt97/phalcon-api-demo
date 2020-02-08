<?php

namespace Website\Controllers;

use SVCodebase\Validators\BaseValidate;
use Website\Repos\ProgressMonthlyRepo;

class ProgressmonthlyController extends BaseController
{
    /**
     * @var $repo ProgressMonthlyRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new ProgressMonthlyRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/follow-progress-month/list-team{query}",
     *     description="Theo dõi tiến độ của Team theo tháng (Test)",
     *     summary="Theo dõi tiến độ của Team theo tháng (Test)",
     *     tags={"Progress"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tháng/Năm",
     *         name="month_year",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team",
     *         name="team_id",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="id_team",
     *                      type="object",
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="channel_code", type="string"),
     *                          @SWG\Property(property="name", type="string"),
     *                          @SWG\Property(property="type", type="string"),
     *                          @SWG\Property(property="status", type="string"),
     *                          @SWG\Property(property="created_at", type="string"),
     *                          @SWG\Property(property="created_by", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="updated_by", type="string"),
     *                          @SWG\Property(property="created_source", type="string"),
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                                  @SWG\Property(
     *                                      property="weeks",
     *                                      type="object",
     *                                      @SWG\Property(property="key_week", type="integer"),
     *                                  ),
     *                          @SWG\Property(property="total_progress", type="integer"),
     *                          @SWG\Property(property="target", type="integer"),
     *                          @SWG\Property(property="percent_completed", type="integer"),
     *                      ),
     *                   ),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                type="integer",
     *                property="code"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *      )
     *  ))
     */

    public function processMonthlyListTeamAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'digit_min:1'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = array_values($this->repo->getProcessMonthListTeam());
        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/follow-progress-month/list-staff{query}",
     *     description="Theo dõi tiến độ thành viên của team theo tháng (Test)",
     *     summary="Theo dõi tiến độ thành viên của team theo tháng (Test)",
     *     tags={"Progress"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tháng/Năm",
     *         name="month_year",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Staff",
     *         name="staff_id",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="staff_id",
     *                      type="object",
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="email", type="string"),
     *                          @SWG\Property(property="login_name", type="string"),
     *                          @SWG\Property(property="display_name", type="string"),
     *                          @SWG\Property(property="division_code", type="string"),
     *                          @SWG\Property(property="data_group_code", type="string"),
     *                          @SWG\Property(property="team_id", type="string"),
     *                          @SWG\Property(property="team_role", type="string"),
     *                          @SWG\Property(property="team_member_id", type="string"),
     *                          @SWG\Property(property="team_member_status", type="string"),
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                                  @SWG\Property(
     *                                      property="weeks",
     *                                      type="object",
     *                                      @SWG\Property(property="key_week", type="integer"),
     *                                  ),
     *                          @SWG\Property(property="total_progress", type="integer"),
     *                          @SWG\Property(property="target", type="integer"),
     *                          @SWG\Property(property="percent_completed", type="integer"),
     *                      ),
     *                   ),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                type="integer",
     *                property="code"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *      )
     *  ))
     */

    public function processMonthlyListStaffAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'required|digit_min:1',
            //'staff_id' => 'digit_min:1'
        ];
        BaseValidate::validator($this->request->getQuery(), $rules);
        $result = array_values($this->repo->getProcessMonthlyListStaff());
        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/follow-progress-week/list-team{query}",
     *     description="Theo dõi tiến độ của Team theo tuần trong tháng (Test)",
     *     summary="Theo dõi tiến độ của Team theo tuần trong tháng (Test)",
     *     tags={"Progress"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tháng/Năm",
     *         name="month_year",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team",
     *         name="team_id",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="staff_id",
     *                      type="object",
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="channel_code", type="string"),
     *                          @SWG\Property(property="branch_code", type="string"),
     *                          @SWG\Property(property="name", type="string"),
     *                          @SWG\Property(property="type", type="string"),
     *                          @SWG\Property(property="status", type="string"),
     *                          @SWG\Property(property="created_at", type="string"),
     *                          @SWG\Property(property="created_by", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="updated_by", type="string"),
     *                          @SWG\Property(property="created_source", type="string"),
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                                  @SWG\Property(
     *                                      property="days",
     *                                      type="object",
     *                                      @SWG\Property(property="key_day", type="integer"),
     *                                  ),
     *                          @SWG\Property(property="total_progress", type="integer"),
     *                          @SWG\Property(property="target", type="integer"),
     *                          @SWG\Property(property="percent_completed", type="integer"),
     *                      ),
     *                   ),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                type="integer",
     *                property="code"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *      )
     *  ))
     */
    public function processWeekListTeamAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'digit_min:1'
        ];
        BaseValidate::validator($this->request->getQuery(), $rules);
        $result = array_values($this->repo->getProcessWeekListTeam());
        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/follow-progress-week/list-week{query}",
     *     description="Theo dõi tiến độ của các tuần trong tháng (Test)",
     *     summary="Theo dõi tiến độ của các tuần trong tháng (Test)",
     *     tags={"Progress"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tháng/Năm",
     *         name="month_year",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="Tuần trong tháng Tuần/Năm",
     *         name="week_year",
     *         in="path",
     *         required=false,
     *         type="string"
     *     ),
     *
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                                  @SWG\Property(
     *                                      property="days",
     *                                      type="object",
     *                                      @SWG\Property(property="key_day", type="integer"),
     *                                  ),
     *                          @SWG\Property(property="total_progress", type="integer"),
     *                          @SWG\Property(property="target", type="integer"),
     *                          @SWG\Property(property="percent_completed", type="integer"),
     *                      ),
     *
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                type="integer",
     *                property="code"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *      )
     *  ))
     */
    public function processWeekListWeekAction()
    {
        $this->ruleWeekYearInMonth();

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'required|digit_min:1',
            'month_year' => 'required|month_year',
            'week_year' => 'week_year|week_in_month_year'
        ];
        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getProcessWeekListWeek();
        return $this->outputSuccess($result);
    }

    private function ruleWeekYearInMonth()
    {
        BaseValidate::macro('week_in_month_year', function ($field) {
            $callback = new \Phalcon\Validation\Validator\Callback([
                'callback' => function ($data) use ($field) {
                    if (empty($data['month_year'])){
                        return true;
                    }
                    $weeks = array();
                    $dt = explode('/', $data['month_year']);
                    $month = (int)$dt[0];
                    $year = (int)$dt[1];
                    $date = new \DateTime();
                    $date->setDate($year, $month, 1);
                    $weekStart = (int)$date->format('W');
                    $current = (new \DateTimeImmutable())->setISODate($year, $weekStart);
                    for ($i = 0; $i < 6; $i++) {
                        $dateStart = $current->modify('+' . $i . 'week');
                        if ($dateStart >= $current->modify('+1 month')) {
                            break;
                        }
                        array_push($weeks, (int)$dateStart->format('W'));
                    }
                    $date = explode('/', $data[$field]);
                    return in_array($date[0], $weeks);
                },
                'message' => 'Trường :attr_name có giá trị tuần/năm không nằm trong tháng hợp lệ',
                'allowEmpty' => true
            ]);

            $this->add($field, $callback);
        });
    }

    /**
     * @SWG\Get(
     *     path="/planning/follow-progress-week/list-staff{query}",
     *     description="Theo dõi tiến độ thành viên của Team theo tuần trong tháng (Test)",
     *     summary="Theo dõi tiến độ của thành viên Team theo tuần trong tháng (Test)",
     *     tags={"Progress"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Tuần/Năm",
     *         name="week_year",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Team",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Staff",
     *         name="staff_id",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(
     *                  property="data",
     *                  type="object",
     *                  @SWG\Property(
     *                      property="staff_id",
     *                      type="object",
     *                       @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="email", type="string"),
     *                          @SWG\Property(property="login_name", type="string"),
     *                          @SWG\Property(property="display_name", type="string"),
     *                          @SWG\Property(property="division_code", type="string"),
     *                          @SWG\Property(property="data_group_code", type="string"),
     *                          @SWG\Property(property="team_id", type="string"),
     *                          @SWG\Property(property="team_role", type="string"),
     *                          @SWG\Property(property="team_member_id", type="string"),
     *                          @SWG\Property(property="team_member_status", type="string"),
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                                  @SWG\Property(
     *                                      property="week",
     *                                      type="object",
     *                                      @SWG\Property(property="key_week", type="integer"),
     *                                  ),
     *                          @SWG\Property(property="total_progress", type="integer"),
     *                          @SWG\Property(property="target", type="integer"),
     *                          @SWG\Property(property="percent_completed", type="integer"),
     *                      ),
     *                   ),
     *              ),
     *          ),
     *     ),
     *     @SWG\Response(
     *         response=400,
     *         description="Bad request",
     *         @SWG\Schema(
     *             @SWG\Property(
     *                type="integer",
     *                property="code"
     *             ),
     *             @SWG\Property(
     *                 property="message",
     *                 type="string"
     *             )
     *         )
     *      )
     *  ))
     */
    public function processWeekListStaffAction()
    {
        $rules = [
            'branch_code' => 'required',
            'week_year' => 'required|week_year',
            'team_id' => 'required|digit_min:1',
            //'staff_id' => 'digit_min:1'
        ];
        BaseValidate::validator($this->request->getQuery(), $rules);
        $result = array_values($this->repo->getProcessWeekListStaff());
        return $this->outputSuccess($result);
    }
}