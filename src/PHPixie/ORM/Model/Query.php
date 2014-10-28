<?php
namespace PHPixie\ORM\Model;

interface Query
{
    public function modelName();
    public function limit($limit);
    public function getLimit();
    public function offset($offset);
    public function getOffset();
    public function orderBy($field, $dir = 'asc');
    public function getOrderBy();
    public function planDelete();
    public function planUpdate($type, $data);
    public function planFind($type, $preload = array());
    public function delete();
    public function update($data);
    public function find($preload = array());
    public function findOne($preload = array());
    public function endConditionGroup($logic = 'and');
    public function addCondition($logic, $negate, $args);
    public function startGroup($logic='and', $negate = false);
    public function endGroup();
    public function addCollection($logic, $negate, $collectionItems);
    public function addRelated($logic, $negate, $relationship, $condition);
    public function where();
    public function orWhere();
    public function xorWhere();
    public function andWhereNot();
    public function orWhereNot();
    public function xorWhereNot();
    public function _and();
    public function _or();
    public function _xor();
    public function _andNot();
    public function _orNot();
    public function _xorNot();
    public function in($collectionItems);
    public function orIn($collectionItems);
    public function xorIn($collectionItems);
    public function andInNot($collectionItems);
    public function orInNot($collectionItems);
    public function xorInNot($collectionItems);
    public function related($relationship, $condition);
    public function orRelated($relationship, $condition);
    public function xorRelated($relationship, $condition);
    public function andRelatedNot($relationship, $condition);
    public function orRelatedNot($relationship, $condition);
    public function xorRelatedNot($relationship, $condition);
    public function __get($name);
    public function __call($method, $params);
}
