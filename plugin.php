<?php
require __DIR__ . '/vendor/autoload.php';

use Xhgui\Profiler\Profiler;
use Xhgui\Profiler\ProfilingFlags;

// Add this block inside some bootstrapper or other "early central point in execution"
try {
    /**
     * The constructor will throw an exception if the environment
     * isn't fit for profiling (extensions missing, other problems)
     */
    $profiler = new Profiler(
        array(
            'save.handler.upload' => array(
                // Use docker's internal networking to connect containers.
                'url' => 'http://host.docker.internal:8142/run/import',
            ),
            'profiler.flags' => array(
                ProfilingFlags::CPU,
                ProfilingFlags::MEMORY,
                // Comment out below to include built in PHP functions in profiling output.
                ProfilingFlags::NO_BUILTINS,
            ),
        )
    );

    // The profiler itself checks whether it should be enabled
    // for request (executes lambda function from config)
    $profiler->start();
} catch (Exception $e){
    // throw away or log error about profiling instantiation failure
    error_log($e->getMessage());
}