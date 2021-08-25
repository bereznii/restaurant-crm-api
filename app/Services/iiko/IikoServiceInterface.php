<?php

namespace App\Services\iiko;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class IikoServiceInterface
{
    /** @var IikoClient  */
    private IikoClient $iikoSmakiClient;

    /** @var IikoClient  */
    private IikoClient $iikoGoClient;

    /**
     * @param IikoServiceParser $iikoServiceParser
     */
    public function __construct(
        private IikoServiceParser $iikoServiceParser,
    ) {
        $this->iikoSmakiClient = new IikoClient(IikoClient::SMAKI);
        $this->iikoGoClient = new IikoClient(IikoClient::GO);
    }

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
//            switch ($validated['restaurant']) {
//                case IikoClient::SMAKI:
//                    $organizationId = IikoClient::ORGANIZATION_ID_SMAKI;
//                    $client = $this->iikoSmakiClient;
//                    break;
//                case IikoClient::GO:
//                    $organizationId = IikoClient::ORGANIZATION_ID_GO;
//                    $client = $this->iikoGoClient;
//                    break;
//                default:
//                    throw new InvalidArgumentException();
//            }
//
//            $response = Http::post(
//                IikoClient::API_URL . "/orders/set_order_delivered?access_token={$client->getAccessToken()}&organization={$organizationId}",
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
//                'access_token' => $this->iikoSmakiClient->getAccessToken(),
//                'courier' => $courierIikoId,
//                'organization' => IikoClient::ORGANIZATION_ID_SMAKI
//            ]);
//dd($response->body());
//            $smakiOrders = $response->json();
//
//            $response = Http::get(IikoClient::API_URL . '/orders/get_courier_orders', [
//                'access_token' => $this->iikoGoClient->getAccessToken(),
//                'courier' => $courierIikoId,
//                'organization' => IikoClient::ORGANIZATION_ID_GO
//            ]);
//
//            $goOrders = $response->json();
//        } catch (\Exception $e) {
//            Log::error($e->getMessage());
//            throw $e;
//        }

        $smakiOrders = json_decode($this->smaki, true);
        $goOrders = json_decode($this->go, true);

        return array_merge(
            $this->iikoServiceParser->parseDeliveryOrdersResponse($smakiOrders),
            $this->iikoServiceParser->parseDeliveryOrdersResponse($goOrders)
        );
    }

    private string $go = '{"INFO":"","deliveryOrders":[{"customer":{"sex":0,"id":"ac55197f-1baf-4e1b-b252-aa41bcf8fb40","externalId":null,"name":"Олена","surName":null,"nick":null,"comment":null,"phone":"+380999358514","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":false,"counteragentInfo":null},"orderId":"f9cc20aa-7ac0-4a83-91e5-f81400f8d903","customerId":"ac55197f-1baf-4e1b-b252-aa41bcf8fb40","movedDeliveryId":null,"customerName":"Олена","customerPhone":null,"address":{"city":"Херсон","street":"Перекопская улица","streetId":"eb44ce49-4a6d-9daa-0175-2b5bc0893603","streetClassifierId":null,"index":"","home":"178","housing":"","apartment":"","entrance":"","floor":"","doorphone":"","comment":"","regionId":null,"externalCartographyId":""},"restaurantId":"dcc74ad6-ad40-11e9-80dd-d8d385655247","organization":"dcc74ad6-ad40-11e9-80dd-d8d385655247","sum":336.000000000,"discount":0.0,"number":"67992","status":"Готово","statusCode":"NEW","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"0c42ac09-670c-4872-9303-8b5b2f2ca9f6","location":null},"orderLocationInfo":{"latitude":46.6668104,"longitude":32.6640702},"deliveryDate":"2021-06-26 12:40:51","actualTime":null,"billTime":null,"cancelTime":null,"closeTime":null,"confirmTime":"2021-06-26 12:11:51","createdTime":"2021-06-26 12:09:54","printTime":"2021-06-26 12:12:49","sendTime":null,"comment":"","problem":null,"operator":{"id":"4a813163-1fac-4bd8-9b09-ab373d78cc25","firstName":"Світлана","middleName":null,"lastName":"Дюжикова","displayName":"Світлана Дюжикова СГ","phone":null,"cellPhone":null,"email":null,"code":"29270","pinCode":"","note":null,"mainRole":{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false},"roles":[{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false}],"deleted":false,"externalRevision":11382507},"conception":{"id":"5b4c646f-3928-4332-93d2-a7b6d94590f1","name":"СГ Херсон","code":"30"},"marketingSource":{"id":"851bdd3d-a104-4965-b509-b8fdeceb1a8c","name":"Постійний кліент","attachedSources":[],"externalRevision":36032},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"691144aa-a1e3-414e-898e-ed783f2433ad","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00327","name":"Філадельфія ","category":null,"amount":2.000000000,"size":null,"modifiers":[],"sum":264.000000000,"comment":null,"guestId":"f9b1395a-a9f1-492a-b981-b8330a4b3bcb","comboInformation":null},{"id":"95509c98-4212-41ac-b01d-75790935f457","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01285","name":"Рол з копченою куркою","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":72.000000000,"comment":null,"guestId":"f9b1395a-a9f1-492a-b981-b8330a4b3bcb","comboInformation":null},{"id":"4e763b87-044c-4a1f-b0c2-e63213a7719e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00710","name":"Набір палички звичайні","category":null,"amount":3.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"f9b1395a-a9f1-492a-b981-b8330a4b3bcb","comboInformation":null}],"guests":[{"id":"f9b1395a-a9f1-492a-b981-b8330a4b3bcb","name":"Олена"}],"payments":[{"sum":1.000000000,"paymentType":{"id":"09322f46-578a-d210-add7-eec222a08871","code":"CASH","name":"Наличные","comment":null,"combinable":true,"externalRevision":10085142,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":false,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"b9e9d1fb-f212-e41a-0179-1272d6eea339","crmId":"793649","restaurantName":"SG Херсон","groupName":null,"externalRevision":10507837,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"f9cc20aa-7ac0-4a83-91e5-f81400f8d903","comment":null,"marks":[]},"referrerId":null}]}';
    private string $smaki = '{"deliveryOrders":[{"customer":{"sex":0,"id":"82f4e9dc-8411-4211-97a8-d2715585081d","externalId":null,"name":"Ірина","surName":"","nick":"","comment":"","phone":"+380953102027","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":true,"counteragentInfo":null},"orderId":"446cbc3d-325c-8bef-d629-0503ba247f40","customerId":"82f4e9dc-8411-4211-97a8-d2715585081d","movedDeliveryId":null,"customerName":"Ирина","customerPhone":null,"address":{"city":"Херсон","street":"Перекопская улица","streetId":"eb44ce49-4a6d-9daa-0175-2b5bc0893603","streetClassifierId":null,"index":"","home":"193а","housing":"","apartment":"134","entrance":"4","floor":"","doorphone":"","comment":"","regionId":null,"externalCartographyId":""},"restaurantId":"f445683a-adf7-11e9-80dd-d8d385655247","organization":"f445683a-adf7-11e9-80dd-d8d385655247","sum":264.000000000,"discount":0.0,"number":"34203","status":"Доставлена","statusCode":"DELIVERED","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"0c42ac09-670c-4872-9303-8b5b2f2ca9f6","location":{"latitude":46.644340100,"longitude":32.592405200,"accuracy":52,"date":"2021-08-25 17:33:01"}},"orderLocationInfo":{"latitude":46.6651514,"longitude":32.6600438},"deliveryDate":"2021-07-03 21:58:00","actualTime":"2021-07-11 13:23:47","billTime":"2021-07-03 22:26:53","cancelTime":null,"closeTime":null,"confirmTime":"2021-07-03 21:31:55","createdTime":"2021-07-03 21:27:00","printTime":"2021-07-03 21:32:37","sendTime":"2021-07-03 22:26:53","comment":"Грибы заменить куриным филе ;","problem":null,"operator":{"id":"fe9e1dc0-5b05-481f-aa62-368c46a07b84","firstName":"Софія","middleName":null,"lastName":"Дерич","displayName":"Софія Дерич","phone":null,"cellPhone":null,"email":null,"code":"29278","pinCode":"","note":null,"mainRole":{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":7739694,"deleted":false},"roles":[{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":7739694,"deleted":false}],"deleted":false,"externalRevision":26886046},"conception":{"id":"faefe192-a871-4bd3-8217-db430db51047","name":"Херсон","code":"20"},"marketingSource":{"id":"b26b3206-2bd3-4c4d-941b-1286f89a85f0","name":"Сайт інтернет","attachedSources":[],"externalRevision":101112},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"56b1f6a9-d61d-45be-9b80-e5ca7f0be15d","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00485","name":"Набір для персони звичайний","category":null,"amount":2.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"b88f3d59-3471-7f38-017a-6d9553258bdc","comboInformation":null},{"id":"6d2937a3-af5e-4983-90c0-51cf381fe78d","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01262","name":"Акційний рол Каліфорнія хіт","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"b88f3d59-3471-7f38-017a-6d9553258bdc","comboInformation":null},{"id":"b6f829e7-46b2-4f62-8fb6-2c225964aed9","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00551","name":"Піца Ніжна 40 см","category":null,"amount":1.000000000,"size":null,"modifiers":[{"id":"87c04a51-5925-40f7-8aa8-faad8437a907","orderItemId":"00000000-0000-0000-0000-000000000000","name":"Курка смажена","amount":1.000000000,"groupName":null,"size":null,"comboInformation":null,"sum":0.0,"code":null,"category":null}],"sum":245.000000000,"comment":"Грибы заменить куриным филе ;","guestId":"b88f3d59-3471-7f38-017a-6d9553258bdc","comboInformation":null}],"guests":[{"id":"b88f3d59-3471-7f38-017a-6d9553258bdc","name":"Ирина"}],"payments":[{"sum":264.000000000,"paymentType":{"id":"09322f46-578a-d210-add7-eec222a08871","code":"CASH","name":"Наличные","comment":null,"combinable":true,"externalRevision":18,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":true,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"eb44ce49-4a6d-9daa-0175-5fee03e2aa18","crmId":"1394797","restaurantName":"Херсон","groupName":null,"externalRevision":27987055,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"446cbc3d-325c-8bef-d629-0503ba247f40","comment":null,"marks":[]},"referrerId":null}]}';
}
