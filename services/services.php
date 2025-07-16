<?php

use Nails\Testimonial\Model;
use Nails\Testimonial\Resource;

return [
    'models'    => [
        'Testimonial' => function (): Model\Testimonial {
            if (class_exists('\App\Testimonial\Model\Testimonial')) {
                return new \App\Testimonial\Model\Testimonial();
            } else {
                return new Model\Testimonial();
            }
        },
    ],
    'resources' => [
        'Testimonial' => function ($resource, $model): Resource\Testimonial {
            if (class_exists('\App\Testimonial\Resource\Testimonial')) {
                return new \App\Testimonial\Resource\Testimonial($resource, $model);
            } else {
                return new Resource\Testimonial($resource, $model);
            }
        },
    ],
];
