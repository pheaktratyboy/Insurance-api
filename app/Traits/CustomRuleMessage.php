<?php

namespace App\Traits;

trait CustomRuleMessage
{
    public function formatMessage($messages)
    {
        return collect($messages)->values()->map(function ($message) {
            return $message[0];
        })->implode(',');
    }
}
