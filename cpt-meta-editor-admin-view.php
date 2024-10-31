<div class="cpt-meta-editor container-fluid">
    <h2 class="h2 text-center"><?php _e('Quick Meta Editor', 'cpt-meta-editor'); ?></h2>
    <h4 class="h4 text-center"><?php _e('For Custom Post Types', 'cpt-meta-editor'); ?></h4>
    <div class="cpt-meta-editor-header container form-group text-center mt-3">
        <label for="types-select" class="font-italic"><?php _e('Select a Custom Post Type', 'cpt-meta-editor'); ?></label>
        <select name="types-select" id="types-select" class="form-control form-control-lg m-auto" onchange="javascript:select_type()">
            <option value=""></option>
        </select>
        
        <?php wp_nonce_field('plugin-nonce'); ?>
        <input type="hidden" id="ajax-url" value="<?php echo admin_url( 'admin-ajax.php' ); ?>"/>
        <input type="hidden" name="success-message" id="success-message" value="<?php _e('Edited successfully!', 'cpt-meta-editor'); ?>" />
        <input type="hidden" name="entries-text" id="entries-text" value="<?php _e('entries', 'cpt-meta-editor'); ?>" />
    </div>

    <div class="cpt-meta-editor-metas container p-3 bg-light border mb-3" style="display:none">
        <div class="font-weight-bold"><?php _e('Custom Post Type', 'cpt-meta-editor'); ?></div>
        <div class="custom-post-type bg-success d-inline-block text-white pl-2 pr-2 pt-1 pb-1"></div>
        <div class="custom-post-type-count d-inline-block font-italic ml-2"></div>
        <div class="font-weight-bold mt-1"><?php _e('Meta Data', 'cpt-meta-editor'); ?></div>
        <div class="metas"></div>
        <div class="font-weight-bold mt-1"><?php _e('Filter', 'cpt-meta-editor'); ?></div>
        <input type="text" name="filter" id="filter" onkeyup="javascript:filter_check()" />
    </div>

    <div class="cpt-meta-editor-content container">
    
    </div>
</div>

<style>
.post-meta {
    position: relative;
}
</style>