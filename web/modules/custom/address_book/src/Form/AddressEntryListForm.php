<?php

namespace Drupal\address_book\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\address_book\Entity\AddressEntry;
use Drupal\Core\Url;

/**
 * Define manage form page
 */
class AddressEntryListForm extends BaseAjaxListForm {

  /**
     * {@inheritdoc}
     */
  public function getFormId() {
    return 'address_entry_list_form';
  }
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form,$form_state);
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
      ['data' => t('Day of birth'),'sourceField'=>'birth_day'],
      ['data' => t('Operations'),'sourceField'=>'operations'],
    ];

    // Use form value to pagination- > Support multiple pagination on same page
    $num_per_page = 5;
    $offset = $this->buildPagerOffset($form,$form_state,$num_per_page);

    $query = $this->database->select('address_entry', 'a');
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
          case 'operations':
            $row['operations']['data'] = [
              '#type' => 'operations',
              '#links' => $this->getDefaultOperations($entity),
            ];
            break;
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
