<?php

namespace App\Repositories;

use App\Models\Order\Order;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class OrderRepository extends AbstractRepository
{
    private const DEFAULT_PAGE_SIZE = 20;

    /** @var string */
    protected string $modelClass = Order::class;

    /**
     * Все заказы: Системный администратор, Оператор Call Center, Аналитик
     * Заказы своей кухни: Повар, Управляющий, Администратор
     *
     * @param array $queryParams
     * @return mixed
     */
    public function index(array $queryParams): mixed
    {
        $kitchen = null;
        if (!in_array(Auth::user()->roles[0]?->name, User::ACCESS_ALL_ORDERS)) {
            $kitchen = Auth::user()->kitchen_code;
        }

        return match ($queryParams['type'] ?? null) {
            Order::TYPE_END_STATUSES => $this->getCompletedOrders($kitchen),
            default => $this->getNotCompletedOrders($kitchen),
        };
    }

    /**
     * @param string|null $kitchen
     * @return array
     */
    private function getNotCompletedOrders(?string $kitchen): array
    {
        $query = $this->_getInstance()
            ->with([
                'address',
                'items',
                'items.product',
                'client',
                'history',
                'payments',
            ])
            ->whereIn('status', ['new', 'cooking', 'preparing', 'for_delivery'])
            ->orderBy('created_at', 'desc');

        if (isset($kitchen)) {
            $query->where('kitchen_code', '=', $kitchen);
        }

        $res = $query->get();

        $response['new'] = array_values($res->where('status', 'new')->toArray());
        $response['cooking'] = array_values($res->where('status', 'cooking')->toArray());
        $response['preparing'] = array_values($res->where('status', 'preparing')->toArray());
        $response['for_delivery'] = array_values($res->where('status', 'for_delivery')->toArray());

        return $response;
    }

    /**
     * @param string|null $kitchen
     * @return array
     */
    private function getCompletedOrders(?string $kitchen): array
    {
        $query = $this->_getInstance()
            ->with([
                'address',
                'items',
                'items.product',
                'client',
                'history',
                'payments',
            ])
            ->whereIn('status', ['closed', 'rejected'])
            ->orderBy('created_at', 'desc');

        if (isset($kitchen)) {
            $query->where('kitchen_code', '=', $kitchen);
        }

        $res = $query->get();

        $response['closed'] = array_values($res->where('status', 'closed')->toArray());
        $response['rejected'] = array_values($res->where('status', 'rejected')->toArray());

        return $response;
    }
}
