<?php
/**
 * Header do tema AGERT
 *
 * @package AGERT
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#main"><?php _e('Pular para o conteúdo', 'agert'); ?></a>

    <header class="site-header" role="banner">
      <div class="site-header__bar">
        <div class="container">
          <div class="brand-wrap">
            <a class="brand" href="<?php echo esc_url(home_url('/')); ?>" aria-label="<?php bloginfo('name'); ?>">
              <?php if (has_custom_logo()) : ?>
                <span class="brand__logo">
                  <?php the_custom_logo(); ?>
                </span>
              <?php else : ?>
                <span class="brand__logo brand__logo--placeholder" aria-hidden="true"></span>
              <?php endif; ?>
              <span class="brand__text">
                <span class="brand__title"><?php bloginfo('name'); ?></span>
                <span class="brand__tagline"><?php bloginfo('description'); ?></span>
              </span>
            </a>
          </div>

          <nav class="main-nav" role="navigation" aria-label="<?php esc_attr_e('Navegação principal','agert'); ?>">
            <?php
              wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'menu',
                'link_before'    => '<span>',
                'link_after'     => '</span>',
                'fallback_cb'    => false,
              ]);
            ?>
          </nav>
        </div>
      </div>
    </header>

    <main id="main" class="site-main">
