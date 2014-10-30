<?php
namespace Icecave\Overpass\PubSub;

use Psr\Log\LoggerAwareInterface;

class PresenceAwareSubscriber implements PublisherInterface
{
    public function __construct(
        $subscriberId,
        SubscriberInterface $subscriber,
        PublisherInterface $presencePublisher,
        $presenceTopic = 'overpass/presence'
    ) {
        $this->subscriberId = $subscriberId;
        $this->subscriber = $subscriber;
        $this->presencePublisher = $presencePublisher;
        $this->presenceTopic = $presenceTopic;
    }

    /**
     * Subscribe to the given topic.
     *
     * @param string $topic The topic or topic pattern to subscribe to.
     */
    public function subscribe($topic)
    {
        $this->subscriber->subscribe($topic);

        $this->presencePublisher->publish(
            $this->presenceTopic,
            (object) [
                'subscriber' => $this->subscriberId,
                'topic'      => $topic,
                'action'     => 'subscribe',
            ]
        );
    }

    /**
     * Unsubscribe from the given topic.
     *
     * @param string $topic The topic or topic pattern to unsubscribe from.
     */
    public function unsubscribe($topic)
    {
        $this->subscriber->unsubscribe($topic);

        $this->presencePublisher->publish(
            $this->presenceTopic,
            (object) [
                'subscriber' => $this->subscriberId,
                'topic'      => $topic,
                'action'     => 'unsubscribe',
            ]
        );
    }

    /**
     * Consume messages from subscriptions.
     *
     * When a message is received the callback is invoked with two parameters,
     * the first is the topic to which the message was published, the second is
     * the message payload.
     *
     * The callback must return true in order to keep consuming messages, or
     * false to end consumption.
     *
     * @param callable $callback The callback to invoke when a message is received.
     */
    public function consume(callable $callback)
    {
        $this->subscriber->consume($callback);
    }

    private $subscriberId;
    private $subscriber;
    private $presencePublisher;
    private $presenceTopic;
}
