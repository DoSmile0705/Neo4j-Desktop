<?php

namespace MiscBundle\Entity\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use forestlib\Doctrine\ORM\LimitableNativeQuery;
use MiscBundle\Util\BatchLogger;
use MiscBundle\Util\DbCommonUtil;

/**
 * ForestMailtemplatesRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ForestMailtemplatesRepository extends BaseRepository
{
  const ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING = '（空白）';

  const TYPE_DEFAULT = 'a';
  const TYPE_YAHOO = 'b';

  /**
   * メールテンプレート 一覧全件取得 (title, bodyは取得しない)
   */
  public function getAllMailTemplateList($type)
  {
    $sql = <<<EOD
      SELECT
          m.id
        , m.choices1
        , m.choices2
        , m.choices3
        , m.choices4
        , m.choices5
        , m.choices6
        , m.choices7
        , m.choices8
        , m.choices9
        , m.active
        , m.created
        , m.updated
      FROM forest_mailtemplates m
      WHERE m.type = :type
EOD;
    $db = $this->getConnection('main');
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    return $result;
  }


  // 未使用？ 様子を見て削除
//  /**
//   * メールテンプレート 一覧取得
//   * @param array $conditions
//   * @param array $orders
//   * @param int $page
//   * @param int $limit
//   * @return \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination
//   */
//  public function findMailTemplateList($conditions = [], $orders = [], $page = 1, $limit = 100)
//  {
//    /** @var BatchLogger $logger */
//    $logger = $this->getContainer()->get('misc.util.batch_logger');
//
//    /** @var EntityManager $em */
//    $em = $this->getEntityManager();
//
//    /** @var DbCommonUtil $commonUtil */
//    $commonUtil = $this->getContainer()->get('misc.util.db_common');
//
//    $params = [];
//
////    $sqlIncludeRecentReminder = '';
////    if (isset($conditions['include_recent_reminder']) && $conditions['include_recent_reminder']) {
////      $sqlIncludeRecentReminder = ' OR o.sun_payment_reminder = CURRENT_DATE ';
////    }
//
//    // level3
//    $sqlChoices3 = '';
//    if (isset($conditions['choices3']) && strlen($conditions['choices3'])) {
//      $sqlChoices3 = ' AND m.choices3 = :choices3';
//      $str = $conditions['choices3'];
//      $params[':choices3'] = $str == self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING ? '' : $str;
//    }
//
//    // level4
//    $sqlChoices4 = '';
//    if (isset($conditions['choices4']) && strlen($conditions['choices4'])) {
//      $sqlChoices4 = ' AND m.choices4 = :choices4';
//      $str = $conditions['choices4'];
//      $params[':choices4'] = $str == self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING ? '' : $str;
//    }
//
//    $sqlSelect = <<<EOD
//      SELECT
//          m.id
//        , m.choices1
//        , m.choices2
//        , m.choices3
//        , m.choices4
//        , m.choices5
//        , m.choices6
//        , m.choices7
//        , m.choices8
//        , m.title
//        , m.body
//        , m.active
//        , m.created
//        , m.updated
//EOD;
//    $sqlBody = <<<EOD
//      FROM forest_mailtemplates m
//      WHERE 1
//        {$sqlChoices3}
//        {$sqlChoices4}
//EOD;
//
//    $rsm =  new ResultSetMapping();
//    $rsm->addScalarResult('id', 'id', 'integer');
//    $rsm->addScalarResult('choices1', 'choices1', 'string');
//    $rsm->addScalarResult('choices2', 'choices2', 'string');
//    $rsm->addScalarResult('choices3', 'choices3', 'string');
//    $rsm->addScalarResult('choices4', 'choices4', 'string');
//    $rsm->addScalarResult('choices5', 'choices5', 'string');
//    $rsm->addScalarResult('choices6', 'choices6', 'string');
//    $rsm->addScalarResult('choices7', 'choices7', 'string');
//    $rsm->addScalarResult('choices8', 'choices8', 'string');
//    $rsm->addScalarResult('title', 'title', 'string');
//    $rsm->addScalarResult('body', 'body', 'string');
//    $rsm->addScalarResult('active', 'active', 'integer');
//    $rsm->addScalarResult('created', 'created', 'string');
//    $rsm->addScalarResult('updated', 'updated', 'string');
//
//    $query = LimitableNativeQuery::createQuery($em, $rsm, $sqlSelect, $sqlBody);
//    foreach($params as $k => $v) {
//      $query->setParameter($k, $v);
//    }
//
//    $resultOrders = [];
//    $defaultOrders = [
//        'choices1'  => 'ASC'
//      , 'choices2'  => 'ASC'
//      , 'choices3'  => 'ASC'
//      , 'choices4'  => 'ASC'
//      , 'choices5'  => 'ASC'
//      , 'choices6'  => 'ASC'
//      , 'choices7'  => 'ASC'
//      , 'choices8'  => 'ASC'
//    ];
//
//    if ($orders) {
//      foreach($orders as $k => $v) {
//        switch($k) {
//          case 'daihyo_syohin_code':
////            $k = 'o.' . $k;
//            break;
//        }
//
//        $resultOrders[$k] = $v;
//        if (isset($defaultOrders[$k])) {
//          unset($defaultOrders[$k]);
//        }
//      }
//    }
//    $query->setOrders(array_merge($resultOrders, $defaultOrders));
//
//    /** @var \Knp\Component\Pager\Paginator $paginator */
//    $paginator  = $this->getContainer()->get('knp_paginator');
//    /** @var \Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination $pagination */
//    $pagination = $paginator->paginate(
//        $query /* query NOT result */
//      , $page
//      , $limit
//    );
//
//    return $pagination;
//  }

  /**
   * メールテンプレート 絞り込み用choices配列取得
   * @param string $type
   * @return array
   * @throws \Doctrine\DBAL\DBALException
   */
  public function getFilterChoiceList($type)
  {
    $dbMain = $this->getConnection('main');

    $choices = [
        'choices3' => []
      , 'choices4' => []
      , 'choices5' => []
      , 'choices6' => []
    ];

    // lv3
    $sql = <<<EOD
      SELECT
          m.choices3
        , CASE WHEN SUM(m.active) = 0 THEN 0 ELSE -1 END AS active
      FROM forest_mailtemplates m
      WHERE m.type = :type
      GROUP BY m.choices3
      ORDER BY m.choices3
      ;
EOD;
    $stmt = $dbMain->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $choice) {
      // 絞り込みのため、空白文字は「（空白）」に置き換える。
      $choices3 = strlen($choice['choices3']) ? $choice['choices3'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices['choices3'][] = [
          'name' => $choices3
        , 'active' => $choice['active']
      ];
    }

    // lv4
    $sql = <<<EOD
      SELECT
          m.choices3
        , m.choices4
        , CASE WHEN SUM(m.active) = 0 THEN 0 ELSE -1 END AS active
      FROM forest_mailtemplates m
      WHERE m.type = :type
      GROUP BY m.choices3
             , m.choices4
      ORDER BY m.choices3
             , m.choices4
      ;
EOD;
    $stmt = $dbMain->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $choice) {
      // 絞り込みのため、空白文字は「（空白）」に置き換える。
      $choices3 = strlen($choice['choices3']) ? $choice['choices3'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices4 = strlen($choice['choices4']) ? $choice['choices4'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;

      if (!isset($choices['choices4'][$choices3])) {
        $choices['choices4'][$choices3] = [];
      }
      $choices['choices4'][$choices3][] = [
          'name' => $choices4
        , 'active' => $choice['active']
      ];
    }

    // lv5
    $sql = <<<EOD
      SELECT
          m.choices3
        , m.choices4
        , m.choices5
        , CASE WHEN SUM(m.active) = 0 THEN 0 ELSE -1 END AS active
      FROM forest_mailtemplates m
      WHERE m.type = :type
      GROUP BY m.choices3
             , m.choices4
             , m.choices5
      ORDER BY m.choices3
             , m.choices4
             , m.choices5
      ;
EOD;

    $stmt = $dbMain->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $choice) {

      // 絞り込みのため、空白文字は「（空白）」に置き換える。
      $choices3 = strlen($choice['choices3']) ? $choice['choices3'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices4 = strlen($choice['choices4']) ? $choice['choices4'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices5 = strlen($choice['choices5']) ? $choice['choices5'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;

      if (!isset($choices['choices5'][$choices3])) {
        $choices['choices5'][$choices3] = [];
      }
      if (!isset($choices['choices5'][$choices3][$choices4])) {
        $choices['choices5'][$choices3][$choices4] = [];
      }
      $choices['choices5'][$choices3][$choices4][] = [
          'name' => $choices5
        , 'active' => $choice['active']
      ];
    }

    // lv6
    $sql = <<<EOD
      SELECT
          m.choices3
        , m.choices4
        , m.choices5
        , m.choices6
        , CASE WHEN SUM(m.active) = 0 THEN 0 ELSE -1 END AS active
      FROM forest_mailtemplates m
      WHERE m.type = :type
      GROUP BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
      ORDER BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
      ;
EOD;

    $stmt = $dbMain->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $choice) {

      // 絞り込みのため、空白文字は「（空白）」に置き換える。
      $choices3 = strlen($choice['choices3']) ? $choice['choices3'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices4 = strlen($choice['choices4']) ? $choice['choices4'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices5 = strlen($choice['choices5']) ? $choice['choices5'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices6 = strlen($choice['choices6']) ? $choice['choices6'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;

      if (!isset($choices['choices6'][$choices3])) {
        $choices['choices6'][$choices3] = [];
      }
      if (!isset($choices['choices6'][$choices3][$choices4])) {
        $choices['choices6'][$choices3][$choices4] = [];
      }
      if (!isset($choices['choices6'][$choices3][$choices4][$choices5])) {
        $choices['choices6'][$choices3][$choices4][$choices5] = [];
      }
      $choices['choices6'][$choices3][$choices4][$choices5][] = [
          'name' => $choices6
        , 'active' => $choice['active']
      ];
    }

    // lv7
    $sql = <<<EOD
      SELECT
          m.choices3
        , m.choices4
        , m.choices5
        , m.choices6
        , m.choices7
        , CASE WHEN SUM(m.active) = 0 THEN 0 ELSE -1 END AS active
      FROM forest_mailtemplates m
      WHERE m.type = :type
      GROUP BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
             , m.choices7
      ORDER BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
             , m.choices7
      ;
EOD;

    $stmt = $dbMain->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $choice) {

      // 絞り込みのため、空白文字は「（空白）」に置き換える。
      $choices3 = strlen($choice['choices3']) ? $choice['choices3'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices4 = strlen($choice['choices4']) ? $choice['choices4'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices5 = strlen($choice['choices5']) ? $choice['choices5'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices6 = strlen($choice['choices6']) ? $choice['choices6'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices7 = strlen($choice['choices7']) ? $choice['choices7'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;

      if (!isset($choices['choices7'][$choices3])) {
        $choices['choices7'][$choices3] = [];
      }
      if (!isset($choices['choices7'][$choices3][$choices4])) {
        $choices['choices7'][$choices3][$choices4] = [];
      }
      if (!isset($choices['choices7'][$choices3][$choices4][$choices5])) {
        $choices['choices7'][$choices3][$choices4][$choices5] = [];
      }
      if (!isset($choices['choices7'][$choices3][$choices4][$choices5][$choices6])) {
        $choices['choices7'][$choices3][$choices4][$choices5][$choices6] = [];
      }
      $choices['choices7'][$choices3][$choices4][$choices5][$choices6][] = [
          'name' => $choices7
        , 'active' => $choice['active']
      ];
    }

    // lv8
    $sql = <<<EOD
      SELECT
          m.choices3
        , m.choices4
        , m.choices5
        , m.choices6
        , m.choices7
        , m.choices8
        , CASE WHEN SUM(m.active) = 0 THEN 0 ELSE -1 END AS active
      FROM forest_mailtemplates m
      WHERE m.type = :type
      GROUP BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
             , m.choices7
             , m.choices8
      ORDER BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
             , m.choices7
             , m.choices8
      ;
EOD;

    $stmt = $dbMain->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $choice) {

      // 絞り込みのため、空白文字は「（空白）」に置き換える。
      $choices3 = strlen($choice['choices3']) ? $choice['choices3'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices4 = strlen($choice['choices4']) ? $choice['choices4'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices5 = strlen($choice['choices5']) ? $choice['choices5'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices6 = strlen($choice['choices6']) ? $choice['choices6'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices7 = strlen($choice['choices7']) ? $choice['choices7'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices8 = strlen($choice['choices8']) ? $choice['choices8'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;

      if (!isset($choices['choices8'][$choices3])) {
        $choices['choices8'][$choices3] = [];
      }
      if (!isset($choices['choices8'][$choices3][$choices4])) {
        $choices['choices8'][$choices3][$choices4] = [];
      }
      if (!isset($choices['choices8'][$choices3][$choices4][$choices5])) {
        $choices['choices8'][$choices3][$choices4][$choices5] = [];
      }
      if (!isset($choices['choices8'][$choices3][$choices4][$choices5][$choices6])) {
        $choices['choices8'][$choices3][$choices4][$choices5][$choices6] = [];
      }
      if (!isset($choices['choices8'][$choices3][$choices4][$choices5][$choices6][$choices7])) {
        $choices['choices8'][$choices3][$choices4][$choices5][$choices6][$choices7] = [];
      }
      $choices['choices8'][$choices3][$choices4][$choices5][$choices6][$choices7][] = [
          'name' => $choices8
        , 'active' => $choice['active']
      ];
    }

    // lv9
    $sql = <<<EOD
      SELECT
          m.choices3
        , m.choices4
        , m.choices5
        , m.choices6
        , m.choices7
        , m.choices8
        , m.choices9
        , CASE WHEN SUM(m.active) = 0 THEN 0 ELSE -1 END AS active
      FROM forest_mailtemplates m
      WHERE m.type = :type
      GROUP BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
             , m.choices7
             , m.choices8
             , m.choices9
      ORDER BY m.choices3
             , m.choices4
             , m.choices5
             , m.choices6
             , m.choices7
             , m.choices8
             , m.choices9
      ;
EOD;

    $stmt = $dbMain->prepare($sql);
    $stmt->bindValue(':type', $type);
    $stmt->execute();
    foreach($stmt->fetchAll(\PDO::FETCH_ASSOC) as $choice) {

      // 絞り込みのため、空白文字は「（空白）」に置き換える。
      $choices3 = strlen($choice['choices3']) ? $choice['choices3'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices4 = strlen($choice['choices4']) ? $choice['choices4'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices5 = strlen($choice['choices5']) ? $choice['choices5'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices6 = strlen($choice['choices6']) ? $choice['choices6'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices7 = strlen($choice['choices7']) ? $choice['choices7'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices8 = strlen($choice['choices8']) ? $choice['choices8'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;
      $choices9 = strlen($choice['choices9']) ? $choice['choices9'] : self::ALTER_STRING_FOR_FILTER_CHOICE_EMPTY_STRING;

      if (!isset($choices['choices9'][$choices3])) {
        $choices['choices9'][$choices3] = [];
      }
      if (!isset($choices['choices9'][$choices3][$choices4])) {
        $choices['choices9'][$choices3][$choices4] = [];
      }
      if (!isset($choices['choices9'][$choices3][$choices4][$choices5])) {
        $choices['choices9'][$choices3][$choices4][$choices5] = [];
      }
      if (!isset($choices['choices9'][$choices3][$choices4][$choices5][$choices6])) {
        $choices['choices9'][$choices3][$choices4][$choices5][$choices6] = [];
      }
      if (!isset($choices['choices9'][$choices3][$choices4][$choices5][$choices6][$choices7])) {
        $choices['choices9'][$choices3][$choices4][$choices5][$choices6][$choices7] = [];
      }
      if (!isset($choices['choices9'][$choices3][$choices4][$choices5][$choices6][$choices7][$choices8])) {
        $choices['choices9'][$choices3][$choices4][$choices5][$choices6][$choices7][$choices8] = [];
      }
      $choices['choices9'][$choices3][$choices4][$choices5][$choices6][$choices7][$choices8][] = [
          'name' => $choices9
        , 'active' => $choice['active']
      ];
    }

    return $choices;
  }


  /**
   * choices1～8 の文字列正規化。保存時処理。
   * （ひとまず、半角スペース除去のみ）
   * @param string $choice
   * @return mixed
   */
  public function fixChoiceText($choice)
  {
    return str_replace(' ', '', $choice);
  }



}