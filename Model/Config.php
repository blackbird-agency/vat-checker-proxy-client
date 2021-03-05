<?php
/*
 * @copyright Open Software License (OSL 3.0)
 */

namespace Blackbird\VatCheckerProxyClient\Model;


use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * Config constructor.
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Return config proxy url
     *
     * @return string
     */
    public function getProxyUrl()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/proxy_url',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return config proxy url
     *
     * @return string
     */
    public function getToken()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/token',
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Return config module enabled
     *
     * @return bool
     */
    public function isModuleEnable()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/enable',
            ScopeInterface::SCOPE_STORE
        ) ? true : false;
    }

    /**
     * @return bool
     */
    public function useOnlyOnFailure()
    {
        return $this->scopeConfig->getValue(
            'blackbird_vat_checker_proxy_client/general/use_only_on_failure',
            ScopeInterface::SCOPE_STORE
        ) ? true : false;
    }

}
