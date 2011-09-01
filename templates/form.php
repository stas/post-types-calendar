<p>
    <label for="<?php echo $title_id; ?>">
        <?php _e( 'Title', 'cptc' ); ?>:
    </label> 
    <input class="widefat" id="<?php echo $title_id; ?>" name="<?php echo $title_name; ?>" type="text" value="<?php echo $title; ?>" />
</p>

<p>
    <label for="<?php echo $type_id; ?>">
        <?php _e( 'Types of content', 'cptc' ); ?>:
    </label> 
    <?php if( !empty( $types ) ): ?>
        <select class="widefat" id="<?php echo $type_id; ?>" name="<?php echo $type_name; ?>">
            <?php foreach( $types as $t ): ?>
                <option value="<?php echo $t->name; ?>" <?php selected( $t->name, $type ); ?> >
                    <?php echo $t->label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>
        <br/>
        <em>
            <?php _e( 'No types are available yet.','cptc' )?>
        </em>
    <?php endif; ?>
    <?php do_action( 'cptc-calendar-widget', $type ); ?>
</p>