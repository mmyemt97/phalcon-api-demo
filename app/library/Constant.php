<?php
namespace Website;

class Constant extends \SVCodebase\Library\Constant
{
    const SALES_MASTER_KPI = 'sales_master_kpi';
    const MASTER_KPI_ABSOLUTE = 'absolute';
    const MASTER_KPI_POTENTIAL = 'potential';
    const MASTER_KPI_FULL_ATTEMPT = 'full_attempt';

    public static function listKpiCode()
    {
        return [
            self::MASTER_KPI_ABSOLUTE,
            self::MASTER_KPI_POTENTIAL,
            self::MASTER_KPI_FULL_ATTEMPT
        ];
    }

    const RANK_TYPE_ALL = 'type_all';
    const RANK_TYPE_CHANNEL = 'type_channel';
    const RANK_TYPE_BRANCH = 'type_branch';

    public static function listRankType()
    {
        return [
            self::RANK_TYPE_ALL,
            self::RANK_TYPE_CHANNEL,
            self::RANK_TYPE_BRANCH
        ];
    }

    public static function wrapCountTotal($total)
    {
        return [
            self::RANK_TYPE_ALL => $total['count_team_all'] ?? 0,
            self::RANK_TYPE_CHANNEL => $total['count_team_channel'] ?? 0,
            self::RANK_TYPE_BRANCH => $total['count_team_branch'] ?? 0
        ];
    }
}