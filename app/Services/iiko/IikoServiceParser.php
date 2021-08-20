<?php

namespace App\Services\iiko;

use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;

class IikoServiceParser
{
    /**
     * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=id.xsy1q2yg3v46
     */
    public function parseDeliveryOrdersResponse(array $responseObjects): array
    {
        $parsed = [];

        foreach ($responseObjects['deliveryOrders'] ?? [] as $key => $orderInfo) {
            /**
             * @var array $orderInfo
             * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=kix.xqk6fzvxgaeo
             */

            $parsed[$key]['restaurant'] = Location::SMAKI_MAKI_RESTAURANT;
            $parsed[$key]['statusTitle'] = $orderInfo['status'];
            $parsed[$key]['orderUuid'] = $orderInfo['orderId'];
            $parsed[$key]['orderId'] = (int) $orderInfo['number'];
            $parsed[$key]['comment'] = $orderInfo['comment'];

            $parsedPayments = $this->parsePayments($orderInfo['payments']);
            $parsed[$key]['payment']['title'] = $parsedPayments['mainPayment']['title'];
            $parsed[$key]['payment']['sum'] = $parsedPayments['mainPayment']['sum'];

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
     * @return array
     */
    private function parsePayments(array $payments): array
    {
        $parsedPayments = [];

        $mainPayment = array_values(array_filter($payments, fn ($item) => $item['paymentType']['code'] !== 'BALL'))[0] ?? null;
        $additionalPayment = array_values(array_filter($payments, fn ($item) => $item['paymentType']['code'] === 'BALL'))[0] ?? null;

        if (isset($mainPayment)) {
            $parsedPayments['mainPayment']['title'] = $mainPayment['paymentType']['name'];
            $parsedPayments['mainPayment']['sum'] = $mainPayment['sum'];
        }

        if (isset($additionalPayment)) {
            $parsedPayments['additionalPayment']['title'] = $mainPayment['paymentType']['name'];
            $parsedPayments['additionalPayment']['sum'] = $mainPayment['sum'];
        }

        return $parsedPayments;
    }
}