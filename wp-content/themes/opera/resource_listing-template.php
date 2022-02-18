<?php 
/**
* Template Name: Resource Listing
*/
get_header();
$title = get_field('filter_title');
$disc = get_field('discription');
?>
<!--main section start-->
<main>
    <div class="wrapper">
      <?php if($title) {?>
        <h2><?php echo $title; ?></h2>
      <?php } ?>
      <?php if($disc) {?>
        <p><?php echo $disc; ?></p>
      <?php } ?>
      <?php
        $cat_iam = get_categories(array('taxonomy' => 'resource-cat-iam','hide_empty' => false,));
        $cat_looking = get_categories(array('taxonomy' => 'resource-cat-looking','hide_empty' => false,));
      ?>
      <select name="cat_iam" class='cat_iam'>
        <option value="">I AM A</option>
        <?php
        if ($cat_iam) {
          foreach($cat_iam as $val){
          ?>
            <option value="<?php echo $val->term_id; ?>"><?php echo $val->name; ?></option>
          <?php
          }
        } 
        ?>
      </select>
      <select name="cat_looking" class='cat_looking'>
        <option value="">I AM LOOKING FOR</option>
        <?php
        if ($cat_looking) {
          foreach($cat_looking as $val){
          ?>
            <option value="<?php echo $val->term_id; ?>"><?php echo $val->name; ?></option>
          <?php
          }
        } 
        ?>
      </select>
      <botton class="find btn">Explore</botton>
      <div class="filter-box">
        <ul class="lists list-resource">
          <li>
            <p>Loading ..</p>
          </li>
        </ul>
        <button class='btn show_more'>Load More</button>
        <button class='btn show_less'>Show Less</button>
      </div>
    </div>
</main>
<!--main section end-->
<?php
get_footer(); 