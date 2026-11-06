<?php
/**
 *
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 * 
 *   http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 *
 * @license http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @version //autogentag//
 * @filesource
 * @package SignalSlot
 * @subpackage Tests
 */

require_once( "test_classes.php" );

/**
 * Tests the optional options. Default values are tested in the signal collection test file.
 *
 * @package SignalSlot
 * @subpackage Tests
 */
class ezcSignalSlotCollectionOptionsTest extends ezcTestCase
{
    private $giver;
    private $receiver;

    protected function setUp(): void
    {
        $this->giver = new TheGiver();
        $this->receiver = new TheReceiver();
        TheReceiver::$globalFunctionRun = false;
        TheReceiver::$staticFunctionRun = false;
        ezcSignalStaticConnections::getInstance()->connections = array();
        ezcSignalCollection::setStaticConnectionsHolder( new EmptyStaticConnections() );
    }

    public function testSetOptionsArray()
    {
        $this->giver->signals->setOptions( array( 'signals' => array( 'someSignal' ) ) );
        $this->assertSame( array( 'someSignal' ), $this->giver->signals->options->signals );
    }

    public function testSetOptionsObject()
    {
        $this->giver->signals->setOptions( new ezcSignalCollectionOptions( array( 'signals' => array( 'someSignal' ) ) ) );
        $this->assertSame( array( 'someSignal' ), $this->giver->signals->options->signals );
    }

    public function testsetOptionsDirect()
    {
        $this->giver->signals->options = new ezcSignalCollectionOptions( array( 'signals' => array( 'someSignal' ) ) );
        $this->assertSame( array( 'someSignal' ), $this->giver->signals->options->signals );
    }

    public function testGetOptions()
    {
        $this->assertTrue( $this->giver->signals->options instanceof ezcSignalCollectionOptions );
    }

    public function testOptionSignalsFunctionIsConnectedValid()
    {
        $this->giver->signals->options->signals = array( 'mySignal' );
        $this->assertFalse( $this->giver->signals->isConnected( 'mySignal' ) );
    }

    public function testOptionSignalsFunctionIsConnectedInvalid()
    {
        $this->giver->signals->options->signals = array( 'mySignal' );
        $this->expectException( 'ezcSignalSlotException' );
        $this->giver->signals->isConnected( 'NoSuchSignal' );
    }

    public function testOptionSignalsFunctionEmitValid()
    {
        $this->giver->signals->options->signals = array( 'mySignal' );
        // The test is that emitting a listed signal does not throw.
        $this->expectNotToPerformAssertions();
        $this->giver->signals->emit( 'mySignal' );
    }

    public function testOptionSignalsFunctionEmitInvalid()
    {
        $this->giver->signals->options->signals = array( 'mySignal' );
        $this->expectException( 'ezcSignalSlotException' );
        $this->giver->signals->emit( 'NoSuchSignal' );
    }


    public function testOptionsSignalsFunctionConnectValid()
    {
        $this->giver->signals->options->signals = array( 'mySignal' );
        $this->giver->signals->connect( "mySignal", array( $this->receiver, "slotSingleParam" ) );
        $this->assertTrue( $this->giver->signals->isConnected( 'mySignal' ) );
    }

    public function testOptionsSignalsFunctionConnectInvalid()
    {
        $this->giver->signals->options->signals = array( 'mySignal' );
        $this->expectException( 'ezcSignalSlotException' );
        $this->giver->signals->connect( "noSuchSignal", array( $this->receiver, "slotSingleParam" ) );
    }

    public function testOptionsSignalsFunctionDisconnectValid()
    {
        $this->giver->signals->options->signals = array( 'mySignal' );
        $this->giver->signals->connect( "mySignal", array( $this->receiver, "slotSingleParam" ) );
        $this->giver->signals->disconnect( "mySignal", array( $this->receiver, "slotSingleParam" ) );
        $this->assertFalse( $this->giver->signals->isConnected( 'mySignal' ) );
    }

    public function testOptionsSignalsFunctionDisconnectInvalid()
    {
        $this->giver->signals->options->signals = array( 'myignal' );
        $this->expectException( 'ezcSignalSlotException' );
        $this->giver->signals->disconnect( "noSuchSignal", array( $this->receiver, "slotSingleParam" ) );
    }

    public static function suite()
    {
         return new \PHPUnit\Framework\TestSuite( "ezcSignalSlotCollectionOptionsTest" );
    }


}

?>
