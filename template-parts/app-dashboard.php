<?php
/**
 * Template Name: client dashboard
 * Description: Displays the client dashboard
 */
// redirect non-logged-in users


global $wp_query;
// restore the dashboard page context so POCS knows where we are:
$wp_query->set('post_type','page');
$wp_query->set('page_id', pocscd_dashboard_page_id());

// if POCS says “lock it”, show its lock screen and STOP
if ( pocscd_lock_page() ) {
    do_action('pocscd_display_lock_page');
    return;
}

// otherwise continue to render your dashboard…
do_action('pocscd');
// get current user
$current_user = wp_get_current_user();

// get current screen param
$screen = isset($_GET['screen']) ? sanitize_key($_GET['screen']) : 'home';

// enqueue jQuery if missing
add_action('wp_enqueue_scripts', function() {
  wp_enqueue_script('jquery');
});

// handle AJAX send message
if ( $_SERVER['REQUEST_METHOD'] === 'POST' && ! empty( $_POST['send_message'] ) ) {

  // 1) security check
  if ( empty( $_POST['send_message_nonce'] ) 
       || ! wp_verify_nonce( $_POST['send_message_nonce'], 'send_message_form' ) 
  ) {
      wp_die( 'Nonce check failed.' );
  }

  // 2) grab & sanitize
  $msg        = sanitize_textarea_field( $_POST['msg'] );
  $project_id = intval( $_POST['project_id'] );
  $user_id    = get_current_user_id();

  // 3) insert the message
  $message_id = wp_insert_post([
      'post_type'    => 'message',
      'post_title'   => 'msg-' . time(),
      'post_content' => $msg,
      'post_status'  => 'publish',
      'post_author'  => $user_id,
  ]);

  if ( $message_id && ! is_wp_error( $message_id ) ) {
      // 4) save the project relation
      update_post_meta( $message_id, 'project_id', $project_id );
  }

  // 5) redirect back into this project’s chat
  wp_safe_redirect( add_query_arg([
      'screen'     => 'messages',
      'project_id' => $project_id,
  ], get_permalink()) );
  exit;
}

// handle file upload
if (!empty($_FILES['file_upload']) && check_admin_referer('file_upload_action')) {
    require_once(ABSPATH . 'wp-admin/includes/file.php');
    $uploaded = wp_handle_upload($_FILES['file_upload'], ['test_form' => false]);
    if (empty($uploaded['error'])) {
        $attachment_id = wp_insert_attachment([
            'post_mime_type' => $uploaded['type'],
            'post_title' => sanitize_file_name($uploaded['file']),
            'post_content' => '',
            'post_status' => 'inherit',
            'post_author' => $current_user->ID,
        ], $uploaded['file']);
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        wp_update_attachment_metadata($attachment_id, wp_generate_attachment_metadata($attachment_id, $uploaded['file']));
        wp_redirect( add_query_arg('screen','files', get_permalink()) );
        exit;
    }
}

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Client Dashboard</title>
  <?php wp_head(); ?>
  <style>
    body { display: flex; font-family: sans-serif; margin:0; }
    .sidebar { width: 220px; background: #111; color: #fff; min-height: 100vh; padding: 20px; }
    .sidebar a { color: #fff; display: block; margin: 10px 0; text-decoration: none; }
    .main { flex:1; padding: 30px; }
    textarea { width:100%; height:100px; }
    .msg { margin-bottom: 10px; padding: 10px; border-radius: 6px; }
    .mine { background: #dcf8c6; text-align: right; }
    .theirs { background: #eee; text-align: left; }
    ul { list-style: none; padding: 0; }
    li { margin: 5px 0; }
    header { margin-bottom: 20px; }
    form { margin: 20px 0; }
  </style>
</head>
<body <?php body_class(); ?>>
<div class="sidebar">
  <h2>Dashboard</h2>
  <nav>
    <a href="<?php echo add_query_arg('screen', 'home', get_permalink()); ?>">Home</a>
    <a href="<?php echo add_query_arg('screen', 'messages', get_permalink()); ?>">Messages</a>
    <a href="<?php echo add_query_arg('screen', 'files', get_permalink()); ?>">Files</a>
    <a href="<?php echo add_query_arg('screen','projects',get_permalink()); ?>">New Project</a>
    <a href="<?php echo add_query_arg('screen', 'stats', get_permalink()); ?>">Projects Stats</a>
    <a href="<?php echo add_query_arg('screen', 'services', get_permalink()); ?>">Services & Plans</a>
    <a href="<?php echo add_query_arg('screen', 'payments', get_permalink()); ?>">Invoices</a>
    <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
  </nav>
</div>

<div class="main">
  <header><h1><?php echo ucfirst($screen); ?></h1></header>

  <?php if ($screen == 'home') : ?>
    <p>Welcome back, <?php echo esc_html($current_user->display_name); ?>!</p>

    <?php elseif ( $screen == 'messages' ) :

// 2a) get the project_id from the URL
$current_project = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;

if ( ! $current_project ) {
  echo '<p><em>No project selected. Go back and click “View Chat” on a project.</em></p>';
} else {
  echo '<h2>Chat for Project #'.esc_html($current_project).'</h2>';

  // 2b) fetch only messages for this project
  $messages = get_posts([
    'post_type'   => 'message',
    'meta_query'  => [[
       'key'   => 'project_id',
       'value' => $current_project,
       'type'  => 'NUMERIC',
    ]],
    'orderby'     => 'date',
    'order'       => 'ASC',
    'posts_per_page' => -1,
  ]);

  echo '<div class="chat-window">';
  foreach( $messages as $m ){
    $message_author_id = (int) $m->post_author; // Cast author ID to integer
    $current_client_id = (int) get_current_user_id(); // Cast current user ID to integer

    if ($message_author_id == $current_client_id) { // Use == for robust comparison
        // $who = 'You'; // Simple label
        // Or, for more specific naming:
        $current_user_info = get_userdata($current_client_id);
        $who = $current_user_info ? esc_html($current_user_info->display_name) : 'You';

    } else {
        // Message is from someone else (assumed Admin)
        $author_info = get_userdata($message_author_id);
        $who = $author_info ? esc_html($author_info->display_name) : 'Admin'; // Display Admin's name or "Admin"
    }
    // Use existing CSS classes for styling if available
    $msg_class = ($message_author_id == $current_client_id) ? 'mine' : 'theirs';
    echo '<p class="msg ' . $msg_class . '"><strong>'.esc_html($who).':</strong> '.esc_html($m->post_content).'</p>';
  }
  echo '</div>';

  // 2c) render the send form, including hidden project_id
  ?>
  <form id="sendMessage" method="post">
  <?php wp_nonce_field( 'send_message_form', 'send_message_nonce' ); ?>
    <input type="hidden" name="project_id" value="<?php echo esc_attr($current_project); ?>">
    <textarea name="msg" required placeholder="Type your message…"></textarea><br>
    <button type="submit" name="send_message" value="1">Send</button>
  </form>
  <?php
}
?>



  <?php elseif ($screen == 'files') : ?>
    <h2>Upload File</h2>
    <form method="post" enctype="multipart/form-data">
      <?php wp_nonce_field('file_upload_action'); ?>
      <input type="file" name="file_upload" required>
      <button type="submit">Upload</button>
    </form>

    <h2>Your Files</h2>
    <ul>
      <?php
      $files = get_posts([
        'post_type' => 'attachment',
        'author' => $current_user->ID,
        'posts_per_page' => 20
      ]);
      foreach ($files as $file) {
        echo '<li><a href="' . esc_url(wp_get_attachment_url($file->ID)) . '" download>' . esc_html($file->post_title) . '</a></li>';
      }
      ?>
    </ul>

    <?php elseif ($screen == 'stats') : ?>
    <div class="dashboard-section project-stats">
        <h2 class="section-title">Your Projects</h2>
        <?php
        $projects = get_posts([
            'post_type'      => 'project',
            'post_status'    => ['reviewing', 'more_info', 'editing', 'sample_complete', 'waiting_review', 'completed'],
            'author'         => $current_user->ID,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ]);

        if ($projects) : ?>
            <div class="project-list">
                <?php
                // Define a color map for statuses for visual consistency
                $status_colors = [
                    'reviewing'       => '#3498db', // Blue
                    'more_info'       => '#f1c40f', // Yellow
                    'editing'         => '#e67e22', // Orange
                    'sample_complete' => '#1abc9c', // Turquoise
                    'waiting_review'  => '#9b59b6', // Purple
                    'completed'       => '#2ecc71', // Green
                    'default'         => '#7f8c8d'  // Grey for any other status
                ];

                // Define order or progress for statuses (0 to 100)
                $status_progress = [
                    'reviewing'       => 15,
                    'more_info'       => 30,
                    'editing'         => 50,
                    'sample_complete' => 75,
                    'waiting_review'  => 85,
                    'completed'       => 100,
                ];

                foreach ($projects as $p) :
                    $project_status_slug = get_post_status($p->ID);
                    $project_status_object = get_post_status_object($project_status_slug);
                    $status_label = $project_status_object ? esc_html($project_status_object->label) : 'N/A';
                    $status_color = $status_colors[$project_status_slug] ?? $status_colors['default'];
                    $progress_value = $status_progress[$project_status_slug] ?? 0;
                ?>
                    <div class="project-item">
                        <div class="project-header">
                            <h3 class="project-title"><?php echo esc_html($p->post_title); ?></h3>
                            <span class="project-status-label" style="background-color: <?php echo $status_color; ?>;">
                                <?php echo $status_label; ?>
                            </span>
                        </div>
                        <div class="project-details">
                            <div class="progress-bar-container">
                                <div class="progress-bar" style="width: <?php echo $progress_value; ?>%; background-color: <?php echo $status_color; ?>;">
                                    <?php echo $progress_value; ?>%
                                </div>
                            </div>
                            <div class="project-actions">
                                <a href="<?php echo esc_url(add_query_arg(['screen' => 'messages', 'project_id' => $p->ID], get_permalink())); ?>" class="action-link view-chat-link">
                                    <span class="icon">&#128172;</span> View Chat
                                </a>
                                </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else : ?>
            <p class="no-projects">You don't have any projects yet. <a href="<?php echo esc_url(add_query_arg('screen', 'projects', get_permalink())); ?>">Start a new project?</a></p>
        <?php endif; ?>
    </div>
    </ul>

  <?php elseif ($screen == 'services') : ?>
    <h2>Available Services</h2>
    <section class="pricing-section">
    <div class="container" style="display:contents;">
        <!-- Video Editing Plans -->
        <div class="pricing-type-group" data-plan-type="video-editing">
            <h3 class="plan-type-title">Video Editing Packages</h3>
            <div class="pricing-grid">
                <?php 
                $video_editing_plans = get_posts(array(
                    'post_type' => 'pricing_plan',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'plan_type',
                            'field' => 'slug',
                            'terms' => 'video-editing'
                        )
                    )
                ));

                foreach ($video_editing_plans as $plan) : 
                    $price_durations = get_post_meta($plan->ID, '_price_durations', true);
                    $features = get_post_meta($plan->ID, '_features', true);
                    $button_text = get_post_meta($plan->ID, '_button_text', true);
                    $youtube_example = get_post_meta($plan->ID, '_youtube_example_id', true);
                    $drive_example = get_post_meta($plan->ID, '_drive_example_id', true);
                ?>
                <div class="pricing-card" 
                     data-durations="<?php echo esc_attr(wp_json_encode($price_durations)); ?>"
                     data-youtube-example-id="<?php echo esc_attr($youtube_example); ?>"
                     data-drive-example-id="<?php echo esc_attr($drive_example); ?>">
                    <h2><?php echo esc_html($plan->post_title); ?></h2>
                    <?php if ($youtube_example || $drive_example) : ?>
                        <button class="example-video-button">
                            View Sample
                        </button>
                    <?php endif; ?>
                    <ul class="plan-features">
                        <h3>Benefits</h3>
                        <?php foreach (explode("\n", $features) as $feature) : ?>
                            <?php if (!empty(trim($feature))) : ?>
                            <li><?php echo esc_html($feature); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="price-duration-list">
                        <h3>Price</h3>
                        <?php foreach ($price_durations as $pd) : ?>
                            <div class="price-duration-item">
                                <span class="duration"><?php echo esc_html($pd['duration']); ?></span>
                                <span class="price"><?php echo esc_html($pd['price']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    

                    

                    <button class="cta-modern open-form" 
                            data-plan="<?php echo esc_attr($plan->post_title); ?>">
                        <?php echo esc_html($button_text); ?>
                    </button>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Motion Graphics Plans -->
        <div class="pricing-type-group" data-plan-type="motion-graphics">
            <h3 class="plan-type-title">Motion Graphics Packages</h3>
            <div class="pricing-grid">
                <?php 
                $motion_graphics_plans = get_posts(array(
                    'post_type' => 'pricing_plan',
                    'posts_per_page' => -1,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'plan_type',
                            'field' => 'slug',
                            'terms' => 'motion-graphics'
                        )
                    )
                ));

                foreach ($motion_graphics_plans as $plan) : 
                    $price_durations = get_post_meta($plan->ID, '_price_durations', true);
                    $features = get_post_meta($plan->ID, '_features', true);
                    $button_text = get_post_meta($plan->ID, '_button_text', true);
                    $youtube_example = get_post_meta($plan->ID, '_youtube_example_id', true);
                    $drive_example = get_post_meta($plan->ID, '_drive_example_id', true);
                ?>
                <div class="pricing-card" 
                     data-durations="<?php echo esc_attr(wp_json_encode($price_durations)); ?>"
                     data-youtube-example-id="<?php echo esc_attr($youtube_example); ?>"
                     data-drive-example-id="<?php echo esc_attr($drive_example); ?>">
                    <h2><?php echo esc_html($plan->post_title); ?></h2>
                    <?php if ($youtube_example || $drive_example) : ?>
                        <button class="example-video-button">
                            View Examples
                        </button>
                    <?php endif; ?>

                    <ul class="plan-features">
                        <h3>Benefits</h3>
                        <?php foreach (explode("\n", $features) as $feature) : ?>
                            <?php if (!empty(trim($feature))) : ?>
                            <li><?php echo esc_html($feature); ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <h3>Price</h3>
                    <div class="price-duration-list">
                        
                        <?php foreach ($price_durations as $pd) : ?>
                            <div class="price-duration-item">
                                <span class="duration"><?php echo esc_html($pd['duration']); ?></span>
                                <span class="price"><?php echo esc_html($pd['price']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    
                    <button style="display:none" class="cta-modern open-form"  href="<?php echo add_query_arg([
     'screen' => 'projects',
     'plan'   => urlencode( get_the_title($plan) ),
   ], get_permalink()); ?>"
                            data-plan="<?php echo esc_attr(get_the_title($plan)); ?>">
                        <?php echo esc_html($button_text); ?>
  </button>
                    

                    <a class="cta-modern open-form"  href="<?php echo add_query_arg([
     'screen' => 'projects',
     'plan'   => urlencode( get_the_title($plan) ),
   ], get_permalink()); ?>"
                            data-plan="<?php echo esc_attr(get_the_title($plan)); ?>">
                        <?php echo esc_html($button_text); ?>
                        </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</section>


<?php elseif ( $screen == 'projects' ) : ?>
  <h2>Start a New Project</h2>

  <?php
    // Grab the plan name from the URL, and load its durations meta
    $selected_plan = isset( $_GET['plan'] ) ? sanitize_text_field( $_GET['plan'] ) : '';
    $plan_post     = $selected_plan
        ? get_page_by_title( $selected_plan, OBJECT, 'pricing_plan' )
        : null;
    $price_durations = $plan_post
        ? get_post_meta( $plan_post->ID, '_price_durations', true )
        : [];
  ?>

  <form method="post" enctype="multipart/form-data" action="">
    <?php wp_nonce_field( 'create_project', 'create_project_nonce' ); ?>
    <input type="hidden" name="plan" value="<?php echo esc_attr( $selected_plan ); ?>">

    <p>
      <label>Project Description:<br>
      <textarea name="description" required rows="4" style="width:100%"></textarea>
      </label>
    </p>

    <p>
      <label>Upload Files:<br>
      <input type="file" name="project_files[]" multiple>
      </label>
    </p>

    <p>
      <label>Preferred Duration:<br>
      <select name="duration" required>
        <?php foreach ( (array) $price_durations as $pd ) : ?>
          <option value="<?php echo esc_attr( $pd['duration'] ); ?>">
            <?php echo esc_html( $pd['duration'] ); ?>
          </option>
        <?php endforeach; ?>
        <option value="I don’t know">I don’t know</option>
      </select>
      </label>
    </p>

    <button type="submit" name="create_project" value="1">Submit Project</button>
  </form>


  

  <?php elseif ($screen == 'payments') : ?>
    <h2>Payments</h2>
    <?php
    $invoices = get_posts([
      'post_type'=>'invoice',
      'author'   => $current_user->ID,
      'orderby'  => 'date',
      'order'    => 'DESC',
    ]);
    if( $invoices ) {
      echo '<ul>';
      foreach($invoices as $inv){
        $amt  = get_post_meta($inv->ID,'amount',true);
        $paid = get_post_meta($inv->ID,'paid',true);
        echo '<li>Invoice #'. $inv->ID
           . ' – $'. number_format($amt,2)
           . ' – '. ($paid ? 'Paid' : 'Unpaid');
        if( ! $paid ) :
          ?>
          <form method="post" style="display:inline">
            <?php wp_nonce_field("pay_invoice_{$inv->ID}"); ?>
            <button name="pay_invoice" value="<?php echo $inv->ID; ?>">Pay Now</button>
          </form>
          <?php
        endif;
        echo '</li>';
      }
      echo '</ul>';
    } else {
      echo '<p>No invoices yet.</p>';
    }

    // handle payment
    if( $_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['pay_invoice']) ){
      $iid = intval($_POST['pay_invoice']);
      if( wp_verify_nonce($_POST["_wpnonce"],"pay_invoice_{$iid}") ){
        update_post_meta($iid,'paid',1);
        // optionally credit project or send email…
        echo '<p>Thank you — invoice paid.</p>';
      }
    }
  ?>

  <?php else: ?>
    <p>Page not found!</p>

  <?php endif; ?>

</div>

<?php wp_footer(); ?>
</body>
</html>