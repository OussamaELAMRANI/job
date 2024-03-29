<?php

namespace Drupal\heading\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Heading widget for the heading field type.
 *
 * @FieldWidget(
 *   id = "heading",
 *   label = @Translation("Heading"),
 *   field_types = {
 *     "heading"
 *   }
 * )
 */
class HeadingWidget extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a HeadingWidget object.
   *
   * @param string $plugin_id
   *   The plugin_id for the formatter.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Field\FieldDefinitionInterface $field_definition
   *   The definition of the field to which the formatter is associated.
   * @param array $settings
   *   The widget settings.
   * @param array $third_party_settings
   *   Any third party settings.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   The String translation.
   */
  public function __construct(
    $plugin_id,
    $plugin_definition,
    FieldDefinitionInterface $field_definition,
    array $settings,
    array $third_party_settings,
    TranslationInterface $translation
  ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->setStringTranslation($translation);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $stringTranslation = $container->get('string_translation');
    /* @var $stringTranslation TranslationInterface */

    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $stringTranslation
    );
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['#attached']['library'][] = 'heading/widget';
    $element['container'] = [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['heading-widget--container'],
      ],
    ];

    $element['container']['text'] = [
      '#type' => 'textfield',
      '#title' => $this->fieldDefinition->getLabel(),
      '#default_value' => $items[$delta]->text,
    ];

    $element['container']['size'] = $this->formElementSize($items, $delta);

    return $element;
  }

  /**
   * Create the size form element.
   *
   * The heading size select will be hidden if there is only one heading size
   * allowed.
   *
   * @param \Drupal\Core\Field\FieldItemListInterface $items
   *   The field items.
   * @param int $delta
   *   The current field delta.
   *
   * @return array
   *   Form element render array.
   */
  protected function formElementSize(FieldItemListInterface $items, $delta) {
    $size_options = $this->getTypes();
    if (count($size_options) === 1) {
      reset($size_options);
      return [
        '#type' => 'value',
        '#value' => key($size_options),
      ];
    }

    $size_options_keys = array_keys($size_options);
    $size_default = isset($items[$delta]->size)
      ? $items[$delta]->size
      : reset($size_options_keys);

    return [
      '#type' => 'select',
      '#title' => $this->t('Size'),
      '#default_value' => $size_default,
      '#options' => $size_options,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $data) {
      $values[$delta]['text'] = $data['container']['text'];
      $values[$delta]['size'] = $data['container']['size'];
      unset($values[$delta]['container']);
    }

    return $values;
  }

  /**
   * Get the available heading types.
   *
   * @return array
   *   The heading size labels keyed by their size (h1-h6).
   */
  protected function getTypes() {
    $allowed_sizes = array_filter($this->fieldDefinition->getSetting('allowed_sizes'));
    $sizes = [
      'h1' => $this->t('Heading 1')->render(),
      'h2' => $this->t('Heading 2')->render(),
      'h3' => $this->t('Heading 3')->render(),
      'h4' => $this->t('Heading 4')->render(),
      'h5' => $this->t('Heading 5')->render(),
      'h6' => $this->t('Heading 6')->render(),
    ];

    return array_intersect_key($sizes, $allowed_sizes);
  }

}
