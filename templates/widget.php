<?php if( !empty( $title ) ) : ?>
    <?php echo $title; ?>
<?php endif; ?>

<div id="calendar_wrap">
<?php
    _get_calendar( true,
        array(
            'echo' => true,
            'post_type' => $type,
            'post_status' => 'publish'
        )
    );
?>
</div>