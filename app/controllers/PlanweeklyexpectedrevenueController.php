<?php

namespace Website\Controllers;

use SVCodebase\Services\MailService;
use SVCodebase\Validators\BaseValidate;
use Website\Repos\PlanWeeklyExpectedRevenueRepo;

class PlanweeklyexpectedrevenueController extends BaseController
{
    /**
     * @var $repo PlanWeeklyExpectedRevenueRepo
     */
    protected $repo;

    public function onConstruct()
    {
        $this->repo = new PlanWeeklyExpectedRevenueRepo();
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-weekly-expected-revenue{query}",
     *     description="Kế hoạch trong tuần danh sách thống kê theo team",
     *     summary="Kế hoạch trong tuần danh sách thống kê theo team",
     *     tags={"Plan Weekly"},
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
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="next",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_1",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_2",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_3",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_4",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                      ),
     *                      @SWG\Property(
     *                          property="calc",
     *                          type="object",
     *                              @SWG\Property(property="avarage_4_week", type="integer"),
     *                              @SWG\Property(property="compare_target_current", type="integer"),
     *                              @SWG\Property(property="compare_target_back_1", type="integer"),
     *                              @SWG\Property(property="compare_target_4_week", type="integer"),
     *                          ),
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
    public function planTeamByWeekAction()
    {
        $rules = [
            'branch_code' => 'required',
            'team_id' => 'digit_min:1',
            //'staff_id' => 'required',
            'week_year' => 'required|week_year',
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getPlanTeamByWeek();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-weekly-expected-revenue/detail{query}",
     *     description="Kế hoạch trong tuần danh sách thống kê theo thành viên",
     *     summary="Kế hoạch trong tuần danh sách thống kê theo thành viên",
     *     tags={"Plan Weekly"},
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
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description=" min:1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Parameter(
     *         description="min:1",
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
     *                  type="array",
     *                  @SWG\Items(
     *                      type="object",
     *                          @SWG\Property(property="id", type="integer"),
     *                          @SWG\Property(property="email", type="string"),
     *                          @SWG\Property(property="login_name", type="string"),
     *                          @SWG\Property(property="display_name", type="string"),
     *                          @SWG\Property(property="data_group_code", type="string"),
     *                          @SWG\Property(property="created_at", type="string"),
     *                          @SWG\Property(property="team_id", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="team_role", type="string"),
     *                          @SWG\Property(property="team_member_id", type="string"),
     *                          @SWG\Property(property="team_member_status", type="string"),
     *                          @SWG\Property(
     *                              property="result",
     *                              type="object",
     *                              @SWG\Property(
     *                                  property="current",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="next",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_1",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_2",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_3",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                              @SWG\Property(
     *                                  property="back_4",
     *                                  type="object",
     *                                  @SWG\Property(property="team_id", type="integer"),
     *                                  @SWG\Property(property="week", type="integer"),
     *                                  @SWG\Property(property="total_revenue_target", type="integer"),
     *                                  @SWG\Property(property="total_revenue_real", type="integer"),
     *                                  @SWG\Property(property="date_start", type="string"),
     *                                  @SWG\Property(property="date_end", type="string"),
     *                              ),
     *                      ),
     *                      @SWG\Property(
     *                          property="calc",
     *                          type="object",
     *                              @SWG\Property(property="avarage_4_week", type="integer"),
     *                              @SWG\Property(property="compare_target_current", type="integer"),
     *                              @SWG\Property(property="compare_target_back_1", type="integer"),
     *                              @SWG\Property(property="compare_target_4_week", type="integer"),
     *                          ),
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
            'team_id' => 'required|digit_min:1',
            //'staff_id' => 'required',
            'week_year' => 'required|week_year',
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getPlanTeamMemberOfTeam();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-weekly-expected-revenue/report{query}",
     *     description="report",
     *     summary="Báo cáo kế hoạch trong tuần",
     *     tags={"Plan Weekly"},
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
    public function reportPlanWeeklyRevenueAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'week_year' => 'required|week_year',
            //'team_id' => 'digit_min:1'
        ];

        BaseValidate::validator($request, $rules);

        $datas = $this->repo->reportPlanWeeklyRevenue();

        //parse view email
        $view = new \Phalcon\Mvc\View\Simple();
        $view->setViewsDir(APP_PATH . '/app/views/exports/');
        $view->render('planWeeklyExpectedRevenue', compact('datas'));
        $content = $view->getContent();

        //$email = 'nghiavt@nhanlucsieuviet.com';
        $auth = $this->auth->user();
        $email = $auth->email;
        $name = $auth->display_name;
        $params = [
            'to' => [$email => $name],
            'subject' => 'Mục tiêu tuần',
            'content' => $content
        ];
        $response = MailService::send($params);

        return $this->outputSuccess($response);
    }

    /**
     * @SWG\Post(
     *     path="/planning/plan-weekly-expected-revenue/save",
     *     description="Tạo + sửa chỉ tiêu tuần của thành viên",
     *     summary="Tạo + sửa chỉ tiêu tuần của thành viên",
     *     tags={"Plan Weekly"},
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
     *         description="Min : 1",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Min : 1",
     *         name="staff_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1000",
     *         name="revenue",
     *         in="path",
     *         required=true,
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
    public function createOrUpdateWeeklyRevenueByMemberAction()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'week_year' => 'required|week_year',
            'team_id' => 'required|digit_min:1',
            'staff_id' => 'required|digit_min:1',
            'revenue' => 'required|digit_min:1000'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createOrUpdateWeeklyRevenueByMember();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/plan-weekly-expected-revenue/comment",
     *     description="Chi tiết bình luận",
     *     summary="Chi tiết bình luận",
     *     tags={"Plan Weekly"},
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
     *     produces={"application/json"},
     *     @SWG\Response(
     *         response=200,
     *         description="Thành công",
     *         @SWG\Schema(
     *              @SWG\Property(type="integer",property="code"),
     *              @SWG\Property(property="message",type="string"),
     *              @SWG\Property(property="data",type="object")
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
    public function getCommentInWeekAction()
    {
        $rules = [
            'branch_code' => 'required',
            'week_year' => 'required|week_year'
        ];

        BaseValidate::validator($this->request->getQuery(), $rules);

        $result = $this->repo->getCommentInWeek();

        $response = [];

        if($result){
            $response = $this->fractal
                ->item($result->toArray())
                ->transformWith(function($item){
                    $item['created_at'] = strtotime($item['created_at']);
                    $item['updated_at'] = strtotime($item['updated_at']);
                    return $item;
                })
                ->toArray();
        }

        return $this->outputSuccess($response);
    }

    /**
     * @SWG\Post(
     *     path="/planning/plan-weekly-expected-revenue/save-comment",
     *     description="Tạo + sửa bình luận",
     *     summary="Tạo + sửa bình luận",
     *     tags={"Plan Weekly"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="branch code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="comment",
     *         name="comment",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="tuần/năm",
     *         name="week_year",
     *         in="path",
     *         required=true,
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
     *                          @SWG\Property(property="week", type="integer"),
     *                          @SWG\Property(property="year", type="integer"),
     *                          @SWG\Property(property="comment", type="string"),
     *                          @SWG\Property(property="created_at", type="string"),
     *                          @SWG\Property(property="created_by", type="string"),
     *                          @SWG\Property(property="updated_at", type="string"),
     *                          @SWG\Property(property="updated_by", type="string"),
     *                          @SWG\Property(property="created_source", type="string"),
     *              ),
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
    public function createOrUpdateCommentInWeekAction()
    {
        $request = (array)$this->request->getJsonRawBody(true);

        $rules = [
            'branch_code' => 'required',
            'comment' => 'required',
            'week_year' => 'required|week_year'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->createOrUpdateCommentInWeek();

        return $this->outputSuccess($result);
    }
}