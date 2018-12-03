<?php

namespace Drupal\first_module;

use Drupal\Core\Entity\EntityTypeManager;

class OldNodesService {

  protected $entityTypeManager;

  public function __construct(EntityTypeManager $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  public function load() {
	  $nid=3;
    $storage = $this->entityTypeManager->getStorage('node');
    /**$query = $storage->getQuery()
      ->condition('created', strtotime('-30 days'), '<');
    $nids = $query->execute();
	return $storage->loadMultiple($nids);
	**/
	$node = $storage->load($nid);
    return $node;
  }

}