<?php
/**
 * @file
 * Contains \Drupal\first_module\Controller\FirstController.
 */
 
namespace Drupal\first_module\Controller;
 
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
 
class FirstController extends ControllerBase {
  public function content() {
    return array(
      '#type' => 'markup',
      '#markup' => t('Hello world'),
    );
  }
  public function content_lod() {
    $node = Node::create(['type' => 'article']);
   $node->set('title', 'ntitle');

	//Body can now be an array with a value and a format.
	//If body field exists.
	$body = [
	'value' => 'nbody value',
	'format' => 'basic_html',
	];
	$node->set('body', $body);
	$node->set('uid', 1);
	$node->status = 1;
	$node->enforceIsNew();
	$node->save();
	drupal_set_message( "Node with nid " . $node->id() . " saved!\n");
	return [  '#type' => 'markup','#markup' => t('Hello world')];
  }
   public function nupdate() {
    $nid = 3;     // example value
$node_storage = \Drupal::entityTypeManager()->getStorage('node');
$node = $node_storage->load($nid);
echo $node->id();  // 123
echo $node->bundle();    // 'article'
//echo $node->getType();    // 'article' - this is the same as bundle() for most entities, but doesn't work for all entity types
echo $node->get('title')->value;           // "Lorem Ipsum..."
echo $node->get('created')->value;         // 1510948801
echo $node->get('body')->value;            // "The full node body, <strong>with HTML</strong>"
//echo $node->get('body')->summary;          // "This is the summary"
// a custom text field
//echo $node->get('field_foo')->value;       // "whatever is in your custom field"
// a file field
//echo $node->get('field_image')->target_id; // 432 (a managed file FID)
echo $node->title->value;            // "Lorem Ipsum..."
echo $node->created->value;          // 1510948801
echo $node->body->value;             // "This is the full node body, <strong>with HTML</strong>"
echo $node->body->summary;           // "This is the summary"
//echo $node->field_foo->value;        // "whatever is in your custom field"
//echo $node->field_image->target_id;  // 432
echo "\n";
$node->set('title', "Moby Dick");
$node->set('body', array(
'summary' => "Book about a whale",
'value' => "Call me Ishmael...",
'format' => 'basic_html',
));
$node->save();
echo $node->title->value; 
echo "\n";
$nid_l=2;
$query = \Drupal::entityQuery('node')->condition('type', 'article')->condition('nid', $nid_l);
$nids = $query->execute();
$nodes = $node_storage->loadMultiple($nids);
 
foreach ($nodes as $n) {
  echo $n->title->value;            // "Lorem Ipsum..."
 
  // do whatever you would do with a node object (set fields, save, etc.)
  $n->set('title', "this is a test");
  $n->save();
}
  }
  
public function nunpublish() {
  $old_nodes = \Drupal::service('first_module.old_nodes')->load();
  print_r($old_nodes);exit;
  foreach ($old_nodes as $node) {
    $node->setPublished(false);
    $node->save();
  }
  
  //create taxonomy term
 /** 
  $new_term = \Drupal\taxonomy\Entity\Term::create([
          'vid' => 'example_vocabulary_machine_name',
          'name' => 'Example term name',
    ]);

$new_term->enforceIsNew();
$new_term->save();
	echo "dd";**/
$service = \Drupal::service('first_module.say_hello');
print_r($service);
print_r($service->sayHello('rakesh'));
 
  }
  /**
 * Helper function to create a new user.
 */
function create_new_user(){
  $user = User::create();

  //Mandatory settings
  $user->setPassword('password');
  $user->enforceIsNew();
  $user->setEmail('karthick@email.com');

  //This username must be unique and accept only a-Z,0-9, - _ @ .
  $user->setUsername('karthick'); 

  //Optional settings
  $language = 'en';
  $user->set("init", 'email');
  $user->set("langcode", $language);
  $user->set("preferred_langcode", $language);
  $user->set("preferred_admin_langcode", $language);
  $user->activate();

  //Save user
  $user->save();
  drupal_set_message("user with uid " . $user->id() . " saved!\n");
}
function upcasting_lod($my_menu){
	echo "contt";
	print_r($my_menu);
	exit;
}	
}