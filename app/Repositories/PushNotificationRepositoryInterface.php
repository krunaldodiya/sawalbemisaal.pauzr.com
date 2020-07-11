<?php

namespace App\Repositories;

interface PushNotificationRepositoryInterface
{
    public function subscribeToTopic($topic, $user_id);
    public function updateTopicSubscriptions($topic, $tokens);
    public function notify($topic, $data);
}
