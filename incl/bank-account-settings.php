<?php
/**

*ACCOUNT SETTINGS - devArtist

*@see https://iconicwp.com/blog/the-ultimate-guide-to-adding-custom-woocommerce-user-account-fields/

*/







/**

 * Get additional account fields.

 *

 * @return array

 */

function vendhub_add_account_fields() {

    return apply_filters( 'vendhub_account_fields', array(



        'bank_account_name' => array(

            'type'        => 'text',

           // 'description'       => __('<br>The bank where the sales revenue will be deposited.','vendhub'),

            'label'       => __( 'Bank Name', 'vendhub' ),

            'placeholder' => __( 'E.g. Barclays / HSBC / Citibank / Creditwest', 'vendhub' ),

            'required'    => false,

        ),

        'bank_account_number' => array(

            'type'        => 'text',

           //'description'       => __('<br>Your International Bank Account Number (IBAN) and Bank Identifier Code (BIC) are your account number and sort code written in a standard, internationally recognised format. They help us to process your international payments automatically, making them faster, safer and cheaper.', 'vendhub'),

            'label'       => __( 'IBAN Number', 'vendhub' ),

            'maxlength'         => 20,

            'placeholder' => __( 'E.g. GB15MIDL40051512345678', 'vendhub' ),

            'required'    => false,

        ),



        'bank_account_location' => array(

            'type'        => 'text',

           // 'description'       => __('<br>The Country where the individual or company holds the bank account','vendhub'),

            'label'       => __( 'Bank Account Location', 'vendhub' ),

            'placeholder' => __( 'E.g. Zimbabwe / Cyprus / Botswana / USA', 'vendhub' ),

            'required'    => false,

        ),

        'bank_account_ecocash_number' => array(

            'type'        => 'text',

           // 'description'       => __('<br>Ecocash is a mobile payment platform offered by Econet Wireless only in selected countries','vendhub'),

            'label'       => __( 'Ecocash Number (*only in selected countries)', 'vendhub' ),

            'placeholder' => __( 'E.g. +263 772 000 000', 'vendhub' ),

            'required'    => false,

        ),

        'bank_account_telecash_number' => array(

            'type'        => 'text',

            // 'description'       => __('<br>Telecash is a mobile payment platform offered by Telecel Zimbabwe. only in selected countries','vendhub'),

            'label'       => __( 'Telecash Number (*only in selected countries)', 'vendhub' ),

            'placeholder' => __( 'E.g. +263 733  000 000', 'vendhub' ),

            'required'    => false,

        ),



    ) );

}


/**
 * Get currently editing user ID (frontend account/edit profile/edit other user).
  * @return int
 */
function vendhub_get_edit_user_id() {
    return isset( $_GET['user_id'] ) ? (int) $_GET['user_id'] : get_current_user_id();
}

/**

 * Add fields to registration form and account area.

 */

function vendhub_print_user_bankdetails_fields() {

    $fields = vendhub_add_account_fields();
    $is_user_logged_in = is_user_logged_in();


    foreach ( $fields as $key => $field_args ) {
        $value = null;

        if($is_user_logged_in && !empty($field_args['hide_in_acount'])) {
          continue;
        }

        // if ( ! $is_user_logged_in && ! empty( $field_args['hide_in_registration'] ) ) {
        //     continue;
        // }

        if($is_user_logged_in) {
          $user_id = vendhub_get_edit_user_id();
          $value = get_user_meta($user_id,$key,true);
        }

        $value = isset($field_args['value']) ? $field_args['value'] : $value ;

        woocommerce_form_field( $key, $field_args , $value );

    }

}

//add_action( 'woocommerce_register_form', 'vendhub_print_user_bankdetails_fields', 10 ); // register form

add_action( 'woocommerce_edit_account_form', 'vendhub_print_user_bankdetails_fields', 10 ); // my account





/**

 * Add fields to admin area.

 */

function vendhub_bankdetails_user_admin_fields() {

    $fields = vendhub_add_account_fields();

    ?>

    <h2><?php _e( 'Bank Account Details', 'vendhub' ); ?></h2>

    <table class="form-table" id="iconic-additional-information">

        <tbody>

        <?php foreach ( $fields as $key => $field_args ) { ?>
          <?php
          if ( ! empty( $field_args['hide_in_admin'] ) ) {
              continue;
          }

          $user_id = vendhub_get_edit_user_id();
          $value   = get_user_meta( $user_id, $key, true );
          ?>

            <tr>

                <th>

                    <label for="<?php echo $key; ?>"><?php echo $field_args['label']; ?></label>

                </th>

                <td>

                    <?php $field_args['label'] = false; ?>

                    <?php woocommerce_form_field( $key, $field_args, $value ); ?>

                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>

    <?php

}



add_action( 'show_user_profile', 'vendhub_bankdetails_user_admin_fields', 30 ); // admin: edit profile

add_action( 'edit_user_profile', 'vendhub_bankdetails_user_admin_fields', 30 ); // admin: edit other users





/**

 * Save registration fields.

 *

 * @param int $customer_id

 *

 */

function vendhub_save_account_fields( $customer_id ) {

	$fields = vendhub_add_account_fields();



	foreach ( $fields as $key => $field_args ) {

		$sanitize = isset( $field_args['sanitize'] ) ? $field_args['sanitize'] : 'wc_clean';

		$value    = isset( $_POST[ $key ] ) ? call_user_func( $sanitize, $_POST[ $key ] ) : '';

		update_user_meta( $customer_id, $key, $value );

	}

}



//add_action( 'woocommerce_created_customer', 'vendhub_save_account_fields' ); // register/checkout

add_action( 'personal_options_update', 'vendhub_save_account_fields' ); // edit own account admin

add_action( 'edit_user_profile_update', 'vendhub_save_account_fields' ); // edit other account admin

add_action( 'woocommerce_save_account_details', 'vendhub_save_account_fields' ); // edit WC account


/**
 * To access any custom field above in any part of the site
 */
// $text_field = get_user_meta( $user_id, 'text-field', true );
// echo $text_field;
