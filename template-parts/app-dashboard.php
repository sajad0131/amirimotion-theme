<?php

/**
 * Template Name: client dashboard
 * Description: Displays the client dashboard
 */
// redirect non-logged-in users


global $wp_query;
// restore the dashboard page context so POCS knows where we are:
$wp_query->set('post_type', 'page');
$wp_query->set('page_id', pocscd_dashboard_page_id());

// if POCS says “lock it”, show its lock screen and STOP
if (pocscd_lock_page()) {
  wp_safe_redirect( wp_login_url( get_permalink() ) ); // Redirect to login, then back to dashboard
    exit; // Important to prevent further code execution
}

// otherwise continue to render your dashboard…
do_action('pocscd');
// get current user
$current_user = wp_get_current_user();

// get current screen param
$screen = isset($_GET['screen']) ? sanitize_key($_GET['screen']) : 'home';

// enqueue jQuery if missing
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script('jquery');
});

// handle AJAX send message
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ! empty($_POST['send_message'])) {

  // 1) security check
  if (
    empty($_POST['send_message_nonce'])
    || ! wp_verify_nonce($_POST['send_message_nonce'], 'send_message_form')
  ) {
    wp_die('Nonce check failed.');
  }

  // 2) grab & sanitize
  $msg        = sanitize_textarea_field($_POST['msg']);
  $project_id = intval($_POST['project_id']);
  $user_id    = get_current_user_id();

  // 3) insert the message
  $message_id = wp_insert_post([
    'post_type'    => 'message',
    'post_title'   => 'msg-' . time(),
    'post_content' => $msg,
    'post_status'  => 'publish',
    'post_author'  => $user_id,
  ]);

  if ($message_id && ! is_wp_error($message_id)) {
    // 4) save the project relation
    update_post_meta($message_id, 'project_id', $project_id);
  }

  // 5) redirect back into this project’s chat
  wp_safe_redirect(add_query_arg([
    'screen'     => 'messages',
    'project_id' => $project_id,
  ], get_permalink()));
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
    wp_redirect(add_query_arg('screen', 'files', get_permalink()));
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
    body {
      display: flex;
      font-family: sans-serif;
      margin: 0;
    }

    .sidebar {
      width: 220px;
      background: #111;
      color: #fff;
      min-height: 100vh;
      padding: 20px;
    }

    .sidebar a {
      color: #fff;
      display: block;
      margin: 10px 0;
      text-decoration: none;
    }

    .main {
      flex: 1;
      padding: 30px;
    }

    textarea {
      width: 100%;
      height: 100px;
    }

    .msg {
      margin-bottom: 10px;
      padding: 10px;
      border-radius: 6px;
    }


    ul {
      list-style: none;
      padding: 0;
    }

    li {
      margin: 5px 0;
    }

    header {
      margin-bottom: 20px;
    }

    form {
      margin: 20px 0;
    }
  </style>
</head>

<body <?php body_class(); ?>>
  <div class="sidebar">
    <h2>Dashboard</h2>
    <nav>
      <a href="<?php echo add_query_arg('screen', 'home', get_permalink()); ?>">Home</a>
      <a href="<?php echo add_query_arg('screen', 'messages', get_permalink()); ?>">Messages</a>
      <!-- <a href="<?php // echo add_query_arg('screen', 'files', get_permalink()); ?>">Files</a> -->
      <a href="<?php echo add_query_arg('screen', 'projects', get_permalink()); ?>">New Project</a>
      <a href="<?php echo add_query_arg('screen', 'stats', get_permalink()); ?>">Projects Stats</a>
      <a href="<?php echo add_query_arg('screen', 'services', get_permalink()); ?>">Services & Plans</a>
      <a href="<?php echo add_query_arg('screen', 'payments', get_permalink()); ?>">Invoices</a>
      <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
    </nav>
  </div>

  <div class="main">
    <header>
      <h1><?php echo ucfirst($screen); ?></h1>
    </header>

    <?php if ($screen == 'home') : ?>
    <div class="welcome-banner">
        <h2>Welcome back, <?php echo esc_html($current_user->display_name); ?>!</h2>
        <p>Here's a quick overview of your account.</p>
    </div>

    <div class="dashboard-home-grid">
        <div class="info-card">
            <h3>Active Projects</h3>
            <?php
            $active_projects_count = count(get_posts([
                'post_type'      => 'project',
                'post_status'    => ['reviewing','more_info','editing','sample_complete','waiting_review'], // Active statuses
                'author'         => $current_user->ID,
                'posts_per_page' => -1,
                'fields'         => 'ids' // More efficient
            ]));
            ?>
            <span class="stat"><?php echo esc_html($active_projects_count); ?></span>
            <p>You have <?php echo esc_html($active_projects_count); ?> projects currently in progress.</p>
        </div>

        <div class="info-card">
            <h3>Completed Projects</h3>
            <?php
            $completed_projects_count = count(get_posts([
                'post_type'      => 'project',
                'post_status'    => 'completed',
                'author'         => $current_user->ID,
                'posts_per_page' => -1,
                'fields'         => 'ids'
            ]));
            ?>
            <span class="stat"><?php echo esc_html($completed_projects_count); ?></span>
            <p>A total of <?php echo esc_html($completed_projects_count); ?> projects have been completed.</p>
        </div>
        
        <div class="info-card">
            <h3>Recent Messages</h3>
            <?php
            $recent_messages = get_posts([
                'post_type'   => 'message',
                'author'      => $current_user->ID, // Or messages related to user's projects
                'posts_per_page' => 1,
                'orderby'     => 'date',
                'order'       => 'DESC',
            ]);
            if ($recent_messages) {
                $last_message = $recent_messages[0];
                $project_id_for_message = get_post_meta($last_message->ID, 'project_id', true);
                echo '<p><strong>Last Message:</strong> "' . esc_html(wp_trim_words($last_message->post_content, 10, '...')) . '"';
                if($project_id_for_message){
                     echo ' (Project #'.esc_html($project_id_for_message).')';
                }
                echo '</p>';
                echo '<p><small>Sent on: ' . esc_html(get_the_date('', $last_message->ID)) . '</small></p>';
            } else {
                echo '<p>No recent messages.</p>';
            }
            ?>
        </div>

        <div class="info-card">
            <h3>Unpaid Invoices</h3>
            <?php
            $unpaid_invoices_args = array(
              'post_type'      => 'invoice',
              'author'         => $current_user->ID,
              'posts_per_page' => -1,
              'meta_query'     => array(
                  array(
                      'key'     => '_paid',
                      'value'   => '0', // Assuming '0' means unpaid
                      'compare' => '=',
                  ),
              ),
              'fields' => 'ids', // Only get post IDs to count them
          );
          $unpaid_invoices = get_posts($unpaid_invoices_args);
          $unpaid_count = count($unpaid_invoices);
        ?>
        <span class="stat"><?php echo esc_html($unpaid_count); ?></span>
        <p>You have <?php echo $unpaid_count; ?> unpaid invoice(s). <a href="<?php echo add_query_arg('screen', 'payments', get_permalink()); ?>">View Invoices</a></p>
        </div>
    </div>

    <div class="quick-actions">
        <a href="<?php echo add_query_arg('screen', 'projects', get_permalink()); ?>" class="btn">Start a New Project</a>
        <a href="<?php echo add_query_arg('screen', 'stats', get_permalink()); ?>" class="btn">View All Projects</a>
        <a href="<?php echo add_query_arg('screen', 'payments', get_permalink()); ?>" class="btn">View Invoices</a>
    </div>

    <?php elseif ($screen == 'messages') : ?>
      <div class="dashboard-section messages-section">
        <?php
        $current_project_id = isset($_GET['project_id']) ? intval($_GET['project_id']) : 0;
        $current_client_id = get_current_user_id(); // Client's own ID

        if (!$current_project_id) :
          // Get a list of projects for the current user to select from
          $client_projects = get_posts([
            'post_type'      => 'project',
            'author'         => $current_client_id,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
          ]);
        ?>
          <h2 class="section-title">Select a Project to View Messages</h2>
          <?php if ($client_projects) : ?>
            <ul class="project-selection-list">
              <?php foreach ($client_projects as $project_to_select) : ?>
                <li>
                  <a href="<?php echo esc_url(add_query_arg(['screen' => 'messages', 'project_id' => $project_to_select->ID], get_permalink())); ?>">
                    <?php echo esc_html($project_to_select->post_title); ?>
                  </a>
                </li>
              <?php endforeach; ?>
            </ul>
          <?php else : ?>
            <p class="no-projects">You don't have any projects with messages yet.</p>
          <?php endif; ?>

          <?php else :
          $project_post = get_post($current_project_id);
          // Ensure the current user is the author of the project they are trying to view messages for, or an admin
          if (!$project_post || ($project_post->post_author != $current_client_id && !current_user_can('edit_others_posts'))) {
            echo '<p class="error-message">You do not have permission to view messages for this project, or the project does not exist.</p>';
          } else {
          ?>
            <div class="chat-header">
              <h2 class="section-title">Chat for: <?php echo esc_html($project_post->post_title); ?></h2>
              <a href="<?php echo esc_url(add_query_arg('screen', 'stats', get_permalink())); ?>" class="back-to-projects-link">&larr; Back to Projects</a>
            </div>

            <div class="chat-window-container">
              <div class="chat-window" id="chat-window">
                <?php
                $messages = get_posts([
                  'post_type'   => 'message',
                  'meta_query'  => [[
                    'key'   => 'project_id',
                    'value' => $current_project_id,
                    'type'  => 'NUMERIC',
                  ]],
                  'orderby'     => 'date',
                  'order'       => 'ASC',
                  'posts_per_page' => -1,
                ]);

                if ($messages) {
                  foreach ($messages as $m) {
                    $message_author_id = (int) $m->post_author;
                    $is_client_message = ($message_author_id == $current_client_id);
                    $sender_info = get_userdata($message_author_id);
                    $sender_name = $sender_info ? esc_html($sender_info->display_name) : ($is_client_message ? 'You' : 'Admin');
                    $message_time = human_time_diff(get_post_time('U', true, $m), current_time('timestamp')) . ' ago';
                    // A more precise time:
                    // $message_time = get_the_time( 'M j, Y g:i a', $m );

                    $avatar_url = get_avatar_url($message_author_id, ['size' => 40, 'default' => 'mystery']);

                ?>
                    <div class="chat-message <?php echo $is_client_message ? 'mine' : 'theirs'; ?>">
                      <img src="<?php echo esc_url($avatar_url); ?>" alt="<?php echo esc_attr($sender_name); ?> avatar" class="avatar">
                      <div class="message-content">
                        <div class="message-bubble">
                          <span class="sender-name"><?php echo $sender_name; ?></span>
                          <p><?php echo nl2br(esc_html($m->post_content)); ?></p>
                        </div>
                        <span class="timestamp"><?php echo $message_time; ?></span>
                      </div>
                    </div>
                <?php
                  }
                } else {
                  echo '<p class="no-messages-yet">No messages in this chat yet. Start the conversation!</p>';
                }
                ?>
              </div>
            </div>

            <form id="sendMessageForm" class="send-message-form" method="post">
              <?php wp_nonce_field('send_message_form', 'send_message_nonce'); ?>
              <input type="hidden" name="project_id" value="<?php echo esc_attr($current_project_id); ?>">
              <textarea name="msg" class="message-input" required placeholder="Type your message…"></textarea>
              <button type="submit" name="send_message" value="1" class="send-button">
                <span class="button-text">Send</span>
                <span class="button-icon">&#10148;</span>
              </button>
            </form>
            <script>
              // Auto-scroll to the bottom of the chat window
              document.addEventListener('DOMContentLoaded', function() {
                const chatWindow = document.getElementById('chat-window');
                if (chatWindow) {
                  chatWindow.scrollTop = chatWindow.scrollHeight;
                }
              });
            </script>
        <?php
          } // end permission check
        endif; // end $current_project_id check
        ?>
      </div>



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





                  <a class="cta-modern" href="<?php echo add_query_arg([
                                                'screen' => 'projects',
                                                'plan'   => urlencode($plan->post_title),
                                              ], get_permalink()); ?>"
                    data-plan="<?php echo esc_attr($plan->post_title); ?>">
                    <?php echo esc_html($button_text); ?>
                  </a>
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


                  <a class="cta-modern" href="<?php echo add_query_arg([
                                                'screen' => 'projects',
                                                'plan'   => urlencode($plan->post_title), // Changed get_the_title($plan) to $plan->post_title for consistency
                                              ], get_permalink()); ?>"
                    data-plan="<?php echo esc_attr($plan->post_title); ?>"> <?php // Changed get_the_title($plan) to $plan->post_title 
                                                                            ?>
                    <?php echo esc_html($button_text); ?>
                  </a>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
        </div>
      </section>


    <?php elseif ($screen == 'projects') : ?>
      <h2>Start a New Project</h2>

      <?php
      // Grab the plan name from the URL, and load its durations meta
      $selected_plan = isset($_GET['plan']) ? sanitize_text_field($_GET['plan']) : '';
      $plan_post     = $selected_plan
        ? get_page_by_title($selected_plan, OBJECT, 'pricing_plan')
        : null;
      $price_durations = $plan_post
        ? get_post_meta($plan_post->ID, '_price_durations', true)
        : [];
      ?>

      <form method="post" enctype="multipart/form-data" action="">
        <?php wp_nonce_field('create_project', 'create_project_nonce'); ?>
        <input type="hidden" name="plan" value="<?php echo esc_attr($selected_plan); ?>">

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
              <?php foreach ((array) $price_durations as $pd) : ?>
                <option value="<?php echo esc_attr($pd['duration']); ?>">
                  <?php echo esc_html($pd['duration']); ?>
                </option>
              <?php endforeach; ?>
              <option value="I don’t know">I don’t know</option>
            </select>
          </label>
        </p>

        <button type="submit" name="create_project" value="1">Submit Project</button>
      </form>




    <?php elseif ($screen == 'payments') : ?>
      <h2><?php _e('Invoices & Payments', 'sina-amiri'); ?></h2>
    <?php
    $invoices = get_posts([
      'post_type' => 'invoice',
      'author'    => $current_user->ID,
      'orderby'   => 'date',
      'order'     => 'DESC',
      'posts_per_page' => -1,
    ]);

    if( $invoices ) {
      echo '<ul>';
      foreach($invoices as $inv){
        $invoice_id    = $inv->ID;
        $project_id    = get_post_meta($invoice_id, '_project_id', true);
        $project_title = $project_id ? get_the_title($project_id) : __('General Invoice', 'sina-amiri');
        $amount        = get_post_meta($invoice_id, '_amount', true);
        $is_paid       = get_post_meta($invoice_id, '_paid', true);
        $invoice_date  = get_post_meta($invoice_id, '_invoice_date', true);
        $invoice_content = $inv->post_content; // Get the invoice description/content

        echo '<li>';
        echo '<strong>'. esc_html($inv->post_title) . '</strong>';
        if ($project_id) {
            echo ' ('.__('for project:', 'sina-amiri').' ' . esc_html($project_title) . ')';
        }
        echo '<br>';
        echo __('Invoice ID:', 'sina-amiri').' #'. esc_html($invoice_id) .'<br>';
        echo __('Amount:', 'sina-amiri').' $'. number_format(floatval($amount), 2) .'<br>';
        echo __('Date:', 'sina-amiri').' '. ($invoice_date ? date_i18n(get_option('date_format'), strtotime($invoice_date)) : __('N/A', 'sina-amiri')) .'<br>';
        echo __('Status:', 'sina-amiri').' '. ($is_paid ? '<span style="color:green;">'.__('Paid', 'sina-amiri').'</span>' : '<span style="color:red;">'.__('Unpaid', 'sina-amiri').'</span>');

        if( ! $is_paid ) {
          ?>
          <form method="post" style="display:inline; margin-left: 10px;">
            <?php wp_nonce_field("pay_invoice_{$invoice_id}"); ?>
            <input type="hidden" name="invoice_to_pay_id" value="<?php echo esc_attr($invoice_id); ?>">
            <button type="submit" name="pay_invoice_button" value="pay"><?php _e('Pay Now', 'sina-amiri'); ?></button>
          </form>
          <?php
        }
        // Display the invoice description
        if (!empty($invoice_content)) {
            echo '<div class="invoice-description">' . wpautop(wp_kses_post($invoice_content)) . '</div>';
        }
        echo '</li><hr>';
      }
      echo '</ul>';
    } else {
      echo '<p>'.__('You have no invoices at the moment.', 'sina-amiri').'</p>';
    }

    // Handle payment
    if( $_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['pay_invoice_button']) && isset($_POST['invoice_to_pay_id']) ){
      $invoice_to_pay_id = intval($_POST['invoice_to_pay_id']);
      if( isset($_POST['_wpnonce']) && wp_verify_nonce($_POST["_wpnonce"], "pay_invoice_{$invoice_to_pay_id}") ){
        update_post_meta($invoice_to_pay_id, '_paid', 1);
        echo '<div class="notice notice-success"><p>'.__('Thank you for your payment. The invoice has been marked as paid.', 'sina-amiri').'</p></div>';
        // Refresh to show updated status
        echo "<meta http-equiv='refresh' content='1;url=" . esc_url(add_query_arg('screen', 'payments', get_permalink())) . "'>";
      } else {
        echo '<div class="notice notice-error"><p>'.__('Security check failed or invalid invoice ID. Payment not processed.', 'sina-amiri').'</p></div>';
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