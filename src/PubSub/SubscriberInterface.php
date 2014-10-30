<?php
namespace Icecave\Overpass\PubSub;

use Psr\Log\LoggerAwareInterface;

interface SubscriberInterface extends LoggerAwareInterface
{
    /**
     * Subscribe to the given topic.
     *
     * @param string $topic The topic or topic pattern to subscribe to.
     */
    public function subscribe($topic);

    /**
     * Unsubscribe from the given topic.
     *
     * @param string $topic The topic or topic pattern to unsubscribe from.
     */
    public function unsubscribe($topic);

    /**
     * Start consuming messages.
     */
    public function start();

    /**
     * Stop consuming messages.
     */
    public function stop();

    /**
     * Consume messages from subscriptions.
     *
     * @param integer|null $timeout
     *
     * @return tuple<string|null, mixed>
     */
    public function wait($timeout = null);
}
