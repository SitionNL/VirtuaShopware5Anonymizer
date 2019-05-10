<?php
/**
 * Created by PhpStorm.
 * User: virtua
 * Date: 2019-05-09
 * Time: 11:41
 */

namespace ShopwareAnonymizer\Anonymizer;

use ShopwareAnonymizer\Anonymizer\Bridge\Entity\Address\UserAddresses;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\Address\UserBillingaddress;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\Address\UserShippingaddress;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\CampaignsLogs;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\CampaignsMailaddresses;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\CampaignsMaildata;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\CmsSupport;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\Comment\ArticlesVote;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\Comment\BlogComments;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\CoreAuth;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\CoreLog;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\CorePaymentData;
use ShopwareAnonymizer\Anonymizer\Bridge\Entity\User;

/**
 * Class Seeder
 * Containing index of entites, grouped by seed, which is used for anonymiziation
 */
class Seeder
{
    /** todo get tesing data and check wchich tables needs to be seed by same seed
     * AbstractBridgeEntity[] by seed used to seed anonymizer
     * @var array[]
     */
    protected $entitiesBySeed = array(
        'userSeed' => array(
            User::class,
            UserAddresses::class,
            UserBillingAddress::class,
            UserShippingAddress::class,
            ArticlesVote::class,
            BlogComments::class,

            CampaignsLogs::class,
            CampaignsMailaddresses::class,
            CampaignsMaildata::class,
        ),
        'cms' => array(
            CmsSupport::class,
        ),
        'coreAuth' => array(
            CoreAuth::class,
            CoreLog::class,
            CorePaymentData::class,
        ),
    );

    /**
     * @return array[]
     */
    public function getEntitiesBySeed()
    {
        return $this->entitiesBySeed;
    }

    /**
     * @param array $entitiesBySeed
     */
    public function setEntitiesBySeed(array $entitiesBySeed)
    {
        $this->entitiesBySeed = $entitiesBySeed;
    }
}
