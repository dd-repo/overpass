<?php
namespace Icecave\Overpass\Amqp\PubSub;

use Icecave\Overpass\PubSub\SubscriberInterface;
use Icecave\Overpass\Serialization\JsonSerialization;
use Icecave\Overpass\Serialization\SerializationInterface;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Message\AMQPMessage;
use Psr\Log\LoggerAwareTrait;

class AmqpSubscriber implements SubscriberInterface
{
    use LoggerAwareTrait;

    /**
     * @param AMQPChannel                 $channel
     * @param DeclarationManager|null     $declarationManager
     * @param SerializationInterface|null $serialization
     */
    public function __construct(
        AMQPChannel $channel,
        DeclarationManager $declarationManager = null,
        SerializationInterface $serialization = null
    ) {
        $this->channel = $channel;
        $this->declarationManager = $declarationManager ?: new DeclarationManager($channel);
        $this->serialization = $serialization ?: new JsonSerialization();
    }

    public function __destruct()
    {
        $this->stop();
    }

    /**
     * Subscribe to the given topic.
     *
     * @param string $topic The topic or topic pattern to subscribe to.
     */
    public function subscribe($topic)
    {
        $normalizedTopic = $this->normalizeTopic($topic);

        if (isset($this->subscriptions[$normalizedTopic])) {
            return;
        }

        $queue = $this
            ->declarationManager
            ->queue();

        $exchange = $this
            ->declarationManager
            ->exchange();

        $this
            ->channel
            ->queue_bind(
                $queue,
                $exchange,
                $normalizedTopic
            );

        $this->subscriptions[$normalizedTopic] = true;

        if ($this->logger) {
            $this->logger->debug(
                'Subscribed to topic "{topic}"',
                [
                    'topic' => $topic,
                ]
            );
        }
    }

    /**
     * Unsubscribe from the given topic.
     *
     * @param string $topic The topic or topic pattern to unsubscribe from.
     */
    public function unsubscribe($topic)
    {
        $normalizedTopic = $this->normalizeTopic($topic);

        if (!isset($this->subscriptions[$normalizedTopic])) {
            return;
        }

        $queue = $this
            ->declarationManager
            ->queue();

        $exchange = $this
            ->declarationManager
            ->exchange();

        $this
            ->channel
            ->queue_unbind(
                $this->declarationManager->queue(),
                $this->declarationManager->exchange(),
                $normalizedTopic
            );

        unset($this->subscriptions[$normalizedTopic]);

        if ($this->logger) {
            $this->logger->debug(
                'Unsubscribed from topic "{topic}"',
                [
                    'topic' => $topic,
                ]
            );
        }
    }

    public function start()
    {
        if ($this->consumerTag) {
            return;
        }

        $this->consumerTag = $this
            ->channel
            ->basic_consume(
                $this->declarationManager->queue(),
                '',    // consumer tag
                false, // no local
                true,  // no ack
                true,  // exclusive
                false, // no wait
                function ($message) {
                    $this->dispatch($message);
                }
            );
    }

    public function stop()
    {
        if (!$this->consumerTag) {
            return;
        }

        $this
            ->channel
            ->basic_cancel(
                $this->consumerTag
            );

        $this->consumerTag = null;
    }

    /**
     * Consume messages from subscriptions.
     *
     * @param integer|null $timeout
     *
     * @return tuple<string, mixed>|null
     */
    public function wait($timeout = null)
    {
        $this->start();

        $this->message = [null, null];

        if ($this->subscriptions) {
            $this
                ->channel
                ->wait(
                    null,  // method type
                    false, // non-blocking
                    $timeout
                );
        }

        $message = $this->message;
        $this->message = [null, null];

        return $message;
    }

    /**
     * Convert a topic with wildcard strings into an AMQP-style topic wildcard.
     *
     * @param string $topic
     *
     * @return string
     */
    private function normalizeTopic($topic)
    {
        return strtr(
            $topic,
            [
                '*' => '#',
                '?' => '*',
            ]
        );
    }

    /**
     * Dispatch a received message.
     *
     * @param AMQPMessage $message
     */
    private function dispatch(AMQPMessage $message)
    {
        $payload = $this
            ->serialization
            ->unserialize($message->body);

        $topic = $message->get('routing_key');

        if ($this->logger) {
            $this->logger->debug(
                'Received {payload} from topic "{topic}"',
                [
                    'topic' => $topic,
                    'payload' => json_encode($payload),
                ]
            );
        }

        $this->message = [
            $topic,
            $payload
        ];
    }

    private $channel;
    private $declarationManager;
    private $serialization;
    private $subscriptions;
    private $consumerTag;
    private $message;
}
