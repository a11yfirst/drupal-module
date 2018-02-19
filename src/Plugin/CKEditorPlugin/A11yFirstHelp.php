<?php

/**
 * @file
 * Definition of \Drupal\a11yfirst\Plugin\CKEditorPlugin\A11yFirstHelp.
 */

namespace Drupal\a11yfirst\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the “A11yFirstHelp” plugin.
 *
 * @CKEditorPlugin(
 *   id = "a11yfirsthelp",
 *   label = @Translation("A11yFirstHelp")
 * )
 */
class A11yFirstHelp extends CKEditorPluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface, CKEditorPluginConfigurableInterface {

  /**
   * Implements Drupal\ckeditor\CKEditorPluginButtonsInterface::getButtons().
   */
  function getButtons() {
    return [
      'A11yFirstHelp' => [
        'label' => $this->t('A11yFirst Help'),
        'image_alternative' => [
          '#type' => 'inline_template',
          '#template' => '<a href="#" role="button" aria-label="{{ a11yfirsthelp }}"><span class="ckeditor-button-dropdown">{{ a11yfirsthelp }}<span class="ckeditor-button-arrow"></span></span></a>',
          '#context' => [
            'a11yfirsthelp' => $this->t('A11yFirst Help'),
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
    if (!isset($settings['plugins']['a11yfirsthelp'])) {
      return [];
    }
    // The plugin code wants the settings inside a common container.
    return ['a11yfirst' => $settings['plugins']['a11yfirsthelp']];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getDependencies().
   */
  function getDependencies(Editor $editor) {
    return [];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getFile().
   */
  function getFile() {
    return drupal_get_path('module', 'a11yfirst') . '/js/plugins/a11yfirsthelp/plugin.js';
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
    $config = [
      'organization' => '',
      'a11yPolicyLink' => '',
      'a11yPolicyLabel' => '',
    ];
    $settings = $editor->getSettings();
    if (isset($settings['plugins']['a11yfirsthelp'])) {
      $config = $settings['plugins']['a11yfirsthelp'];
    }

    $form['boilerplate'] = [
      '#title' => 'Importance of Accessibility',
      '#type' => 'item',
      '#description' => 'Optional. Use these fields to customize the text in “About A11yFirst”.',
    ];

    $form['organization'] = [
      '#title' => $this->t('Organization'),
      '#type' => 'textfield',
      '#default_value' => $config['organization'],
      // TODO: validation
    ];

    $form['a11yPolicyLink'] = [
      '#title' => $this->t('Accessibility Policy link'),
      '#type' => 'textfield',
      '#default_value' => $config['a11yPolicyLink'],
      // TODO: validation
    ];

    $form['a11yPolicyLabel'] = [
      '#title' => $this->t('Label for Accessibility Policy link'),
      '#type' => 'textfield',
      '#default_value' => $config['a11yPolicyLabel'],
      // TODO: validation
    ];

    return $form;
  }
}
