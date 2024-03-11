<?php

namespace Invertus\Academy\ApiHelper;


use Symfony\Component\HttpFoundation\Request;

class ApiHelper
{
    public function getData(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }

    public function isApiKeyValid(Request $request): bool
    {
        $authorizationHeader = $request->headers->get('Authorization');
        $token = str_replace('Bearer ', '', $authorizationHeader);
        if ($_ENV['API_KEY'] !== $token)
        {
            return false;
        }
        return true;
    }
}