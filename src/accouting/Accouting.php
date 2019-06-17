<?php

declare(strict_types=1);

namespace Accouting;

class Accouting implements AccoutingInterface
{
    public function createMerchantById(CreateMerchantByIdRequest $request): ReponseCreateMerchantById
    {
        /*
    	$CountryCode = $request->getCountryCode();
        $UserId = $request->getUserId();
        $CurrencyCode = $request->getCurrencyCode();
    	return (new ReponseCreateMerchantById())->setCountryCode($CountryCode)
                     ->setUserId($UserId);
        */
    }

    public function getListMerchantById(GetListMerchantByIdRequest $request): GetListMerchantByIdResponse
    {
        // return (new GetListMerchantByIdRequest())->
    }

    public function wsCreateTransaction(WsCreateTransactionRequest $request):WsCreateTransactionResponse
    {

    }
}

