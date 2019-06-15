<?php

declare(strict_types=1);

namespace Accouting;

use Google\Protobuf\Internal\Message;

class AccoutingClient implements AccoutingInterface
{
    public function createMerchantById(CreateMerchantByIdRequest $request): ReponseCreateMerchantById
    {
        $reply = new CreateMerchantByIdRequest();
        $reply->mergeFromString($this->makeRequest($request, 'CreateMerchantById'));

        return $reply;
    }


    public function getListMerchantById(GetListMerchantByIdRequest $request): GetListMerchantByIdResponse
    {
        $reply = new GetListMerchantByIdRequest();
        $reply->mergeFromString($this->makeRequest($request, 'GetListMerchantById'));

        return $reply;
    }

    public function wsCreateTransaction(WsCreateTransactionRequest $request): WsCreateTransactionResponse
    {
        $reply = new WsCreateTransactionRequest();
        $reply->mergeFromString($this->makeRequest($request, 'WsCreateTransaction'));

        return $reply;
    }

    private function makeRequest(Message $message, string $method): string
    {
        $body = $message->serializeToString();

        $ch = curl_init("http://localhost:8000/{$method}");

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => $body,
        ]);

        $response = curl_exec($ch);

        curl_close($ch);

        return $response;
    }
}
