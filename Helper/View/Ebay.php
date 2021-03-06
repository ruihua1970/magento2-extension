<?php

/*
 * @author     M2E Pro Developers Team
 * @copyright  2011-2015 ESS-UA [M2E Pro]
 * @license    Commercial use is forbidden
 */

namespace Ess\M2ePro\Helper\View;

class Ebay extends \Ess\M2ePro\Helper\AbstractHelper
{
    // M2ePro_TRANSLATIONS
    // Sell On eBay

    const NICK  = 'ebay';

    const WIZARD_INSTALLATION_NICK = 'installationEbay';
    const MENU_ROOT_NODE_NICK = 'Ess_M2ePro::ebay';

    const MODE_SIMPLE = 'simple';
    const MODE_ADVANCED = 'advanced';

    protected $activeRecordFactory;
    protected $urlBuilder;
    protected $cacheConfig;
    protected $modelFactory;
    protected $authSession;

    //########################################

    public function __construct(
        \Ess\M2ePro\Model\ActiveRecord\Factory $activeRecordFactory,
        \Magento\Backend\Model\UrlInterface $urlBuilder,
        \Ess\M2ePro\Model\Config\Manager\Cache $cacheConfig,
        \Ess\M2ePro\Model\ActiveRecord\Factory $modelFactory,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Ess\M2ePro\Helper\Factory $helperFactory,
        \Magento\Framework\App\Helper\Context $context
    )
    {
        $this->activeRecordFactory = $activeRecordFactory;
        $this->urlBuilder = $urlBuilder;
        $this->cacheConfig = $cacheConfig;
        $this->modelFactory = $modelFactory;
        $this->authSession = $authSession;
        parent::__construct($helperFactory, $context);
    }

    //########################################

    public function getTitle()
    {
        return $this->getHelper('Module\Translation')->__('eBay Integration (Beta)');
    }

    //########################################

    public function getMenuRootNodeLabel()
    {
        return $this->getTitle();
    }

    //########################################

    public function getWizardInstallationNick()
    {
        return self::WIZARD_INSTALLATION_NICK;
    }

    public function isInstallationWizardFinished()
    {
        return $this->getHelper('Module\Wizard')->isFinished(
            $this->getWizardInstallationNick()
        );
    }

    //########################################

    public function getMode()
    {
        return $this->cacheConfig->getGroupValue('/view/ebay/', 'mode');
    }

    public function setMode($mode)
    {
        $mode = strtolower($mode);
        if (!in_array($mode,[self::MODE_SIMPLE,self::MODE_ADVANCED])) {
            return;
        }
        $this->cacheConfig->setGroupValue('/view/ebay/', 'mode', $mode);
    }

    // ---------------------------------------

    public function isSimpleMode()
    {
        return $this->getMode() == self::MODE_SIMPLE;
    }

    public function isAdvancedMode()
    {
        return $this->getMode() == self::MODE_ADVANCED;
    }

    //########################################

    public function isFeedbacksShouldBeShown($accountId = NULL)
    {
        $accountCollection = $this->modelFactory->getObject('Ebay\Account')->getCollection();
        $accountCollection->addFieldToFilter(
            'feedbacks_receive', \Ess\M2ePro\Model\Ebay\Account::FEEDBACKS_RECEIVE_YES
        );

        $feedbackCollection = $this->activeRecordFactory->getObject('Ebay\Feedback')->getCollection();

        if (!is_null($accountId)) {
            $accountCollection->addFieldToFilter(
                'account_id', $accountId
            );
            $feedbackCollection->addFieldToFilter(
                'account_id', $accountId
            );
        }

        return $accountCollection->getSize() || $feedbackCollection->getSize();
    }

    //########################################
}