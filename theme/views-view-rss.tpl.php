<?php
// $Id$
/**
 * @file views-view-rss.tpl.php
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php print "<?xml"; ?> version="1.0" encoding="utf-8" <?php print "?>"; ?>
<rss version="2.0" xml:base="<?php print $link; ?>"<?php print $namespaces; ?>>
  <?php print $channel; ?>
</rss>