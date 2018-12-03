(function ($, Drupal, drupalSettings) {

Drupal.behaviors.LotusBehavior = {
  attach: function (context, settings) {
    // can access setting from 'drupalSettings';
    var lotusHeight = drupalSettings.lotus.lotusJS.lotus_height;
   // $('lotusElement').css('height', lotusHeight);
  }
};
})(jQuery, Drupal, drupalSettings);