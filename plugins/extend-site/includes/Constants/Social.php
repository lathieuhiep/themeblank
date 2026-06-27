<?php

namespace ExtendSite\Constants;

defined('ABSPATH') || exit;

final class Social
{
    public static function list(): array
    {
        return [
            'facebook-f' => 'Facebook',
            'twitter' => 'Twitter',
            'linkedin-in' => 'LinkedIn',
            'youtube' => 'YouTube',
            'instagram' => 'Instagram',
            'tiktok' => 'TikTok',
        ];
    }
}