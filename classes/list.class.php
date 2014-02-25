<?php
/**
 * Plugin:			Bulk Me Now!
 * Plugin URI:		http://metamorpher.net/bulk-me-now/
 * Description:		Adds a Contact Form Module for your blog, so you don't get your contact form into your e-mail but your WP admin area instead.
 * Author:			mEtAmorPher
 * Author URI:		http://metamorpher.net/
 * Version:			2.0
 * Text Domain:		bulk
 * Domain Path:		/lang
 *
 * Copyright 2014  mEtAmorPher  (email : metamorpher.py@gmail.com)
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package bulk-me-now
 */


//Our class extends the WP_List_Table class, so we need to make sure that it's there

if( ! class_exists( 'WP_List_Table' ) )
{
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}


class BulkMeNow_List extends WP_List_Table {
	
	/**
	 * Saves the status of the current view of the table (Unread, read or trash)
	 *
	 * @access private
	 */
	var $status;

	/**
	 * Saves the current page
	 *
	 * @access private
	 */
	var $paged;
	
	/**
	 * The tables we will use on query, when preparing items
	 *
	 * @access private
	 */
	var $tables;

	/**
	 * The model object
	 *
	 * @access private
	 */
	var $db;

	/**
	 * The external object
	 *
	 * @access private
	 */
	var $ext;
	
	/**
	 * Class constructor
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	function __construct()
	{		
		global $wpdb, $bulkmenow_settings, $bulkmenow_model, $bulkmenow_externals;

		$this->tables = $bulkmenow_settings->tables;
		$this->db = $bulkmenow_model;
		$this->ext = $bulkmenow_externals;
		
		$this->status = ( ! empty( $_GET['status'] ) ) ? $_GET['status'] : 1;
		$this->paged = ( ! empty( $_GET['paged'] ) ) ? $_GET['paged'] : 0;
		
		$this->action = ( ! empty( $_GET['button_action'] ) ) ? $_GET['button_action'] : FALSE;
		
		if( isset( $_GET['button_action'] ) ) $this->execute_individual_actions( $_GET['button_action'], $_GET['message_id'] );
		
		parent::__construct( array(
			'singular'	=> 'bulkmenow_message',		// Singular label
			'plural'	=> 'bulkmenow_messages',	// Plural label, also this well be one of the table css class
			'ajax'		=> FALSE					// We won't support Ajax for this table
		) );
	}

	/**
	 * Extra html to output next to bulk actions
	 *
	 * @param string $which Defines whether to output, top other bottom of the table
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	function extra_tablenav( $which )
	{
		if( $which == "top" )
		{
			if( $this->status == '3' ) : ?>

			<div class="alignleft actions bulkactions" style="margin-top: 1px;">
				<a  href="<?php echo "?page=" . $_REQUEST['page'] . "&status=3&action=empty_trash"; ?>" 
					data-confirm="<?php _e( "Are you sure you want empty the trash? This procedure CAN NOT BE UNDONE.", "bulk" ); ?>"
					class="button action empty-trash"><?php _e( 'Empty Trash', 'bulk' ) ?></a>
			</div>
				
			<?php endif;
			
			// can add bottom too.
		}
	}

	/**
	 * Message to display in case we have no matching items to associate
	 *
	 * @param none
	 * @return output
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	function no_items()
	{
		if( ! empty( $_GET['s'] ) )
		{
			_e( 'No messages were found.', 'bulk' );
		}
		else
		{
			switch( $this->status )
			{
				default:
				case "1": _e( 'There are no new messages yet.', 'bulk' ); break;
				case "2": _e( 'No messages were read yet.', 'bulk' ); break;
				case "3": _e( 'There are no messages on the Trash. Hooray!', 'bulk' ); break;
			}
		}
	}
	
	/**
	 * Defines the views available for the table
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function get_views()
	{
		$views = array(
			'1' => __( "Unread", "bulk" ) . ' <span class="count">(' . $this->db->count_according_status( 1 ) . ')</span>',
			'2' => __( "Read", "bulk" ) . ' <span class="count">(' . $this->db->count_according_status( 2 ) . ')</span>',
			'3' => __( "Trash", "bulk" ) . ' <span class="count">(' . $this->db->count_according_status( 3 ) . ')</span>',
		);
		
		foreach( $views AS $k => $v )
		{
			$current = ( $this->status == $k ) ? ' class="current"' : "";
			$views[$k] = '<a href="admin.php?page=bulkmenow-list&status=' . $k . '"' . $current . '>' . $v . '</a>';
		}
		
		return $views;
	}

	/**
	 * Defines the columns available on the table
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	function get_columns()
	{
		return array(
			'cb'		=> '<input type="checkbox" />',
			'name'		=> __( 'Name', 'bulk' ),
			'email'		=> __( 'E-mail', 'bulk' ),
			'date_sent'	=> __( 'Date', 'bulk' ),
			'status'	=> __( 'Status', 'bulk' ),
		);
	}

	/**
	 * Defines which columns are sortable in the table.
	 * Orders are sent to the query via GET on prepare_items()
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	function get_sortable_columns()
	{
		return array(
			'name'		=> array( 'name', false ),
			'date_sent'	=> array( 'date_sent', false ),
			'email'		=> array( 'email', false )
		);
	}
	
	/**
	 * Defines content for the checkbox column
	 *
	 * @param array/object $item The message array/object of the query
	 * @return string
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	function column_cb( $item )
	{
		return sprintf(
			'<input type="checkbox" name="%1$s[]" value="%2$s" />',
			$this->_args['singular'],	//Let's simply repurpose the table's singular label ("movie")
			$item['message_id']			//The value of the checkbox should be the record's id
		);
	}

	/**
	 * Defines content for the name column
	 *
	 * @param array/object $item The message array/object of the query
	 * @return string
	 * @since 2.0
	 * @author mEtAmorPher
	 */

    function column_name( $item )
	{
		// Action buttons
		$buttons = array(
			'view' => sprintf(
				'<a href="?page=%1$s&button_action=%2$s&message_id=%3$d&status=%4$d">%5$s</a>',
				//$_REQUEST['page'],
				'bulkmenow-message',
				'view',
				$item['message_id'],
				$this->status,
				__( "View", "bulk" )
			),
			'trash' => sprintf(
				'<a href="?page=%1$s&button_action=%2$s&message_id=%3$d&status=%4$d&paged=%5$d">%6$s</a>',
				$_REQUEST['page'],
				'send_trash',
				$item['message_id'],
				$this->status,
				$this->paged,
				__( "Send to Trash", "bulk" )
			),
			'mark_read' => sprintf(
				'<a href="?page=%1$s&button_action=%2$s&message_id=%3$d&status=%4$d&paged=%5$d">%6$s</a>',
				$_REQUEST['page'],
				'mark_read',
				$item['message_id'],
				$this->status,
				$this->paged,
				__( "Mark as Read", "bulk" )
			),
			'mark_unread' => sprintf(
				'<a href="?page=%1$s&button_action=%2$s&message_id=%3$d&status=%4$d&paged=%5$d">%6$s</a>',
				$_REQUEST['page'],
				'mark_unread',
				$item['message_id'],
				$this->status,
				$this->paged,
				__( "Mark Unread", "bulk" )
			),
			'restore' => sprintf(
				'<a href="?page=%1$s&button_action=%2$s&message_id=%3$d&status=%4$d&paged=%5$d">%6$s</a>',
				$_REQUEST['page'],
				'restore',
				$item['message_id'],
				$this->status,
				$this->paged,
				__( "Restore", "bulk" )
			),
			'delete' => sprintf(
				'<a href="?page=%1$s&button_action=%2$s&message_id=%3$d&status=%4$d&paged=%5$d" class="delete-message" data-confirm="%6$s">%7$s</a>',
				$_REQUEST['page'],
				'delete',
				$item['message_id'],
				$this->status,
				$this->paged,
				sprintf( __( 'Are you sure you want to delete the message from %1$s? This procedure CAN NOT BE UNDONE', "bulk" ), $item['name'] ),
				__( "Delete", "bulk" )
			)
		);
		
		//Build row actions
		switch( $this->status )
		{
			default:
			case '1': $actions = array( 'view' => $buttons['view'], 'read' => $buttons['mark_read'], 'delete' => $buttons['trash'] ); break;
			case '2': $actions = array( 'view' => $buttons['view'], 'read' => $buttons['mark_unread'], 'delete' => $buttons['trash'] ); break;
			case '3': $actions = array( 'read' => $buttons['restore'], 'delete' => $buttons['delete'] ); break;
		}

		//Return the title contents
		return sprintf(
			'<strong>%1$s</strong>%2$s',
			( ! empty( $item['name'] ) ) ? $item['name'] : '<span class="empty">' . __( "No Data", "bulk" ) . '</span>',
			$this->row_actions( $actions )
		);
    }

	/**
	 * Defines content for the date_sent column
	 *
	 * @param array/object $item The message array/object of the query
	 * @return string
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	function column_date_sent( $item )
	{
		return ( ! empty( $item['date_sent'] ) ) ? sprintf(
			'%1$s<br />%2$s',
			date( get_option( 'date_format' ), strtotime( $item['date_sent'] ) ),
			date( get_option( 'time_format' ), strtotime( $item['date_sent'] ) )
		) : '<span class="empty">' . __( "No Data", "bulk" ) . '</span>' ;
	}
	
	/**
	 * Defines content for the rest of the columns
	 * (If you want to take out a column to customize and don't want to do it here, you
	 * must define a function column_[index name of the column] and return a string with
	 * the proposed content)
	 *
	 * @param array/object $item The message array/object of the query
	 * @return string
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	function column_default( $item, $column_name )
	{
		switch( $column_name )
		{
			case 'email':
				return ( ! empty( $item[$column_name] ) ) ? $item[$column_name] : '<span class="empty">' . __( "No Data", "bulk" ) . '</span>';
				break;
			case 'status':
				echo ( $this->db->message_replied( $item['message_id'] ) ) ? '<span class="label success">' . __( "Replied" ) . '</span>' : '<span class="label danger">' . __( "Un-replied" ) . '</span>';
				break;
			default:
				return print_r( $item, TRUE );	//Show the whole array for troubleshooting purposes
				break;
		}
	}

	/**
	 * Defines the available bulk actions in the top select input
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	function get_bulk_actions()
	{
		$actions = array(
			'delete' 		=> __( "Delete", "bulk" ),
			'mark_read' 	=> __( "Mark Read", "bulk" ),
			'mark_unread' 	=> __( "Mark Unread", "bulk" ),
			'send_trash' 	=> __( "Send to Trash", "bulk" ),
			'restore' 		=> __( "Restore", "bulk" )
		);
		
		switch( $this->status )
		{
			default:
			case '1': $bulks = array( 'mark_read' => $actions['mark_read'], 'send_trash' => $actions['send_trash'] ); break;
			case '2': $bulks = array( 'mark_unread' => $actions['mark_unread'], 'send_trash' => $actions['send_trash'] ); break;
			case '3': $bulks = array( 'delete' => $actions['delete'], 'restore' => $actions['restore'] ); break;
		}
		
		return $bulks;
    }

	/**
	 * Executes the bulk action selected
	 *
	 * @param none
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	function process_bulk_action()
	{
		if( ! empty( $_GET['bulkmenow_message'] ) )
		{
			switch( $this->current_action() )
			{
				case "mark_read": $this->db->move_message( $_GET['bulkmenow_message'], 2 ); $this->ext->set_advice_message( __( "Messages has been marked as read.", "bulk" ), 'success' ); break;
				case "send_trash": $this->db->move_message( $_GET['bulkmenow_message'], 3 ); $this->ext->set_advice_message( __( "Messages has been sent to trash.", "bulk" ), 'success' ); break;
				case "mark_unread": $this->db->move_message( $_GET['bulkmenow_message'], 1 ); $this->ext->set_advice_message( __( "Messages has been marked as unread.", "bulk" ), 'success' ); break;
				case "restore": $this->db->move_message( $_GET['bulkmenow_message'], 2 ); $this->ext->set_advice_message( __( "Messages has been restored.", "bulk" ), 'success' ); break;
				case "delete": $this->db->delete_message( $_GET['bulkmenow_message'] ); $this->ext->set_advice_message( __( "Messages has been deleted.", "bulk" ), 'success' ); break;
			}
		}
		else
		{	// Empty trash is special, so let's put it outside the pack
			if( $this->current_action() == "empty_trash" )
			{
				$this->db->delete_message( NULL, 3 );
				$this->ext->set_advice_message( __( "Trash has been emptied. Hooray!", "bulk" ), 'success' );
			}
		}
	}

	/**
	 * Prepare the query and items to arrange them on the table.
	 * Here we define and filter all the data before passing to the table.
	 *
	 * @param none
	 * @return none
	 * @since 2.0
	 * @author mEtAmorPher
	 */

	function prepare_items()
	{
		global $wpdb;

		//First, lets decide how many records per page to show
		$per_page = ( get_option( 'bulkmenow_list_rows' ) ) ? get_option( 'bulkmenow_list_rows' ) : get_option( 'posts_per_page' );

		/**
		* REQUIRED. Now we need to define our column headers. This includes a complete
		* array of columns to be displayed (slugs & titles), a list of columns
		* to keep hidden, and a list of columns that are sortable. Each of these
		* can be defined in another method (as we've done here) before being
		* used to build the value for our _column_headers property.
		*/
		$columns = $this->get_columns();
		$hidden = array();
		$sortable = $this->get_sortable_columns();

		/**
		* REQUIRED. Finally, we build an array to be used by the class for column 
		* headers. The $this->_column_headers property takes an array which contains
		* 3 other arrays. One for all columns, one for hidden columns, and one
		* for sortable columns.
		*/
		$this->_column_headers = array( $columns, $hidden, $sortable );

		/**
		* Optional. You can handle your bulk actions however you see fit. In this
		* case, we'll handle them within our package just to keep things clean.
		*/
		$this->process_bulk_action();

		/**
		* REQUIRED for pagination. Let's figure out what page the user is currently 
		* looking at. We'll need this later, so you should always include it in 
		* your own package classes.
		*/
		$current_page = $this->get_pagenum();

		//**********************************************************************//
		//**********************************************************************//

		// In a real-world situation, this is where you would place your query. //

		//**********************************************************************//
		//**********************************************************************//
		
		$query = "SELECT * FROM " . $this->tables->messages . " WHERE status = '$this->status'";
		
		if( ! empty( $_GET['s'] ) )
		{
			$query .= " AND ( name LIKE '%" . $_GET['s'] . "%' OR message LIKE '%" . $_GET['s'] . "%' OR email LIKE '%" . $_GET['s'] . "%' )";
		}
		
		/**
		* REQUIRED for pagination. Let's check how many items are in our data array. 
		* I'll put this here because I want the number of items according to the results without the limits yet.
		*/
		$total_items = $wpdb->query( $query );
		
		// WHERE STATUS
		$orderby = ! empty( $_GET["orderby"] ) ? mysql_real_escape_string( $_GET["orderby"] ) : 'ASC';
		$order = ! empty( $_GET["order"] ) ? mysql_real_escape_string( $_GET["order"] ) : '';
		if( ! empty( $orderby ) AND ! empty( $order ) )
		{
			$query .= ' ORDER BY ' . $orderby . ' ' . $order;
		}
		else
		{
			$query .= ' ORDER BY date_sent DESC';
		}
		
		// Adjust the query to take pagination into account
		if( ! empty( $current_page ) AND ! empty( $per_page ) )
		{
			$offset = ( $current_page - 1 ) * $per_page;
			$query .= ' LIMIT ' . (int)$offset . ',' . (int)$per_page;
		}

		// REQUIRED. Now we can add our *sorted* data to the items property, where it can be used by the rest of the class.
		$this->items = $wpdb->get_results( $query, 'ARRAY_A' );

		// REQUIRED. We also have to register our pagination options & calculations.
		$this->set_pagination_args( array(
			'total_items'	=> $total_items,					// We have to calculate the total number of items
			'per_page'		=> $per_page,						// We have to determine how many items to show on a page
			'total_pages'	=> ceil( $total_items / $per_page )	// We have to calculate the total number of pages
		) );
	}
	
	/**
	 * Execute individuals actions from buttons below the items on the row
	 *
	 * @param string $action The action
	 * @param int $message_id The message to update
	 * @return array
	 * @since 2.0
	 * @author mEtAmorPher
	 */
	
	public function execute_individual_actions( $action, $message_id )
	{
		switch( $action )
		{
			default: break;
			case 'view': break;
			case "mark_read": $this->db->move_message( $message_id, 2 ); $this->ext->set_advice_message( __( "Message has been marked as read.", "bulk" ), 'success' ); break;
			case "send_trash": $this->db->move_message( $message_id, 3 ); $this->ext->set_advice_message( __( "Message has been sent to trash.", "bulk" ), 'success' ); break;
			case "mark_unread": $this->db->move_message( $message_id, 1 ); $this->ext->set_advice_message( __( "Message has been marked as unread.", "bulk" ), 'success' ); break;
			case "restore": $this->db->move_message( $message_id, 2 ); $this->ext->set_advice_message( __( "Message has been restored.", "bulk" ), 'success' ); break;
			case "delete": $this->db->delete_message( $message_id ); $this->ext->set_advice_message( __( "Message has been deleted.", "bulk" ), 'success' ); break;
		}
	}
	
	/**
	 * Registers the main view for lists
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

/*
	public function register()
	{
	    $page = add_menu_page(
			__( "Messages", "bulk" ), 
			__( "Messages", "bulk" ), 
			'read',
			'bulkmenow-list', 
			array( &$this, 'render' ) 
		);

		add_action( 'admin_print_scripts-' . $page, array( 'BulkMeNow_Externals', 'load_scripts' ) );
		add_action( 'admin_print_styles-' . $page, array( 'BulkMeNow_Externals', 'load_styles' ) );
	}
*/

	/**
	 * Renders the list view
	 *
	 * @param none
	 * @return void
	 * @since 2.0
	 * @author mEtAmorPher
	 */

/*
	public function render()
	{
		$messages = $this;
		$messages->prepare_items();
		require( dirname( dirname( __FILE__ ) ) . '/views/list.template.php' );
	}
*/

}


function bulkmenow_register_list()
{
	if( BulkMeNow_Externals::current_user_can_see( 'messages' ) )
	{
	    $page = add_menu_page(
			__( "Messages", "bulk" ), 
			__( "Messages", "bulk" ), 
			'read',
			'bulkmenow-list', 
			'bulkmenow_render_list' 
		);
	
		add_action( 'admin_print_scripts-' . $page, array( 'BulkMeNow_Externals', 'load_scripts' ) );
		add_action( 'admin_print_styles-' . $page, array( 'BulkMeNow_Externals', 'load_styles' ) );
	}
}
add_action( 'admin_menu', 'bulkmenow_register_list' );


function bulkmenow_render_list()
{
	$messages = new BulkMeNow_List();
	$messages->prepare_items();
	if( ! BulkMeNow_Externals::current_user_can_see( 'messages' ) ) $messages->ext->set_advice_message( __( "You do NOT have enough permissions to see the message's list. Contact the Administration team for answers.", "bulk" ), "danger" );
	require( dirname( dirname( __FILE__ ) ) . '/views/list.template.php' );
}

?>