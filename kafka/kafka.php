<?php
$rk = new RdKafka\Producer();
$rk->setLogLevel(LOG_DEBUG);
$rk->addBrokers("192.168.70.168");
$topic = $rk->newTopic("test");
$topic->produce(RD_KAFKA_PARTITION_UA, 0, "Message payload");