<?php

namespace Drupal\address_book\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\address_book\AddressEntryInterface;

/**
 * Defines the address entry entity class.
 *
 * @ContentEntityType(
 *   id = "address_entry",
 *   label = @Translation("Address entry"),
 *   label_collection = @Translation("Address entries"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "access" = "Drupal\address_book\AddressEntryAccessControlHandler",
 *     "form" = {
 *       "add" = "Drupal\address_book\Form\AddressEntryForm",
 *       "edit" = "Drupal\address_book\Form\AddressEntryForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "address_entry",
 *   admin_permission = "administer address entry",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "id",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/address-entry/add",
 *     "canonical" = "/address-entry/{address_entry}",
 *     "edit-form" = "/admin/address-entry/{address_entry}/edit",
 *     "delete-form" = "/admin/address-entry/{address_entry}/delete",
 *     "collection" = "/admin/address-entry/manage"
 *   },
 * )
 */
class AddressEntry extends ContentEntityBase implements AddressEntryInterface {

  /**
   * {@inheritdoc}
   *
   * When a new address entry entity is created, set the uid entity reference to
   * the current user as the creator of the entity.
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += ['uid' => \Drupal::currentUser()->id()];
  }

  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->get('name')->value;
  }
  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields = parent::baseFieldDefinitions($entity_type);

    // Name
    $fields['name'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Name'))
    ->setRequired(TRUE)
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'hidden',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -6,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // Name
    $fields['email'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Email'))
    ->setRequired(TRUE)
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'hidden',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -6,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);


    // Phone
    $fields['phone'] = BaseFieldDefinition::create('string')
    ->setLabel(t('Phone'))
    ->setRequired(TRUE)
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'hidden',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'string_textfield',
      'weight' => -6,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    // Birth day
    $fields['birth_day'] = BaseFieldDefinition::create('timestamp')
    ->setLabel(t('Birthday'))
    ->setRequired(TRUE)
    ->setSettings(array(
      'default_value' => '',
      'max_length' => 255,
      'text_processing' => 0,
    ))
    ->setDisplayOptions('view', array(
      'label' => 'hidden',
      'type' => 'string',
      'weight' => -5,
    ))
    ->setDisplayOptions('form', array(
      'type' => 'datetime_default',
      'weight' => -6,
    ))
    ->setDisplayConfigurable('form', TRUE)
    ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
