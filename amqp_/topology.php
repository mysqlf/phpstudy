<?php
require_once('splitsentence.php');
require_once('RandomSentenceSpout.php');
function create($builder){
    $builder->setSpout("spout", RandomSentenceSpout(), 1);
    $builder->setBolt("split", SplitSentenceBolt(), 1)->shuffleGrouping("spout");
    $builder->setBolt("count", WordCountBolt(),
                    1)->fieldsGrouping("split", ["word"]);
}