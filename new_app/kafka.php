<?php

$conf = new RdKafka\Conf();
$conf->set('log_level', (string) LOG_DEBUG);
$conf->set('debug', 'all');
$rk = new RdKafka\Consumer($conf);
$rk->addBrokers("kafka");

$topicConf = new RdKafka\TopicConf();
$topicConf->set('auto.commit.interval.ms', 100);
$topicConf->set('offset.store.method', 'file');
$topicConf->set('offset.store.path', sys_get_temp_dir());
$topicConf->set('auto.offset.reset', 'smallest');

$topic = $rk->newTopic("debezium_cdc_topic.old_mysql.t1", $topicConf);

$topic->consumeStart(0, RD_KAFKA_OFFSET_BEGINNING);

while (true) {
    $msg = $topic->consume(0, 1000);
    if (null === $msg || $msg->err === RD_KAFKA_RESP_ERR__PARTITION_EOF) {
        // Constant check required by librdkafka 0.11.6. Newer librdkafka versions will return NULL instead.
        continue;
    } elseif ($msg->err) {
        echo $msg->errstr(), "\n";
        break;
    } else {
        $messageObject = json_decode($msg->payload);
        $after = $messageObject->payload->after;

        $mysqli = new mysqli("new_mysql", "phper", "secret", "new_mysql");
        $sql = "INSERT INTO t1 (id, col1, col2, col3) values($after->id, '$after->col1', '$after->col2', NULL)";
        $mysqli->query($sql);
        $mysqli->close();
    }
}