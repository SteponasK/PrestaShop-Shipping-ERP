<?php

namespace Invertus\Academy\ApiHelper;


use Symfony\Component\HttpFoundation\Request;

class ApiHelper
{
    public function getData(Request $request): array
    {
        return json_decode($request->getContent(), true);
    }
}