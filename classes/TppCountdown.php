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
 * to license@tppconseil.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize TPP Conseil EURL for your
 * needs please refer to http://www.tppconseil.com for more information.
 *
 *  @author    TPP Conseil EURL <contact@tppconseil.com>
 *  @copyright 2018-2020 TPP Conseil EURL
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of TPP Conseil EURL
 */

class TppCountdown extends ObjectModel
{
    /** @var int $id_textmarquee - the ID of TppCountdown */
    public $id_tpp_countdown;

    /** @var String $text - HTML format of TppCountdown values */
    public $text;

    /** @var Date $issuingDate - HTML format of TppCountdown values*/
    public $issuingDate;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'tpp_countdown',
        'primary' => 'id_tpp_countdown',
        'multilang' => true,
        'multishop' => true,
        'multilang_shop' => true,
        'fields' => array(
            'id_tpp_countdown' => array('type' => self::TYPE_NOTHING, 'validate' => 'isUnsignedId'),
            'text' => array('type' => self::TYPE_HTML, 'lang' => true, 'validate' => 'isCleanHtml', 'required' => true),
            'issuingDate' => array('type' => self::TYPE_DATE, 'required' => true),
        )
    );

    public function __construct($id_tpp_countdown = null, $id_lang = null, $id_shop = null)
    {
        Shop::addTableAssociation('tpp_countdown', array('type' => 'shop'));
        parent::__construct($id_tpp_countdown, $id_lang, $id_shop);
    }

    /**
     * Return the TppCountdown ID By shop ID
     * @param int $shopId
     * @return bool|int
     */
    public static function getTppCountdownIdByShop($shopId)
    {
        $sql = 'SELECT i.`id_tpp_countdown` FROM `' . _DB_PREFIX_ . 'tpp_countdown` i
        LEFT JOIN `' . _DB_PREFIX_ . 'tpp_countdown_shop` ish ON ish.`id_tpp_countdown` = i.`id_tpp_countdown`
        WHERE ish.`id_shop` = ' . (int) $shopId;

        if ($result = Db::getInstance()->executeS($sql)) {
            return (int) reset($result)['id_tpp_countdown'];
        }

        return $sql;
    }
}
