<?php
/**
* 2007-2020 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2020 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

require_once _PS_MODULE_DIR_ . 'tpp_countdown/classes/TppCountdown.php';

class tpp_CountdownWidget extends Module implements WidgetInterface
{
    protected $config_form = false;

    public function __construct()
    {
        $this->name = 'tpp_countdown';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'TPP Conseil';
        $this->need_instance = 0;

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        Shop::addTableAssociation('tpp_countdown', array('type' => 'shop'));

        $this->displayName = $this->l('TPP Countdown');
        $this->description = $this->l('This module displays a countdown');

        $this->confirmUninstall = $this->l('Are you sure ?');

        $this->ps_versions_compliancy = array('min' => '1.6', 'max' => _PS_VERSION_);
        $this->templateFile = 'module:tpp_countdown/views/templates/hook/tpp_countdown.tpl';
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {

        include(dirname(__FILE__).'/sql/install.php');

        return parent::install() &&
            $this->registerHook('header') &&
            $this->registerHook('backOfficeHeader') &&
            $this->registerHook('displayLeftColumn') &&
            $this->registerHook('displayRightColumn') &&
            $this->registerHook('displayTop') &&
            $this->registerHook('displayTopColumn');
    }

    public function uninstall()
    {

        include(dirname(__FILE__).'/sql/uninstall.php');

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        /**
         * If values have been submitted in the form, process.
         */
        if (((bool)Tools::isSubmit('submittpp_countdownModule')) == true) {
            $this->postProcess();
        }

        $this->context->smarty->assign('module_dir', $this->_path);

        $output = $this->context->smarty->fetch($this->local_path.'views/templates/admin/configure.tpl');

        return $output.$this->renderForm();
    }

    /**
     * Create the form that will be displayed in the configuration of your module.
     */
    protected function renderForm()
    {

        $default_lang = (int) Configuration::get('PS_LANG_DEFAULT');

        $fields_form = array(
            'tinymce' => true,
            'legend' => array(
                'title' => $this->trans('Countdown', array(), 'Modules.tpp_countdown.Admin'),
            ),
            'input' => array(
                'Coutdown_id' => array(
                    'type' => 'hidden',
                    'name' => 'id_tpp_countdown'
                ),
                'countdown_text' => array(
                    'type' => 'text',
                    'label' => $this->trans('No countdown text', array(), 'Modules.tpp_countdown.Admin'),
                    'lang' => true,
                    'name' => 'text',
                    'cols' => 100,
                    'rows' => 1,
                    'class' => 'rte',
                    'autoload_rte' => true,
                ),
                'countdown_date' => array(
                    'type' => 'datetime',
                    'label' => $this->trans('Issuing date', array(), 'Modules.tpp_countdown.Admin'),
                    'name' => 'issuingDate',
                ),
            ),
            'submit' => array(
                'title' => $this->trans('Save', array(), 'Admin.Actions'),
            ),
            'buttons' => array(
                array(
                    'href' => AdminController::$currentIndex . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name . '&token=' . Tools::getAdminTokenLite('AdminModules'),
                    'title' => $this->trans('Back to list', array(), 'Admin.Actions'),
                    'icon' => 'process-icon-back'
                )
            )
        );

        if (Shop::isFeatureActive() && Tools::getValue('id_tpp_countdown') == false) {
            $fields_form['input'][] = array(
                'type' => 'shop',
                'label' => $this->trans('Shop association', array(), 'Admin.Global'),
                'name' => 'checkBoxShopAsso_theme'
            );
        }

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;
        $helper->name_controller = 'tpp_countdown';
        $helper->title = $this->displayName;
        $helper->identifier = $this->identifier;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        foreach (Language::getLanguages(false) as $lang) {
            $helper->languages[] = array(
                'id_lang' => $lang['id_lang'],
                'iso_code' => $lang['iso_code'],
                'name' => $lang['name'],
                'is_default' => ($default_lang == $lang['id_lang'] ? 1 : 0)
            );
        }


        $helper->toolbar_scroll = true;
        $helper->title = $this->displayName;
        $helper->submit_action = 'submittpp_countdownModule';

        $helper->fields_value = $this->getFormValues();


        return $helper->generateForm(array(array('form' => $fields_form)));
    }

    public function getFormValues()
    {
        $fields_value = array();
        $idShop = $this->context->shop->id;
        $id_tpp_countdown = TppCountdown::getTppCountdownIdByShop($idShop);

        Shop::setContext(Shop::CONTEXT_SHOP, $idShop);
        $tpp_countdown = new TppCountdown((int) $id_tpp_countdown);

        $fields_value['text'] = $tpp_countdown->text;
        $fields_value['issuingDate'] = $tpp_countdown->issuingDate;
        $fields_value['id_tpp_countdown'] = $id_tpp_countdown;

        return $fields_value;
    }
    /**
     * Save form data.
     */
    protected function postProcess()
    {

        $shops = Tools::getValue('checkBoxShopAsso_configuration', array($this->context->shop->id));
        $text = array();
        $languages = Language::getLanguages(false);

        foreach ($languages as $lang) {
            $text[$lang['id_lang']] = Tools::getValue('text_' . $lang['id_lang']);
        }

        $saved = true;
        foreach ($shops as $shop) {
            Shop::setContext(Shop::CONTEXT_SHOP, $shop);
            $tpp_countdown = new TppCountdown(Tools::getValue('id_tpp_countdown', 1));
            $tpp_countdown->text = $text;
            $tpp_countdown->issuingDate = Tools::getValue('issuingDate');
            $saved &= $tpp_countdown->save();
        }

        return $saved;
    }

    public function renderWidget($hookName = null, array $configuration = [])
    {
        if (!$this->isCached($this->templateFile, $this->getCacheId('tpp_countdown'))) {
            $this->smarty->assign($this->getWidgetVariables($hookName, $configuration));
        }

        return $this->fetch($this->templateFile, $this->getCacheId('tpp_countdown'));
    }
    public function getWidgetVariables($hookName = null, array $configuration = [])
    {
        $sql = 'SELECT L.`id_tpp_countdown` as `id_tpp_countdown`, L.`id_shop` as `id_shop`, L.`id_lang`as `id_lang`, L.`text` as `text`, C.`issuingDate`as `issuingDate`
        FROM `' . _DB_PREFIX_ . 'tpp_countdown_lang` L, `' . _DB_PREFIX_ . 'tpp_countdown` C 
            WHERE C.`id_tpp_countdown`= L.`id_tpp_countdown` AND L.`id_lang` = ' . (int) $this->context->language->id . ' AND  L.`id_shop` = ' . (int) $this->context->shop->id;

        return array(
            'tpp_countdown_infos' => Db::getInstance()->getRow($sql),
        );
    }

    /**
    * Add the CSS & JavaScript files you want to be loaded in the BO.
    */
    public function hookBackOfficeHeader()
    {
        if (Tools::getValue('module_name') == $this->name) {
            $this->context->controller->addJS($this->_path.'views/js/back.js');
            $this->context->controller->addCSS($this->_path.'views/css/back.css');
        }
    }

    /**
     * Add the CSS & JavaScript files you want to be added on the FO.
     */
    public function hookHeader()
    {
        $this->context->controller->addJS($this->_path.'/views/js/front.js');
        $this->context->controller->addCSS($this->_path.'/views/css/front.css');
    }

    public function hookDisplayLeftColumn()
    {
        return $this->renderWidget();
    }

    public function hookDisplayRightColumn()
    {
        return $this->renderWidget();
    }

    public function hookDisplayTop()
    {
        return $this->renderWidget();
    }

    public function hookDisplayTopColumn()
    {
        return $this->renderWidget();
    }
}
