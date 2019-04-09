
<!-- Tribe Get Events -->
  <?php

ob_end_clean();




      // output headers so that the file is downloaded rather than displayed
    header('Content-type: text/csv');
    header('Content-Disposition: attachment; filename="attendees.csv"');

    // do not cache the file
    header('Pragma: no-cache');
    header('Expires: 0');

    // create a file pointer connected to the output stream
    $file = fopen('php://output', 'w');

    fputcsv($file, array('Event Name','Purchaser Email','Location'));

    $events = tribe_get_events( array(
        'eventDisplay' => 'custom',
        'start_date'   => '2018-07-16 00:01',
        'end_date'     => '2018-08-27 23:59',
        'posts_per_page' => -1
    ) );

    foreach ($events as $event) {
      $venue = tribe_get_venue_id($event->ID);
      $venueName = tribe_get_venue($venue);
      // echo '<pre>';
      // var_dump($event);
      // echo '</pre>';
      $eventName = $event->post_title;
      $eventURL = $event->post_name;

    $attendees  = Tribe__Tickets__Tickets::get_event_attendees( $event->ID );

    if (is_array($attendees)) {
      foreach ($attendees as $attendee) {
        fputcsv($file, array($eventName,$attendee['purchaser_email'],$venueName));
        // echo "\n";
        // echo $eventName;
        // echo ",";
        // echo $eventURL;
        // echo ",";
        // echo $attendee['purchaser_email'];
        // echo ",";
        // echo $venueName;
      }
    }
  }



exit();

?>
