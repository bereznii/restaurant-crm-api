<?php

namespace App\Repositories\Olap;

use App\Models\Delivery;
use App\Models\DeliveryOrder;
use App\Models\Location;
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

        $query = User::select([
                'users.id AS user_id',
                'courier_iiko.iiko_id AS courier_iiko_id',
                'kitchens.title AS kitchen_title',
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
            ->leftJoin('kitchens', 'users.kitchen_code', '=', 'kitchens.code')
            ->leftJoinSub($deliveries, 'd', 'users.id', '=', 'd.user_id')
            ->leftJoinSub($doWithin, 'do_within', 'do_within.delivery_id', '=', 'd.id')
            ->leftJoinSub($doOutside, 'do_outside', 'do_outside.delivery_id', '=', 'd.id')
            ->where('d.status', '=', Delivery::STATUS_DELIVERED);

        $query = $this->filterByKitchen($query, $validated);

        return $query->groupBy('courier_iiko.iiko_id')->get();
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

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array $validated
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function filterByKitchen(\Illuminate\Database\Eloquent\Builder $query, array $validated): \Illuminate\Database\Eloquent\Builder
    {
        if (isset($validated['kitchen_code'])) {
            $query->where('kitchen_code', '=', $validated['kitchen_code']);
        }

        return $query;
    }

    /**
     * @param array $validated
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDeliveriesStatistics(array $validated): \Illuminate\Database\Eloquent\Collection|array
    {
        $query = Delivery::with('orders', 'user')
            ->filterWhere('user_id', '=', $validated['user_id'] ?? null);

        if (isset($validated['date_from'])) {
            $query->whereDate('created_at', '>=', $validated['date_from']);
        }

        if (isset($validated['date_to'])) {
            $query->whereDate('created_at', '<=', $validated['date_to']);
        }

        if (isset($validated['kitchen_code'])) {
            $deliveryTerminalIds = Location::select('delivery_terminal_id')
                ->where('kitchen_code', '=', $validated['kitchen_code'])
                ->pluck('delivery_terminal_id')
                ->toArray();
            $query->whereIn('delivery_terminal_id', $deliveryTerminalIds);
        }

        return $query->get();
    }
}
