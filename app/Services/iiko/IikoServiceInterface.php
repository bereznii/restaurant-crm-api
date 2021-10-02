<?php

namespace App\Services\iiko;

use Illuminate\Support\Facades\Auth;
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

            Log::channel('outgoing')->info($userId . ' | ' . IikoClient::API_URL . "/orders/set_order_delivered" . ' : ' . $response->body());

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

            Log::channel('outgoing')->info(Auth::id() . ' | ' . IikoClient::API_URL . "/orders/get_courier_orders" . ' : ' . $response->body());

            $smakiOrders = $response->json();

            $response = Http::get(IikoClient::API_URL . '/orders/get_courier_orders', [
                'access_token' => $this->iikoGoClient->getAccessToken(),
                'courier' => $courierIikoId,
                'organization' => IikoClient::ORGANIZATION_ID_GO
            ]);

            Log::channel('outgoing')->info(Auth::id() . ' | ' . IikoClient::API_URL . "/orders/get_courier_orders" . ' : ' . $response->body());

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

    private string $go = '{"INFO":"","deliveryOrders":[{"customer":{"sex":0,"id":"ce295b3e-eb22-4adb-bdec-5af9ee75b007","externalId":null,"name":"Владислава","surName":"","nick":"","comment":"","phone":"+380666639310","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":false,"counteragentInfo":null},"orderId":"49040721-963d-43f8-8bac-8df7f78e54ef","customerId":"ce295b3e-eb22-4adb-bdec-5af9ee75b007","movedDeliveryId":null,"customerName":"Владислава","customerPhone":null,"address":{"city":"Николаев","street":"Херсонское шоссе ","streetId":"018cf4d4-0e07-b00a-0173-044cb77e2445","streetClassifierId":null,"index":"","home":"28","housing":"","apartment":"","entrance":"2","floor":"","doorphone":"","comment":"","regionId":null,"externalCartographyId":""},"restaurantId":"dcc74ad6-ad40-11e9-80dd-d8d385655247","organization":"dcc74ad6-ad40-11e9-80dd-d8d385655247","sum":333.000000000,"discount":41.000000000,"number":"37756","status":"В пути","statusCode":"ON_WAY","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"e715397f-3dbf-45a7-8974-4415b89adb5c","location":null},"orderLocationInfo":{"latitude":46.9582643,"longitude":32.048741},"deliveryDate":"2021-08-27 20:15:18","actualTime":"2021-08-27 20:06:45","billTime":"2021-08-27 19:57:28","cancelTime":null,"closeTime":"2021-08-27 20:06:46","confirmTime":"2021-08-27 19:17:21","createdTime":"2021-08-27 19:14:58","printTime":"2021-08-27 19:24:15","sendTime":"2021-08-27 19:57:28","comment":"","problem":null,"operator":{"id":"fe9e1dc0-5b05-481f-aa62-368c46a07b84","firstName":"Софія","middleName":null,"lastName":"Дерич","displayName":"Софія Дерич","phone":null,"cellPhone":null,"email":null,"code":"29278","pinCode":"","note":null,"mainRole":{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false},"roles":[{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false}],"deleted":false,"externalRevision":7757292},"conception":{"id":"850a7f60-dce2-4778-9d2b-62cec66e362b","name":"СГ Миколаїв","code":"31"},"marketingSource":{"id":"851bdd3d-a104-4965-b509-b8fdeceb1a8c","name":"Постійний кліент","attachedSources":[],"externalRevision":36032},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"51678f86-5c08-4dea-b154-2ab86467508e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01291","name":"Сет Кілограм","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":374.000000000,"comment":null,"guestId":"827f48df-b707-47bc-861d-7d88e0d2e752","comboInformation":null},{"id":"4e763b87-044c-4a1f-b0c2-e63213a7719e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00710","name":"Набір палички звичайні","category":null,"amount":3.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"827f48df-b707-47bc-861d-7d88e0d2e752","comboInformation":null},{"id":"71e103a5-492e-402d-aacb-c894af326c99","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01330","name":"Імбир","category":null,"amount":3.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"827f48df-b707-47bc-861d-7d88e0d2e752","comboInformation":null}],"guests":[{"id":"827f48df-b707-47bc-861d-7d88e0d2e752","name":"Владислава"}],"payments":[{"sum":333.000000000,"paymentType":{"id":"9cd5d67a-89b4-ab69-1365-7b8c51865a90","code":"VISA","name":"Банковская карта","comment":"","combinable":true,"externalRevision":313347,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":false,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"a1ba47ff-82ea-e8e9-0179-e6a2404b7353","crmId":"1822129","restaurantName":"СГ Миколаїв","groupName":null,"externalRevision":11877406,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[{"discountCardTypeId":"8566499f-d476-46e4-b57d-34b048513ace","discountCardSlip":null,"discountOrIncreaseSum":41.000000000}],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"49040721-963d-43f8-8bac-8df7f78e54ef","comment":null,"marks":[]},"referrerId":null},{"customer":{"sex":0,"id":"670b93fe-60ed-41d3-8d4d-9acf0de5298f","externalId":null,"name":"Юлія","surName":"","nick":"","comment":"","phone":"+380930204410","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":false,"counteragentInfo":null},"orderId":"2dfa4bc3-3060-4224-b9dd-2b8ebb48ca07","customerId":"670b93fe-60ed-41d3-8d4d-9acf0de5298f","movedDeliveryId":null,"customerName":"Юлія","customerPhone":null,"address":{"city":"Николаев","street":"Героев Украины просп.","streetId":"018cf4d4-0e07-b00a-0173-044cb77e230f","streetClassifierId":null,"index":"","home":"13г","housing":"","apartment":"","entrance":"3","floor":"","doorphone":"","comment":"на 21.00","regionId":null,"externalCartographyId":""},"restaurantId":"dcc74ad6-ad40-11e9-80dd-d8d385655247","organization":"dcc74ad6-ad40-11e9-80dd-d8d385655247","sum":335.000000000,"discount":342.000000000,"number":"52806","status":"В пути","statusCode":"ON_WAY","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"43148589-5520-4be0-9e26-8fb98a1cb153","location":null},"orderLocationInfo":{"latitude":46.9928917,"longitude":31.9991292},"deliveryDate":"2021-08-27 21:00:59","actualTime":"2021-08-27 20:54:39","billTime":"2021-08-27 20:19:05","cancelTime":null,"closeTime":"2021-08-27 20:54:40","confirmTime":"2021-08-27 16:13:05","createdTime":"2021-08-27 16:10:59","printTime":"2021-08-27 19:42:00","sendTime":"2021-08-27 20:19:05","comment":"виграш","problem":null,"operator":{"id":"4a813163-1fac-4bd8-9b09-ab373d78cc25","firstName":"Світлана","middleName":null,"lastName":"Дюжикова","displayName":"Світлана Дюжикова СГ","phone":null,"cellPhone":null,"email":null,"code":"29270","pinCode":"","note":null,"mainRole":{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false},"roles":[{"id":"f1733194-0fba-4efb-bd7e-63be063be1de","name":"Оператор","code":"О","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":3746412,"deleted":false}],"deleted":false,"externalRevision":11382507},"conception":{"id":"850a7f60-dce2-4778-9d2b-62cec66e362b","name":"СГ Миколаїв","code":"31"},"marketingSource":{"id":"851bdd3d-a104-4965-b509-b8fdeceb1a8c","name":"Постійний кліент","attachedSources":[],"externalRevision":36032},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"c9795450-7b6a-4d24-a51b-6ab14c1901fa","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01381","name":"Сет 2+2","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":335.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null},{"id":"2488aa78-d001-4d6f-8f10-d900f6a9d75d","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01198","name":"Лайт сет","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":342.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null},{"id":"4e763b87-044c-4a1f-b0c2-e63213a7719e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00710","name":"Набір палички звичайні","category":null,"amount":2.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null},{"id":"71e103a5-492e-402d-aacb-c894af326c99","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01330","name":"Імбир","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"224b239d-dc36-4716-8798-47ae1a58302a","comboInformation":null}],"guests":[{"id":"224b239d-dc36-4716-8798-47ae1a58302a","name":"Юлія"}],"payments":[{"sum":335.000000000,"paymentType":{"id":"9cd5d67a-89b4-ab69-1365-7b8c51865a90","code":"VISA","name":"Банковская карта","comment":"","combinable":true,"externalRevision":313347,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":false,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"a1ba47ff-82ea-e8e9-0179-e6a2404b7353","crmId":"1822129","restaurantName":"СГ Миколаїв","groupName":null,"externalRevision":11877406,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[{"discountCardTypeId":"8566499f-d476-46e4-b57d-34b048513ace","discountCardSlip":null,"discountOrIncreaseSum":342.000000000}],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"2dfa4bc3-3060-4224-b9dd-2b8ebb48ca07","comment":null,"marks":[]},"referrerId":null}]}';
    private string $smaki = '{"INFO":"","deliveryOrders":[{"customer":{"sex":0,"id":"93ad6112-748c-4853-b9e6-32320c6129c5","externalId":null,"name":"Ярослава","surName":"","nick":"","comment":"","phone":"+380930238747","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":true,"counteragentInfo":null},"orderId":"d4d4b42b-b3fa-39b6-5e6a-9acbac8e6d0b","customerId":"93ad6112-748c-4853-b9e6-32320c6129c5","movedDeliveryId":null,"customerName":null,"customerPhone":"+380930238747","address":{"city":"Львів","street":"Сельських вул.","streetId":"5ae9f7f2-447d-6643-016e-4322595af5dc","streetClassifierId":null,"index":"","home":"7","housing":"","apartment":"2","entrance":"","floor":"1","doorphone":"","comment":"Львів, Сельських вул., дім 7, поверх 1, квартира 2","regionId":null,"externalCartographyId":""},"restaurantId":"f445683a-adf7-11e9-80dd-d8d385655247","organization":"f445683a-adf7-11e9-80dd-d8d385655247","sum":355.000000000,"discount":0.0,"number":"51621","status":"Закрыта","statusCode":"ON_WAY","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"4676c2fb-4f25-49b0-8e29-9181453d9c0d","location":null},"orderLocationInfo":{"latitude":49.8300656,"longitude":24.0007733},"deliveryDate":"2021-10-01 00:47:00","actualTime":"2021-10-01 00:48:22","billTime":"2021-10-01 00:39:17","cancelTime":null,"closeTime":"2021-10-01 00:52:24","confirmTime":"2021-10-01 00:18:00","createdTime":"2021-10-01 00:15:00","printTime":"2021-10-01 00:19:25","sendTime":"2021-10-01 00:39:17","comment":"","problem":null,"operator":{"id":"caf4ffa3-f896-4081-bf17-6d2552c90a31","firstName":"Оксана","middleName":null,"lastName":"Корнеляк","displayName":"Корнеляк Оксана","phone":null,"cellPhone":null,"email":null,"code":"294662","pinCode":"","note":null,"mainRole":{"id":"24cb0ced-ab84-4fdf-add0-032553434bae","name":"КЦ Оператор","code":"КЦО","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":29843271,"deleted":false},"roles":[{"id":"24cb0ced-ab84-4fdf-add0-032553434bae","name":"КЦ Оператор","code":"КЦО","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":29843271,"deleted":false}],"deleted":false,"externalRevision":29447822},"conception":{"id":"7eb4680c-0b74-4ee4-a92c-8e9cd8b77cf0","name":"Кульпарківська","code":"3"},"marketingSource":{"id":"851bdd3d-a104-4965-b509-b8fdeceb1a8c","name":"Постійний кліент","attachedSources":[],"externalRevision":101112},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"dba3c1c1-a6d0-4755-9a2f-d72215bfa83e","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00208","name":"Сет МІХ","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":355.000000000,"comment":null,"guestId":"97636216-4bbe-073d-017c-388c2ce683ac","comboInformation":null},{"id":"56b1f6a9-d61d-45be-9b80-e5ca7f0be15d","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00485","name":"Набір для персони звичайний","category":null,"amount":2.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"97636216-4bbe-073d-017c-388c2ce683ac","comboInformation":null},{"id":"89910246-2464-423a-9843-e6db71abdb08","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01329","name":"Імбир","category":null,"amount":2.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"97636216-4bbe-073d-017c-388c2ce683ac","comboInformation":null}],"guests":[{"id":"97636216-4bbe-073d-017c-388c2ce683ac","name":"Ярослава"}],"payments":[{"sum":400.000000000,"paymentType":{"id":"09322f46-578a-d210-add7-eec222a08871","code":"CASH","name":"Готівка","comment":"","combinable":true,"externalRevision":29413054,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":false,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false},{"sum":15.000000000,"paymentType":{"id":"1b51a168-c8f2-46cd-95f9-4e124dd6b049","code":"BALL","name":"Оплата бонусами Smaki","comment":"","combinable":true,"externalRevision":12664286,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":"","isProcessedExternally":false,"isPreliminary":false,"isExternal":true,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"aa15c7b2-768f-dbf1-016c-8fc96e6aa61b","crmId":"793643","restaurantName":"Кульпарківська","groupName":"Smaki Maki: Кульпарківська","externalRevision":29910452,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"d4d4b42b-b3fa-39b6-5e6a-9acbac8e6d0b","comment":null,"marks":[]},"referrerId":null},{"customer":{"sex":0,"id":"b7b9d704-3b32-4bab-8b95-24c5eb1798c3","externalId":null,"name":"ТЕСТ ПЕРЕДАТЬ НА КУХНЮ","surName":"","nick":"","comment":"","phone":"+380111111111","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":true,"counteragentInfo":null},"orderId":"911588ea-1dd9-8c55-fecc-8c7fcdd5af16","customerId":"b7b9d704-3b32-4bab-8b95-24c5eb1798c3","movedDeliveryId":null,"customerName":"ТЕСТ ПЕРЕДАТЬ НА КУХНЮ","customerPhone":"+380111111111","address":{"city":"Херсон","street":"Консервный переулок","streetId":"eb44ce49-4a6d-9daa-0175-2b5bc0893597","streetClassifierId":null,"index":"","home":"12","housing":"","apartment":"","entrance":"","floor":"","doorphone":"","comment":"Херсон, Консервный переулок, дім 12","regionId":null,"externalCartographyId":""},"restaurantId":"f445683a-adf7-11e9-80dd-d8d385655247","organization":"f445683a-adf7-11e9-80dd-d8d385655247","sum":382.000000000,"discount":0.0,"number":"34663","status":"Отменена","statusCode":"ON_WAY","deliveryCancelCause":{"id":"26028369-d381-32c2-8039-4de8ac137457","name":"Отказ клиента"},"deliveryCancelComment":"тест","courierInfo":{"courierId":"ba63c9a6-c97b-4408-8b72-06d71ed08d56","location":null},"orderLocationInfo":{"latitude":46.6735648,"longitude":32.6587646},"deliveryDate":"2021-10-02 00:20:55","actualTime":null,"billTime":null,"cancelTime":"2021-10-02 00:03:06","closeTime":null,"confirmTime":"2021-10-01 23:51:46","createdTime":"2021-10-01 23:40:56","printTime":null,"sendTime":"2021-10-01 23:59:35","comment":"; |  | Доставка за 29хв; | Замовлення онлайн | Подготовить сдачу с:  500;| asd","problem":null,"operator":{"id":"b045dba6-ad10-4f99-99a0-518380fbdfc1","firstName":"Христина","middleName":"Володимирівна ","lastName":"Береза","displayName":"Христина Береза","phone":null,"cellPhone":null,"email":null,"code":"29185","pinCode":"","note":null,"mainRole":{"id":"24cb0ced-ab84-4fdf-add0-032553434bae","name":"КЦ Оператор","code":"КЦО","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":29843271,"deleted":false},"roles":[{"id":"24cb0ced-ab84-4fdf-add0-032553434bae","name":"КЦ Оператор","code":"КЦО","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":29843271,"deleted":false}],"deleted":false,"externalRevision":29447822},"conception":{"id":"faefe192-a871-4bd3-8217-db430db51047","name":"Херсон","code":"20"},"marketingSource":{"id":"b26b3206-2bd3-4c4d-941b-1286f89a85f0","name":"Сайт інтернет","attachedSources":[],"externalRevision":101112},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"d8fcd56d-6403-44af-bf31-06c2b5ba43ee","orderItemId":"00000000-0000-0000-0000-000000000000","code":"01349","name":"Преміум Кілограм","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":382.000000000,"comment":null,"guestId":"8cfa264a-7e3a-4635-a4fb-ceca8e28e364","comboInformation":null}],"guests":[{"id":"8cfa264a-7e3a-4635-a4fb-ceca8e28e364","name":"ТЕСТ ПЕРЕДАТЬ НА КУХНЮ"}],"payments":[ { "additionalData": null, "chequeAdditionalInfo": null, "isExternal": false, "isFiscalizedExternally": false, "isPreliminary": true, "isProcessedExternally": false, "organizationDetailsInfo": null, "paymentType": { "applicableMarketingCampaigns": null, "code": "CASH", "combinable": true, "comment": "", "deleted": false, "externalRevision": 29413054, "id": "09322f46-578a-d210-add7-eec222a08871", "name": "Готівка" }, "sum": 500.000000000 } ],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"eb44ce49-4a6d-9daa-0175-5fee03e2aa18","crmId":"1394797","restaurantName":"Херсон","groupName":"Smaki Maki Херсон: Smaki Maki Херсон","externalRevision":30013380,"technicalInformation":null,"address":null,"protocolVersion":2},"discounts":[],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"911588ea-1dd9-8c55-fecc-8c7fcdd5af16","comment":null,"marks":[]},"referrerId":null},{"customer":{"sex":0,"id":"5570fe19-11a6-424d-a4c8-16c1e4d84858","externalId":null,"name":"Тарас","surName":"","nick":"","comment":"","phone":"+380682122795","additionalPhones":[],"addresses":[],"cultureName":null,"birthday":null,"email":null,"middleName":null,"shouldReceivePromoActionsInfo":true,"counteragentInfo":null},"orderId":"08bbc234-b20b-00e7-9593-11a218aee9bf","customerId":"5570fe19-11a6-424d-a4c8-16c1e4d84858","movedDeliveryId":null,"customerName":null,"customerPhone":"+380682122795","address":{"city":"Львів","street":"Любінська вул.","streetId":"5ae9f7f2-447d-6643-016e-4322595af474","streetClassifierId":null,"index":"","home":"102","housing":"","apartment":"171","entrance":"6","floor":"4","doorphone":"","comment":"Львів, Любінська вул., дім 102, підїзд 6, поверх 4, квартира 171","regionId":null,"externalCartographyId":""},"restaurantId":"f445683a-adf7-11e9-80dd-d8d385655247","organization":"f445683a-adf7-11e9-80dd-d8d385655247","sum":292.000000000,"discount":0.0,"number":"78234","status":"Закрыта","statusCode":"ON_WAY","deliveryCancelCause":null,"deliveryCancelComment":null,"courierInfo":{"courierId":"4676c2fb-4f25-49b0-8e29-9181453d9c0d","location":null},"orderLocationInfo":{"latitude":49.8221223,"longitude":23.9719732},"deliveryDate":"2021-10-01 00:09:46","actualTime":"2021-10-01 00:01:49","billTime":"2021-09-30 23:53:32","cancelTime":null,"closeTime":"2021-10-01 00:03:37","confirmTime":"2021-09-30 23:40:55","createdTime":"2021-09-30 23:32:47","printTime":"2021-09-30 23:42:25","sendTime":"2021-09-30 23:53:32","comment":"","problem":null,"operator":{"id":"bd8e04ce-dad2-4b95-9c42-03d872c33748","firstName":"Ольга","middleName":null,"lastName":"Попел","displayName":"Попел Ольга","phone":null,"cellPhone":null,"email":null,"code":"294750","pinCode":"","note":null,"mainRole":{"id":"24cb0ced-ab84-4fdf-add0-032553434bae","name":"КЦ Оператор","code":"КЦО","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":29843271,"deleted":false},"roles":[{"id":"24cb0ced-ab84-4fdf-add0-032553434bae","name":"КЦ Оператор","code":"КЦО","paymentPerHour":0.000000000,"steadySalary":0.000000000,"comment":"","scheduleType":"BYSESSION","externalRevision":29843271,"deleted":false}],"deleted":false,"externalRevision":29447822},"conception":{"id":"7eb4680c-0b74-4ee4-a92c-8e9cd8b77cf0","name":"Кульпарківська","code":"3"},"marketingSource":{"id":"bfca134b-e4ce-42f1-a008-792be06eee7f","name":"Додаток","attachedSources":[],"externalRevision":24452978},"durationInMinutes":29,"personsCount":1,"splitBetweenPersons":false,"items":[{"id":"11304bc4-baaf-45b0-b2dd-0414a69ec368","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00621","name":"Піца Гавайська 22 см","category":null,"amount":2.000000000,"size":null,"modifiers":[{"id":"2c2e0238-97b2-42a5-a074-00c1be142b99","orderItemId":"00000000-0000-0000-0000-000000000000","name":"Філадельфія 22","amount":1.000000000,"groupName":null,"size":null,"comboInformation":null,"sum":0.0,"code":null,"category":null}],"sum":252.000000000,"comment":null,"guestId":"97636216-4bbe-073d-017c-3868d1102e95","comboInformation":null},{"id":"858ffb22-01f3-4734-83b5-951497ef549a","orderItemId":"00000000-0000-0000-0000-000000000000","code":"00483","name":"Набір до піци","category":null,"amount":1.000000000,"size":null,"modifiers":[],"sum":0.000000000,"comment":null,"guestId":"97636216-4bbe-073d-017c-3868d1102e95","comboInformation":null}],"guests":[{"id":"97636216-4bbe-073d-017c-3868d1102e95","name":"Тарас"}],"payments":[{"sum":300.000000000,"paymentType":{"id":"09322f46-578a-d210-add7-eec222a08871","code":"CASH","name":"Готівка","comment":"","combinable":true,"externalRevision":29413054,"applicableMarketingCampaigns":null,"deleted":false},"additionalData":null,"isProcessedExternally":false,"isPreliminary":false,"isExternal":false,"chequeAdditionalInfo":null,"organizationDetailsInfo":null,"isFiscalizedExternally":false}],"orderType":{"id":"49cf98d2-25ab-d404-a5a8-11eaffc7ce7f","name":"Доставка курьером","orderServiceType":"DELIVERY_BY_COURIER","externalRevision":18},"deliveryTerminal":{"deliveryTerminalId":"aa15c7b2-768f-dbf1-016c-8fc96e6aa61b","crmId":"793643","restaurantName":"Кульпарківська","groupName":"Smaki Maki: Кульпарківська","externalRevision":29910452,"technicalInformation":null,"address":null,"protocolVersion":1},"discounts":[],"iikoCard5Coupon":null,"customData":null,"opinion":{"organization":null,"deliveryId":"08bbc234-b20b-00e7-9593-11a218aee9bf","comment":null,"marks":[]},"referrerId":null}]}';
}
