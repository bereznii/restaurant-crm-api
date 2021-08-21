<?php

namespace App\Services\iiko;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IikoServiceInterface
{
    /**
     * @param IikoClient $iikoClient
     * @param IikoServiceParser $iikoServiceParser
     */
    public function __construct(
        private IikoClient $iikoClient,
        private IikoServiceParser $iikoServiceParser,
    ) {}

    /**
     * @param string $courierIikoId
     * @param string $orderUuid
     * @param array $validated
     * @return bool[]
     * @throws \Exception
     */
    public function setOrderDelivered(string $courierIikoId, string $orderUuid, array $validated): array
    {
//        try {
//            $organizationId = IikoClient::ORGANIZATION_ID_SMAKI;
//
//            $response = Http::post(
//                IikoClient::API_URL . "/orders/set_order_delivered?access_token={$this->iikoClient->getAccessToken()}&organization={$organizationId}",
//                [
//                    'courierId' => $courierIikoId,
//                    'orderId' => $orderUuid,
//                    'delivered' => $validated['delivered'],
//                    'actualDeliveryTime' => date('Y-m-d H:i:s'),
//                ]
//            );
//
//            return $response->json();
//        } catch (\Exception $e) {
//            Log::error($e->getMessage());
//            throw $e;
//        }

        return [
            'success' => true,
        ];
    }

    /**
     * @link https://docs.google.com/document/d/1pRQNIn46GH1LVqzBUY5TdIIUuSCOl-A_xeCBbogd2bE/edit#bookmark=kix.f85lxd2yapf1
     * @param string $courierIikoId
     * @return mixed
     * @throws \Exception
     */
    public function getCourierOrders(string $courierIikoId): mixed
    {
//        try {
//            $response = Http::get(IikoClient::API_URL . '/orders/get_courier_orders', [
//                'access_token' => $this->iikoClient->getAccessToken(),
//                'courier' => $courierIikoId,
//                'organization' => IikoClient::ORGANIZATION_ID_SMAKI
//            ]);
//
//            return $response->json();
//        } catch (\Exception $e) {
//            Log::error($e->getMessage());
//            throw $e;
//        }

        return $this->iikoServiceParser->parseDeliveryOrdersResponse(json_decode($this->onlineBonuses, true));
    }

    private string $onlineBonuses = '{"INFO":"","deliveryOrders":[{
            "customer": {
                "sex": 0,
                "id": "6e6af616-6acf-4559-94f9-02b30f77f9eb",
                "externalId": null,
                "name": "Юлія",
                "surName": "Кухар",
                "nick": "",
                "comment": "",
                "phone": "+380974747560",
                "additionalPhones": [],
                "addresses": [],
                "cultureName": null,
                "birthday": null,
                "email": null,
                "middleName": null,
                "shouldReceivePromoActionsInfo": true,
                "counteragentInfo": null
            },
            "orderId": "257fd1c0-3015-dc6c-f751-d78dd06b4ef5",
            "customerId": "6e6af616-6acf-4559-94f9-02b30f77f9eb",
            "movedDeliveryId": null,
            "customerName": null,
            "customerPhone": null,
            "address": {
                "city": "Львів",
                "street": "Чернівецька вул.",
                "streetId": "5ae9f7f2-447d-6643-016e-4322595af6b6",
                "streetClassifierId": null,
                "index": "",
                "home": "11",
                "housing": "",
                "apartment": "",
                "entrance": "",
                "floor": "2",
                "doorphone": "",
                "comment": "Львів, Чернівецька , дім 11, поверх 2\nЛьвів Чернівецька 11 2",
                "regionId": null,
                "externalCartographyId": ""
            },
            "restaurantId": "f445683a-adf7-11e9-80dd-d8d385655247",
            "organization": "f445683a-adf7-11e9-80dd-d8d385655247",
            "sum": 685.000000000,
            "discount": 119.000000000,
            "number": "83112",
            "status": "Закрыта",
            "statusCode": "CLOSED",
            "deliveryCancelCause": null,
            "deliveryCancelComment": null,
            "courierInfo": {
                "courierId": "561bea9b-3f81-4280-9623-cc2e660a2378",
                "location": {
                    "latitude": 49.818840000,
                    "longitude": 23.988078300,
                    "accuracy": 7,
                    "date": "2021-08-19 16:13:58"
                }
            },
            "orderLocationInfo": {
                "latitude": 49.8395442,
                "longitude": 23.9962096
            },
            "deliveryDate": "2021-08-19 10:25:39",
            "actualTime": "2021-08-19 10:52:29",
            "billTime": "2021-08-19 10:20:57",
            "cancelTime": null,
            "closeTime": "2021-08-19 11:01:53",
            "confirmTime": "2021-08-19 09:56:34",
            "createdTime": "2021-08-19 09:53:39",
            "printTime": "2021-08-19 10:05:53",
            "sendTime": "2021-08-19 10:20:57",
            "comment": "; |  | Доставка за 29хв; | Замовлення онлайн | Підготувати решту з: готівка;",
            "problem": null,
            "operator": {
                "id": "66b9383e-2e0d-456c-af6f-b86813d89586",
                "firstName": "Оксана",
                "middleName": null,
                "lastName": "Медвідь",
                "displayName": "Медвідь Оксана",
                "phone": null,
                "cellPhone": null,
                "email": null,
                "code": "293384",
                "pinCode": "",
                "note": null,
                "mainRole": {
                    "id": "24cb0ced-ab84-4fdf-add0-032553434bae",
                    "name": "КЦ Оператор",
                    "code": "КЦО",
                    "paymentPerHour": 0.000000000,
                    "steadySalary": 0.000000000,
                    "comment": "",
                    "scheduleType": "BYSESSION",
                    "externalRevision": 7739694,
                    "deleted": false
                },
                "roles": [
                    {
                        "id": "24cb0ced-ab84-4fdf-add0-032553434bae",
                        "name": "КЦ Оператор",
                        "code": "КЦО",
                        "paymentPerHour": 0.000000000,
                        "steadySalary": 0.000000000,
                        "comment": "",
                        "scheduleType": "BYSESSION",
                        "externalRevision": 7739694,
                        "deleted": false
                    }
                ],
                "deleted": false,
                "externalRevision": 15875416
            },
            "conception": {
                "id": "7eb4680c-0b74-4ee4-a92c-8e9cd8b77cf0",
                "name": "Кульпарківська",
                "code": "3"
            },
            "marketingSource": {
                "id": "851bdd3d-a104-4965-b509-b8fdeceb1a8c",
                "name": "Постійний кліент",
                "attachedSources": [],
                "externalRevision": 101112
            },
            "durationInMinutes": 29,
            "personsCount": 6,
            "splitBetweenPersons": false,
            "items": [
                {
                    "id": "0b75d54c-9cfd-43b5-a641-ce5277a223a5",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "00135",
                    "name": "Піца 4 сири 30 см",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 167.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5d2247d9ffe2",
                    "comboInformation": null
                },
                {
                    "id": "4ec09da6-a76e-4262-9c6b-f6a918377240",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "00145",
                    "name": "Піца Маргарита 30 см",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 119.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5d2247d9ffe2",
                    "comboInformation": null
                },
                {
                    "id": "e9e97508-b49c-434b-9165-4e0ac5432fef",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "00148",
                    "name": "Піца з шинкою та грибами 30 см",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 142.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5d2247d9ffe2",
                    "comboInformation": null
                },
                {
                    "id": "6d6b4ebf-ac40-4499-aad1-6bcfc8f8023a",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "01353",
                    "name": "Кілограм Преміум",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 376.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5d2247d9ffe2",
                    "comboInformation": null
                },
                {
                    "id": "89910246-2464-423a-9843-e6db71abdb08",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "01329",
                    "name": "Імбир",
                    "category": null,
                    "amount": 6.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 0.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5d2247d9ffe2",
                    "comboInformation": null
                },
                {
                    "id": "56b1f6a9-d61d-45be-9b80-e5ca7f0be15d",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "00485",
                    "name": "Набір для персони звичайний",
                    "category": null,
                    "amount": 6.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 0.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5d2247d9ffe2",
                    "comboInformation": null
                }
            ],
            "guests": [
                {
                    "id": "b8e922da-2495-444c-017b-5d2247d9ffe2",
                    "name": "Кухар Юлія"
                }
            ],
            "payments": [
                {
                    "sum": 609.000000000,
                    "paymentType": {
                        "id": "09322f46-578a-d210-add7-eec222a08871",
                        "code": "CASH",
                        "name": "Наличные",
                        "comment": null,
                        "combinable": true,
                        "externalRevision": 18,
                        "applicableMarketingCampaigns": null,
                        "deleted": false
                    },
                    "additionalData": null,
                    "isProcessedExternally": false,
                    "isPreliminary": false,
                    "isExternal": false,
                    "chequeAdditionalInfo": null,
                    "organizationDetailsInfo": null,
                    "isFiscalizedExternally": false
                },
                {
                    "sum": 76.000000000,
                    "paymentType": {
                        "id": "1b51a168-c8f2-46cd-95f9-4e124dd6b049",
                        "code": "BALL",
                        "name": "Оплата бонусами Smaki",
                        "comment": "",
                        "combinable": true,
                        "externalRevision": 12664286,
                        "applicableMarketingCampaigns": null,
                        "deleted": false
                    },
                    "additionalData": null,
                    "isProcessedExternally": false,
                    "isPreliminary": false,
                    "isExternal": false,
                    "chequeAdditionalInfo": null,
                    "organizationDetailsInfo": null,
                    "isFiscalizedExternally": false
                }
            ],
            "orderType": {
                "id": "49cf98d2-25ab-d404-a5a8-11eaffc7ce7f",
                "name": "Доставка курьером",
                "orderServiceType": "DELIVERY_BY_COURIER",
                "externalRevision": 18
            },
            "deliveryTerminal": {
                "deliveryTerminalId": "aa15c7b2-768f-dbf1-016c-8fc96e6aa61b",
                "crmId": "793643",
                "restaurantName": "Кульпарківська",
                "groupName": null,
                "externalRevision": 27574535,
                "technicalInformation": null,
                "address": null,
                "protocolVersion": 1
            },
            "discounts": [
                {
                    "discountCardTypeId": "96799237-4e7a-9d4c-0178-15d148581ab2",
                    "discountCardSlip": null,
                    "discountOrIncreaseSum": 119.000000000
                }
            ],
            "iikoCard5Coupon": null,
            "customData": null,
            "opinion": {
                "organization": null,
                "deliveryId": "257fd1c0-3015-dc6c-f751-d78dd06b4ef5",
                "comment": null,
                "marks": []
            },
            "referrerId": null
        }]}';

    private string $courierOrderSubReversedPayments = '{"INFO":"Данные являются заглушкой","deliveryOrders": [{
            "customer": {
                "sex": 0,
                "id": "f118c9fc-8b56-4886-016c-d7cfaa4c85ec",
                "externalId": "6ef2d160-c984-11e9-80dd-d8d385655247",
                "name": "Марта",
                "surName": "",
                "nick": "",
                "comment": "",
                "phone": "+380934362109",
                "additionalPhones": [],
                "addresses": [],
                "cultureName": null,
                "birthday": null,
                "email": null,
                "middleName": null,
                "shouldReceivePromoActionsInfo": true,
                "counteragentInfo": null
            },
            "orderId": "9351913f-d752-3349-b21e-b23bcde84747",
            "customerId": "f118c9fc-8b56-4886-016c-d7cfaa4c85ec",
            "movedDeliveryId": null,
            "customerName": null,
            "customerPhone": null,
            "address": {
                "city": "Львів",
                "street": "Угорська вул.",
                "streetId": "5ae9f7f2-447d-6643-016e-4322595af682",
                "streetClassifierId": null,
                "index": "",
                "home": "14а",
                "housing": "",
                "apartment": "48",
                "entrance": "2",
                "floor": "5",
                "doorphone": "",
                "comment": "Львів, Угорська вул., дім 14а, підїзд 2, поверх 5, квартира 48 документи",
                "regionId": null,
                "externalCartographyId": ""
            },
            "restaurantId": "f445683a-adf7-11e9-80dd-d8d385655247",
            "organization": "f445683a-adf7-11e9-80dd-d8d385655247",
            "sum": 311.000000000,
            "discount": 0.0,
            "number": "78765",
            "status": "Закрыта",
            "statusCode": "CLOSED",
            "deliveryCancelCause": null,
            "deliveryCancelComment": null,
            "courierInfo": {
                "courierId": "c7599f2e-3f65-482f-bc99-efe972680da5",
                "location": {
                    "latitude": 49.808303500,
                    "longitude": 24.015957400,
                    "accuracy": 22,
                    "date": "2021-08-19 15:54:20"
                }
            },
            "orderLocationInfo": {
                "latitude": 49.8093896,
                "longitude": 24.0457884
            },
            "deliveryDate": "2021-08-19 08:03:11",
            "actualTime": "2021-08-19 08:34:17",
            "billTime": "2021-08-19 08:17:13",
            "cancelTime": null,
            "closeTime": "2021-08-19 08:56:29",
            "confirmTime": "2021-08-19 07:35:49",
            "createdTime": "2021-08-19 07:32:11",
            "printTime": "2021-08-19 07:36:51",
            "sendTime": "2021-08-19 08:17:13",
            "comment": "",
            "problem": null,
            "operator": {
                "id": "d2a7f9fb-376a-4728-83b8-71c12978700a",
                "firstName": "Христина",
                "middleName": null,
                "lastName": "Яремкевич",
                "displayName": "Христина Яремкевич",
                "phone": null,
                "cellPhone": null,
                "email": null,
                "code": "29061",
                "pinCode": "",
                "note": null,
                "mainRole": {
                    "id": "24cb0ced-ab84-4fdf-add0-032553434bae",
                    "name": "КЦ Оператор",
                    "code": "КЦО",
                    "paymentPerHour": 0.000000000,
                    "steadySalary": 0.000000000,
                    "comment": "",
                    "scheduleType": "BYSESSION",
                    "externalRevision": 7739694,
                    "deleted": false
                },
                "roles": [
                    {
                        "id": "24cb0ced-ab84-4fdf-add0-032553434bae",
                        "name": "КЦ Оператор",
                        "code": "КЦО",
                        "paymentPerHour": 0.000000000,
                        "steadySalary": 0.000000000,
                        "comment": "",
                        "scheduleType": "BYSESSION",
                        "externalRevision": 7739694,
                        "deleted": false
                    }
                ],
                "deleted": false,
                "externalRevision": 26886046
            },
            "conception": {
                "id": "7eb4680c-0b74-4ee4-a92c-8e9cd8b77cf0",
                "name": "Кульпарківська",
                "code": "3"
            },
            "marketingSource": {
                "id": "851bdd3d-a104-4965-b509-b8fdeceb1a8c",
                "name": "Постійний кліент",
                "attachedSources": [],
                "externalRevision": 101112
            },
            "durationInMinutes": 29,
            "personsCount": 1,
            "splitBetweenPersons": false,
            "items": [
                {
                    "id": "02e9fdc0-34f7-413e-94a7-2b82e783b474",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "01209",
                    "name": "Хітовий сет",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 287.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5c5da1506b7f",
                    "comboInformation": null
                },
                {
                    "id": "bbc4a066-cdef-4caa-8c5c-8b512449c99d",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "01266",
                    "name": "Піца з шинкою та грибами до Дня народження 30 см",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 0.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5c5da1506b7f",
                    "comboInformation": null
                },
                {
                    "id": "f958621d-dff9-4730-a47a-1ad1914773db",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "00484",
                    "name": "Набір для персони навчальний",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 0.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5c5da1506b7f",
                    "comboInformation": null
                },
                {
                    "id": "56b1f6a9-d61d-45be-9b80-e5ca7f0be15d",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "00485",
                    "name": "Набір для персони звичайний",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 0.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5c5da1506b7f",
                    "comboInformation": null
                },
                {
                    "id": "9c19e243-ca4f-404e-8572-2fc8a85b1693",
                    "orderItemId": "00000000-0000-0000-0000-000000000000",
                    "code": "00255",
                    "name": "Fanta",
                    "category": null,
                    "amount": 1.000000000,
                    "size": null,
                    "modifiers": [],
                    "sum": 24.000000000,
                    "comment": null,
                    "guestId": "b8e922da-2495-444c-017b-5c5da1506b7f",
                    "comboInformation": null
                }
            ],
            "guests": [
                {
                    "id": "b8e922da-2495-444c-017b-5c5da1506b7f",
                    "name": "Марта"
                }
            ],
            "payments": [
                {
                    "sum": 11.000000000,
                    "paymentType": {
                        "id": "1b51a168-c8f2-46cd-95f9-4e124dd6b049",
                        "code": "BALL",
                        "name": "Оплата бонусами Smaki",
                        "comment": "",
                        "combinable": true,
                        "externalRevision": 12664286,
                        "applicableMarketingCampaigns": null,
                        "deleted": false
                    },
                    "additionalData": null,
                    "isProcessedExternally": false,
                    "isPreliminary": false,
                    "isExternal": false,
                    "chequeAdditionalInfo": null,
                    "organizationDetailsInfo": null,
                    "isFiscalizedExternally": false
                },
                {
                    "sum": 300.000000000,
                    "paymentType": {
                        "id": "09322f46-578a-d210-add7-eec222a08871",
                        "code": "CASH",
                        "name": "Наличные",
                        "comment": null,
                        "combinable": true,
                        "externalRevision": 18,
                        "applicableMarketingCampaigns": null,
                        "deleted": false
                    },
                    "additionalData": null,
                    "isProcessedExternally": false,
                    "isPreliminary": false,
                    "isExternal": false,
                    "chequeAdditionalInfo": null,
                    "organizationDetailsInfo": null,
                    "isFiscalizedExternally": false
                }
            ],
            "orderType": {
                "id": "49cf98d2-25ab-d404-a5a8-11eaffc7ce7f",
                "name": "Доставка курьером",
                "orderServiceType": "DELIVERY_BY_COURIER",
                "externalRevision": 18
            },
            "deliveryTerminal": {
                "deliveryTerminalId": "aa15c7b2-768f-dbf1-016c-8fc96e6aa61b",
                "crmId": "793643",
                "restaurantName": "Кульпарківська",
                "groupName": null,
                "externalRevision": 27574535,
                "technicalInformation": null,
                "address": null,
                "protocolVersion": 1
            },
            "discounts": [],
            "iikoCard5Coupon": null,
            "customData": null,
            "opinion": {
                "organization": null,
                "deliveryId": "9351913f-d752-3349-b21e-b23bcde84747",
                "comment": null,
                "marks": []
            },
            "referrerId": null
        }]}';

    /**
     * @var string
     */
    private string $courierOrdersStub = '{"INFO":"Данные являются заглушкой","deliveryOrders": [
            {
                "customer": {
                    "sex": 0,
                    "id": "c5aaa2ef-daea-4695-b265-146467e55c06",
                    "externalId": "eaa82420-4f70-11ea-80f2-d8d38565926f",
                    "name": "Марія",
                    "surName": "",
                    "nick": "",
                    "comment": "",
                    "phone": "+380686215657",
                    "additionalPhones": [],
                    "addresses": [],
                    "cultureName": null,
                    "birthday": null,
                    "email": null,
                    "middleName": null,
                    "shouldReceivePromoActionsInfo": true,
                    "counteragentInfo": null
                },
                "orderId": "1ec34487-796b-0d80-2ada-bd91362739d8",
                "customerId": "c5aaa2ef-daea-4695-b265-146467e55c06",
                "movedDeliveryId": null,
                "customerName": null,
                "customerPhone": null,
                "address": {
                    "city": "Львів",
                    "street": "Володимира Великого вул.",
                    "streetId": "5ae9f7f2-447d-6643-016e-4322595af2cb",
                    "streetClassifierId": null,
                    "index": "",
                    "home": "5",
                    "housing": "",
                    "apartment": "",
                    "entrance": "5",
                    "floor": "",
                    "doorphone": "",
                    "comment": "Львів, Володимира Великого вул., дім 5, підїзд 5",
                    "regionId": null,
                    "externalCartographyId": ""
                },
                "restaurantId": "f445683a-adf7-11e9-80dd-d8d385655247",
                "organization": "f445683a-adf7-11e9-80dd-d8d385655247",
                "sum": 376,
                "discount": 0,
                "number": "38976",
                "status": "В пути",
                "statusCode": "ON_WAY",
                "deliveryCancelCause": null,
                "deliveryCancelComment": null,
                "courierInfo": {
                    "courierId": "f3b8acfa-da99-454c-9ba4-9220130b85ac",
                    "location": {
                        "latitude": 49.816455,
                        "longitude": 23.993155,
                        "accuracy": 26,
                        "date": "2021-08-19 16:17:35"
                    }
                },
                "orderLocationInfo": {
                    "latitude": 49.8080537,
                    "longitude": 24.0152372
                },
                "deliveryDate": "2021-08-19 16:24:39",
                "actualTime": null,
                "billTime": "2021-08-19 16:08:55",
                "cancelTime": null,
                "closeTime": null,
                "confirmTime": "2021-08-19 15:55:54",
                "createdTime": "2021-08-19 15:53:40",
                "printTime": "2021-08-19 15:56:57",
                "sendTime": "2021-08-19 16:08:55",
                "comment": "",
                "problem": null,
                "operator": {
                    "id": "caf4ffa3-f896-4081-bf17-6d2552c90a31",
                    "firstName": "Оксана",
                    "middleName": null,
                    "lastName": "Корнеляк",
                    "displayName": "Корнеляк Оксана",
                    "phone": null,
                    "cellPhone": null,
                    "email": null,
                    "code": "294662",
                    "pinCode": "",
                    "note": null,
                    "mainRole": {
                        "id": "24cb0ced-ab84-4fdf-add0-032553434bae",
                        "name": "КЦ Оператор",
                        "code": "КЦО",
                        "paymentPerHour": 0,
                        "steadySalary": 0,
                        "comment": "",
                        "scheduleType": "BYSESSION",
                        "externalRevision": 7739694,
                        "deleted": false
                    },
                    "roles": [
                        {
                            "id": "24cb0ced-ab84-4fdf-add0-032553434bae",
                            "name": "КЦ Оператор",
                            "code": "КЦО",
                            "paymentPerHour": 0,
                            "steadySalary": 0,
                            "comment": "",
                            "scheduleType": "BYSESSION",
                            "externalRevision": 7739694,
                            "deleted": false
                        }
                    ],
                    "deleted": false,
                    "externalRevision": 24850140
                },
                "conception": {
                    "id": "7eb4680c-0b74-4ee4-a92c-8e9cd8b77cf0",
                    "name": "Кульпарківська",
                    "code": "3"
                },
                "marketingSource": {
                    "id": "851bdd3d-a104-4965-b509-b8fdeceb1a8c",
                    "name": "Постійний кліент",
                    "attachedSources": [],
                    "externalRevision": 101112
                },
                "durationInMinutes": 29,
                "personsCount": 1,
                "splitBetweenPersons": false,
                "items": [
                    {
                        "id": "56b1f6a9-d61d-45be-9b80-e5ca7f0be15d",
                        "orderItemId": "00000000-0000-0000-0000-000000000000",
                        "code": "00485",
                        "name": "Набір для персони звичайний",
                        "category": null,
                        "amount": 4,
                        "size": null,
                        "modifiers": [],
                        "sum": 0,
                        "comment": null,
                        "guestId": "b8e922da-2495-444c-017b-5e73bff44331",
                        "comboInformation": null
                    },
                    {
                        "id": "89910246-2464-423a-9843-e6db71abdb08",
                        "orderItemId": "00000000-0000-0000-0000-000000000000",
                        "code": "01329",
                        "name": "Імбир",
                        "category": null,
                        "amount": 4,
                        "size": null,
                        "modifiers": [],
                        "sum": 0,
                        "comment": null,
                        "guestId": "b8e922da-2495-444c-017b-5e73bff44331",
                        "comboInformation": null
                    },
                    {
                        "id": "6d6b4ebf-ac40-4499-aad1-6bcfc8f8023a",
                        "orderItemId": "00000000-0000-0000-0000-000000000000",
                        "code": "01353",
                        "name": "Кілограм Преміум",
                        "category": null,
                        "amount": 1,
                        "size": null,
                        "modifiers": [],
                        "sum": 376,
                        "comment": null,
                        "guestId": "b8e922da-2495-444c-017b-5e73bff44331",
                        "comboInformation": null
                    }
                ],
                "guests": [
                    {
                        "id": "b8e922da-2495-444c-017b-5e73bff44331",
                        "name": "Марія"
                    }
                ],
                "payments": [
                    {
                        "sum": 358,
                        "paymentType": {
                            "id": "9cd5d67a-89b4-ab69-1365-7b8c51865a90",
                            "code": "VISA",
                            "name": "Банковская карта",
                            "comment": "",
                            "combinable": true,
                            "externalRevision": 350257,
                            "applicableMarketingCampaigns": null,
                            "deleted": false
                        },
                        "additionalData": null,
                        "isProcessedExternally": false,
                        "isPreliminary": true,
                        "isExternal": false,
                        "chequeAdditionalInfo": null,
                        "organizationDetailsInfo": null,
                        "isFiscalizedExternally": false
                    },
                    {
                        "sum": 18,
                        "paymentType": {
                            "id": "1b51a168-c8f2-46cd-95f9-4e124dd6b049",
                            "code": "BALL",
                            "name": "Оплата бонусами Smaki",
                            "comment": "",
                            "combinable": true,
                            "externalRevision": 12664286,
                            "applicableMarketingCampaigns": null,
                            "deleted": false
                        },
                        "additionalData": null,
                        "isProcessedExternally": false,
                        "isPreliminary": true,
                        "isExternal": false,
                        "chequeAdditionalInfo": null,
                        "organizationDetailsInfo": null,
                        "isFiscalizedExternally": false
                    }
                ],
                "orderType": {
                    "id": "49cf98d2-25ab-d404-a5a8-11eaffc7ce7f",
                    "name": "Доставка курьером",
                    "orderServiceType": "DELIVERY_BY_COURIER",
                    "externalRevision": 18
                },
                "deliveryTerminal": {
                    "deliveryTerminalId": "aa15c7b2-768f-dbf1-016c-8fc96e6aa61b",
                    "crmId": "793643",
                    "restaurantName": "Кульпарківська",
                    "groupName": null,
                    "externalRevision": 27574535,
                    "technicalInformation": null,
                    "address": null,
                    "protocolVersion": 1
                },
                "discounts": [],
                "iikoCard5Coupon": null,
                "customData": null,
                "opinion": {
                    "organization": null,
                    "deliveryId": "1ec34487-796b-0d80-2ada-bd91362739d8",
                    "comment": null,
                    "marks": []
                },
                "referrerId": null
            }
        ]
    }';

}
