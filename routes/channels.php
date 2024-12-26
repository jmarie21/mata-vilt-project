<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('tasks', function ($user) {
    return true;
});
