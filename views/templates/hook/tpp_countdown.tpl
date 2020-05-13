{*
* 2007-2015 PrestaShop
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
* versions in the future. If you wish to customize TPP Conseil EURL for your
* needs please refer to http://www.tppconseil.com for more information.
*
* @author TPP Conseil EURL <contact@tppconseil.com>
    * @copyright 2018-2020 TPP Conseil EURL
    * @license http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
    * International Registered Trademark & Property of TPP Conseil EURL
    *}
    <div id="tpp_countdown">
        <div class="lotery-container">
                <a id="item-loterie" data-depth="0">
                    <span>{$tpp_countdown_infos.issuingDate|date_format:"%Y-%m-%d %H:%M:%S"|escape:"htmlall":"UTF-8"}</span>
                <ul id="countdown-loterie">
                    <li><span id="ct-weeks">00</span>{l s='Week' mod='tpp_countdown'}</li>
                    <li><span id="ct-days">00</span>{l s='Day' mod='tpp_countdown'}</li>
                    <li><span id="ct-hours">00</span>{l s='Hour' mod='tpp_countdown'}</li>
                    <li><span id="ct-minutes">00</span>{l s='Min' mod='tpp_countdown'}</li>
                    <li><span id="ct-seconds">00</span>{l s='Sec' mod='tpp_countdown'}</li>
                </ul>
                
                <script>
                    var textIssuingDate= '{$tpp_countdown_infos.issuingDate|date_format:"%b %d, %Y %H:%M:%S"|escape:"htmlall":"UTF-8"}';
                    var textNoCountdown= "{$tpp_countdown_infos.text|escape:'htmlall':'UTF-8'}";
                </script>
            </a>         
        </div>
    </div>
    </div>