<?php

namespace App\Services\iiko;

use App\Models\DeliveryOrder;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class IikoServiceParser
{
    private const PAYMENTS_TO_SHOW = ['CASH', 'VISA'];

    /**
     * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=id.xsy1q2yg3v46
     *
     * @param array $responseObjects
     * @param Collection|null $ordersInDb
     * @return array
     */
    public function parseDeliveryOrdersResponse(array $responseObjects, ?Collection $ordersInDb): array
    {
        $parsed = [];

        foreach ($responseObjects['deliveryOrders'] ?? [] as $key => $orderInfo) {
            /**
             * @var array $orderInfo
             * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=kix.xqk6fzvxgaeo
             */

            if ($orderInfo['statusCode'] !== 'ON_WAY') {
                continue;
            }

            $parsed[$key]['restaurant'] = $orderInfo['organization'] === IikoClient::ORGANIZATION_ID_SMAKI
                ? Location::SMAKI_MAKI_RESTAURANT
                : Location::SUSHI_GO_RESTAURANT;
            $parsed[$key]['delivery_terminal_id'] = $orderInfo['deliveryTerminal']['deliveryTerminalId'];
            $parsed[$key]['status'] = $ordersInDb === null
                ? DeliveryOrder::STATUS_WAITING
                : $this->parseStatus($orderInfo, $ordersInDb);
            $parsed[$key]['order_uuid'] = $orderInfo['orderId'];
            $parsed[$key]['order_id'] = (int) $orderInfo['number'];
            $parsed[$key]['comment'] = $orderInfo['comment'];
            $parsed[$key]['expected_delivery_at'] = $orderInfo['deliveryDate'];

            $parsedPayment = $this->parsePayments($orderInfo['payments']);
            if (isset($parsedPayment)) {
                $parsed[$key]['payment']['title'] = $parsedPayment['title'];
                $parsed[$key]['payment']['sum'] = $parsedPayment['sum'];
            } else {
                $parsed[$key]['payment'] = null;
            }

            /** @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=kix.uknh114942rg */
            $parsed[$key]['customer']['name'] = trim("{$orderInfo['customer']['name']} {$orderInfo['customer']['surName']}") !== ''
                ? trim("{$orderInfo['customer']['name']} {$orderInfo['customer']['surName']}")
                : $orderInfo['customerName'];
            $parsed[$key]['customer']['phone'] = $orderInfo['customer']['phone'] ?? $orderInfo['customerPhone'];

            /** @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=kix.fofadhfluhl3 */
            $parsed[$key]['address']['city'] = $orderInfo['address']['city'];
            $parsed[$key]['address']['street'] = $orderInfo['address']['street'];
            $parsed[$key]['address']['index'] = $orderInfo['address']['index'];
            $parsed[$key]['address']['home'] = $orderInfo['address']['home'];
            $parsed[$key]['address']['housing'] = $orderInfo['address']['housing'];
            $parsed[$key]['address']['apartment'] = $orderInfo['address']['apartment'];
            $parsed[$key]['address']['entrance'] = $orderInfo['address']['entrance'];
            $parsed[$key]['address']['floor'] = $orderInfo['address']['floor'];
            $parsed[$key]['address']['comment'] = $orderInfo['address']['comment'];
        }

        return $parsed;
    }

    /**
     * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=kix.ka5lk06p09ui
     * @param array $payments
     * @return array|null
     */
    private function parsePayments(array $payments): ?array
    {
        $parsedPayment = null;

        $mainPayment = array_values(array_filter($payments, function ($item) {
            return in_array($item['paymentType']['code'], self::PAYMENTS_TO_SHOW);
        }))[0] ?? null;

        if (isset($mainPayment)) {
            $parsedPayment['title'] = $mainPayment['paymentType']['name'];
            $parsedPayment['sum'] = $mainPayment['sum'];
        }

        return $parsedPayment;
    }

    /**
     * @param array $orderInfo
     * @param Collection $ordersInDb
     * @return string
     */
    private function parseStatus(array $orderInfo, Collection $ordersInDb): string
    {
        $currentOrderInDb = $ordersInDb->where('iiko_order_id', $orderInfo['orderId'])->first();

        if (isset($currentOrderInDb)) {
            $status = $currentOrderInDb->status;
        } else {
            $status = DeliveryOrder::STATUS_WAITING;
        }

        return $status;
    }
}
