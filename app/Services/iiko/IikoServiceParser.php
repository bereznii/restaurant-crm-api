<?php

namespace App\Services\iiko;

use App\Models\Delivery;
use App\Models\DeliveryOrder;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

class IikoServiceParser
{
    private const PAYMENTS_TO_SHOW = ['CASH', 'VISA'];
    private const PAYMENT_CODE_CASH = 'CASH';

    private const SECONDARY_PAYMENTS_TO_SHOW = ['ON'];

    /**
     * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=id.xsy1q2yg3v46
     *
     * @param array|null $responseObjects
     * @param Collection|null $ordersInDb
     * @return array
     */
    public function parseDeliveryOrdersResponse(?array $responseObjects, ?Collection $ordersInDb): array
    {
        $parsed = [];

        if (Auth::user()->courierStatus === User::COURIER_STATUS_ON_DELIVERY) {
            $currentDelivery = Delivery::where('id', Auth::user()->courierCurrentDeliveryId)->first();
        }

        foreach ($responseObjects['deliveryOrders'] ?? [] as $key => $orderInfo) {
            /**
             * @var array $orderInfo
             * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=kix.xqk6fzvxgaeo
             */

            // Если у курьера есть активная поездка возвращать только те заказы, которые есть в этой поездке
            if (Auth::user()->courierStatus === User::COURIER_STATUS_ON_DELIVERY) {
                if (isset($currentDelivery)) {
                    $currentOrderInDb = $ordersInDb
                        ->where('iiko_order_id', $orderInfo['orderId'])
                        ->where('delivery_id', $currentDelivery->id)
                        ->first();
                }
                if (!isset($currentOrderInDb)) {
                    continue;
                }
            }

            // Взвращать только те заказы, которые в айко в статусах ON_WAY и WAITING
            if (!in_array($orderInfo['statusCode'], ['ON_WAY', 'WAITING'])) {
                continue;
            }

            $parsed[$key]['restaurant'] = $orderInfo['organization'] === IikoClient::ORGANIZATION_ID_SMAKI
                ? Location::SMAKI_MAKI_RESTAURANT
                : Location::SUSHI_GO_RESTAURANT;
            $parsed[$key]['delivery_terminal_id'] = $orderInfo['deliveryTerminal']['deliveryTerminalId'];

            /*
             * Заказ в статусе WAITING, если:
             * - У этого курьера нет начатой поездки
             * - Если Заказа нет в начатой поездке
             * Иначе, берется статус из бд.
             */
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
                $parsed[$key]['payment']['code'] = $parsedPayment['code'];
                $parsed[$key]['payment']['sum'] = $parsedPayment['sum'];
                $parsed[$key]['payment']['prepareChangeFrom'] = null;
                $this->parseChange($parsed[$key]['payment'], $parsedPayment, $orderInfo);
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

            $parsed[$key]['items'] = $this->prepareItems($orderInfo['items']);
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
            $parsedPayment['code'] = $mainPayment['paymentType']['code'];
            $parsedPayment['sum'] = $mainPayment['sum'];
        } else {
            $secondaryPayment = array_values(array_filter($payments, function ($item) {
                return in_array($item['paymentType']['code'], self::SECONDARY_PAYMENTS_TO_SHOW);
            }))[0] ?? null;

            if (isset($secondaryPayment)) {
                $parsedPayment['title'] = $secondaryPayment['paymentType']['name'];
                $parsedPayment['code'] = $secondaryPayment['paymentType']['code'];
                $parsedPayment['sum'] = $secondaryPayment['sum'];
            }
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

    /**
     * @param array $paymentType
     * @param array $preParsedPayment
     * @param array $orderInfo
     * @return void|null
     */
    private function parseChange(array &$paymentType, array $preParsedPayment, array $orderInfo)
    {
        if ($preParsedPayment['code'] !== self::PAYMENT_CODE_CASH || !($preParsedPayment['sum'] > $orderInfo['sum'])) {
            return null;
        }

        $paymentType['sum'] = (float) $orderInfo['sum'];
        $paymentType['prepareChangeFrom'] = (float) $preParsedPayment['sum'];
    }

    /**
     * @param array $unpreparedItem
     * @return array|null
     */
    private function prepareItems(array $unpreparedItem): ?array
    {
        return array_map(function ($item) {
            return [
                'name' => $item['name'] ?? null,
                'amount' => $item['amount'] ?? null,
                'sum' => $item['sum'] ?? null,
                'comment' => $item['comment'] ?? null,
            ];
        }, $unpreparedItem);
    }
}
