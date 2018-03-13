<?php
/*
 * compiles etc/events.xml, etc/frontend/events.xml
 */
 $events = $configs['processors']['events']['subscriptions'] ?>
?>

<?php foreach($events as $event): ?>
	<event name="<?php echo $event ?>">
  	<observer name="@@vendor@@_@@module@@_observer" instance="@@vendor@@\@@module@@\Observer\Observer" />
</event>
<?php endforeach; ?>
