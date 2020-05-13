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
*
* Don't forget to prefix your containers with your own identifier
* to avoid any conflicts with others containers.
*/
  // Set the date we're counting down to
    var countDownDate = new Date(textIssuingDate).getTime();

    // Update the count down every 1 second
    var x = setInterval(function () {

    // Get today's date and time
    var now = new Date().getTime();

    // Find the distance between now and the count down date
    var distance = countDownDate - now;

    // Time calculations for days, hours, minutes and seconds
    var weeks = Math.max(0, Math.floor(distance / (1000 * 60 * 60 * 24 * 7)));
    var days = Math.max(0, Math.floor((distance % (1000 * 60 * 60 * 24 * 7)) / (1000 * 60 * 60 * 24)));
    var hours = Math.max(0, Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)));
    var minutes = Math.max(0, Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)));
    var seconds = Math.max(0, Math.floor((distance % (1000 * 60)) / 1000));
    document.getElementById("ct-weeks").innerHTML = weeks;
    document.getElementById("ct-days").innerHTML = days;
    document.getElementById("ct-hours").innerHTML = String(hours).padStart(2, '0');
    document.getElementById("ct-minutes").innerHTML = String(minutes).padStart(2, '0');
    document.getElementById("ct-seconds").innerHTML = String(seconds).padStart(2, '0');;
    // Display Countdown

    // If the count down is finished, write some text
    if (distance < 0) {
      clearInterval(x);
      document.getElementById("item-loterie").innerHTML = textNoCountdown;
    }
  }, 1000);

    function setIssuingDate($TextDate) {
        var countDownDate = new Date("Dec 31, 2021 12:00:00").getTime();
        document.getElementById("ct-days").innerHTML = 'set';
    }