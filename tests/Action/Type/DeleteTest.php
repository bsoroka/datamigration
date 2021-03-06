<?php

namespace Maketok\DataMigration\Action\Type;

use Maketok\DataMigration\Storage\Db\ResourceInterface;

class DeleteTest extends \PHPUnit_Framework_TestCase
{
    use ServiceGetterTrait;

    public function testGetCode()
    {
        $action = new Delete(
            $this->getUnitBag(),
            $this->getConfig(),
            $this->getResource()
        );
        $this->assertEquals('delete', $action->getCode());
    }

    /**
     * @param bool $expects
     * @return ResourceInterface
     */
    protected function getResource($expects = false)
    {
        $resource = $this->getMockBuilder('\Maketok\DataMigration\Storage\Db\ResourceInterface')
            ->getMock();
        if ($expects) {
            $resource->expects($this->atLeastOnce())->method('deleteUsingTempPK');
        }
        return $resource;
    }

    public function testProcess()
    {
        $unit = $this->getUnit('tmp');
        $unit->setTmpTable('tmp1');
        $action = new Delete(
            $this->getUnitBag([$unit]),
            $this->getConfig(),
            $this->getResource(true)
        );
        $action->process($this->getResultMock());
    }

    /**
     * @expectedException \Maketok\DataMigration\Action\Exception\WrongContextException
     */
    public function testWrongProcess()
    {
        $action = new Delete(
            $this->getUnitBag([$this->getUnit('tmp')]),
            $this->getConfig(),
            $this->getResource()
        );
        $action->process($this->getResultMock());
    }
}
