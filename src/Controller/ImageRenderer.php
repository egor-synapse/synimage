<?php

namespace Drupal\synimage\Controller;

/**
 * @file
 * Contains \Drupal\synapse\Controller\Page.
 */
use Drupal\Core\Controller\ControllerBase;
use Drupal\image\Entity\ImageStyle;
use Drupal\Core\Url;

/**
 * Controller routines for page example routes.
 */
class ImageRenderer extends ControllerBase {

  /**
   * Render.
   */
  public function render($form_state) {
    $synimage = $form_state->getValue('synimage');
    $style = $form_state->getValue('style');
    $fid = $form_state->getValue('file-id');
    $img = '<img ';
    $img .= ' alt=\'' . $form_state->getValue('alt') . '\'';
    $img .= ' src="' . self::styledPath($fid, $style) . '"';
    $img .= ' class="synimage text-xs-' . $form_state->getValue('align') . '"';
    $img .= ' data-align="' . $form_state->getValue('align') . '"';
    // Js: $img .= ' data-caption="' . $form_state->getValue('caption') . '"';!
    $img .= ' data-synimage="' . $form_state->getValue('synimage') . '"';
    $img .= ' data-entity-uuid="' . $form_state->getValue('file-uuid') . '"';
    $img .= ' data-entity-type="file"';
    $img .= ' />';
    if ($form_state->getValue('colorbox')) {
      $image = self::colorbox($form_state, $img);
    }
    else {
      $image = '<span>' . $img . '</span>';
    }
    return $image;
  }

  /**
   * Colorbox.
   */
  public static function colorbox($form_state, $image) {
    $fid = $form_state->getValue('file-id');
    $href = self::styledPath($fid, 'full');
    if ($form_state->getValue('watermark') && FALSE) {
      $href = self::styledPath($fid, 'watermark');
    }

    $img = '<a ';
    $img .= ' href="' . $href . '"';
    $img .= ' class="syncolorbox colorbox"';
    $img .= ' >' . $image . '</a>';
    return $img;
  }

  /**
   * Colorbox.
   */
  public static function styledPath($fid, $image_style = NULL) {
    $file = \Drupal::entityTypeManager()->getStorage('file')->load($fid);
    if (!empty($image_style)) {
      $image_style = ImageStyle::load($image_style);
    }
    $image_uri = $file->getFileUri();
    if (!empty($image_style)) {
      $absolute_path = ImageStyle::load($image_style->getName())->buildUrl($image_uri);
    }
    else {
      // Get absolute path for original image.
      $absolute_path = Url::fromUri(file_create_url($image_uri))->getUri();
    }
    return file_url_transform_relative($absolute_path);
  }

}
