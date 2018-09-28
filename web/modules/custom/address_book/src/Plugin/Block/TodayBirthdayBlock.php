<?php

namespace Drupal\address_book\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection;
use Drupal\address_book\Entity\AddressEntry;
/**
 * Provides a 'Today&#039;s Birthday' block.
 *
 * @Block(
 *   id = "address_book_today_birthday",
 *   admin_label = @Translation("Today&#039;s Birthday"),
 *   category = @Translation("Custom")
 * )
 */
class TodayBirthdayBlock extends BlockBase implements ContainerFactoryPluginInterface {
  /**
   * The database service.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;
  /**
   * Constructs a new TodaySBirthdayBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition,Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $headers = [
      ['data' => t('Name'),'sourceField'=>'name'],
      ['data' => t('Day of birth'),'sourceField'=>'birth_day']
    ];
    $query = $this->database->select('address_entry', 'a');

    // Build query base on filter condition
    $today = strtotime(date('Y-m-d', time()));
    $query->condition('birth_day',$today,'>=');
    $query->condition('birth_day',$today+86400,'<');

    $query->fields('a',['id']);
    $query->orderBy('id','esc');
    $entities = $query->range(0,5)->execute()->fetchAll();

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

    $build['title'] =  [
      '#markup'=> '<h3>'.t('Today\'s birth day').'</h3>',
    ];
    $build['table'] =  [
      '#type' => 'table',
      '#header' => $headers,
      '#rows' => $rows,
      '#weight'=> 100,
    ];
    $build['#cache']['max-age'] = 0;
    return $build;
  }

}
