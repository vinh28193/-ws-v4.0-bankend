<?php
/**
 * Created by PhpStorm.
 * User: galat
 * Date: 22/02/2019
 * Time: 15:37
 */

/**
 * @var $faker \Faker\Generator
 * @var $index integer
 */

$id = $index + 1;
$name = $faker->word;

$list_rule = [
  '[{"conditions":[{"value":100,"key":"price","type":"int","operator":">="}],"value":10,"unit":"quantity","type":"P"},{"conditions":[{"value":100,"key":"price","type":"int","operator":"<"}],"value":8,"unit":"quantity","type":"$"}]',
  '[{"conditions":[{"value":0,"key":"price","type":"int","operator":">"}],"value":10,"unit":"quantity","type":"P"}]',
  '[{"value":"5","unit":"quantity","type":"P","conditions":[{"value":"5","key":"quantity","type":"int","operator":">"}]}]',
  '[{"conditions":[{"value":0,"key":"price","type":"int","operator":">"}],"value":10,"unit":"quantity","type":"P"}]',
  '[{"conditions":[{"value":0,"key":"price","type":"int","operator":">"}],"value":5,"unit":"quantity","type":"P"}]',
];
return [
    'id' => $id,
    'name' => $name,
    'description' => $name,
    'store_id' => 1,
    'parent_id' => null,
    'rule' => $id >= count($list_rule) ? $list_rule[rand(0,count($list_rule)-1)] : $list_rule[$id-1],
    'rule_description' => $name,
    'created_at' => time(),
    'updated_at' => time(),
    'active' => 1,
    'remove' => 0,
    'version' => 04,
];
