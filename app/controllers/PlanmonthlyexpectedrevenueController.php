<?php
namespace Website\Controllers;

use SVCodebase\Services\MailService;
use SVCodebase\Validators\BaseValidate;
use Website\Repos\PlanMonthlyExpectedRevenueRepo;

class PlanmonthlyexpectedrevenueController extends BaseController
{
    /**
     * @var $repo PlanMonthlyExpectedRevenueRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new PlanMonthlyExpectedRevenueRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-expected-revenue{query}",
     *     description="Kế hoạch trong tuần danh sách thống kê theo team",
     *     summary="Kế hoạch trong tuần danh sách thống kê theo team",
     *     tags={"Plan Monthly"},
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
     *         description="ID Team | min:1",
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
     *                  type="array",
     *                  @SWG\Items(
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
     *                              @SWG\Property(
     *                                  property="current",
     *                                  type="object",
     *                                  @SWG\Property(property="staff_id", type="integer"),
     *                                  @SWG\Property(property="month", type="integer"),
     *                                  @SWG\Property(property="total_realizable_revenue", type="integer"),
     *                                  @SWG\Property(property="total_realized_revenue", type="integer"),
     *                                  @SWG\Property(property="total_old_revenue", type="integer"),
     *                                  @SWG\Property(property="total_new_revenue", type="integer"),
     *                                  @SWG\Property(property="total_revenue", type="integer"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_1",
     *                                  type="object",
     *                                  @SWG\Property(property="staff_id", type="integer"),
     *                                  @SWG\Property(property="month", type="integer"),
     *                                  @SWG\Property(property="total_realizable_revenue", type="integer"),
     *                                  @SWG\Property(property="total_realized_revenue", type="integer"),
     *                                  @SWG\Property(property="total_old_revenue", type="integer"),
     *                                  @SWG\Property(property="total_new_revenue", type="integer"),
     *                                  @SWG\Property(property="total_revenue", type="integer"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_4",
     *                                  type="object",
     *                                  @SWG\Property(property="staff_id", type="integer"),
     *                                  @SWG\Property(property="month", type="integer"),
     *                                  @SWG\Property(property="total_realizable_revenue", type="integer"),
     *                                  @SWG\Property(property="total_realized_revenue", type="integer"),
     *                                  @SWG\Property(property="total_old_revenue", type="integer"),
     *                                  @SWG\Property(property="total_new_revenue", type="integer"),
     *                                  @SWG\Property(property="total_revenue", type="integer"),
     *                              ),
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
    public function planTeamByMonthAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'digit_min:1'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getPlanTeamByMonth();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-expected-revenue/detail{query}",
     *     description="Kế hoạch trong tuần danh sách thống kê theo  thành viên",
     *     summary="Kế hoạch trong tuần danh sách thống kê theo  thành viên",
     *     tags={"Plan Monthly"},
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
     *         description="ID Team | min:1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min:1",
     *         name="staff_id[]",
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
     *                  type="array",
     *                  @SWG\Items(
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
     *                              @SWG\Property(
     *                                  property="current",
     *                                  type="object",
     *                                  @SWG\Property(property="plan_monthly_expected_revenue_id", type="integer"),
     *                                  @SWG\Property(property="staff_id", type="integer"),
     *                                  @SWG\Property(property="month", type="integer"),
     *                                  @SWG\Property(property="note", type="string"),
     *                                  @SWG\Property(property="total_realizable_revenue", type="integer"),
     *                                  @SWG\Property(property="total_realized_revenue", type="integer"),
     *                                  @SWG\Property(property="total_old_revenue", type="integer"),
     *                                  @SWG\Property(property="total_new_revenue", type="integer"),
     *                                  @SWG\Property(property="total_revenue", type="integer"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_1",
     *                                  type="object",
     *                                  @SWG\Property(property="plan_monthly_expected_revenue_id", type="integer"),
     *                                  @SWG\Property(property="staff_id", type="integer"),
     *                                  @SWG\Property(property="month", type="integer"),
     *                                  @SWG\Property(property="note", type="string"),
     *                                  @SWG\Property(property="total_realizable_revenue", type="integer"),
     *                                  @SWG\Property(property="total_realized_revenue", type="integer"),
     *                                  @SWG\Property(property="total_old_revenue", type="integer"),
     *                                  @SWG\Property(property="total_new_revenue", type="integer"),
     *                                  @SWG\Property(property="total_revenue", type="integer"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_4",
     *                                  type="object",
     *                                  @SWG\Property(property="plan_monthly_expected_revenue_id", type="integer"),
     *                                  @SWG\Property(property="staff_id", type="integer"),
     *                                  @SWG\Property(property="month", type="integer"),
     *                                  @SWG\Property(property="note", type="string"),
     *                                  @SWG\Property(property="total_realizable_revenue", type="integer"),
     *                                  @SWG\Property(property="total_realized_revenue", type="integer"),
     *                                  @SWG\Property(property="total_old_revenue", type="integer"),
     *                                  @SWG\Property(property="total_new_revenue", type="integer"),
     *                                  @SWG\Property(property="total_revenue", type="integer"),
     *                              ),
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
    public function planTeamMemberOfTeamAction()
    {
        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            'team_id' => 'required|digit_min:1',
            //'staff_id' => 'isArray'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getPlanTeamMemberOfTeam();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Post(
     *     path="/planning/plan-monthly-expected-revenue/save",
     *     description="tạo + sửa doanh thu + note theo thành viên, id = 0 là tạo",
     *     summary="tạo + sửa doanh thu + note theo thành viên, id = 0 là tạo",
     *     tags={"Plan Monthly"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="  create = 0 | min : 0",
     *         name="plan_monthly_expected_revenue_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
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
     *         description="ID Team | min : 1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="ID Staff | min : 1",
     *         name="staff_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1000",
     *         name="old_revenue",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1000",
     *         name="new_revenue",
     *         in="path",
     *         required=false,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 200",
     *         name="note",
     *         in="path",
     *         required=false,
     *         type="string"
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
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="channel_code", type="string"),
     *                          @SWG\Property(property="team_id", type="integer"),
     *                          @SWG\Property(property="staff_id", type="integer"),
     *                          @SWG\Property(property="month", type="integer"),
     *                          @SWG\Property(property="year", type="integer"),
     *                          @SWG\Property(property="realizable_revenue", type="integer"),
     *                          @SWG\Property(property="realized_revenue", type="integer"),
     *                          @SWG\Property(property="old_revenue", type="integer"),
     *                          @SWG\Property(property="new_revenue", type="integer"),
     *                          @SWG\Property(property="note", type="string"),
     *                          @SWG\Property(property="created_by", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="updated_by", type="string"),
     *                          @SWG\Property(property="created_source", type="string"),
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

    public function createOrUpdateRevenueByMemberAction()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'plan_monthly_expected_revenue_id' => 'required|digit_min:0',
            'month_year' => 'required|month_year',
            'team_id' => 'required|digit_min:1',
            'staff_id' => 'required|digit_min:1',
            'old_revenue' => 'digit_min:1000',
            'new_revenue' => 'digit_min:1000',
            'note' => 'max:200',
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createOrUpdateRevenueByMember();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-monthly-expected-revenue/report{query}",
     *     description="report",
     *     summary="report",
     *     tags={"Plan Monthly"},
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
     *         description="min : 1",
     *         name="team_id",
     *         in="path",
     *         required=false,
     *         type="string"
     *     ),
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(property="data",type="object"),
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
    public function reportPlanMonthlyRevenueAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'month_year' => 'required|month_year',
            //'team_id' => 'digit_min:1'
            //'staff_id' => 'digit_min:1',
        ];
        BaseValidate::validator($request, $rules);

        $result = $this->repo->reportPlanMonthlyRevenue();

        $view = new \Phalcon\Mvc\View\Simple();
        $view->setViewsDir(APP_PATH . '/app/views/exports/');
        $view->render('planMonthlyExpectedRevenue', compact('result'));
        $content = $view->getContent();

        $auth = $this->auth->user();
        $email = $auth->email;
        $name = $auth->display_name;
        $params = [
            'to' => [$email => $name],
            'subject' => 'Doanh thu dự kiến tháng',
            'content' => $content,
            'cc' => ['tester' => 'nghiavt@nhanlucsieuviet.com']
        ];
        $response = MailService::send($params);
        return $this->outputSuccess($response);
    }
}