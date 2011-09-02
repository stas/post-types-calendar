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
            <?php _e( 'No content types are available yet.','cptc' )?>
        </em>
    <?php endif; ?>
</p>

<p>
    <label for="<?php echo $count_id; ?>">
        <?php _e( 'Items listed below calendar', 'cptc' ); ?>:
    </label> 
    <?php if( !empty( $type ) ): ?>
        <select class="widefat" id="<?php echo $count_id; ?>" name="<?php echo $count_name; ?>">
            <?php while( $max_items >= 0 ): ?>
                <option value="<?php echo $max_items; ?>" <?php selected( $max_items, $count ); ?> >
                    <?php echo $max_items; ?>
                </option>
                <?php $max_items--; ?>
            <?php endwhile; ?>
        </select>
    <?php else: ?>
        <br/>
        <em>
            <?php _e( 'No content type was selected yet.','cptc' )?>
        </em>
    <?php endif; ?>
</p>

<p>
    <label for="<?php echo $prefix_id; ?>">
        <?php _e( 'Prefix items with', 'cptc' ); ?>:
    </label> 
    <input class="widefat" id="<?php echo $prefix_id; ?>" name="<?php echo $prefix_name; ?>" type="text" value="<?php echo $prefix; ?>" />
    <br/>
    <em>
        <?php _e( 'The above content will be prefixed before every listed item.','cptc' )?>
        <?php _e( 'Output date using <code>date()</code> formatting. Eg.: <code>j F &mdash;</code>','cptc' )?>
    </em>
</p>

<p>
    <label for="<?php echo $tax_id; ?>">
        <?php _e( 'Content taxonomy', 'cptc' ); ?>:
    </label>
    <?php if( !empty( $taxs ) ): ?>
        <select class="widefat" id="<?php echo $tax_id; ?>" name="<?php echo $tax_name; ?>">
            <option value="" <?php selected( '', $tax ); ?> ><?php _e( '(none)', 'cptc' ) ?></option>
            <?php foreach( $taxs as $tx ): ?>
                <option value="<?php echo $tx->name; ?>" <?php selected( $tx->name, $tax ); ?> >
                    <?php echo $tx->label; ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>
        <br/>
        <em>
            <?php _e( 'No taxonomy available or post type was not selected.','cptc' ); ?>
        </em>
    <?php endif; ?>
</p>

<p>
    <label for="<?php echo $term_id; ?>">
        <?php _e( 'Taxonomy Term', 'cptc' ); ?>:
    </label>
    <?php if( !empty( $terms ) ): ?>
        <select class="widefat" id="<?php echo $term_id; ?>" name="<?php echo $term_name; ?>">
            <option value="" <?php selected( '', $term ); ?> ><?php _e( '(none)', 'cptc' ) ?></option>
            <?php foreach( $terms as $tm ): ?>
                <option value="<?php echo $tm->slug; ?>" <?php selected( $tm->slug, $term ); ?> >
                    <?php echo $tm->name; ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php else: ?>
        <br/>
        <em>
            <?php _e( 'No taxonomy term available or taxonomy was not selected.','cptc' ); ?>
        </em>
    <?php endif; ?>
</p>