<?php

/*
Plugin Name: DM forms Plugin  
Plugin URI: https://vishwajitrikame.com/using-wp_list_table-to-create-wordpress-admin-tables/
Description: DM Forms is plugin which help user to add form and integrate it 
Version: 1.0
Author: Vishwajit Rikame
Author URI:  https://vishwajitrikame.com
*/


/**
    * PART 1. Defining Custom Database Table
    * ============================================================================
    *
    * In this part you are going to define custom database table,
    * create it, update, and fill with some dummy data
    *
    * http://codex.wordpress.org/Creating_Tables_with_Plugins
    *
    * In case your are developing and want to check plugin use:
    *
    * DROP TABLE IF EXISTS wp_dmforms_forms_data_db;
    * DELETE FROM wp_options WHERE option_name = 'dmforms_plugin_install_data';
    *
    * to drop table and option
    */

/**
    * $dmforms_plugin_db_version - holds current database version
    * and used on plugin update to sync database tables
    */
global $dmforms_plugin_db_version;

$dmforms_plugin_db_version = '1.1'; // version changed from 1.0 to 1.1

global $timezone;
$timezone = date_default_timezone_get('Asia/Calcutta');
// echo "The current server timezone is: " . $timezone;

/**
    * register_activation_hook implementation
    *
    * will be called when user activates plugin first time
    * must create needed database tables
    */
function dmforms_plugin_install()
{
    global $wpdb;
    global $dmforms_plugin_db_version;

    $table_name = $wpdb->prefix . 'dmforms_forms_data_db'; // do not forget about tables prefix

    // sql to create your table
    // NOTICE that:
    // 1. each field MUST be in separate line
    // 2. There must be two spaces between PRIMARY KEY and its name
    //    Like this: PRIMARY KEY[space][space](id)
    // otherwise dbDelta will not work

     $sql = "CREATE TABLE " . $table_name . "  (
      `id` mediumint(255) NOT NULL auto_increment,
      `name` varchar(10000) NOT NULL,
      `dmform_shortcode` varchar(10000) NOT NULL,
      `htmlcode` varchar(10000) NOT NULL,
      `author` varchar(10000) NOT NULL,
      `email_to` varchar(1000) NOT NULL,
      `email_from` varchar(1000) NOT NULL,
      `create_date` date NOT NULL,
      `modified_by` varchar(10000) NOT NULL,
      `modified_date` date NOT NULL,
       PRIMARY KEY  (id)
    )";

    // $sql = "CREATE TABLE " . $table_name . " (
    //     id int(11) NOT NULL AUTO_INCREMENT,
    //     name tinytext NOT NULL,
    //     email_to VARCHAR(100) NOT NULL,
    //     age int(11) NULL,
    //     PRIMARY KEY  (id)
    // );";

    // we do not execute sql directly
    // we are calling dbDelta which cant migrate database
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // save current database version for later use (on upgrade)
    add_option('dmforms_plugin_db_version', $dmforms_plugin_db_version);

    /**
        * [OPTIONAL] Example of updating to 1.1 version
        *
        * If you develop new version of plugin
        * just increment $dmforms_plugin_db_version variable
        * and add following block of code
        *
        * must be repeated for each new version
        * in version 1.1 we change email_to field
        * to contain 200 chars rather 100 in version 1.0
        * and again we are not executing sql
        * we are using dbDelta to migrate table changes
        */
    $installed_ver = get_option('dmforms_plugin_db_version');
    if ($installed_ver != $dmforms_plugin_db_version) {

         $sql = "CREATE TABLE " . $table_name . " (
          `id` mediumint(255) NOT NULL auto_increment,
          `name` varchar(10000) NOT NULL,
          `dmform_shortcode` varchar(10000) NOT NULL,
          `htmlcode` varchar(10000) NOT NULL,
          `author` varchar(10000) NOT NULL,
          `email_to` varchar(1000) NOT NULL,
          `email_from` varchar(1000) NOT NULL,
          `create_date` date NOT NULL,
          `modified_by` varchar(10000) NOT NULL,
          `modified_date` date NOT NULL,
           PRIMARY KEY  (id)
        )";

        // $sql = "CREATE TABLE " . $table_name . " (
        //     id int(11) NOT NULL AUTO_INCREMENT,
        //     name tinytext NOT NULL,
        //     email_to VARCHAR(200) NOT NULL,
        //     age int(11) NULL,
        //     PRIMARY KEY  (id)
        // );";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        // notice that we are updating option, rather than adding it
        update_option('dmforms_plugin_db_version', $dmforms_plugin_db_version);
    }
}

register_activation_hook(__FILE__, 'dmforms_plugin_install');

/**
    * register_activation_hook implementation
    *
    * [OPTIONAL]
    * additional implementation of register_activation_hook
    * to insert some dummy data
    */
function dmforms_plugin_install_data()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'dmforms_forms_data_db'; // do not forget about tables prefix

    // $wpdb->insert($table_name, array(
    //     'name' => 'Alex',
    //     'dmform_shortcode' => '[dmforms id="1"]',
    //     'author' => 'Vishwajit',
    //     'email_to' => 'alex@example.com'
    // ));
    // $wpdb->insert($table_name, array(
    //     'name' => 'Maria',
    //     'dmform_shortcode' => '[dmforms id="2"]',
    //     'author' => 'ABC',
    //     'email_to' => 'maria@example.com'
    // ));
}

register_activation_hook(__FILE__, 'dmforms_plugin_install_data');

/**
    * Trick to update plugin database, see docs
    */
function dmforms_plugin_update_db_check()
{
    global $dmforms_plugin_db_version;
    if (get_site_option('dmforms_plugin_db_version') != $dmforms_plugin_db_version) {
        dmforms_plugin_install();
    }
}

add_action('plugins_loaded', 'dmforms_plugin_update_db_check');

/**
    * PART 2. Defining Custom Table List
    * ============================================================================
    *
    * In this part you are going to define custom table list class,
    * that will display your database records in nice looking table
    *
    * http://codex.wordpress.org/Class_Reference/WP_List_Table
    * http://wordpress.org/extend/plugins/custom-list-table-example/
    */

if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

/**
    * Dmform_Example_List_Table class that will display our custom table
    * records in nice table
    */
class Dmform_Example_List_Table extends WP_List_Table
{
    /**
        * [REQUIRED] You must declare constructor and give some basic params
        */
    function __construct()
    {
        global $status, $page;

        parent::__construct(array(
            'singular' => 'dmform',
            'plural' => 'dmforms',
        ));
    }

    /**
        * [REQUIRED] this is a default column renderer
        *
        * @param $item - row (key, value array)
        * @param $column_name - string (key)
        * @return HTML
        */
    function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    /**
        * [OPTIONAL] this is example, how to render specific column
        *
        * method name must be like this: "column_[column_name]"
        *
        * @param $item - row (key, value array)
        * @return HTML
        */
    function column_age($item)
    {
        return '<em>' . $item['age'] . '</em>';
    }

    /**
        * [OPTIONAL] this is example, how to render column with actions,
        * when you hover row "Edit | Delete" links showed
        *
        * @param $item - row (key, value array)
        * @return HTML
        */
    function column_name($item)
    {
        // links going to /admin.php?page=[your_plugin_page][&other_params]
        // notice how we used $_REQUEST['page'], so action will be done on curren page
        // also notice how we use $this->_args['singular'] so in this example it will
        // be something like &dmform=2
        $actions = array(
            'edit' => sprintf('<a href="?page=dmforms_form&id=%s">%s</a>', $item['id'], __('Edit', 'dmforms_plugin')),
            'delete' => sprintf('<a href="?page=%s&action=delete&id=%s">%s</a>', $_REQUEST['page'], $item['id'], __('Delete', 'dmforms_plugin')),
        );

        return sprintf('%s %s',
            $item['name'],
            $this->row_actions($actions)
        );
    }

    /**
        * [REQUIRED] this is how checkbox column renders
        *
        * @param $item - row (key, value array)
        * @return HTML
        */
    function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    /**
        * [REQUIRED] This method return columns to display in table
        * you can skip columns that you do not want to show
        * like content, or description
        *
        * @return array
        */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
            'name' => __('Name', 'dmforms_plugin'),
            'dmform_shortcode' => __('Dmform Shortcode', 'dmforms_plugin'),
            'author' => __('Author', 'dmforms_plugin'),
            'create_date' => __('Create Date', 'dmforms_plugin'),           
        );
        return $columns;
    }

    /**
        * [OPTIONAL] This method return columns that may be used to sort table
        * all strings in array - is column names
        * notice that true on name column means that its default sort
        *
        * @return array
        */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => array('name', true),
            'dmform_shortcode' => array('Dmform Shortcode', 'dmforms_plugin'),
            'author' => array('Author', 'dmforms_plugin'),
            'email_to' => array('email_to', false),
            'create_date' => __('Created Date', 'dmforms_plugin'),
        );
        return $sortable_columns;
    }

    /**
        * [OPTIONAL] Return array of bult actions if has any
        *
        * @return array
        */
    function get_bulk_actions()
    {
        $actions = array(
            'delete' => 'Delete'
        );
        return $actions;
    }

    /**
        * [OPTIONAL] This method processes bulk actions
        * it can be outside of class
        * it can not use wp_redirect coz there is output already
        * in this example we are processing delete action
        * message about successful deletion will be shown on page in next part
        */
    function process_bulk_action()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dmforms_forms_data_db'; // do not forget about tables prefix

        if ('delete' === $this->current_action()) {
            $ids = isset($_REQUEST['id']) ? $_REQUEST['id'] : array();
            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM $table_name WHERE id IN($ids)");
            }
        }
    }

    /**
        * [REQUIRED] This is the most important method
        *
        * It will get rows from database and prepare them to be showed in table
        */
    function prepare_items()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'dmforms_forms_data_db'; // do not forget about tables prefix

        $per_page = 10; // constant, how much records will be shown per page

        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();

        // here we configure table headers, defined in our methods
        $this->_column_headers = array($columns, $hidden, $sortable);

        // [OPTIONAL] process bulk action if any
        $this->process_bulk_action();

        // will be used in pagination settings
        $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

        // prepare query params, as usual current page, order by and order direction
        $paged = isset($_REQUEST['paged']) ? ($per_page * max(0, intval($_REQUEST['paged']) - 1)) : 0;
        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'name';
        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'asc';

        // [REQUIRED] define $items array
        // notice that last argument is ARRAY_A, so we will retrieve array
        $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged), ARRAY_A);
        $items = $this->items;
        foreach ($items as $key => $value) {
            $items[$key]['dmform_shortcode'] = stripslashes($value['dmform_shortcode']);
        }


        $this->items =  $items;
    

        // [REQUIRED] configure pagination
        $this->set_pagination_args(array(
            'total_items' => $total_items, // total items defined above
            'per_page' => $per_page, // per page constant defined at top of method
            'total_pages' => ceil($total_items / $per_page) // calculate pages count
        ));
    }
}

/**
    * PART 3. Admin page
    * ============================================================================
    *
    * In this part you are going to add admin page for custom table
    *
    * http://codex.wordpress.org/Administration_Menus
    */

/**
    * admin_menu hook implementation, will add pages to list dmforms and to add new one
    */
function dmforms_plugin_admin_menu()
{
    add_menu_page(__('Dmforms', 'dmforms_plugin'), __('Dmforms', 'dmforms_plugin'), 'activate_plugins', 'dmforms', 'dmforms_plugin_dmforms_page_handler');
    add_submenu_page('dmforms', __('Dmforms', 'dmforms_plugin'), __('Dmforms', 'dmforms_plugin'), 'activate_plugins', 'dmforms', 'dmforms_plugin_dmforms_page_handler');
    // add new will be described in next part
    add_submenu_page('dmforms', __('Add new', 'dmforms_plugin'), __('Add new', 'dmforms_plugin'), 'activate_plugins', 'dmforms_form', 'dmforms_plugin_dmforms_form_page_handler');
}

add_action('admin_menu', 'dmforms_plugin_admin_menu');

/**
    * List page handler
    *
    * This function renders our custom table
    * Notice how we display message about successfull deletion
    * Actualy this is very easy, and you can add as many features
    * as you want.
    *
    * Look into /wp-admin/includes/class-wp-*-list-table.php for examples
    */
function dmforms_plugin_dmforms_page_handler()
{
    global $wpdb;

    $table = new Dmform_Example_List_Table();
    $table->prepare_items();

    $message = '';
    if ('delete' === $table->current_action()) {
        $message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'dmforms_plugin'), count($_REQUEST['id'])) . '</p></div>';
    }
    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Dmforms', 'dmforms_plugin')?> <a class="add-new-h2"
                                    href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=dmforms_form');?>"><?php _e('Add new', 'dmforms_plugin')?></a>
    </h2>
    <?php echo $message; ?>

    <form id="dmforms-table" method="GET">
        <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>"/>
        <?php $table->display() ?>
    </form>

</div>
<?php
}

/**
    * PART 4. Form for adding andor editing row
    * ============================================================================
    *
    * In this part you are going to add admin page for adding andor editing items
    * You cant put all form into this function, but in this example form will
    * be placed into meta box, and if you want you can split your form into
    * as many meta boxes as you want
    *
    * http://codex.wordpress.org/Data_Validation
    * http://codex.wordpress.org/Function_Reference/selected
    */

/**
    * Form page handler checks is there some data posted and tries to save it
    * Also it renders basic wrapper in which we are callin meta box render
    */
function dmforms_plugin_dmforms_form_page_handler()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'dmforms_forms_data_db'; // do not forget about tables prefix

    $message = '';
    $notice = '';

    // this is default $item which will be used for new records
    $default = array(
        'id' => 0,
        'name' => '',
        'dmform_shortcode' => '',
        'htmlcode' => '',
        // 'author' => '',
        'email_to' => '',
        'email_from' => '',
    );

    // here we are verifying does this request is post back and have correct nonce
    if (wp_verify_nonce($_REQUEST['nonce'], basename(__FILE__))) {
        // combine our default item with request params
        $item = shortcode_atts($default, $_REQUEST);
        // validate data, and if all ok save item to database
        // if id is zero insert otherwise update
        $item_valid = dmforms_plugin_validate_dmform($item);
        if ($item_valid === true) {

            if ($item['id'] == 0) {
                
                $item['htmlcode'] = htmlentities($item['htmlcode']);
                print_r($wpdb->last_error);            
                $result = $wpdb->insert($table_name, $item);

                $item['id'] = $wpdb->insert_id;
                // =============== to update shortcode once data inserted =============== //
                $item['dmform_shortcode'] = '[dmform_contact_form id="'.$item['id'].'" title="'.$item['name'].'"]';
                $item['dmform_shortcode'] = htmlentities($item['dmform_shortcode']);
                $current_user = wp_get_current_user();
                $item['author'] = $current_user->display_name;
                $item['create_date'] = date('Y-m-d H:i:s');
                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                // =============== to update shortcode once data inserted =============== //


                if ($result) {
                    $message = __('Item was successfully saved', 'dmforms_plugin');
                } else {
                    $notice = __('There was an error while saving item', 'dmforms_plugin');
                }
            } else {
                $item['htmlcode'] = htmlentities($item['htmlcode']);
                $item['dmform_shortcode'] = '[dmform_contact_form id="'.$item['id'].'" title="'.$item['name'].'"]';
                $item['dmform_shortcode'] = htmlentities($item['dmform_shortcode']);

                $current_user = wp_get_current_user();
                $item['modified_by'] = $current_user->display_name;
                $item['modified_date'] = date('Y-m-d H:i:s');
                // print_r($item);

                $result = $wpdb->update($table_name, $item, array('id' => $item['id']));
                if ($result) {
                    $message = __('Item was successfully updated', 'dmforms_plugin');
                } else {
                    $notice = __('There was an error while updating item', 'dmforms_plugin');
                }
            }
        } else {
            // if $item_valid not true it contains error message(s)
            $notice = $item_valid;
        }
    }
    else {
        // if this is not post back we load item to edit or give new one to create
        $item = $default;
        if (isset($_REQUEST['id'])) {
            $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $_REQUEST['id']), ARRAY_A);
            if (!$item) {
                $item = $default;
                $notice = __('Item not found', 'dmforms_plugin');
            }
        }
    }

    // here we adding our custom meta box
    add_meta_box('dmforms_form_meta_box', 'Dmform data', 'dmforms_plugin_dmforms_form_meta_box_handler', 'dmform', 'normal', 'default');

    ?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Dmform', 'dmforms_plugin')?> <a class="add-new-h2" 
        href="<?php echo get_admin_url(get_current_blog_id(), 'admin.php?page=dmforms');?>"><?php _e('back to list', 'dmforms_plugin')?></a>
    </h2>
    

    <?php if (!empty($notice)): ?>
    <div id="notice" class="error"><p><?php echo $notice ?></p></div>
    <?php endif;?>
    <?php if (!empty($message)): ?>
    <div id="message" class="updated"><p><?php echo $message ?></p></div>
    <?php endif;?>

    <form id="form" method="POST">
        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce(basename(__FILE__))?>"/>
        <?php /* NOTICE: here we storing id to determine will be item added or updated */ ?>
        <input type="hidden" name="id" value="<?php echo $item['id'] ?>"/>

        <div class="metabox-holder" id="poststuff">
            <div id="post-body">
                <div id="post-body-content">
                    
                    <?php /* And here we call our custom meta box */
                    // echo "<pre>";
                    // print_r($item);
                    // echo "</pre>";
                    if(isset($item['dmform_shortcode'])&&!empty($item['dmform_shortcode'])){
                    $item['dmform_shortcode'] = '[dmform_contact_form id="'.$item['id'].'" title="'.$item['name'].'"]';
                    $item['dmform_shortcode'] = stripslashes($item['dmform_shortcode']);
                    echo '<div class="sc_header"><label>Shortcode:</label><span class="highlighter">'.$item['dmform_shortcode'].'</span></div>';
                    }

                    ?>
                    <style type="text/css">
                        .highlighter{padding: 2px; background: #d2f5a9;line-height: 30px;}
.sc_header{font-weight: bold;background: #ffffff; padding: 10px; border: 1px solid #cccccc; border-bottom: 0; border-top: 0; border-left: 1px solid #f1f1f1;}
.sc_header label{margin-right: 10px; line-height: 30px;}
                    </style> 
                    <!-- <textarea><?php //print_r($item['htmlcode']); ?></textarea> --> <?php do_meta_boxes('dmform', 'normal', $item); ?>
                    <input type="submit" value="<?php _e('Save', 'dmforms_plugin')?>" id="submit" class="button-primary" name="submit">
                </div>
            </div>
        </div>
    </form>
</div>
<?php
}

/**
    * This function renders our custom meta box
    * $item is row
    *
    * @param $item
    */
function dmforms_plugin_dmforms_form_meta_box_handler($item)
{
 

// $dmform_shortcode = stripslashes( $item['dmform_shortcode'] );
$htmlcode = stripslashes($item['htmlcode']);

?>
<table cellspacing="2" cellpadding="5" style="width: 100%;" class="form-table">
    <tbody>
    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="name"><?php _e('Name', 'dmforms_plugin')?></label>
        </th>
        <td>
            <input id="name" name="name" type="text" style="width: 95%" value="<?php echo esc_attr($item['name'])?>"
                     class="code" placeholder="<?php _e('Your name', 'dmforms_plugin')?>" required>
        </td>
    </tr>

    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="tags">Tags</label>
        </th>
        <td>
            <span id="tag-generator-list">
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-text&TB_iframe=true&inlineId=tag-generator-panel-text" class="thickbox button" title="Form-tag Generator: text">text</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-email&TB_iframe=true&inlineId=tag-generator-panel-email" class="thickbox button" title="Form-tag Generator: email">email</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-url&TB_iframe=true&inlineId=tag-generator-panel-url" class="thickbox button" title="Form-tag Generator: URL">URL</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-tel&TB_iframe=true&inlineId=tag-generator-panel-tel" class="thickbox button" title="Form-tag Generator: tel">tel</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-number&TB_iframe=true&inlineId=tag-generator-panel-number" class="thickbox button" title="Form-tag Generator: number">number</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-date&TB_iframe=true&inlineId=tag-generator-panel-date" class="thickbox button" title="Form-tag Generator: date">date</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-textarea&TB_iframe=true&inlineId=tag-generator-panel-textarea" class="thickbox button" title="Form-tag Generator: text area">text area</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-menu&TB_iframe=true&inlineId=tag-generator-panel-menu" class="thickbox button" title="Form-tag Generator: drop-down menu">drop-down menu</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-checkbox&TB_iframe=true&inlineId=tag-generator-panel-checkbox" class="thickbox button" title="Form-tag Generator: checkboxes">checkboxes</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-radio&TB_iframe=true&inlineId=tag-generator-panel-radio" class="thickbox button" title="Form-tag Generator: radio buttons">radio buttons</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-acceptance&TB_iframe=true&inlineId=tag-generator-panel-acceptance" class="thickbox button" title="Form-tag Generator: acceptance">acceptance</a>           
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-text&TB_iframe=true&inlineId=tag-generator-panel-recaptcha" class="thickbox button" title="Form-tag Generator: reCAPTCHA">reCAPTCHA</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-file&TB_iframe=true&inlineId=tag-generator-panel-file" class="thickbox button" title="Form-tag Generator: file">file</a>
            <a href="<?php echo plugins_url('', __FILE__); ?>/tag_generator.php?keepThis=true&width=900&height=500&tagreq=tag-generator-panel-submit&TB_iframe=true&inlineId=tag-generator-panel-submit" class="thickbox button" title="Form-tag Generator: submit">submit</a></span>
        </td>        
    </tr>



    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="htmlcode"><?php _e('Form Code', 'dmforms_plugin')?></label>
        </th>
        <td>
            <textarea rows="20" id="htmlcode" name="htmlcode" type="htmlcode" style="width: 95%"
                     class="code" placeholder="<?php _e('Your Form Code', 'dmforms_plugin')?>" required><?php echo $htmlcode; ?></textarea>
        </td>
    </tr>


    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="email_to"><?php _e('E-Mail To', 'dmforms_plugin')?></label>
        </th>
        <td>
            <input id="email_to" name="email_to" type="email_to" style="width: 95%" value="<?php echo esc_attr($item['email_to'])?>"
                     class="code" placeholder="<?php _e('Your E-Mail To', 'dmforms_plugin')?>" required>
        </td>
    </tr>

    <tr class="form-field">
        <th valign="top" scope="row">
            <label for="email_from"><?php _e('E-Mail From', 'dmforms_plugin')?></label>
        </th>
        <td>
            <input id="email_from" name="email_from" type="email_from" style="width: 95%" value="<?php echo esc_attr($item['email_from'])?>"
                     class="code" placeholder="<?php _e('Your E-Mail From', 'dmforms_plugin')?>" required>
        </td>
    </tr>
   
    </tbody>
</table>


<?php
add_thickbox();
?>



<?php
}
/**
    * Simple function that validates data and retrieve bool on success
    * and error message(s) on error
    *
    * @param $item
    * @return bool|string
    */
function dmforms_plugin_validate_dmform($item)
{
    $messages = array();

    if (empty($item['name'])) $messages[] = __('Name is required', 'dmforms_plugin');
    if (!empty($item['email_to']) && !is_email($item['email_to'])) $messages[] = __('E-Mail To is in wrong format', 'dmforms_plugin');
    if (!empty($item['email_from']) && !is_email($item['email_from'])) $messages[] = __('E-Mail From is in wrong format', 'dmforms_plugin');
    // if (!ctype_digit($item['age'])) $messages[] = __('Age in wrong format', 'dmforms_plugin');
    //if(!empty($item['age']) && !absint(intval($item['age'])))  $messages[] = __('Age can not be less than zero');
    //if(!empty($item['age']) && !preg_match('/[0-9]+/', $item['age'])) $messages[] = __('Age must be number');
    //...

    if (empty($messages)) return true;
    return implode('<br />', $messages);
}

/**
    * Do not forget about translating your plugin, use __('english string', 'your_uniq_plugin_name') to retrieve translated string
    * and _e('english string', 'your_uniq_plugin_name') to echo it
    * in this example plugin your_uniq_plugin_name == dmforms_plugin
    *
    * to create translation file, use poedit FileNew catalog...
    * Fill name of project, add "." to path (ENSURE that it was added - must be in list)
    * and on last tab add "__" and "_e"
    *
    * Name your file like this: [my_plugin]-[ru_RU].po
    *
    * http://codex.wordpress.org/Writing_a_Plugin#Internationalizing_Your_Plugin
    * http://codex.wordpress.org/I18n_for_WordPress_Developers
    */
function dmforms_plugin_languages()
{
    load_plugin_textdomain('dmforms_plugin', false, dirname(plugin_basename(__FILE__)));
}

add_action('init', 'dmforms_plugin_languages');


function dmform_html_form_code($atts) {

  $parameters = $atts;
  global $wpdb;
  $table_name = $wpdb->prefix . 'dmforms_forms_data_db'; // do not forget about tables prefix
  if (isset($parameters['id'])) {
      $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $parameters['id']), ARRAY_A);
      // print_r($item);
      if (!$item) {
          $item = $default;
          $notice = __('Item not found', 'dmforms_plugin');
      }
  }

  echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
  ?>
  <?php 
  $htmlcode = stripslashes($item['htmlcode']);
  print_r(html_entity_decode($htmlcode));
  echo '</form>';


  if (wp_mail( 'vishwajitrikame@gmail.com' , "subject", "HI" , "From: vishwajitrikame@gmail.com" ) ) {
      echo '<div>';
      echo '<p>Thanks for contacting me, expect a response soon.</p>';
      echo '</div>';
  } else {
      echo 'An unexpected error occurred';
  }

}



function dmform_deliver_mail($atts) {
    $parameters = $atts;
    global $wpdb;
    $table_name = $wpdb->prefix . 'dmforms_forms_data_db'; // do not forget about tables prefix
    
    if (isset($parameters['id'])) {
      $item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $parameters['id']), ARRAY_A);
      echo '<h1>'.$item['name'].'</h1>';    
         
      // print_r($item);
      if (!$item) {
          $item = $default;
          $notice = __('Item not found', 'dmforms_plugin');
      }else{
                    if ( isset( $_POST['submit'] ) ) {

                    

                    // sanitize form values
                    // $hello_name = sanitize_text_field( $_POST["hello_name"] );
                    // $hello_phone = sanitize_text_field( $_POST["hello_phone"] );
                    // $hello_email   = sanitize_email( $_POST["hello_email"] );
                    // $hello_organization = sanitize_text_field( $_POST["hello_organization"] );
                    // $hello_message = sanitize_text_field( $_POST["hello_message"] );

                    

                    $email_to = $item['email_to'];
                    $email_from  = $item['email_from'];
                    $table_name =  $wpdb->prefix . 'dmforms_'.$parameters['id'].''; 

                    $table_fields_to_create = array(); 
                    $fields_data_to_insert =  array(); 


                    foreach($_POST as $key => $value){                    
                    array_push($table_fields_to_create, $key);
                    $fields_data_to_insert[$key] = sanitize_text_field($_POST[$key]);
                    }


                    $fields_data_to_insert['create_date'] =  date('Y-m-d H:i:s');
                    // print_r($table_fields_to_create);
                    
                    
                    //table not in database. Create new table
                    

                    if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
                                        
                    $sql = "CREATE TABLE " . $table_name . "  (
                          `id` mediumint(255) NOT NULL auto_increment,";
                    foreach ($table_fields_to_create as $key => $value) {
                    $sql .= "`$value` varchar(10000) NOT NULL,";
                    }              
                    $sql .= "`create_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                            PRIMARY KEY  (id)
                        )";

                    // echo "<br><pre>";    
                    // print_r($sql);
                    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');    
                    dbDelta($sql);    
                    }else{
                     
                    }
                    $result = $wpdb->insert($table_name, $fields_data_to_insert);
                    // print_r($result);   
                    

                    


                    
                    // $message  = "hello_name : $hello_name". "\r\n";
                    // $message .= "hello_phone : $hello_phone". "\r\n";
                    // $message .= "hello_email : $hello_email". "\r\n";
                    // $message .= "hello_organization : $hello_organization". "\r\n";
                    // $message .= "hello_message : $hello_message". "\r\n";


                    // $subject = "Mail From DM Forms";
                    
                    // // get the blog administrator's email address

                    // $headers = "From: $hello_name - $email_from" . "\r\n";
                    // // print_r($headers);


                    // // If email has been process for sending, display a success message
                    // if ( wp_mail( $email_to, $subject, $message, $headers ) ) {
                    //   echo '<div>';
                    //   echo '<p>Thanks for contacting me, expect a response soon.</p>';
                    //   echo '</div>';
                    // } else {
                    //   echo 'An unexpected error occurred';
                    // }
                  }
      }
      
      }
  // if the submit button is clicked, send the email
}
function dmform_shortcode($atts) {
  ob_start();
$atts = array_change_key_case((array)$atts, CASE_LOWER);
$form_atts = shortcode_atts([
                            'id' => '1',
                         ], $atts , 'dmform_contact_form' );  
  // default attrributes
  // $atts = shortcode_atts(
  // array(
  //   'id' => '1',
  // ), $atts, 'dmform_contact_form' );


  dmform_deliver_mail($form_atts);
  dmform_html_form_code($form_atts);

  return ob_get_clean();
}

add_shortcode( 'dmform_contact_form', 'dmform_shortcode' );



function input_type_text_shortcode($atts){
$atts = array_change_key_case((array)$atts, CASE_LOWER);
print_r($atts);
return '<input type="text" class="" id="" placeholder="">';
}

add_shortcode( 'dmform_text_sc', 'text' );

// Register Style
function dmforms_styles() {

    wp_register_style( 'dmforms_styles', plugins_url('/includes/css/admin-style.css', __FILE__ ), array('thickbox'), '1.0' );
    wp_enqueue_style( 'dmforms_styles' );
    wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );


}
add_action( 'admin_enqueue_scripts', 'dmforms_styles');


