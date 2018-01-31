<?php
if ( ! class_exists( 'WP_List_Table' ) ) {
  require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class Dmforms_List extends WP_List_Table {

  /** Class constructor */
  public function __construct() {

    parent::__construct( [
      'singular' => __( 'DMForm', 'sp' ), //singular name of the listed records
      'plural'   => __( 'DMForms', 'sp' ), //plural name of the listed records
      'ajax'     => false //does this table support ajax?
    ] );

  }


  /**
   * Retrieve dmforms data from the database
   *
   * @param int $per_page
   * @param int $page_number
   *
   * @return mixed
   */
  public static function get_dmforms( $per_page = 5, $page_number = 1 ) {

    global $wpdb;

    $sql = "SELECT * FROM {$wpdb->prefix}formtest2";

    if ( ! empty( $_REQUEST['orderby'] ) ) {
      $sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
      $sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
    }

    $sql .= " LIMIT $per_page";
    $sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;


    $result = $wpdb->get_results( $sql, 'ARRAY_A' );

    return $result;
  }


  /**
   * Delete a dmform record.
   *
   * @param int $id dmform ID
   */
  public static function delete_dmform( $id ) {
    global $wpdb;

    $wpdb->delete(
      "{$wpdb->prefix}dmforms",
      [ 'ID' => $id ],
      [ '%d' ]
    );
  }


  /**
   * Returns the count of records in the database.
   *
   * @return null|string
   */
  public static function record_count() {
    global $wpdb;

    $sql = "SELECT COUNT(*) FROM {$wpdb->prefix}dmforms";

    return $wpdb->get_var( $sql );
  }


  /** Text displayed when no dmform data is available */
  public function no_items() {
    _e( 'No dmforms avaliable.', 'sp' );
  }


  /**
   * Render a column when no column specific method exist.
   *
   * @param array $item
   * @param string $column_name
   *
   * @return mixed
   */
  public function column_default( $item, $column_name ) {
    switch ( $column_name ) {
      case 'dmform_shortcode':
      case 'author':
        return $item[ $column_name ];
      case 'create_date':
        return $item[ $column_name ];
      case 'modified_by':
        return $item[ $column_name ];      
      case 'modified_date':
        return $item[ $column_name ];    
      default:
        return print_r( $item, true ); //Show the whole array for troubleshooting purposes
    }
  }

  /**
   * Render the bulk edit checkbox
   *
   * @param array $item
   *
   * @return string
   */
  function column_cb( $item ) {
    return sprintf(
      '<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
    );
  }


  /**
   * Method for name column
   *
   * @param array $item an array of DB data
   *
   * @return string
   */
  function column_name( $item ) {

    $delete_nonce = wp_create_nonce( 'sp_delete_dmform' );
    $edit_nonce = wp_create_nonce( 'sp_edit_dmform' );

    $title = '<strong>' . $item['name'] . '</strong>';

    $actions = [
      'delete' => sprintf( '<a href="?page=%s&action=%s&dmform=%s&_wpnonce=%s">Delete</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce ),
      'edit' => sprintf( '<a href="?page=%s&action=%s&dmform=%s&_wpnonce=%s">Edit</a>', esc_attr( $_REQUEST['page'] ), 'edit', absint( $item['ID'] ), $edit_nonce )
    ];

    return $title . $this->row_actions( $actions );
  }


  /**
   *  Associative array of columns
   *
   * @return array
   */
  function get_columns() {
    $columns = [
      'cb'      => '<input type="checkbox" />',
      'name'    => __( 'Name', 'sp' ),
      'dmform_shortcode' => __( 'Dmf Shortcode', 'sp' ),
      'author'    => __( 'Author', 'sp' ),
      'create_date'    => __( 'Date', 'sp' ),
      'modified_by'    => __( 'Modified By', 'sp' ),
      'modified_date'    => __( 'Modified Date', 'sp' )
    ];

    return $columns;
  }


  /**
   * Columns to make sortable.
   *
   * @return array
   */
  public function get_sortable_columns() {
    $sortable_columns = array(
      'name' => array( 'name', true ),
      'city' => array( 'city', false )
    );

    return $sortable_columns;
  }

  /**
   * Returns an associative array containing the bulk action
   *
   * @return array
   */
  public function get_bulk_actions() {
    $actions = [
      'bulk-delete' => 'Delete'
    ];

    return $actions;
  }


  /**
   * Handles data query and filter, sorting, and pagination.
   */
  public function prepare_items() {

    $this->_column_headers = $this->get_column_info();

    /** Process bulk action */
    $this->process_bulk_action();

    $per_page     = $this->get_items_per_page( 'dmforms_per_page', 5 );
    $current_page = $this->get_pagenum();
    $total_items  = self::record_count();

    $this->set_pagination_args( [
      'total_items' => $total_items, //WE have to calculate the total number of items
      'per_page'    => $per_page //WE have to determine how many items to show on a page
    ] );

    $this->items = self::get_dmforms( $per_page, $current_page );
  }

  public function process_bulk_action() {

    //Detect when a bulk action is being triggered...
    if ( 'delete' === $this->current_action() ) {

      // In our file that handles the request, verify the nonce.
      $nonce = esc_attr( $_REQUEST['_wpnonce'] );

      if ( ! wp_verify_nonce( $nonce, 'sp_delete_dmform' ) ) {
        die( 'Go get a life script kiddies' );
      }
      else {
        self::delete_dmform( absint( $_GET['dmform'] ) );

                    // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
                    // add_query_arg() return the current url
                    wp_redirect( esc_url_raw(add_query_arg()) );
        exit;
      }

    }

    // If the delete bulk action is triggered
    if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
         || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
    ) {

      $delete_ids = esc_sql( $_POST['bulk-delete'] );

      // loop over the array of record IDs and delete them
      foreach ( $delete_ids as $id ) {
        self::delete_dmform( $id );

      }

      // esc_url_raw() is used to prevent converting ampersand in url to "#038;"
            // add_query_arg() return the current url
            wp_redirect( esc_url_raw(add_query_arg()) );
      exit;
    }
  }

}


class SP_Plugin {

  // class instance
  static $instance;

  // dmform WP_List_Table object
  public $dmforms_obj;

  // class constructor
  public function __construct() {
    add_filter( 'set-screen-option', [ __CLASS__, 'set_screen' ], 10, 3 );
    add_action( 'admin_menu', [ $this, 'plugin_menu' ] );
  }


  public static function set_screen( $status, $option, $value ) {
    return $value;
  }

  public function plugin_menu() {

    $hook = add_menu_page(
      'DM Form Builder',
      'DM Form Builder',
      'manage_options',
      'wp_list_table_class',
      [ $this, 'plugin_settings_page' ]
    );

    add_action( "load-$hook", [ $this, 'screen_option' ] );

  }


  /**
   * Plugin settings page
   */
  public function plugin_settings_page() {
    ?>
    <div class="wrap">
      <h2>DM Form Builder</h2>

      <div id="poststuff">
        <div id="post-body" class="metabox-holder columns-2">
          <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
              <form method="post">
                <?php
                $this->dmforms_obj->prepare_items();
                $this->dmforms_obj->display(); ?>
              </form>
            </div>
          </div>
        </div>
        <br class="clear">
      </div>
    </div>
  <?php
  }

  /**
   * Screen options
   */
  public function screen_option() {

    $option = 'per_page';
    $args   = [
      'label'   => 'Dmforms',
      'default' => 5,
      'option'  => 'dmforms_per_page'
    ];

    add_screen_option( $option, $args );

    $this->dmforms_obj = new Dmforms_List();
  }


  /** Singleton instance */
  public static function get_instance() {
    if ( ! isset( self::$instance ) ) {
      self::$instance = new self();
    }

    return self::$instance;
  }

}


add_action( 'plugins_loaded', function () {
  SP_Plugin::get_instance();
} );
