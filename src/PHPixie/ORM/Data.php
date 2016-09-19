<?php

namespace PHPixie\ORM;

/**
 * Class Data
 * @package PHPixie\ORM
 */
class Data
{
    /**
     * @type Data\Types\Document\Builder
     */
    protected $documentBuilder;

    /**
     * @param $set
     * @return Data\Diff
     */
    public function diff($set)
    {
        return new \PHPixie\ORM\Data\Diff($set);
    }

    /**
     * @param $set
     * @param $unset
     * @return Data\Diff\Removing
     */
    public function removingDiff($set, $unset)
    {
        return new \PHPixie\ORM\Data\Diff\Removing($set, $unset);
    }

    /**
     * @param null|array $data
     * @return Data\Types\Map
     */
    public function map($data = null)
    {
        return new \PHPixie\ORM\Data\Types\Map($this, $data);
    }

    /**
     * @param $documentNode
     * @return Data\Types\Document
     */
    public function document($documentNode)
    {
        return new \PHPixie\ORM\Data\Types\Document($documentNode);
    }

    /**
     * @param $documentNode
     * @return Data\Types\Document\Diffable
     */
    public function diffableDocument($documentNode)
    {
        return new \PHPixie\ORM\Data\Types\Document\Diffable($this, $documentNode);
    }

    /**
     * @param null|array $data
     * @return Data\Types\Document
     */
    public function documentFromData($data = null)
    {
        $document = $this->documentBuilder()->document($data);

        return $this->document($document);
    }

    /**
     * @param null|array $data
     * @return Data\Types\Document\Diffable
     */
    public function diffableDocumentFromData($data = null)
    {
        $document = $this->documentBuilder()->document($data);

        return $this->diffableDocument($document);
    }

    /**
     * @return Data\Types\Document\Builder
     */
    protected function documentBuilder()
    {
        if ($this->documentBuilder === null) {
            $this->documentBuilder = $this->buildDocumentBuilder();
        }

        return $this->documentBuilder;
    }

    /**
     * @return Data\Types\Document\Builder
     */
    protected function buildDocumentBuilder()
    {
        return new \PHPixie\ORM\Data\Types\Document\Builder();
    }
}