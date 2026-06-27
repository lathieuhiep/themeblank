<?php

namespace ExtendSite\Admin\AdminManager;

defined('ABSPATH') || exit;

final class AdminConstants
{
    public const DOMAIN = 'extend_site_admin';
    public const CAPABILITY_MANAGE = 'manage_options';

    /**
     * Internal parent menu slug (KHÔNG render page)
     */
    public const MENU_PARENT = self::DOMAIN;

    /**
     * Sub page prefix
     */
    public const PAGE_PREFIX = self::DOMAIN . '_';
    public const OPTION_PREFIX = self::DOMAIN . '_option_';
    public const NONCE_PREFIX  = self::DOMAIN . '_nonce_';

    public const PATH_VIEWS =  'includes/Admin/AdminManager/Views/';
}
