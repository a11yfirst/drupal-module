<?php

/**
 * @file
 * Definition of \Drupal\a11yfirst\Plugin\CKEditorPlugin\A11yStylesCombo.
 */

namespace Drupal\a11yfirst\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the A11yStylesCombo plugin.
 *
 * @CKEditorPlugin(
 *   id = "a11ystylescombo",
 *   label = @Translation("A11yStylesCombo")
 * )
 */
class A11yStylesCombo extends CKEditorPluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface, CKEditorPluginConfigurableInterface {

  /**
   * Implements Drupal\ckeditor\CKEditorPluginButtonsInterface::getButtons().
   */
  function getButtons() {
    return [
      'InlineStyle' => [
        'label' => $this->t('Character Style'),
        'image_alternative' => [
          '#type' => 'inline_template',
          '#template' => '<a href="#" role="button" aria-label="{{ a11ystylescombo }}"><span class="ckeditor-button-dropdown">{{ a11ystylescombo }}<span class="ckeditor-button-arrow"></span></span></a>',
          '#context' => [
            'a11ystylescombo' => $this->t('Character Style'),
          ],
        ],
      ],
    ];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getConfig().
   *
   * Copied from Drupal\ckeditor\Plugin\CKEditorPlugin\StylesCombo.
   */
  function getConfig(Editor $editor) {
    $config = [];
    $settings = $editor->getSettings();
    if (!isset($settings['plugins']['a11ystylescombo']['styles'])) {
      return $config;
    }
    $styles = $settings['plugins']['a11ystylescombo']['styles'];
    $config['stylesSet'] = $this->generateStylesSetSetting($styles);
    return $config;
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getDependencies().
   */
  function getDependencies(Editor $editor) {
    return ['a11yfirsthelp'];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getFile().
   */
  function getFile() {
    return drupal_get_path('module', 'a11yfirst') . '/js/plugins/a11ystylescombo/plugin.js';
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getLibraries().
   */
  function getLibraries(Editor $editor) {
    return [];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginConfigurableInterface::settingsForm().
   *
   * Copied from Drupal\ckeditor\Plugin\CKEditorPlugin\StylesCombo.
   */
  function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
    // Defaults.
    $config = ['styles' => ''];
    $settings = $editor->getSettings();
    if (isset($settings['plugins']['a11ystylescombo'])) {
      $config = $settings['plugins']['a11ystylescombo'];
    }

    $form['styles'] = [
      '#title' => $this->t('Styles'),
      '#title_display' => 'invisible',
      '#type' => 'textarea',
      '#default_value' => $config['styles'],
      '#description' => $this->t('A list of classes that will be provided in the “Styles” dropdown. Enter one or more classes on each line in the format: <code>element.classA.classB|Label</code>. Example: <code>h1.title|Title</code>. Advanced example: <code>h1.fancy.title|Fancy title</code>.<br />These styles should be available in your theme’s CSS file.'),
      '#attached' => [
        'library' => ['ckeditor/drupal.ckeditor.stylescombo.admin'],
      ],
      '#element_validate' => [
        [$this, 'validateStylesValue'],
      ],
    ];

    return $form;
  }

  /**
   * #element_validate handler for the "styles" element in settingsForm().
   *
   * Copied from Drupal\ckeditor\Plugin\CKEditorPlugin\StylesCombo.
   */
  public function validateStylesValue(array $element, FormStateInterface $form_state) {
    $styles_setting = $this->generateStylesSetSetting($element['#value']);
    if ($styles_setting === FALSE) {
      $form_state->setError($element, $this->t('The provided list of styles is syntactically incorrect.'));
    }
    else {
      $style_names = array_map(function ($style) {
        return $style['name'];
      }, $styles_setting);
      if (count($style_names) !== count(array_unique($style_names))) {
        $form_state->setError($element, $this->t('Each style must have a unique label.'));
      }
    }
  }

  /**
   * Builds the "stylesSet" configuration part of the CKEditor JS settings.
   *
   * @see getConfig()
   *
   * @param string $styles
   *   The "styles" setting.
   * @return array|false
   *   An array containing the "stylesSet" configuration, or FALSE when the
   *   syntax is invalid.
   *
   * Copied from Drupal\ckeditor\Plugin\CKEditorPlugin\StylesCombo.
   */
  protected function generateStylesSetSetting($styles) {
    $styles_set = [];

    // Early-return when empty.
    $styles = trim($styles);
    if (empty($styles)) {
      return $styles_set;
    }

    $styles = str_replace(["\r\n", "\r"], "\n", $styles);
    foreach (explode("\n", $styles) as $style) {
      $style = trim($style);

      // Ignore empty lines in between non-empty lines.
      if (empty($style)) {
        continue;
      }

      // Validate syntax: element[.class...]|label pattern expected.
      if (!preg_match('@^ *[a-zA-Z0-9]+ *(\\.[a-zA-Z0-9_-]+ *)*\\| *.+ *$@', $style)) {
        return FALSE;
      }

      // Parse.
      list($selector, $label) = explode('|', $style);
      $classes = explode('.', $selector);
      $element = array_shift($classes);

      // Build the data structure CKEditor's stylescombo plugin expects.
      // @see http://docs.cksource.com/CKEditor_3.x/Developers_Guide/Styles
      $configured_style = [
        'name' => trim($label),
        'element' => trim($element),
      ];
      if (!empty($classes)) {
        $configured_style['attributes'] = [
          'class' => implode(' ', array_map('trim', $classes))
        ];
      }
      $styles_set[] = $configured_style;
    }
    return $styles_set;
  }
}
