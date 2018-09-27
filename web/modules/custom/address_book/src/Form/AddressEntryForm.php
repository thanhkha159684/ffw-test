<?php

namespace Drupal\address_book\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the address entry entity edit forms.
 */
class AddressEntryForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $form['#prefix'] = '<div id="address-entry-form-wrapper">';
    $form['#suffix'] = '</div>';

    // Disable time widget
    $form['birth_day']['widget'][0]['value']['#date_time_element'] = 'none';
    $form['actions']['submit']['#ajax'] = [
      'callback' => '::ajaxCallcack',
      'wrapper' => 'address-entry-form-wrapper',
    ];
    return $form;
  }
  /**
   * Define ajax callback for this form
   */
  public function ajaxCallcack(array $form, FormStateInterface $form_state) {
    return $form;
  }
  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      drupal_set_message($this->t('New address entry %label has been created. ', $message_arguments));
      $this->logger('address_book')->notice('Created new address entry %label', $logger_arguments);
    }
    else {
      drupal_set_message($this->t('The address entry %label has been updated.', $message_arguments));
      $this->logger('address_book')->notice('Created new address entry %label.', $logger_arguments);
    }
    
    return $form;
  }

}
