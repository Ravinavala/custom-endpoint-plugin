<div class="plugin-setting-container">
    <h3><?php esc_html_e('Inspyde User Listing', 'inspyde-user-list'); ?></h3>
    <h4><?php echo esc_html('Note: Please make  sure to  flush permalinks before visiting that endpoint.'); ?> <?php echo site_url() . '/' . get_option('inspyde_custom_endpoint'); ?></h4>
    <h4><?php echo esc_html('Go to WP Admin > Settings > Permalinks > Save.'); ?></h4>
</div>
<?php
if (isset($_POST['submit'])) {
    update_option('inspyde_custom_endpoint', str_replace(' ', '-', strtolower($_POST['inspyde_custom_endpoint'])));
}
?>
<form name="" method="post" action="">
    <div id="general-section" class="yoatab active plugin-setting-contain">
        <div class="rows">
            <h4><?php echo esc_html('Note: If you are updating or changing custom endpoint make sure Flush permalinks before visiting endpoint:'); ?>
            <div class="left-rows">
                <label>Customize your endpoint:</label>
                <input type="text" name="inspyde_custom_endpoint" class="inspyde_custom_endpoint" value="<?php echo (get_option('inspyde_custom_endpoint')) ? get_option('inspyde_custom_endpoint') : ''; ?>" />
            </div>
        </div>
        <?php submit_button('Update'); ?>
    </div>
</form>
