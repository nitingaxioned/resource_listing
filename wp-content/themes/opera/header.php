<!doctype html>
<html lang="en"> 
<head>
  <?php wp_head(); ?>
  <meta charset="utf-8">
</head>
<body <?php body_class(); ?>>
  <!--container start-->
  <div class="container">
    <!--header section start-->
    <header>
      <div class="wrapper">
        <?php
          if( function_exists('the_custom_logo')) {
            the_custom_logo();
          }
        ?>
        <nav>
          <?php wp_nav_menu(array(
            'theme_location' => 'primary-menu',
            'menu_class' => 'nav-list'
            ))?>
        </nav>
      </div>
    </header>
    <!--header section end-->