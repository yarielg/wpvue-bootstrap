<?php

function wrnb_upload_file( $file, $post_id = 0, $desc = null ) {
	if( empty( $file['name'] ) ) {
		return new \WP_Error( 'error', 'File is empty' );
	}


	// Get filename and store it into $file_array
	preg_match( '/[^\?]+\.(jpe?g|jpe|gif|png)\b/i', $file, $matches );


	// If error storing temporarily, return the error.
	if ( is_wp_error( $file['tmp_name'] ) ) {
		return new \WP_Error( 'error', 'Error while storing file temporarily' );
	}

	// Store and validate
	$id = media_handle_sideload( $file, $post_id, $desc );

	// Unlink if couldn't store permanently
	if ( is_wp_error( $id ) ) {
		unlink( $file['tmp_name'] );
		return new \WP_Error( 'error', "Couldn't store upload permanently" );
	}

	if ( empty( $id ) ) {
		return new \WP_Error( 'error', "Upload ID is empty" );
	}

	return $id;
}
