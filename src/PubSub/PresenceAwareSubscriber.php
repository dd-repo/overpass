<?php
namespace Icecave\Overpass\PubSub;

use Psr\Log\LoggerAwareInterface;

class PresenceAwareSubscriber implements SubscriberInterface
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
     * Start consuming messages.
     */
    public function start()
    {
        return $this->subscriber->start();
    }

    /**
     * Stop consuming messages.
     */
    public function stop()
    {
        return $this->subscriber->stop();
    }

    /**
     * Consume messages from subscriptions.
     *
     * @param integer|null $timeout
     *
     * @return tuple<string|null, mixed>
     */
    public function wait($timeout = null)
    {
        return $this->subscriber->wait($timeout);
    }

    private $subscriberId;
    private $subscriber;
    private $presencePublisher;
    private $presenceTopic;
}
