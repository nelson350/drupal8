<?php

namespace Drupal\site_information\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Controller routines for field example routes.
 */
class PageJsonController extends ControllerBase {

  protected $entityType;
  protected $queryFactory;
  protected $configFactory;

  /**
   * Constructor for the class PageJsonController to inject dependency.
   *
   * @param Drupal\Core\Entity\EntityTypeManagerInterface $entityType
   *   The action storage.
   * @param Drupal\Core\Entity\Query\QueryFactory $queryFactory
   *   The action plugin manager.
   */
  public function __construct(EntityTypeManagerInterface $entityType, QueryFactory $queryFactory, ConfigFactoryInterface $configFactory) {
    $this->entityType = $entityType;
    $this->queryFactory = $queryFactory;
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('entity.query'),
      $container->get('config.factory')
    );
  }

  /**
   * Provides a render array containing a single node's body field.
   *
   * @return JsonResponse
   *   Return the json response of the input nid for the corresponding type.
   */
  public function PageJson($type, $nid) {
    $query = $this->queryFactory->get('node');
    $query->condition('status', 1);
    $query->condition('nid', $nid);
    $query->condition('type', $type);
    // Retrieve an array of entity ids.
    $nid = $query->execute();

    // If there are no nodes, Show the Access denied message to the user.
    if (count($nid) < 1) {
      return new JsonResponse('{
        "error": {
          "code": 403,
          "message": "Access Denied"
        }
      }', 403, ['Content-Type' => 'application/json']);
    }
    $entity_storage = $this->entityType->getStorage('node');
    $entity = $entity_storage->load(array_values($nid)[0]);
    return new JsonResponse($entity->toArray(), 200, ['Content-Type' => 'application/json']);
  }

  /**
   * Checks access for a specific request.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Run access checks for this account.
   */
  public function access(AccountInterface $account) {
    // Check whether site api key exist.
    return AccessResult::allowedIf($account->hasPermission('access content') && $this->checksiteapikey());
  }

  /**
   * Custom Access callback to check Site API key.
   */
  public function checksiteapikey() {
    $site_config = $this->configFactory->get('site_information.site');
    $siteapikey = $site_config->get('siteapikey');
    if ($siteapikey != "No API Key yet" && isset($siteapikey)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }

}
