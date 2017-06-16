<?php

namespace vendelev\cache\Test;

use PHPUnit_Framework_TestCase;
use vendelev\cache\RuntimeCache;

/**
 * @coversDefaultClass \vendelev\cache\RuntimeCache
 */
class RuntimeCacheTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var RuntimeCache
     */
    protected $memory = null;

    /**
     * @test
     * @covers ::me()
     */
    public function me()
    {
        $me1 = RuntimeCache::me();
        $me2 = RuntimeCache::me();

        $this->assertInstanceOf('\Vendelev\Cache\RuntimeCache', $me1);
        $this->assertInstanceOf('\Vendelev\Cache\RuntimeCache', $me2);

        $me1->set('test/test3/test4', 1234);
        $this->assertEquals($me1->get('test/test3/test4'), $me2->get('test/test3/test4'));
    }

    /**
     * @test
     * @covers ::add()
     */
    public function add()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertTrue($this->getMemory()->add('test/test3/test4', 1234));
        $this->assertFalse($this->getMemory()->add('test/test3/test4', 1234));
    }

    /**
     * @test
     * @covers ::set()
     * @covers ::get()
     */
    public function set()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertEquals(1234, $this->getMemory()->set('test2', 1234)->get('test2'));
        $this->assertEquals(1234, $this->getMemory()->set('test/test3/test4', 1234)->get('test/test3/test4'));
        $this->assertEquals(1234, $this->getMemory()->set('test/test3/test4/test5', 1234)->get('test/test3/test4/test5'));
    }

    /**
     * @test
     * @covers ::get()
     */
    public function get()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertEquals(1234, $this->getMemory()->get('test/test3/test4/test5', 1234));
    }

    /**
     * @test
     * @dataProvider getDataProvider
     * @covers ::has()
     * @covers ::setSeparate()
     *
     * @param array  $data
     * @param string $separate
     * @param string $hasKey
     * @param string $notKey
     */
    public function has($data, $separate, $hasKey, $notKey)
    {
        fwrite(STDOUT, "\n". __METHOD__ .' with ('. $separate .')');

        $this->getMemory()->setAll($data)->setSeparate($separate);
        $this->assertTrue($this->getMemory()->has($hasKey));
        $this->assertFalse($this->getMemory()->has($notKey));
    }

    /**
     * @test
     * @covers ::delete()
     */
    public function delete()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->getMemory()->set('test/test3/test4/test5', 1234);
        $this->assertTrue($this->getMemory()->delete('test/test3'));
        $this->assertFalse($this->getMemory()->has('test/test3'));
    }

    /**
     * @test
     * @dataProvider getDataProvider
     * @covers ::setAll()
     * @covers ::getAll()
     *
     * @param array  $data
     */
    public function cache($data)
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertEquals($data, $this->getMemory()->setAll($data)->getAll());
    }

    /**
     * @return array
     */
    public function getDataProvider()
    {
        return [
            [['test' => ['test2' => ['test4' => 123]]], '/', 'test/test2', 'test/test4'],
            [['test' => ['test2' => 123, 'test3' => ['test4' => 123]]], '.', 'test.test3.test4', 'test.test2.test4'],
        ];
    }

    /**
     * @test
     * @covers ::setSeparate()
     * @covers ::getSeparate()
     */
    public function separate()
    {
        fwrite(STDOUT, "\n". __METHOD__);

        $this->assertEquals('/', $this->getMemory()->getSeparate());
        $this->assertEquals('.', $this->getMemory()->setSeparate('.')->getSeparate());
    }

    protected function setUp()
    {
        $this->setMemory(new RuntimeCache());
    }

    /**
     * @return RuntimeCache
     */
    public function getMemory()
    {
        return $this->memory;
    }

    /**
     * @param RuntimeCache $memory
     *
     * @return $this
     */
    public function setMemory($memory)
    {
        $this->memory = $memory;

        return $this;
    }

}
