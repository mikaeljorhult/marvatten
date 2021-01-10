<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class NetworkAdapterTest extends TestCase
{
    /**
     * MAC address is formated with colon delimiters.
     *
     * @return void
     */
    public function testMacAddressIsOutputWithDelimiters()
    {
        $adapter = new \App\Models\NetworkAdapter([
            'mac_address' => str_repeat('A', 12),
        ]);

        $this->assertEquals($adapter->mac_address, 'AA:AA:AA:AA:AA:AA');
    }

    /**
     * MAC address is stored without colon delimiters.
     *
     * @return void
     */
    public function testMacAddressIsStoredWithoutDelimiters()
    {
        $adapter = new \App\Models\NetworkAdapter([
            'mac_address' => 'AA:AA:AA:AA:AA:AA',
        ]);

        $this->assertEquals($adapter->getAttributes()['mac_address'], 'AAAAAAAAAAAA');
    }
}
