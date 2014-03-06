<?php

class Push
{
    protected $updateQuery;
    protected $ids = array();
    protected $path;
    protected $resultSteps = array();
    protected $idField;

    public function __construct($updateQuery, $path, $idField)
    {
        $this->updateQuery = $updateQuery;
        $this->path = $path;
        $this->idField = $idField;
    }

    public function addResultStep($step)
    {
        $this->resultSteps[] = $step;
    }

    public function addId($id)
    {
        $this->ids[] = $id;
    }

    public function execute()
    {
        $ids = $this->ids;

        foreach($this->resultSteps as $step)
            foreach($step->result() as $item)
                $ids[] = $item->{$this->idField};

        $this->updateQuery
                        ->data(array(
                            '$pull' => array(
                                $this->path => array(
                                    $this->idField => array(
                                        'in' => $ids
                                    )
                                )
                            )
                        ))
                        ->execute();
    }
}
