<?php

namespace App\Services\iiko;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;

class IikoServiceInterface
{
    /** @var IikoClient  */
    private IikoClient $iikoSmakiClient;

    /** @var IikoClient  */
    private IikoClient $iikoGoClient;

    /** @var DeliveryOrderService  */
    private DeliveryOrderService $deliveryOrderService;

    /** @var DeliveryService  */
    private DeliveryService $deliveryService;

    /**
     * @param IikoServiceParser $iikoServiceParser
     */
    public function __construct(
        private IikoServiceParser $iikoServiceParser,
    ) {
        $this->iikoSmakiClient = new IikoClient(IikoClient::SMAKI);
        $this->iikoGoClient = new IikoClient(IikoClient::GO);

        $this->deliveryOrderService = new DeliveryOrderService();
        $this->deliveryService = new DeliveryService();
    }

    /**
     * @param string $courierIikoId
     * @param int $userId
     * @param string $orderUuid
     * @param array $validated
     * @return bool[]
     * @throws \Exception
     */
    public function setOrderDelivered(string $courierIikoId, int $userId, string $orderUuid, array $validated): array
    {
        $success = false;
        DB::beginTransaction();

        try {
            switch ($validated['restaurant']) {
                case IikoClient::SMAKI:
                    $organizationId = IikoClient::ORGANIZATION_ID_SMAKI;
                    $client = $this->iikoSmakiClient;
                    break;
                case IikoClient::GO:
                    $organizationId = IikoClient::ORGANIZATION_ID_GO;
                    $client = $this->iikoGoClient;
                    break;
                default:
                    throw new InvalidArgumentException();
            }

            $response = Http::post(
                IikoClient::API_URL . "/orders/set_order_delivered?access_token={$client->getAccessToken()}&organization={$organizationId}",
                [
                    'courierId' => $courierIikoId,
                    'orderId' => $orderUuid,
                    'delivered' => true,
                    'actualDeliveryTime' => date('Y-m-d H:i:s'),
                ]
            );

            Log::channel('outgoing')->info(IikoClient::API_URL . "/orders/set_order_delivered");

            if ($response->successful()) {
                $this->deliveryOrderService->setAsDelivered($courierIikoId, $userId, $orderUuid, $validated);
                $success = true;
            } else {
                Log::error($response->body());
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }

        if ($success) {
            DB::commit();
        } else {
            DB::rollBack();
        }

        return [
            'success' => $success,
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
        try {
            $response = Http::get(IikoClient::API_URL . '/orders/get_courier_orders', [
                'access_token' => $this->iikoSmakiClient->getAccessToken(),
                'courier' => $courierIikoId,
                'organization' => IikoClient::ORGANIZATION_ID_SMAKI
            ]);

            Log::channel('outgoing')->info(IikoClient::API_URL . "/orders/get_courier_orders" . ' : ' . $response->body());

            $smakiOrders = $response->json();

            $response = Http::get(IikoClient::API_URL . '/orders/get_courier_orders', [
                'access_token' => $this->iikoGoClient->getAccessToken(),
                'courier' => $courierIikoId,
                'organization' => IikoClient::ORGANIZATION_ID_GO
            ]);

            Log::channel('outgoing')->info(IikoClient::API_URL . "/orders/get_courier_orders" . ' : ' . $response->body());

            $goOrders = $response->json();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw $e;
        }

//        $smakiOrders = json_decode($this->smaki, true);
//        $goOrders = json_decode($this->go, true);

        $existingOrdersInDb = $this->deliveryService->existingDeliveryForCourier($courierIikoId);

        return array_merge(
            $this->iikoServiceParser->parseDeliveryOrdersResponse($smakiOrders, $existingOrdersInDb),
            $this->iikoServiceParser->parseDeliveryOrdersResponse($goOrders, $existingOrdersInDb)
        );
    }

//    private string $go = '{"INFO":"","deliveryOrders":[{"customer":{"sex":0,"id":"ce295b3e-eb22-4adb-bdec-5af9ee75b007","externalId":null,"name":"Владислава","surName":"","nick":"","comment":"","phone":"+380666639310","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":false,"counteragentInfo":null},"orderId":"49040721-963d-43f8-8bac-8df7f78e54ef","customerId":"ce295b3e-eb22-4adb-bdec-5af9ee75b007","movedDeliveryId":null,"customerName":"Владислава","customerPhone":null,"address":{"city":"Николаев","street":"Херсонское шоссе ","streetId":"018cf4d4-0e07-b00a-0173-044cb77e2445","streetClassifierId":null,"index":"","home":"28","housing":"","apartment":"","entrance":"2","floor":"","doorphone":"","comment":"","regionId":null,"externalCartographyId":""},"restaurantId":"dcc74ad6-ad40-11e9-80dd-d8d385655247","organization":"dcc74ad6-ad40-11e9-80dd-d8d385655247","sum":333.000000000,"discount":41.000000000,"number":"37756","status":"В пути","statusCode":"ON_WAY","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"e715397f-3dbf-45a7-8974-4415b89adb5c","location":null},"orderLocationInfo":{"latitude":46.9582643,"longitude":32.048741},"deliveryDate":"2021-08-27 20:15:18","actualTime":"2021-08-27 20:06:45","billTime":"2021-08-27 19:57:28","cancelTime":null,"closeTime":"2021-08-27 20:06:46","confirmTime":"2021-08-27 19:17:21","createdTime":"2021-08-27 19:14:58","printTime":"2021-08-27 19:24:15","sendTime":"2021-08-27 19:57:28","comment":"","problem":null,"operator":{"id":"fe9e1dc0-5b05-481f-aa62-368c46a07b84","firstName":"Софія","middleName":null,"lastName":"Дерич","displayName":"Софія Дерич","phone":null,"cellPhone":null,"email":null,"code":"29278","pinCode":"","note":null,"mainRole":{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false},"roles":[{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false}],"deleted":false,"externalRevision":7757292},"conception":{"id":"850a7f60-dce2-4778-9d2b-62cec66e362b","name":"СГ Миколаїв","code":"31"},"marketingSource":{"id":"851bdd3d-a104-4965-b509-b8fdeceb1a8c","name":"Постійний кліент","attachedSources":[],"externalRevision":36032},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"51678f86-5c08-4dea-b154-2ab86467508e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01291","name":"Сет Кілограм","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":374.000000000,"comment":null,"guestId":"827f48df-b707-47bc-861d-7d88e0d2e752","comboInformation":null},{"id":"4e763b87-044c-4a1f-b0c2-e63213a7719e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00710","name":"Набір палички звичайні","category":null,"amount":3.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"827f48df-b707-47bc-861d-7d88e0d2e752","comboInformation":null},{"id":"71e103a5-492e-402d-aacb-c894af326c99","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01330","name":"Імбир","category":null,"amount":3.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"827f48df-b707-47bc-861d-7d88e0d2e752","comboInformation":null}],"guests":[{"id":"827f48df-b707-47bc-861d-7d88e0d2e752","name":"Владислава"}],"payments":[{"sum":333.000000000,"paymentType":{"id":"9cd5d67a-89b4-ab69-1365-7b8c51865a90","code":"VISA","name":"Банковская карта","comment":"","combinable":true,"externalRevision":313347,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":false,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"a1ba47ff-82ea-e8e9-0179-e6a2404b7353","crmId":"1822129","restaurantName":"СГ Миколаїв","groupName":null,"externalRevision":11877406,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[{"discountCardTypeId":"8566499f-d476-46e4-b57d-34b048513ace","discountCardSlip":null,"discountOrIncreaseSum":41.000000000}],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"49040721-963d-43f8-8bac-8df7f78e54ef","comment":null,"marks":[]},"referrerId":null},{"customer":{"sex":0,"id":"670b93fe-60ed-41d3-8d4d-9acf0de5298f","externalId":null,"name":"Юлія","surName":"","nick":"","comment":"","phone":"+380930204410","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":false,"counteragentInfo":null},"orderId":"2dfa4bc3-3060-4224-b9dd-2b8ebb48ca07","customerId":"670b93fe-60ed-41d3-8d4d-9acf0de5298f","movedDeliveryId":null,"customerName":"Юлія","customerPhone":null,"address":{"city":"Николаев","street":"Героев Украины просп.","streetId":"018cf4d4-0e07-b00a-0173-044cb77e230f","streetClassifierId":null,"index":"","home":"13г","housing":"","apartment":"","entrance":"3","floor":"","doorphone":"","comment":"на 21.00","regionId":null,"externalCartographyId":""},"restaurantId":"dcc74ad6-ad40-11e9-80dd-d8d385655247","organization":"dcc74ad6-ad40-11e9-80dd-d8d385655247","sum":335.000000000,"discount":342.000000000,"number":"52806","status":"В пути","statusCode":"ON_WAY","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"43148589-5520-4be0-9e26-8fb98a1cb153","location":null},"orderLocationInfo":{"latitude":46.9928917,"longitude":31.9991292},"deliveryDate":"2021-08-27 21:00:59","actualTime":"2021-08-27 20:54:39","billTime":"2021-08-27 20:19:05","cancelTime":null,"closeTime":"2021-08-27 20:54:40","confirmTime":"2021-08-27 16:13:05","createdTime":"2021-08-27 16:10:59","printTime":"2021-08-27 19:42:00","sendTime":"2021-08-27 20:19:05","comment":"виграш","problem":null,"operator":{"id":"4a813163-1fac-4bd8-9b09-ab373d78cc25","firstName":"Світлана","middleName":null,"lastName":"Дюжикова","displayName":"Світлана Дюжикова СГ","phone":null,"cellPhone":null,"email":null,"code":"29270","pinCode":"","note":null,"mainRole":{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false},"roles":[{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false}],"deleted":false,"externalRevision":11382507},"conception":{"id":"850a7f60-dce2-4778-9d2b-62cec66e362b","name":"СГ Миколаїв","code":"31"},"marketingSource":{"id":"851bdd3d-a104-4965-b509-b8fdeceb1a8c","name":"Постійний кліент","attachedSources":[],"externalRevision":36032},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"c9795450-7b6a-4d24-a51b-6ab14c1901fa","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01381","name":"Сет 2+2","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":335.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null},{"id":"2488aa78-d001-4d6f-8f10-d900f6a9d75d","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01198","name":"Лайт сет","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":342.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null},{"id":"4e763b87-044c-4a1f-b0c2-e63213a7719e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00710","name":"Набір палички звичайні","category":null,"amount":2.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null},{"id":"71e103a5-492e-402d-aacb-c894af326c99","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01330","name":"Імбир","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null}],"guests":[{"id":"224b239d-dc36-4716-8798-47ae1a58302a","name":"Юлія"}],"payments":[{"sum":335.000000000,"paymentType":{"id":"9cd5d67a-89b4-ab69-1365-7b8c51865a90","code":"VISA","name":"Банковская карта","comment":"","combinable":true,"externalRevision":313347,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":false,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"a1ba47ff-82ea-e8e9-0179-e6a2404b7353","crmId":"1822129","restaurantName":"СГ Миколаїв","groupName":null,"externalRevision":11877406,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[{"discountCardTypeId":"8566499f-d476-46e4-b57d-34b048513ace","discountCardSlip":null,"discountOrIncreaseSum":342.000000000}],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"2dfa4bc3-3060-4224-b9dd-2b8ebb48ca07","comment":null,"marks":[]},"referrerId":null}]}';
//    private string $smaki = '{"INFO":"","deliveryOrders":[]}';
}
