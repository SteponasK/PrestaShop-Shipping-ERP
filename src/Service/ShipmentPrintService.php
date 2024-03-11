<?php

namespace Invertus\Academy\ShipmentPrintService;

use Symfony\Component\HttpFoundation\Request;

class ShipmentPrintService
{
    public function isApiKeyValid(Request $request): bool
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token) {
            return false;
        }
        return true;
    }
}