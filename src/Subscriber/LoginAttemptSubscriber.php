<?php

namespace App\Subscriber;


use App\Event\BadPasswordLoginEvent;
use App\Service\LoginAttemptService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LoginAttemptSubscriber implements EventSubscriberInterface
{

    private  $service;

    public function __construct(LoginAttemptService $service)
    {
        $this->service = $service;
    }

    /**
     * @return array<string, string>
     */
    public static function getSubscribedEvents(): array
    {
        return [
            BadPasswordLoginEvent::class => 'onAuthenticationFailure'
        ];
    }

    public function onAuthenticationFailure(BadPasswordLoginEvent $event): void
    {
        $this->service->addAttempt($event->getUser());
    }
}
