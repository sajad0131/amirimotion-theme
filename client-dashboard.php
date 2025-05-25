// front end client panel


// Make sure WP loads jQuery on our front-end dashboard
add_action( 'wp_enqueue_scripts', 'amiri_enqueue_jquery' );
function amiri_enqueue_jquery() {
    if ( is_page( 'dashboard' ) ) {
        wp_enqueue_script( 'jquery' );
    }
}


// 1) point the plugin at our template file
add_filter('pocscd_dashboard_page_template_file', 'amiri_dashboard_template', 10, 2);
function amiri_dashboard_template($template, $dashboard_page_id) {
    // load our custom template in the theme’s /template/ folder
    return get_stylesheet_directory() . '/template-parts/app-dashboard.php';
}

// 2) tell POCS we’ll use a “screen” query-var
add_filter('pocscd_add_page_param', function($params){
    $params[] = 'screen';
    return $params;
}, 5, 1);

// 3) pretty URLs like /dashboard/screen/messages
add_filter('pocscd_create_rewrite_rules','amiri_dashboard_rewrites');
function amiri_dashboard_rewrites(){
    return [
        'screen' => [
            'regex' => '^dashboard/screen/([^/]+)/?$',
            'query' => 'index.php?pagename=dashboard&screen=$matches[1]',
        ],
    ];
}
// only allow access if user is logged in
add_filter('pocscd_lock_page', function(){
    return ! is_user_logged_in();
});




// AJAX handler for sending message
add_action('wp_ajax_amiri_send_message', function(){
    $u   = get_current_user_id();
    $msg = sanitize_text_field($_POST['msg']);
    $post_id = wp_insert_post([
      'post_type'    =>'message',
      'post_title'   =>'msg-'.time(),
      'post_content' =>$msg,
      'post_status'  =>'publish',
      'post_author'  =>$u
    ]);
  
    if($post_id){
       
      // send email to site admin
      $admin_email = get_option('admin_email');
      $subject     = '📩 New message from '. wp_get_current_user()->user_login;
      $body        = "A new message has been posted. View it in wp-admin: "
                   . admin_url("post.php?post={$post_id}&action=edit");
      wp_mail( $admin_email, $subject, $body );        // :contentReference[oaicite:1]{index=1}
      wp_send_json_success();
    }
    wp_send_json_error();
  });

add_action('init', function(){
  $labels = [
    'name'          => 'Messages',
    'singular_name' => 'Message',
    'add_new_item'  => 'Add New Message',
    'edit_item'     => 'Edit Message',
    'parent_item'   => 'Parent Message',
    'parent_item_colon' => 'Parent Message:',
    'menu_name'     => 'Messages',
  ];
  $args = [
    'labels'             => $labels,
    'public'             => false,
    'show_ui'            => true,
    'show_in_menu'       => true,
    'hierarchical'       => true,                // ← enable parent/child :contentReference[oaicite:0]{index=0}
    'supports'           => ['title','editor','author','page-attributes'], 
    'capability_type'    => 'post',
    'capabilities'       => [
      'edit_post'          =>'edit_message',
      'read_post'          =>'read_message',
      'delete_post'        =>'delete_message',
      'edit_posts'         =>'edit_messages',
      'edit_others_posts'  =>'edit_others_messages',
      'publish_posts'      =>'publish_messages',
      'read_private_posts' =>'read_private_messages',
    ],
    'map_meta_cap'       => true,
  ];
  register_post_type('message',$args);
});


// 3a) add the meta box
add_action('add_meta_boxes', function(){
    add_meta_box(
      'message_replies',
      'Replies',
      'render_message_replies_metabox',
      'message',
      'normal',
      'high'
    );
  });
  
  // 3b) render its contents
  function render_message_replies_metabox( $post ){
    // list child messages
    $replies = get_children([
      'post_parent' => $post->ID,
      'post_type'   => 'message',
      'orderby'     => 'date',
      'order'       => 'ASC'
    ]);
  
    echo '<div style="max-height:200px; overflow:auto;">';
    foreach( $replies as $r ){
      $author = get_userdata($r->post_author)->display_name;
      echo "<p><strong>{$author}:</strong> " . esc_html($r->post_content) . "</p><hr>";
    }
    echo '</div>';
  
    // reply form
    ?>
    <textarea id="admin-reply" rows="3" style="width:100%"></textarea>
    <button class="button button-primary" id="send-admin-reply">Send Reply</button>
    <script>
    jQuery('#send-admin-reply').on('click', function(){
      jQuery.post( ajaxurl, {
        action:      'admin_send_reply',
        parent_id:   <?php echo $post->ID ?>,
        reply:       jQuery('#admin-reply').val()
      }, function(r){
        if(r.success) location.reload();
        else alert('Error sending reply');
      });
    });
    </script>
    <?php
  }
  
  // 3c) handle the AJAX reply
  add_action('wp_ajax_admin_send_reply', function(){
    if ( ! current_user_can('edit_messages') ) wp_send_json_error();
    $parent = intval($_POST['parent_id']);
    $msg    = sanitize_text_field($_POST['reply']);
    $reply_id = wp_insert_post([
      'post_type'    => 'message',
      'post_parent'  => $parent,
      'post_content' => $msg,
      'post_status'  => 'publish',
      'post_author'  => get_current_user_id()
    ]);
    if($reply_id){
      // notify the original author
      $orig = get_post($parent);
      $user = get_userdata($orig->post_author);
      if($user){
        wp_mail(
          $user->user_email,
          'Reply to your message',
          "Your message has a new reply. View it: "
          . get_permalink( pocscd_dashboard_page_id() ) . '?screen=messages'
        );
      }
      wp_send_json_success();
    }
    wp_send_json_error();
  });
  add_filter( 'register_post_type_args', function( $args, $post_type ) {
    if ( $post_type === 'message' ) {
        // ensure the admin UI is generated
        $args['show_ui']           = true;                                // 
        // put it as a top-level menu (you can also use 'edit.php?post_type=dashboard' to nest)
        $args['show_in_menu']      = true;                                // 
        // allow it in the admin-bar “+New” menu
        $args['show_in_admin_bar'] = true;                                // 
        // give it its own menu icon (optional)
        $args['menu_icon']         = 'dashicons-email';                  
    }
    return $args;
}, 20, 2 );


// 2a) tell WP to build message-caps instead of post-caps:
add_filter( 'register_post_type_args', function( $args, $post_type ) {
    if ( $post_type === 'message' ) {
        // use a custom capability type so WP creates message-specific caps
        $args['capability_type'] = 'message';
        // map meta capabilities so WP knows how to check them
        $args['map_meta_cap']    = true;                                 // :contentReference[oaicite:0]{index=0}
    }
    return $args;
}, 20, 2 );

// 2b) give those new caps to the Administrator role
add_action( 'admin_init', function() {
    // the caps that WP will now expect on 'message' CPT
    $caps = [
      'edit_message', 'read_message', 'delete_message',
      'edit_messages', 'edit_others_messages', 'publish_messages',
      'read_private_messages', 'delete_messages', 'delete_others_messages',
      'delete_private_messages', 'delete_published_messages',
      'edit_private_messages', 'edit_published_messages'
    ];
    $role = get_role( 'administrator' );
    foreach ( $caps as $cap ) {
        $role->add_cap( $cap );                                        // :contentReference[oaicite:1]{index=1}
    }
});











// 1) add a “Conversations” sub-page under the Messages CPT menu
add_action('admin_menu', function(){
    add_submenu_page(
        'edit.php?post_type=message',   // parent = Messages CPT menu
        'Conversations',                // page <title>
        'Conversations',                // menu label
        'edit_messages',                // capability required
        'message_conversations',        // menu slug
        'render_message_conversations'  // callback to output the page
    );  // :contentReference[oaicite:0]{index=0}
});

// 2) add a “View Chat” action link on each message row
add_filter('post_row_actions', function($actions, $post){
    if($post->post_type==='message'){
        $user_id = $post->post_author;
        $url = add_query_arg([
            'page'    => 'message_conversations',
            'user_id' => $user_id
        ], admin_url('edit.php?post_type=message'));
        $actions['view_chat'] = '<a href="'.esc_url($url).'">View Chat</a>';
    }
    return $actions;
}, 10, 2);  // :contentReference[oaicite:1]{index=1}

// 3) render the Conversations page
function render_message_conversations(){
    if(! current_user_can('edit_messages') ) wp_die('No permission');

    $user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
    echo '<div class="wrap"><h1>Conversations</h1>';

    // — 1a) list authors if none selected
    if( ! $user_id ){
        global $wpdb;
        $admin = get_current_user_id();
    
        // get all authors of top-level messages
        $authors = $wpdb->get_col("
          SELECT DISTINCT post_author
            FROM {$wpdb->posts}
           WHERE post_type='message'
             AND post_parent=0
        ");  // :contentReference[oaicite:0]{index=0}
    
        // build array [ user_id => unread_count ]
        $data = [];
        foreach( $authors as $aid ){
          $last = get_last_read( $admin, $aid );
          $unread = count( get_posts([
             'post_type'      => 'message',
             'post_parent'    => 0,
             'author'         => $aid,
             'date_query'     => [['after'=>$last,'inclusive'=>false]],
             'fields'         => 'ids',
             'posts_per_page' => -1
          ]) );
          $data[ $aid ] = $unread;
        }
    
        // sort by unread desc
        arsort( $data );
    
        echo '<ul>';
        foreach( $data as $aid => $unread ){
          if( $unread === 0 ) {
            $badge = '';
          } else {
            $badge = " <span class='awaiting-mod'>($unread)</span>";
          }
          $u   = get_userdata( $aid );
          $url = add_query_arg(
            ['page'=>'message_conversations','user_id'=>$aid],
            admin_url('edit.php?post_type=message')
          );
          echo '<li><a href="'.esc_url($url).'">'
               . esc_html($u->display_name)
               . $badge
               .'</a></li>';
        }
        echo '</ul>';
        return;
    }
    

    // — 1b) user selected: show their chat
    $user = get_userdata($user_id);
    if(!$user){ echo '<p>Invalid user.</p></div>'; return; }
    echo '<h2>Chat with '.esc_html($user->display_name).'</h2>';

    // fetch top-level + replies
    $threads = get_posts([
      'post_type'      => 'message',
      'post_parent'    => 0,
      'author'         => $user_id,
      'orderby'        => 'date',
      'order'          => 'ASC',
      'posts_per_page' => -1,
    ]);
    $ids     = wp_list_pluck($threads,'ID');
    $replies = get_posts([
      'post_type'        => 'message',
      'post_parent__in'  => $ids,
      'orderby'          => 'date',
      'order'            => 'ASC',
      'posts_per_page'   => -1,
    ]);
    $all = array_merge($threads,$replies);
    usort($all, function($a,$b){ return strcmp($a->post_date,$b->post_date); });

    // mark their top-level unread as read now
    foreach($threads as $t){
      delete_post_meta($t->ID,'unread');
    }




    update_user_meta(
        get_current_user_id(),
        'last_read_messages_for_' . $user_id,
        current_time('mysql')
      );
    // render bubbles
    echo '<div style="max-width:600px;">';
    foreach($all as $m){
        $is_user = $m->post_author == $user_id;
        $who     = $is_user ? 'User' : 'Admin';
        $bg      = $is_user ? '#eef' : '#fee';
        echo "<div style=\"background:{$bg};padding:8px;border-radius:4px;margin:8px 0;\">
                <strong>{$who}:</strong> ".esc_html($m->post_content)."
                <br><small>".esc_html($m->post_date)."</small>
              </div>";
    }
    echo '</div>';

    // reply box (same AJAX you already have)
    $last = end(array_filter($all, fn($m)=>$m->post_author==$user_id));
    $pid  = $last ? $last->ID : 0;
    echo '<h2>Send Reply</h2>
          <textarea id="admin-reply" rows="4" style="width:100%;max-width:600px;"></textarea><br>
          <button class="button button-primary" id="send-admin-reply">Send</button>';
    ?>
    <script>
    jQuery('#send-admin-reply').on('click', function(){
      var r = jQuery('#admin-reply').val().trim();
      if(!r){ alert('Type a reply first.'); return; }
      jQuery.post(ajaxurl,{
        action:     'admin_send_reply',
        parent_id:  <?php echo intval($pid)?>,
        reply:      r
      }, res=>{
        res.success? location.reload() : alert('Error');
      });
    });
    </script>
    <?php

    echo '</div>';
}


  // 4a) show user dropdown on message list
add_action('restrict_manage_posts', function(){
    global $typenow;
    if($typenow !== 'message') return;
    wp_dropdown_users([
      'show_option_all' => 'All Users',
      'name'            => 'author',
      'who'             => 'all',
      'selected'        => isset($_GET['author']) ? intval($_GET['author']) : 0
    ]);
  }); 
  
  // 4b) apply it to the query
  add_action('pre_get_posts', function($q){
    if( is_admin() && $q->get('post_type')==='message' && !empty($_GET['author']) ){
      $q->set('author', intval($_GET['author']));
    }
  });
  




add_action('admin_menu', function(){
    add_submenu_page(
      'edit.php?post_type=message',
      'Conversations',
      'Conversations',
      'edit_messages',
      'message_conversations',
      'render_message_conversations'
    );
}, 20);

// 3) — badge the “Messages” admin menu with total unread
// helper: get last-read timestamp for this admin & user
function get_last_read( $admin_id, $user_id ) {
    $ts = get_user_meta( $admin_id, 'last_read_messages_for_' . $user_id, true );
    return $ts ? $ts : '1970-01-01 00:00:00';
}

// 3a) Admin-menu badge: total unread across all users
add_action('admin_menu', function(){
    global $menu;
    $admin    = get_current_user_id();
    $wpdb     = $GLOBALS['wpdb'];

    // get all authors of top-level messages
    $authors = $wpdb->get_col("
      SELECT DISTINCT post_author
        FROM {$wpdb->posts}
       WHERE post_type='message'
         AND post_parent=0
    ");

    $total = 0;
    foreach( $authors as $aid ) {
      $last = get_last_read( $admin, $aid );
      $cnt  = count( get_posts([
         'post_type'      => 'message',
         'post_parent'    => 0,
         'author'         => $aid,
         'date_query'     => [['after' => $last, 'inclusive'=>false]],
         'fields'         => 'ids',
         'posts_per_page' => -1,
      ]) );
      $total += $cnt;
    }

    if( $total ){
      foreach( $menu as &$m ){
        if( isset($m[2]) && $m[2] === 'edit.php?post_type=message' ){
          $m[0] .= " <span class='awaiting-mod'>($total)</span>";
          break;
        }
      }
    }
}, 999);




// ── DEBUG 1: show total unread top-level messages ──
add_action('admin_notices', function(){
    if ( ! current_user_can('manage_options') ) return;
    $unread = get_posts([
      'post_type'      => 'message',
      'post_parent'    => 0,
      'meta_key'       => 'unread',
      'meta_value'     => '1',
      'fields'         => 'ids',
      'posts_per_page' => -1,
    ]);
    $count = count( $unread );
    echo '<div class="notice notice-info"><p><strong>DEBUG:</strong> Unread top-level messages = '. $count .'</p></div>';
});

// ── DEBUG 2: show the rows we’ll list as “Conversations” ──
add_action('admin_notices', function(){
    if ( ! current_user_can('manage_options') ) return;
    global $wpdb;
    $rows = $wpdb->get_results("
      SELECT p.post_author AS user_id,
             SUM( CASE WHEN pm.meta_key='unread' THEN 1 ELSE 0 END ) AS unread_count
        FROM {$wpdb->posts} p
   LEFT JOIN {$wpdb->postmeta} pm
          ON pm.post_id = p.ID
       WHERE p.post_type='message'
         AND p.post_parent=0
       GROUP BY p.post_author
       ORDER BY unread_count DESC
    ");
    echo '<div class="notice notice-warning"><p><strong>DEBUG:</strong> Conversation rows:</p><pre>';
    foreach($rows as $r){
        echo " user_id={$r->user_id}  unread_count={$r->unread_count}\n";
    }
    echo '</pre></div>';
});

// DEBUG: show us what WP thinks your "message" CPT flags are
add_action('admin_notices', function(){
    if ( ! current_user_can('manage_options') ) return;
    $pt = get_post_type_object('message');
    if ( ! $pt ) {
        echo '<div class="notice notice-error"><p><strong>Debug:</strong> CPT "message" is not registered at all.</p></div>';
    } else {
        echo '<div class="notice notice-info"><p><strong>Debug:</strong> '
            . 'show_ui=' . ($pt->show_ui ? 'true' : 'false') . ', '
            . 'show_in_menu=' . ($pt->show_in_menu ? 'true' : 'false') . ', '
            . 'show_in_admin_bar=' . ($pt->show_in_admin_bar ? 'true' : 'false') 
            . '</p></div>';
    }
});


add_action('admin_init', function(){
    $role = get_role('administrator');
    foreach([
      'edit_message','read_message','delete_message',
      'edit_messages','edit_others_messages',
      'publish_messages','read_private_messages'
    ] as $cap){
      $role->add_cap($cap);
    }
  });





add_action('init', function(){
  $c = new POCSCD_CustomPostType();
  $c->set_post_type_slug('style');
  $c->set_post_type_name(['Style','Styles']);
  $c->register_post_type();
});




// 1a) Register Project CPT
add_action('init', function(){
    $labels = [
      'name'               => 'Projects',
      'singular_name'      => 'Project',
      'add_new_item'       => 'Add New Project',
      'edit_item'          => 'Edit Project',
      'view_item'          => 'View Project',
      'all_items'          => 'All Projects',
      'menu_name'          => 'Projects',
    ];
    $args = [
      'labels'             => $labels,
      'public'             => false,
      'show_ui'            => true,
      'show_in_menu'       => true,
      'capability_type'    => 'post',
      'supports'           => ['title','editor','author','custom-fields'],
      'has_archive'        => false,
      'map_meta_cap'       => true,
    ];
    register_post_type('project',$args);
});

// 1b) Register each custom status
add_action('init', function(){
  $statuses = [
    'reviewing'       => 'Reviewing your order',
    'more_info'       => 'Back to you for more info',
    'editing'         => 'Editing',
    'sample_complete' => 'Sample video completed',
    'waiting_review'  => 'Waiting for your review',
    'completed'       => 'Completed',
  ];
  foreach($statuses as $slug => $label){
    register_post_status( $slug, [
      'label'                     => $label,
      'public'                    => true,
      'show_in_admin_all_list'    => true,
      'show_in_admin_status_list' => true,
      'label_count'               => _n_noop("$label <span class='count'>(%s)</span>", "$label <span class='count'>(%s)</span>"),
    ]);
  }
});



// add status dropdown into the “Publish” box on Project CPT
add_action('add_meta_boxes', function(){
    add_meta_box('project_status','Project Status','render_project_status_metabox','project','side', 'high');
  });
  function render_project_status_metabox( $post ){
    $current = $post->post_status;
    $all     = ['reviewing','more_info','editing','sample_complete','waiting_review','completed'];
    wp_nonce_field('save_project_status','project_status_nonce');
    echo '<select name="project_status">';
    foreach($all as $s){
      $o = get_post_status_object($s);
      printf(
        '<option value="%1$s"%2$s>%3$s</option>',
        esc_attr($s),
        selected($s,$current,false),
        esc_html($o->label)
      );
    }
    echo '</select>';
  }
  add_action('save_post_project', function($post_id){
    if ( ! isset($_POST['project_status_nonce']) || ! wp_verify_nonce($_POST['project_status_nonce'],'save_project_status') ) return;
    if ( isset($_POST['project_status']) ) {
      $new = sanitize_text_field($_POST['project_status']);
      wp_update_post([
        'ID'          => $post_id,
        'post_status' => $new,
      ]);
    }
  });

  
  // 4a) Register Invoice CPT
add_action('init', function(){
    $labels = [ 'name'=>'Invoices','singular_name'=>'Invoice','menu_name'=>'Invoices' ];
    register_post_type('invoice', [
      'labels'=>$labels,
      'public'=>false,
      'show_ui'=>true,
      'supports'=>['title','custom-fields','author'],
      'map_meta_cap'=>true,
    ]);
  });
  
  // 4b) When viewing a Project in admin, add “Create Invoice” button
  add_action('add_meta_boxes_project', function(){
    add_meta_box('project_invoice','Invoice','render_project_invoice_metabox','project','side','default');
  });
  function render_project_invoice_metabox($post){
    $inv = get_posts([ 'post_type'=>'invoice','meta_key'=>'project_id','meta_value'=>$post->ID ]);
    if($inv){
      echo '<p>Invoice already created: <a href="'.get_edit_post_link($inv[0]->ID).'">#'.$inv[0]->ID.'</a></p>';
    } else {
      echo '<form method="post">';
      wp_nonce_field('create_invoice','invoice_nonce');
      echo '<p><label>Amount: <input name="invoice_amount" type="number" step="0.01" required></label></p>';
      echo '<button name="create_invoice" class="button button-primary">Create Invoice</button>';
      echo '</form>';
    }
  }
  add_action('save_post_project', function($post_id){
    if( ! empty($_POST['create_invoice']) && wp_verify_nonce($_POST['invoice_nonce'],'create_invoice') ){
      $amt = floatval($_POST['invoice_amount']);
      $inv_id = wp_insert_post([
        'post_type'=>'invoice',
        'post_title'=> 'Invoice for Project '.$post_id,
        'post_status'=>'publish',
        'post_author'=> get_post_field('post_author',$post_id),
      ]);
      if($inv_id){
        update_post_meta($inv_id,'project_id',$post_id);
        update_post_meta($inv_id,'amount',$amt);
        update_post_meta($inv_id,'paid',0);
      }
    }
  });
  





  add_action( 'template_redirect', 'amiri_handle_frontend_project_submission' );
function amiri_handle_frontend_project_submission() {

    // Only run on our Dashboard page
    if ( ! is_page( 'dashboard' ) ) {
        return;
    }

    // Bail if our form submit button isn't present
    if ( empty( $_POST['create_project'] ) ) {
        return;
    }

    // Verify nonce
    if ( empty( $_POST['create_project_nonce'] )
      || ! wp_verify_nonce( $_POST['create_project_nonce'], 'create_project' )
    ) {
        wp_die( 'Security check failed.' );
    }

    // Collect & sanitize inputs
    $current_user = wp_get_current_user();
    $user_id      = $current_user->ID;
    $plan         = sanitize_text_field( $_POST['plan'] );
    $description  = sanitize_textarea_field( $_POST['description'] );
    $duration     = sanitize_text_field( $_POST['duration'] );

    // Create the Project post
    $title = sprintf( '%s – %s', $plan, current_time( 'Y-m-d H:i' ) );
    $pid   = wp_insert_post([
      'post_type'   => 'project',
      'post_title'  => $title,
      'post_status' => 'reviewing',     // initial custom status slug
      'post_author' => $user_id,
    ]);

    if ( is_wp_error( $pid ) || ! $pid ) {
        error_log( 'Project creation failed: ' . print_r( $pid, true ) );
        wp_die( 'Could not create project. Check debug log.' );
    }

    // Save meta fields
    update_post_meta( $pid, 'plan',        $plan );
    update_post_meta( $pid, 'description', $description );
    update_post_meta( $pid, 'duration',    $duration );

    // Ensure upload & image functions are available
    if ( ! function_exists( 'wp_handle_upload' ) ) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }

    // Process each uploaded file
    if ( ! empty( $_FILES['project_files'] ) ) {
        foreach ( $_FILES['project_files']['name'] as $i => $name ) {
            if ( empty( $name ) ) {
                continue;
            }
            $file = [
              'name'     => $_FILES['project_files']['name'][ $i ],
              'type'     => $_FILES['project_files']['type'][ $i ],
              'tmp_name' => $_FILES['project_files']['tmp_name'][ $i ],
              'error'    => $_FILES['project_files']['error'][ $i ],
              'size'     => $_FILES['project_files']['size'][ $i ],
            ];
            $over = wp_handle_upload( $file, [ 'test_form' => false ] );
            if ( empty( $over['error'] ) ) {
                // Insert attachment
                $att_id = wp_insert_attachment([
                  'post_mime_type' => $over['type'],
                  'post_title'     => sanitize_file_name( $over['file'] ),
                  'post_parent'    => $pid,
                  'post_status'    => 'inherit',
                ], $over['file'] );
                // Generate & save metadata
                $meta = wp_generate_attachment_metadata( $att_id, $over['file'] );
                wp_update_attachment_metadata( $att_id, $meta );
            }
        }
    }

    // Redirect to “Your Projects” (stats) screen
    wp_safe_redirect( add_query_arg( 'screen', 'stats', get_permalink() ) );
    exit;
}



// — 1a) Add a “Project Files” metabox to the Project CPT
add_action('add_meta_boxes', function(){
    add_meta_box(
      'project_files',
      'Project Files',
      'render_project_files_metabox',
      'project',
      'normal',
      'default'
    );
});
function render_project_files_metabox( $post ){
    $files = get_children([
      'post_parent' => $post->ID,
      'post_type'   => 'attachment',
      'orderby'     => 'date',
      'order'       => 'ASC',
    ]);
    if ( $files ) {
      echo '<ul>';
      foreach( $files as $f ){
        $url = wp_get_attachment_url( $f->ID );
        echo '<li><a href="'.esc_url($url).'" target="_blank">'.esc_html($f->post_title).'</a></li>';
      }
      echo '</ul>';
    } else {
      echo '<p><em>No files uploaded for this project.</em></p>';
    }
}
