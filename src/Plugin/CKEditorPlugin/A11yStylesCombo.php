<?php

/**
 * @file
 * Definition of \Drupal\a11yfirst\Plugin\CKEditorPlugin\A11yStylesCombo.
 */

namespace Drupal\a11yfirst\Plugin\CKEditorPlugin;

use Drupal\ckeditor\CKEditorPluginBase;
use Drupal\ckeditor\CKEditorPluginInterface;
use Drupal\ckeditor\CKEditorPluginButtonsInterface;
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
class A11yStylesCombo extends CKEditorPluginBase implements CKEditorPluginInterface, CKEditorPluginButtonsInterface {

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
   */
  function getConfig(Editor $editor) {
    return [];
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
}
