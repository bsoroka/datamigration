<?php

namespace Maketok\DataMigration\Action\Type;

use Maketok\DataMigration\Action\ActionInterface;
use Maketok\DataMigration\Action\Exception\WrongContextException;
use Maketok\DataMigration\Unit\ImportDbUnitInterface;
use Maketok\DataMigration\Unit\ImportFileUnitInterface;
use Maketok\DataMigration\Unit\UnitBagInterface;
use Maketok\DataMigration\Workflow\ResultInterface;

/**
 * Load data from tmp files to tmp tables
 */
class Load extends AbstractDbAction implements ActionInterface
{
    /**
     * @var UnitBagInterface|ImportDbUnitInterface[]|ImportFileUnitInterface[]
     */
    protected $bag;

    /**
     * {@inheritdoc}
     * @throws WrongContextException
     */
    public function process(ResultInterface $result)
    {
        $result->setActionStartTime($this->getCode(), new \DateTime());
        foreach ($this->bag as $unit) {
            if ($unit->getTmpFileName() === null) {
                throw new WrongContextException(sprintf(
                    "Action can not be used for current unit %s. Tmp file is missing.",
                    $unit->getCode()
                ));
            }
            $unit->setTmpTable($this->getTmpTableName($unit));
            $values = array_map(function () {
                return ['text', ['notnull' => false]];
            }, $unit->getMapping());
            $this->resource->createTmpTable(
                $unit->getTmpTable(),
                array_combine(array_keys($unit->getMapping()), $values)
            );
            $loaded = $this->resource->loadData(
                $unit->getTmpTable(),
                $unit->getTmpFileName(),
                $this->config->offsetGet('local_infile')
            );
            $result->incrementActionProcessed($this->getCode(), $loaded);
            if (!$this->config['file_debug']) {
                $unit->getFilesystem()->cleanUp($unit->getTmpFileName());
            }
        }
        $result->setActionEndTime($this->getCode(), new \DateTime());
    }

    /**
     * {@inheritdoc}
     */
    public function getCode()
    {
        return 'load';
    }
}
