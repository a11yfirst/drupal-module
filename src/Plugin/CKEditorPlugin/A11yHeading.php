<?php

/**
 * @file
 * Definition of \Drupal\a11yfirst\Plugin\CKEditorPlugin\A11yHeading.
 */

namespace Drupal\a11yfirst\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the “A11yHeading” plugin.
 *
 * @CKEditorPlugin(
 *   id = "a11yheading",
 *   label = @Translation("A11yHeading")
 * )
 */
class A11yHeading extends CKEditorPluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface, CKEditorPluginConfigurableInterface {

  /**
   * Implements Drupal\ckeditor\CKEditorPluginButtonsInterface::getButtons().
   */
  function getButtons() {
    return [
      'Heading' => [
        'label' => $this->t('Heading'),
        'image_alternative' => [
          '#type' => 'inline_template',
          '#template' => '<a href="#" role="button" aria-label="{{ a11yheading }}"><span class="ckeditor-button-dropdown">{{ a11yheading }}<span class="ckeditor-button-arrow"></span></span></a>',
          '#context' => [
            'a11yheading' => $this->t('Heading'),
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
    if (!isset($settings['plugins']['a11yheading'])) {
      return [];
    }
    return $settings['plugins']['a11yheading'];
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
    return drupal_get_path('module', 'a11yfirst') . '/js/plugins/a11yheading/plugin.js';
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
    $config = ['headings' => 'h1:h4', 'oneLevel1' => 1];
    $settings = $editor->getSettings();
    if (isset($settings['plugins']['a11yheading'])) {
      $config = $settings['plugins']['a11yheading'];
    }

    $form['headings'] = [
      '#title' => $this->t('Usable heading levels'),
      '#type' => 'textfield',
      '#default_value' => $config['headings'],
      '#description' => $this->t('Enter a range of headings to allow, such as <code>h1:h4</code> for <code>&lt;h1&gt;</code> to <code>&lt;h4&gt;</code>.'),
      // TODO: validation
    ];

    $form['oneLevel1'] = [
      '#title' => $this->t('One H1 per Page'),
      '#type' => 'checkbox',
      '#default_value' => $config['oneLevel1'],
      '#description' => $this->t('If editors are allowed to use <code>&lt;h1&gt;</code>, check this box if they are only allowed to use it once per node.'),
      // TODO: validation
    ];

    return $form;
  }
}
