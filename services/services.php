<?php

return [
    'models'    => [
        'Testimonial' => function () {
            if (class_exists('\App\Testimonial\Model\Testimonial')) {
                return new \App\Testimonial\Model\Testimonial();
            } else {
                return new \Nails\Testimonial\Model\Testimonial();
            }
        }
    ],
    'resources' => [
        'Testimonial' => function ($mObj): Resource\Testimonial {

            if (class_exists('\App\Testimonial\Resource\Testimonial')) {
                return new \App\Testimonial\Resource\Testimonial($mObj);
            } else {
                return new Resource\Testimonial($mObj);
            }
        },
    ],
];
