<article id="node-<?php print $node->nid; ?>" class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>

  <?php if ($title_prefix || $title_suffix || $display_submitted || !$page): ?>
  <header>
    <?php print render($title_prefix); ?>
    <?php if (!$page): ?>
      <h2<?php print $title_attributes; ?>><a href="<?php print $node_url; ?>"><?php print $title; ?></a></h2>
    <?php endif; ?>
    <?php print render($title_suffix); ?>

    <?php if ($display_submitted): ?>
      <div class="submitted">
        <?php print $user_picture; ?>
        <span class="glyphicon glyphicon-calendar"></span> <?php print $submitted; ?>
      </div>
    <?php endif; ?>
  </header>
  <?php endif; ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['comments']);
      hide($content['links']);
      hide($content['field_tags']);
      print render($content);
    ?>

    <?php
    $f = true;
    if (isset($_SESSION['quote_list'])) {
      foreach($_SESSION['quote_list'] as $item) {
        if ($item[0] == $node->nid) {
          $f = false;
          break;
        }
      }
    }

    if ($f) {  ?>

    Add to Quote: <input type="checkbox" id="product-<?php print $node->nid;?>" name="product-<?php print $node->nid;?>" value="<?php print $node->nid;?>" class="product-checkbox">
    <br><span class="wrapper-quantity wrapper-quantity-<?php print $node->nid;?>">Quantity: <br>
    <input type="text" id="product_quantity" name="product_quantity"value="1" class="quantity-input"></span>
    
    <input type="hidden" name="product_pid" id="product_pid" value="<?php print $node->nid;?>">

    <input type="button" name="add_to_quote" id="add_to_quote" value="Add to Quote">
  <?php } ?>

  <div style="display:none;">
      <div class="quote-form" id="quote-form2">
        <form id="form-quote-form2" name="form-quote-form">
          <div class="form-item"><span class="label">Full name:</span> <input type="text" name="full_name" id="edit_full_name" required></div>
          <div class="form-item"><span class="label">Email:</span> <input type="email" name="email" id="edit_email" required></div>
          <div class="form-item"><span class="label">Comment: </span><textarea id="comment"></textarea></div>
          <div class="form-item"><span class="label">&nbsp;</span><input type="submit" value="Submit" id="submit-quote-button">
        </form>
      </div>
    </div>
  
  </div>
  <a id="submit_quote" class="button" value="Submit Quote" href="#quote-form2">Submit Quote</a>
  <a id="my_quote" class="button" value="View Quote" href="/my-quote">View Quote</a>

  <?php
    $nid = ($node->nid - 1);
    $countnode = db_query("SELECT COUNT(nid) AS count FROM {node} WHERE nid = :nid", array(':nid' => $nid))->fetchField();
    if ($countnode) {
      print l('Previous Product', 'node/'.$nid, array('attributes' => array('class' => 'button')));
    }

    $nid = ($node->nid + 1);
    $countnode = db_query("SELECT COUNT(nid) AS count FROM {node} WHERE nid = :nid", array(':nid' => $nid))->fetchField();
    if ($countnode) {
      print l('Next Product', 'node/'.$nid, array('attributes' => array('class' => 'button')));
    }
  ?>

  <?php if (!empty($content['field_tags']) || !empty($content['links'])): ?>
    <footer>
    <?php print render($content['field_tags']); ?>
    <?php print render($content['links']); ?>
    </footer>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</article>
