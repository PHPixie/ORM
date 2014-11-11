<?php

namespace PHPixie\ORM\Models\Type\Database;

interface Query extends \PHPixie\ORM\Conditions\Condition\Collection\Item
{
    public function modelName();
    
    public function limit($limit);
    public function getLimit();
    public function clearLimit();
    
    public function offset($offset);
    public function getOffset();
    public function clearOffset();
    
    public function orderAscendingBy($field);
    public function orderDescendingBy($field);
    public function getOrderBy();
    public function clearOrderBy();
    
    
    public function planFind($preload = array());
    public function find($preload = array());
    public function findOne($preload = array());
    
    public function planDelete();
    public function delete();
    
    public function planUpdate($data);
    public function update($data);
    
    public function planCount();
    public function count();
    
    public function where();
    public function andWhere();
    public function orWhere();
    public function xorWhere();
    public function whereNot();
    public function andWhereNot();
    public function orWhereNot();
    public function xorWhereNot();
    public function startWhereGroup();
    public function startAndWhereGroup();
    public function startOrWhereGroup();
    public function startXorWhereGroup();
    public function startWhereNotGroup();
    public function startAndWhereNotGroup();
    public function startOrWhereNotGroup();
    public function startXorWhereNotGroup();
    public function endWhereGroup();
    
    public function _and();
    public function _or();
    public function _xor();
    public function _not();
    public function andNot();
    public function orNot();
    public function xorNot();
    public function startGroup();
    public function startAndGroup();
    public function startOrGroup();
    public function startXorGroup();
    public function startNotGroup();
    public function startAndNotGroup();
    public function startOrNotGroup();
    public function startXorNotGroup();
    public function endGroup();
    
    public function in($items);
    public function andIn($items);
    public function orIn($items);
    public function xorIn($items);
    public function notIn($items);
    public function andNotIn();
    public function orNotIn($items);
    public function xorNotIn($items);
    
    public function relatedTo($relationship, $items);
    public function andRelatedTo($relationship, $items);
    public function orRelatedTo($relationship, $items);
    public function xorRelatedTo($relationship, $items);
    public function notRelatedTo($relationship, $items);
    public function andNotRelatedTo($relationship, $items);
    public function orNotRelatedTo($relationship, $items);
    public function xorNotRelatedTo($relationship, $items);
    public function startRelatedToGroup($relationship);
    public function startAndRelatedToGroup($relationship);
    public function startOrRelatedToGroup($relationship);
    public function startXorRelatedToGroup($relationship);
    public function startNotRelatedToGroup($relationship);
    public function startAndNotRelatedToGroup($relationship);
    public function startOrNotRelatedToGroup($relationship);
    public function startXorNotRelatedToGroup($relationship);
    
    public function addOperatorCondition($logic, $negate, $args);
    public function addCollectionCondition($logic, $negate, $items);
    public function addRelatedCondition($logic, $negate, $relationship, $items);
    
    public function __get($name);
    public function __call($method, $params);
}
