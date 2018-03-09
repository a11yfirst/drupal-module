<?php

/**
 * @file
 * Definition of \Drupal\a11yfirst\Plugin\CKEditorPlugin\A11yLink.
 */

namespace Drupal\a11yfirst\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
use Drupal\ckeditor\CKEditorPluginConfigurableInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\editor\Entity\Editor;

/**
 * Defines the “A11yLink” plugin.
 *
 * @CKEditorPlugin(
 *   id = "a11ylink",
 *   label = @Translation("A11yLink")
 * )
 */
class A11yLink extends CKEditorPluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface, CKEditorPluginConfigurableInterface {

  /**
   * Implements Drupal\ckeditor\CKEditorPluginButtonsInterface::getButtons().
   */
  function getButtons() {
    $path = drupal_get_path('module', 'a11yfirst') . '/js/plugins/a11ylink';
    return [
      'Link' => [
        'label' => $this->t('A11yLink'),
        'image' => $path . '/icons/link.png',
      ],
      'Unlink' => [
        'label' => $this->t('A11yUnlink'),
        'image' => $path . '/icons/unlink.png',
      ],
    ];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getConfig().
   */
  function getConfig(Editor $editor) {
    $settings = $editor->getSettings();
    if (!isset($settings['plugins']['a11ylink'])) {
      return [];
    }
    return $settings['plugins']['a11ylink'];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getDependencies().
   */
  function getDependencies(Editor $editor) {
    return ['fakeobjects'];
  }

  /**
   * Implements Drupal\ckeditor\CKEditorPluginInterface::getFile().
   */
  function getFile() {
    return drupal_get_path('module', 'a11yfirst') . '/js/plugins/a11ylink/plugin.js';
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
      'linkShowAdvancedTab' => TRUE,
      // 'linkShowTargetTab' => TRUE,
      // 'linkJavaScriptLinksAllowed' => FALSE,
    ];
    $settings = $editor->getSettings();
    if (isset($settings['plugins']['a11ylink'])) {
      $config = $settings['plugins']['a11ylink'];
    }

    $form['linkShowAdvancedTab'] = [
      '#title' => $this->t('Show Advanced tab in link dialog'),
      '#type' => 'checkbox',
      '#default_value' => $config['linkShowAdvancedTab'],
      '#description' => $this->t('Allows editors to specify a language code and direction for the link'),
      // TODO: validation
    ];

    // $form['linkShowTargetTab'] = [
    //   '#title' => $this->t('Show Target tab in link dialog'),
    //   '#type' => 'checkbox',
    //   '#default_value' => $config['linkShowAdvancedTab'],
    //   '#description' => $this->t('Allows editors to specify a target for the link'),
    //   // TODO: validation
    // ];

    // $form['linkJavaScriptLinksAllowed'] = [
    //   '#title' => $this->t('Allow links to JavaScript functions'),
    //   '#type' => 'checkbox',
    //   '#default_value' => $config['linkJavaScriptLinksAllowed'],
    //   '#description' => $this->t("Allows editors to make links to JavaScript functions, such as <code>javascript:alert('Hello world!')</code>"),
    //   // TODO: validation
    // ];

    return $form;
  }
}
