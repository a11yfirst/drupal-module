<?php

/**
 * @file
 * Definition of \Drupal\a11yfirst\Plugin\CKEditorPlugin\A11yFormat.
 */

namespace Drupal\a11yfirst\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the “A11yFormat” plugin.
 *
 * @CKEditorPlugin(
 *   id = "a11yformat",
 *   label = @Translation("A11yFormat")
 * )
 */
class A11yFormat extends CKEditorPluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface, CKEditorPluginConfigurableInterface {

  /**
   * Implements Drupal\ckeditor\CKEditorPluginButtonsInterface::getButtons().
   */
  function getButtons() {
    return [
      'BlockFormat' => [
        'label' => $this->t('Paragraph Format'),
        'image_alternative' => [
          '#type' => 'inline_template',
          '#template' => '<a href="#" role="button" aria-label="{{ a11yformat }}"><span class="ckeditor-button-dropdown">{{ a11yformat }}<span class="ckeditor-button-arrow"></span></span></a>',
          '#context' => [
            'a11yformat' => $this->t('Paragraph Format'),
          ],
        ],
      ],
    ];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getConfig().
   */
  function getConfig(Editor $editor) {
    $settings = $editor->getSettings();
    if (!isset($settings['plugins']['a11yformat'])) {
      return [];
    }
    return $settings['plugins']['a11yformat'];
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
    return drupal_get_path('module', 'a11yfirst') . '/js/plugins/a11yformat/plugin.js';
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getLibraries().
   */
  function getLibraries(Editor $editor) {
    return [];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginConfigurableInterface::settingsForm().
   */
  function settingsForm(array $form, FormStateInterface $form_state, Editor $editor) {
    // Defaults.
    $config = ['format_tags' => 'p;pre;address;div'];
    $settings = $editor->getSettings();
    if (isset($settings['plugins']['a11yformat'])) {
      $config = $settings['plugins']['a11yformat'];
    }

    // TODO: replace this with something more like the style definition: we can construct
    // a list of tags based on which rows have entries

    $form['format_tags'] = [
      '#title' => $this->t('Paragraph Format tags'),
      '#type' => 'textfield',
      '#default_value' => $config['format_tags'],
      '#description' => $this->t('A list of semicolon-separated style names (by default: tags) representing the style definition for each entry to be displayed in the Paragraph Format drop-down list in the toolbar. Currently only <code>p</code>, <code>pre</code>, <code>address</code>, and <code>div</code> are allowed.'),
      // TODO: validation
    ];

    return $form;
  }
}
