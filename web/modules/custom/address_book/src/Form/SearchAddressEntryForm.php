<?php

namespace Drupal\address_book\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\address_book\Entity\AddressEntry;
use Drupal\Core\Url;

/**
 * Define search form page
 */
class SearchAddressEntryForm extends BaseAjaxListForm {

  /**
     * {@inheritdoc}
     */
  public function getFormId() {
    return 'search_address_entry_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $wrapper = $this->getFormId();
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => t('Name'),
      '#prefix' => '<div class="row"><div class="col-md-2">',
      '#suffix' => '</div>',
    ];
    $form['email'] = [
      '#type' => 'textfield',
      '#title' => t('Email'),
      '#prefix' => '<div class="col-md-2">',
      '#suffix' => '</div>',
    ];
    $form['phone'] = [
      '#type' => 'textfield',
      '#title' => t('Phone'),
      '#prefix' => '<div class="col-md-2">',
      '#suffix' => '</div>',
    ];
    $form['search'] = [
      '#type' => 'submit',
      '#value' => t('Search'),
      '#prefix' => '<div class="col-md-2">',
      '#suffix' => '</div></div>',
      '#ajax' => [
        'callback' => '::submitForm',
        'wrapper' => $wrapper,
        'effect'=>'fade'
      ],
    ];
    $form += parent::buildForm($form,$form_state);
    return $form;
  }
  /**
    * Return activity log table
    */
  public function buildFormTable(array &$form, FormStateInterface $form_state) {
    $headers = [
      ['data' => t('Name'),'sourceField'=>'name'],
      ['data' => t('Phone'),'sourceField'=>'phone'],
      ['data' => t('Email'),'sourceField'=>'email'],
      ['data' => t('Day of birth'),'sourceField'=>'birth_day']
    ];

    // Use form value to pagination
    $num_per_page = 5;
    $offset = $this->buildPagerOffset($form,$form_state,$num_per_page);
    $values = $form_state->getValue('name');
    $query = $this->database->select('address_entry', 'a');

    // Build query base on filter condition
    if(!empty($form_state->getValue('name',''))) {
      $query->condition('name', '%'.db_like($form_state->getValue('name','')) . '%', 'LIKE');
    }

    if(!empty($form_state->getValue('phone',''))) {
      $query->condition('phone', '%'.db_like($form_state->getValue('phone','')) . '%', 'LIKE');
    }
    if(!empty($form_state->getValue('email',''))) {
      $query->condition('email', '%'.db_like($form_state->getValue('email','')) . '%', 'LIKE');
    }

    $query->fields('a',['id']);
    $query->orderBy('id','esc');
    $total = $query->countQuery()->execute()->fetchField();


    $entities = $query->range($offset,$num_per_page)->execute()->fetchAll();
    pager_default_initialize($total, $num_per_page);

    $rows = [];
    foreach ($entities as $entity) {
      $row = [];
      $entity = AddressEntry::load($entity->id);
      foreach ($headers as $header) {
        $field = $header['sourceField'];
        switch ($field) {
          case 'birth_day':
            $row[$field] = format_date($entity->{$field}->value,'custom','d/m/Y');
            break;
          default:
            $row[$field] = $entity->{$field}->value;
            break;
        }
      }

      $rows[] = ['data'=>$row];
    }

    $form['table'] =  [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#weight'=> 100,
    ];
    $form['pager']  = [
      '#type' => 'pager',
      '#quantity' => 3,
      '#weight'=> 101,
    ];
    return $form;

  }

}
