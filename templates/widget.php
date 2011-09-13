<?php echo $before_widget; ?>

<?php if( !empty( $title ) ) : ?>
    <?php echo $before_title . $title . $after_title; ?>
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
<?php if( isset( $count ) && $count > 0 ) : ?>
    <ul>
    <?php $cal_items = get_posts( array( $tax => $term, 'post_type' => $type, 'numberposts' => $count ) ); ?>
    <?php foreach( $cal_items as $ci ): ?>
        <li>
            <?php $ci = apply_filters( 'cptc-calendar-list-item', $ci ); ?>
            <?php if ( isset( $prefix ) ) : ?>
                <?php echo mysql2date( $prefix, $ci->post_date); ?>
            <?php endif; ?>
            <a href="<?php echo get_post_permalink( $ci->ID ); ?>"><?php echo get_the_title( $ci->ID ); ?></a>
        </li>
    <?php endforeach; ?>
    <ul>
<?php endif; ?>
</div>

<?php echo $after_widget; ?>

<?php do_action( 'cptc-calendar-widget', compact( $type, $tax, $term ) ); ?>
