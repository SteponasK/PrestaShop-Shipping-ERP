<?php

namespace Tests\Invertus\Academy\ApiHelper;
require_once 'C:\Users\Steponas\Desktop\Academy-ERP\Academy-ERP\src\Service\ApiHelper.php';

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

}
