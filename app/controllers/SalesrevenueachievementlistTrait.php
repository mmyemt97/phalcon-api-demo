<?php
namespace Website\Controllers;

use SVCodebase\Services\MailService;
use SVCodebase\Validators\BaseValidate;

trait SalesrevenueachievementlistTrait
{

    /**
     * @SWG\Get(
     *     path="/planning/sales-revenue-achievement{query}",
     *     description="Doanh thu hằng ngày",
     *     summary="Doanh thu hằng ngày",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Timestamp",
     *         name="date[from]",
     *         in="path",
     *         required=false,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Timestamp",
     *         name="date[to]",
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
     *                  @SWG\Property(
     *                      property="items",
     *                      type="array",
     *                      @SWG\Items(
     *                          type="object",
     *                              @SWG\Property(property="date",type="integer"),
     *                              @SWG\Property(property="total_achievement",type="integer"),
     *                              @SWG\Property(property="total_revenue",type="integer"),
     *                          ),
     *                  ),
     *                  @SWG\Property(property="first",type="integer"),
     *                  @SWG\Property(property="before",type="integer"),
     *                  @SWG\Property(property="previous",type="integer"),
     *                  @SWG\Property(property="current",type="integer"),
     *                  @SWG\Property(property="last",type="integer"),
     *                  @SWG\Property(property="next",type="integer"),
     *                  @SWG\Property(property="total_pages",type="integer"),
     *                  @SWG\Property(property="total_items",type="integer"),
     *                  @SWG\Property(property="limit",type="integer"),
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
    public function listsAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'digit_min:1',
            //'staff_id' => 'digit_min:1',
            'date.from' => 'timestamp',
            'date.to' => 'timestamp'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->getListsByDate();

        $result->items = array_map(function ($item) {
            $item['date'] = strtotime($item['date']);
            return $item;
        }, $result->items->toArray());

        return $this->outputSuccess((array) $result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/sales-revenue-achievement/team{query}",
     *     description="Doanh thu team hằng ngày",
     *     summary="Doanh thu team hằng ngày",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Timestamp",
     *         name="date",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1 , digit",
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
     *                          @SWG\Property(property="total_achievement", type="integer"),
     *                          @SWG\Property(property="total_revenue", type="integer"),
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
    public function listsByTeamAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'digit_min:1',
            'date' => 'required',
            'date.from' => 'timestamp',
            'date.to' => 'timestamp'
        ];

        BaseValidate::validator($request, $rules);

        $result = $this->repo->getListsByTeam();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/sales-revenue-achievement/team-member{query}",
     *     description="Doanh thu hằng ngày staff theo team",
     *     summary="Doanh thu hằng ngày staff theo team",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="Timestamp",
     *         name="date",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="min : 1 , digit",
     *         name="team_id",
     *         in="path",
     *         required=true,
     *         type="string"
     *     ),
     *     @SWG\Parameter(
     *         description="id staff",
     *         name="staff_id",
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
     *                          @SWG\Property(property="total_achievement", type="integer"),
     *                          @SWG\Property(property="total_revenue", type="integer"),
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
    public function listsByTeamMemberAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'required|digit_min:1',
            //'staff_id' => 'digit_min:1',
            'date' => 'required|timestamp'
        ];
        BaseValidate::validator($request, $rules);

        $result = $this->repo->getListsByTeamMember();

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/sales-revenue-achievement/achievement-export{query}",
     *     description="export",
     *     summary="export",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
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
     *     @SWG\Parameter(
     *         description="timestamp",
     *         name="date",
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
    public function exportAchievementAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'digit_min:1',
            //'staff_id' => 'digit_min:1',
            'date.from' => 'required|timestamp',
            'date.to' => 'required|timestamp'
        ];
        BaseValidate::validator($request, $rules);

        $result = $this->repo->exportAchievement();

        $view = new \Phalcon\Mvc\View\Simple();
        $view->setViewsDir(APP_PATH . '/app/views/exports/');
        $view->render('saleAchivement', compact('result'));
        $content = $view->getContent();

        $param = [
            'content' => $content,
            'name' => 'Doanh thu',
            'folder' => 'sale_report'
        ];
        $result = \SVCodebase\Services\ExcelService::excel($param);

        return $this->outputSuccess($result);
    }

    /**
     * @SWG\Get(
     *     path="/planning/sales-revenue-achievement/achievement-report{query}",
     *     description="report",
     *     summary="report",
     *     tags={"Sales Revenue"},
     *     security={{"JwtBearerToken":{}, "KongApiKey":{}, }},
     *     @SWG\Parameter(
     *         description="Branch_code",
     *         name="branch_code",
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
     *     @SWG\Parameter(
     *         description="timestamp",
     *         name="date",
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
    public function reportAchievementAction()
    {
        $request = $this->request->getQuery();

        $rules = [
            'branch_code' => 'required',
            'team_id' => 'digit_min:1',
            //'staff_id' => 'digit_min:1',
            'date.from' => 'required|timestamp',
            'date.to' => 'required|timestamp'
        ];
        BaseValidate::validator($request, $rules);

        $result = $this->repo->exportAchievement();

        $view = new \Phalcon\Mvc\View\Simple();
        $view->setViewsDir(APP_PATH . '/app/views/exports/');
        $view->render('saleAchivement', compact('result'));
        $content = $view->getContent();

        //$email = 'nghiavt@nhanlucsieuviet.com';
        $auth = $this->auth->user();
        $email = $auth->email;
        $name = $auth->display_name;
        $params = [
            'to' => [$email => $name],
            'subject' => 'Doanh thu',
            'content' => $content
        ];
        $response = MailService::send($params);

        return $this->outputSuccess($response);
    }
}