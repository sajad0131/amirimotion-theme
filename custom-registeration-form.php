<?php
/**
 * Plugin Name: Amiri Custom Registration
 * Description: Adds first and last name to registration and emails admin.
 * Version: 1.0
 * Author: Sajad Amiri
 */
// A. Show First & Last Name fields on the wp-login.php?action=register form
add_action( 'register_form', 'amiri_extra_registration_fields' );
function amiri_extra_registration_fields() {
    $first = ( ! empty( $_POST['first_name'] ) ) ? sanitize_text_field( $_POST['first_name'] ) : '';
    $last  = ( ! empty( $_POST['last_name']  ) ) ? sanitize_text_field( $_POST['last_name']  ) : '';
    ?>
    <p>
        <label for="first_name">First Name <strong>*</strong><br/>
            <input type="text" name="first_name" id="first_name" class="input" value="<?php echo esc_attr( $first ); ?>" size="25" />
        </label>
    </p>
    <p>
        <label for="last_name">Last Name <strong>*</strong><br/>
            <input type="text" name="last_name" id="last_name" class="input" value="<?php echo esc_attr( $last ); ?>" size="25" />
        </label>
    </p>
    <?php
}

// B. Validate the new fields
add_filter( 'registration_errors', 'amiri_validate_registration_fields', 10, 3 );
function amiri_validate_registration_fields( $errors, $sanitized_user_login, $user_email ) {
    if ( empty( $_POST['first_name'] ) ) {
        $errors->add( 'first_name_error', __( '<strong>Error</strong>: Please enter your first name.', 'textdomain' ) );
    }
    if ( empty( $_POST['last_name'] ) ) {
        $errors->add( 'last_name_error',  __( '<strong>Error</strong>: Please enter your last name.', 'textdomain'  ) );
    }
    return $errors;
}

// C. Save First & Last Name into user meta when user registers
add_action( 'user_register', 'amiri_save_registration_fields' );
function amiri_save_registration_fields( $user_id ) {
    if ( ! empty( $_POST['first_name'] ) ) {
        update_user_meta( $user_id, 'first_name', sanitize_text_field( $_POST['first_name'] ) );
    }
    if ( ! empty( $_POST['last_name'] ) ) {
        update_user_meta( $user_id, 'last_name', sanitize_text_field( $_POST['last_name'] ) );
    }
}

// D. Access all four values after registration
add_action( 'user_register', 'amiri_after_registration', 20, 1 );
function amiri_after_registration( $user_id ) {
    // Username and email come from core
    $user_info  = get_userdata( $user_id );
    $username   = $user_info->user_login;
    $email      = $user_info->user_email;
    $first_name = get_user_meta( $user_id, 'first_name', true );
    $last_name  = get_user_meta( $user_id, 'last_name',  true );

    // Now you can:
    // – Send yourself an email containing these details
    // – Add them to a custom database table
    // – Integrate with an external CRM
    // Example: send an email to the site admin
    wp_mail( 
        get_option( 'admin_email' ), 
        'New User Registration', 
        "Username: {$username}\nEmail: {$email}\nFirst Name: {$first_name}\nLast Name: {$last_name}"
    );
}