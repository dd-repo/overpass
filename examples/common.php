<?php
require __DIR__ . '/../vendor/autoload.php';

Eloquent\Asplode\Asplode::install();

/**
 * All Overpass components accept a PSR-3 logger.
 *
 * This logger simply writes the logs to STDOUT.
 */
$logger = new Icecave\Stump\Logger();

/**
 * Connect to the AMQP server.
 */
$amqpConnection = new PhpAmqpLib\Connection\AMQPStreamConnection(
    getenv('OVERPASS_HOST') ?: 'localhost',
    getenv('OVERPASS_PORT') ?: 5672,
    getenv('OVERPASS_USER') ?: 'guest',
    getenv('OVERPASS_PASS') ?: 'guest',
    getenv('OVERPASS_VHOST') ?: '/'
);

/**
 * Create an AMQP channel.
 */
$amqpChannel = $amqpConnection->channel();
