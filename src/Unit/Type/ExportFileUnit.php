<?php

namespace Maketok\DataMigration\Unit\Type;

use Maketok\DataMigration\Unit\ExportFileUnitInterface;

abstract class ExportFileUnit extends ExportDbUnit implements ExportFileUnitInterface
{
    /**
     * @var array
     */
    protected $reversedMapping;
    /**
     * @var array
     */
    protected $reversedConnection;
    /**
     * @var bool
     */
    protected $optional = false;

    /**
     * {@inheritdoc}
     */
    public function getReversedMapping()
    {
        return $this->reversedMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function setReversedMapping($reversedMapping)
    {
        $this->reversedMapping = $reversedMapping;
    }

    /**
     * {@inheritdoc}
     */
    public function getReversedConnection()
    {
        return $this->reversedConnection;
    }

    /**
     * {@inheritdoc}
     */
    public function setReversedConnection($reversedConnection)
    {
        $this->reversedConnection = $reversedConnection;
    }

    /**
     * {@inheritdoc}
     */
    public function isOptional()
    {
        return $this->optional;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptional($optional)
    {
        $this->optional = $optional;
    }
}
