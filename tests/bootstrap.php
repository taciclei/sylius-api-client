<?php

declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

\VCR\VCR::configure()->enableLibraryHooks(['curl', 'soap', 'stream_wrapper'])
    ->setStorage('json')
    ->setCassettePath('tests/Cassettes')
    //->setMode('new_episodes');
    ->setMode('none');

