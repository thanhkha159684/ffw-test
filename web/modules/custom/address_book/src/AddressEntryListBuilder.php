<?php

namespace Drupal\address_book;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Routing\RedirectDestinationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* Provides a list controller for the address entry entity type.
*/
class AddressEntryListBuilder extends EntityListBuilder {

  /**
  * The date formatter service.
  *
  * @var \Drupal\Core\Datetime\DateFormatterInterface
  */
  protected $dateFormatter;

  /**
  * The redirect destination service.
  *
  * @var \Drupal\Core\Routing\RedirectDestinationInterface
  */
  protected $redirectDestination;

  /**
  * Constructs a new AddressEntryListBuilder object.
  *
  * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
  *   The entity type definition.
  * @param \Drupal\Core\Entity\EntityStorageInterface $storage
  *   The entity storage class.
  * @param \Drupal\Core\Datetime\DateFormatterInterface $date_formatter
  *   The date formatter service.
  * @param \Drupal\Core\Routing\RedirectDestinationInterface $redirect_destination
  *   The redirect destination service.
  */
  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, DateFormatterInterface $date_formatter, RedirectDestinationInterface $redirect_destination) {
    parent::__construct($entity_type, $storage);
    $this->dateFormatter = $date_formatter;
    $this->redirectDestination = $redirect_destination;
  }

  /**
  * {@inheritdoc}
  */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('entity_type.manager')->getStorage($entity_type->id()),
      $container->get('date.formatter'),
      $container->get('redirect.destination')
    );
  }

  /**
  * {@inheritdoc}
  */
  public function render() {
    $build['#type'] = 'form';
    $build['table'] = parent::render();

    $total = \Drupal::database()
    ->query('SELECT COUNT(*) FROM {address_entry}')
    ->fetchField();

    $build['summary']['#markup'] = $this->t('Total address entries: @total', ['@total' => $total]);
    $build['submit'] = [
      '#type' => 'submit',
      '#value' => static::t('Search'),
      '#ajax' => [
        'callback' => '::submitForm',
        'wrapper' => 'customer-activity-log-table',
        'effect'=>'fade',

      ],
    ];

    $build['page'] = [
      '#type' => 'textfield',
      '#attributes'=>['class'=>['hidden']],
      '#trigger'=> 'changePage',
      '#ajax' => $build['submit']['#ajax'],
    ];
    $build['page']['#ajax']['event'] = 'change';
    return $build;
  }


  /**
  * {@inheritdoc}
  */
  public function buildHeader() {
    $header['id'] = $this->t('ID');
    $header['name'] = $this->t('Name');
    $header['birth_day'] = $this->t('Birth day');
    return $header + parent::buildHeader();
  }

  /**
  * {@inheritdoc}
  */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\address_book\Entity\AddressEntry */
    $row['id'] = $entity->link();
    $row['name'] = $entity->name->value;
    $row['birth_day'] = $entity->birth_day->value;
    return $row + parent::buildRow($entity);
  }



}
