<?php

namespace Drupal\address_book\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use \Drupal\Core\Entity\EntityInterface;
/**
 * Default ajax list form
 */
class BaseAjaxListForm extends FormBase {
  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   *
   * @var Drupal\Core\Session\AccountProxyInterface;
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public function __construct(AccountProxyInterface $current_user,Connection $database) {
    $this->currentUser = $current_user;
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return '';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = $this->buildFormFilter($form,$form_state);
    $form += $this->buildFormTable($form,$form_state);
    $wrapper = $this->getFormId();
    $form += [
      '#prefix' => '<div id="'.$wrapper.'">',
      '#suffix' => '</div>',
    ];
    $form['page'] = [
      '#type' => 'textfield',
      '#attributes'=>['class'=>['hidden']],
      '#trigger'=> 'changePage',
      '#weight'=> 1000,
      '#ajax' => [
        'callback' => '::submitForm',
        'wrapper' => $wrapper,
        'effect'=>'fade',
        'event' => 'change',
      ],
    ];
    $form['#attached']['library'][] = 'address_book/ajax_list';
    return $form;
  }

  /**
  * Build the filter form
  *
  * @return array
  *  The Build the form filter if need
  */
  public function buildFormFilter(array &$form, FormStateInterface $form_state) {
    return [];
  }

  /**
  * Calculate current offset base on form page value
  *
  * @return interger
  *   The offset for the list
  */
  public function buildPagerOffset(array &$form, FormStateInterface $form_state,$num_per_page) {
    $page = $form_state->getValue('page',0);
    $triggerElement = $form_state->getTriggeringElement();
    if (isset($triggerElement['#trigger'])  && $triggerElement['#trigger'] == 'changePage') {
      \Drupal::request()->query->set('page',$page);
    } else {
      \Drupal::request()->query->set('page',0);
    }
    $offset = $num_per_page * (int)$page;
    return $offset;
  }

  /**
  * Gets this list's default operations.
  *
  * @param \Drupal\Core\Entity\EntityInterface $entity
  *   The entity the operations are for.
  *
  * @return array
  *   The array structure is identical to the return value of
  *   self::getOperations().
  */
 protected function getDefaultOperations(EntityInterface $entity) {
   $operations = array();
   if ($entity
     ->access('update') && $entity
     ->hasLinkTemplate('edit-form')) {
     $operations['edit'] = array(
       'title' => $this
         ->t('Edit'),
       'weight' => 10,
       'url' => $entity
         ->urlInfo('edit-form'),
     );
   }
   if ($entity
     ->access('delete') && $entity
     ->hasLinkTemplate('delete-form')) {
     $operations['delete'] = array(
       'title' => $this
         ->t('Delete'),
       'weight' => 100,
       'url' => $entity
         ->urlInfo('delete-form'),
     );
   }
   return $operations;
 }

 /**
  * Build the table form
  *
  * @return array
  *  The form with list table inside
  */
  public function buildFormTable(array &$form, FormStateInterface $form_state) {
    return $form;
  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
  * {@inheritdoc}
  */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->buildFormTable($form,$form_state);
    return $form;
  }
}
