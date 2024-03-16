<?php

namespace Tests\Invertus\Academy\ApiHelper;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;

use Invertus\Academy\ApiHelper\ApiHelper;

class ShipmentPrintServiceTest extends KernelTestCase
{
    protected static $container;

    public function setUp(): void
    {
        self::bootKernel();
        self::$container = static::getContainer();
    }
    public function testGetData()
    {
        $apiHelperService = self::$container->get(ApiHelper::class);
        
        $requestData = ['key1' => 'value1', 'key2' => 'value2'];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));
        $returnedData = $apiHelperService->getData($request);

        self::assertIsArray($returnedData);
        self::assertSame($requestData, $returnedData);

        $requestData = [];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));
        $returnedData = $apiHelperService->getData($request);

        self::assertIsArray($returnedData);
        self::assertEmpty($returnedData);
        self::assertSame($requestData, $returnedData);
    }

    public function testIsApiKeyValid()
    {
        $apiHelperService = self::$container->get(ApiHelper::class);
        $_ENV['API_KEY'] = 'valid_api_key';
        
        $validRequest = new Request([], [], [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer valid_api_key']);
        $isValid = $apiHelperService->isApiKeyValid($validRequest);
        $this->assertTrue($isValid);

         $invalidRequest = new Request([], [], [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer invalid_api_key']);
         $isValid = $apiHelperService->isApiKeyValid($invalidRequest);
         $this->assertFalse($isValid);
    }
}