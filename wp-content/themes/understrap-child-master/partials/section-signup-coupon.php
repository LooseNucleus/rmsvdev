
  <?php
  $email = get_query_var('mc_email');
  $mc_date = get_query_var('mc_date');
  $date_start = date_create($mc_date);
  date_add($date_start,date_interval_create_from_date_string("30 days"));
  $date = date_format($date_start, "M-d-Y");
  ?>
    <div class="card-stitched">
      <div class="card-body">
        <div class="row">
        <div class="col-sm-4 my-auto">
        <img src="<?php echo site_url( '/wp-content/uploads/' ); ?>2018/08/RMSV10yearlogo-1.svg" alt="Rocky Mountain Sewing & Vacuum" class="coupon-logo">
      </div>
      <div class="col-sm-8 my-auto">
        <h1>Welcome to RMSV!</h1>
        <h3>Save 20% up to $100 on Any Notion, Sewing Accessory or Vacuum Part Purchase</h3>
        <p>Issued to <b><?php if (!empty($email)) {
          echo $email;
        } else {
          echo "newsletter subscriber";
        } ?></b>. Thank you for joining us!

        <p>Valid through <b><?php if (!empty($date)) {
          echo $date;
        } else {
          echo "30 days after signup";
        } ?></b>. Discount from regular price. Cannot be combined with any other offer. Does not apply to prior purchases.
</p>
        </div>

    </div>
    </div>

    </div>
