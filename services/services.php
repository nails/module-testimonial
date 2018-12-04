<?php

return [
    'models' => [
        'Testimonial' => function () {
            if (class_exists('\App\Testimonial\Model\Testimonial')) {
                return new \App\Testimonial\Model\Testimonial();
            } else {
                return new \Nails\Testimonial\Model\Testimonial();
            }
        }
    ]
];
