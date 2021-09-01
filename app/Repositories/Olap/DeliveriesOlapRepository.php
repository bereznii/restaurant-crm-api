<?php

namespace App\Repositories\Olap;

use App\Models\DeliveryOrder;
use App\Models\User;
use App\Repositories\AbstractRepository;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DeliveriesOlapRepository extends AbstractRepository
{
    /** @var string */
    protected string $modelClass = User::class;

    /**
     * @param array $validated
     * @return Collection
     */
    public function getStatistics(array $validated): Collection
    {
        $deliveries = $this->getFilteredDeliveries($validated);

        $doWithin = DB::table('delivery_orders')
            ->select('delivery_id', DB::raw('count(*) as count'))
            ->where([
                ['status', '=', DeliveryOrder::STATUS_DELIVERED],
                ['range_type', '=', DeliveryOrder::RANGE_TYPE_WITHIN_CITY],
            ])
            ->groupBy('delivery_id');

        $doOutside = DB::table('delivery_orders')
            ->select('delivery_id', DB::raw('count(*) as count'))
            ->where([
                ['status', '=', DeliveryOrder::STATUS_DELIVERED],
                ['range_type', '=', DeliveryOrder::RANGE_TYPE_OUTSIDE_CITY],
            ])
            ->groupBy('delivery_id');

        return User::select([
                'users.id AS user_id',
                'courier_iiko.iiko_id AS courier_iiko_id',
                'users.first_name',
                'users.last_name',
                DB::raw('COUNT(d.id) count_deliveries'),
                DB::raw('SUM(d.delivery_distance) sum_delivery_distance'),
                DB::raw('SUM(d.return_distance) sum_return_distance'),
                DB::raw('SUM(d.return_distance) sum_return_distance'),
                DB::raw('SUM(do_within.count) orders_within_city'),
                DB::raw('SUM(do_outside.count) orders_outside_city'),
            ])
            ->join('courier_iiko', 'users.id', '=', 'courier_iiko.user_id')
            ->leftJoinSub($deliveries, 'd', 'users.id', '=', 'd.user_id')
            ->leftJoinSub($doWithin, 'do_within', 'do_within.delivery_id', '=', 'd.id')
            ->leftJoinSub($doOutside, 'do_outside', 'do_outside.delivery_id', '=', 'd.id')
            ->groupBy('courier_iiko.iiko_id')->get();
    }

    /**
     * @param array $validated
     * @return Builder
     */
    private function getFilteredDeliveries(array $validated): Builder
    {
        $query = DB::table('deliveries');

        if (isset($validated['date_from'])) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }

        if (isset($validated['date_to'])) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }

        return $query;
    }
}
