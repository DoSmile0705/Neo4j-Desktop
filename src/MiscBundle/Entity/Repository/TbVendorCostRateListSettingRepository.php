<?php

namespace MiscBundle\Entity\Repository;
use MiscBundle\Entity\TbVendorCostRateListSetting;

/**
 * TbVendorCostRateListSettingRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TbVendorCostRateListSettingRepository extends \Doctrine\ORM\EntityRepository
{
  const CURRENT_SETTING_ID = 1;

  /**
   * 設定取得
   */
  public function getCurrentSetting()
  {
    $result = $this->find(self::CURRENT_SETTING_ID);
    if (!$result) {
      $class = $this->getEntityName();

      /** @var TbVendorCostRateListSetting $result */
      $result = new $class();
      $result->setId(self::CURRENT_SETTING_ID)
        ->setMinimumVoucher(5)
        ->setChangeThreshold(3)
        ->setSettledThreshold(3)
        ->setChangeAmountUp(3)
        ->setChangeAmountDown(3)
        ->setChangeAmountAdditional(0)
      ;

      $em = $this->getEntityManager();
      $em->persist($result);
      $em->flush();
    }

    return $result;
  }
}
