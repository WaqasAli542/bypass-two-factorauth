<?php

declare(strict_types=1);

namespace WMZ\Bypass2FA\Helper;

use Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    /**
     * Bypass 2FA config path
     */
    const XML_PATH_2FA_BYPASS = 'bypass2fa/admin_users/multi_users_by_pass_2fa';

    /**
     * @return mixed
     */
    public function get2FAByPassUsers()
    {
        return $this->scopeConfig->getValue(self::XML_PATH_2FA_BYPASS);
    }
}
