<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('queue', function () {
    return true;
});
