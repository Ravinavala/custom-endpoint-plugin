<?php

/**
 * Class ApiTest
 *
 * @package Custom_Endpoint_User_Listing
 */

/**
 * Sample test case.
 */
//include CUSTOM_ENDPOINT_USER_INCLUDE_PATH . 'includes/class-custom-endpoint.php';

class Test_Custom_Endpoint extends WP_UnitTestCase
{
    protected $server;

    public function test_construct()
    {
        $inideUser = new Custom_Endpoint();
        $users = $inideUser->getUsers();
        $this->assertIsArray($users);
        $this->assertNotEmpty($users);
        $this->assertArrayHasKey('id', $users[0]);
    }
}
