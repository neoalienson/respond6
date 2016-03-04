<?php

namespace App\Respond\Database;

/**
 * Models the Site database
 */
class Site{

	/**
   * gets a site for given FriendlyId
   *
   * @param {string} $friendlyId the ID for the user
   * @return {Site}
   */
	public static function GetByFriendlyId($friendlyId){

        $q = "SELECT SiteId, FriendlyId, Domain, Name, LogoUrl, AltLogoUrl, PayPalLogoUrl, IconUrl, IconBg, Theme,
						PrimaryEmail, TimeZone, Language, Direction, Currency,
						ShowCart, ShowSettings, ShowLanguages, ShowLogin, ShowSearch,
						WeightUnit, ShippingCalculation, ShippingRate, ShippingTiers, TaxRate,
						PayPalId, PayPalUseSandbox,
						WelcomeEmail, ReceiptEmail,
						IsSMTP, SMTPHost, SMTPAuth, SMTPUsername, SMTPPassword, SMTPPasswordIV, SMTPSecure,
						FormPublicId, FormPrivateId,
						Status, Plan, Provider, SubscriptionId, CustomerId,
						CanDeploy, UserLimit, FileLimit,
						LastLogin, Version, Created
						FROM Sites WHERE FriendlyId = ?";

        $result = app('db')->select($q, [$friendlyId]);

        if(sizeof($result) == 1){
            return $result[0];
        }
        else{
            return NULL;
        }

	}

	/**
   * gets a site for given SiteId
   *
   * @param {string} $siteId the ID for the user
   * @return {Site}
   */
	public static function GetBySiteId($siteId){

        $q = "SELECT SiteId, FriendlyId, Domain, Name, LogoUrl, AltLogoUrl, PayPalLogoUrl, IconUrl, IconBg, Theme,
						PrimaryEmail, TimeZone, Language, Direction, Currency,
						ShowCart, ShowSettings, ShowLanguages, ShowLogin, ShowSearch,
						WeightUnit, ShippingCalculation, ShippingRate, ShippingTiers, TaxRate,
						PayPalId, PayPalUseSandbox,
						WelcomeEmail, ReceiptEmail,
						IsSMTP, SMTPHost, SMTPAuth, SMTPUsername, SMTPPassword, SMTPPasswordIV, SMTPSecure,
						FormPublicId, FormPrivateId,
						Status, Plan, Provider, SubscriptionId, CustomerId,
						CanDeploy, UserLimit, FileLimit,
						LastLogin, Version, Created,
                        EmbeddedCodeHead, EmbeddedCodeBottom
						FROM Sites WHERE Siteid = ?";


        $result = app('db')->select($q, [$siteId]);

        if(sizeof($result) == 1){
            return $result[0];
        }
        else{
            return NULL;
        }

	}

}