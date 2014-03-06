<?php

class Push
{
    protected $updateQuery;
    protected $items = array();
    protected $path;
    protected $resultSteps = array();

    public function __construct($updateQuery, $path)
    {
        $this->updateQuery = $updateQuery;
        $this->path = $path;
    }

    public function addResultStep($step)
    {
        $this->resultSteps[] = $step;
    }

    public function addItem($item)
    {
        $this->items[] = $item;
    }

    public function execute()
    {
        $data = $this->items;

        foreach($this->resultSteps as $step)
            foreach($step->result() as $item)
                $data[] = $item;

        $this->updateQuery
                        ->data(array(
                            '$pushAll' => array(
                                $this->path,
                                $data
                            )
                        ))
                        ->execute();
    }
}
