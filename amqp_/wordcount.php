<?php

/*#!/usr/bin/env python3
# -*- coding: utf-8 -*-
from collections import defaultdict
from petrel import storm
from petrel.emitter import BasicBolt


class WordCountBolt(BasicBolt):

    def __init__(self):
        super(WordCountBolt, self).__init__(script=__file__)
        self.count = defaultdict(int)

    @classmethod
    def declareOutputFields(cls):
        return ['word', 'count']

    def process(self, tup):
        word = tup.values[0]
        self._count[word] += 1
        storm.emit([word, self._count[word]])


def run():
    WordCountBolt().run()*/
require_once('storm.php');
class WordcountBolt extends BasicBolt
{

    public function process(Tuple $tuple)
    {
        $word = $tup->values[0];
        $this->_count[$word]+=1;
        $this->emit(array($word, $this->_count[$word]));
    }

    public function declareOutputFields($cls){
        return array('word', 'count');
    }

}
$wordcount = new WordcountBolt();
$wordcount->run();
