<?php

namespace Tests\Invertus\Academy\ApiHelperTest;

require_once './src/Service/ApiHelper.php';

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Invertus\Academy\ApiHelper\ApiHelper;

class ApiHelperTest extends TestCase
{
    private $apiHelper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->apiHelper = new ApiHelper();
    }

    public function testGetData()
    {
        $requestData = ['key' => 'value'];
        $request = new Request([], [], [], [], [], [], json_encode($requestData));

        $data = $this->apiHelper->getData($request);

        $this->assertEquals($requestData, $data);
    }

    public function testIsApiKeyValid()
    {
        $_ENV['API_KEY'] = 'valid_api_key';
        
        $validRequest = new Request([], [], [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer valid_api_key']);
        $isValid = $this->apiHelper->isApiKeyValid($validRequest);
        $this->assertTrue($isValid);

         $invalidRequest = new Request([], [], [], [], [], ['HTTP_AUTHORIZATION' => 'Bearer invalid_api_key']);
         $isValid = $this->apiHelper->isApiKeyValid($invalidRequest);
         $this->assertFalse($isValid);
    }
}
