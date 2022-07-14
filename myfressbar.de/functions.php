<?php
/**
* Child theme stylesheet einbinden in Abhängigkeit vom Original-Stylesheet
*/

function child_theme_styles() {
wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
wp_enqueue_style( 'child-theme-css', get_stylesheet_directory_uri() .'/style.css' , array('parent-style'));
}
add_action( 'wp_enqueue_scripts', 'child_theme_styles' );


/**
 * https://www.barf-check.de/kostenloser-barf-rechner/
 */
function calculateFutterplan($gewicht, $faktor) {
	$tagesration = round($gewicht * 1000/100 * $faktor , 1);
	
	$result['tagesration'] = $tagesration;
	
	$tagesRationFleisch = round($tagesration * 0.8 , 1);
	$tagesrationVeggie = round($tagesration - $tagesRationFleisch ,1);
	$result['tagesrationVeggie'] = $tagesrationVeggie;
	$result['tagesRationFleisch'] = $tagesRationFleisch;
	
	
	
	$result['tagesRationVeggieGemuese'] = round($tagesrationVeggie * 0.75 , 1);
	$result['tagesRationVeggieObst'] =  round($tagesrationVeggie * 0.25, 1);
	
	$tagesRationMuskelFleisch = round($tagesRationFleisch * 0.5 ,1);
	$result['tagesRationMuskelFleisch'] = $tagesRationMuskelFleisch;
	$result['tagesRationPansen'] = round($tagesRationFleisch * 0.2 ,1);
	$result['tagesRationInnereien'] = round($tagesRationFleisch * 0.15 ,1);
	$result['tagesRationRfk'] = round($tagesRationFleisch * 0.15 ,1);
	$result['gewicht'] = $gewicht;
	$result['faktor'] = $faktor;
	return $result;
}

// A send custom WebHook
add_action( 'elementor_pro/forms/new_record', function( $record, $handler ) {
    //make sure its our form
    $form_name = $record->get_form_settings( 'form_name' );
    if ( 'Futterberechnung' !== $form_name ) {
        return;
    }
    $raw_fields = $record->get( 'fields' );
    $fields = [];
    foreach ( $raw_fields as $id => $field ) {
        $fields[ $id ] = $field['value'];
    }
    $gewicht = $fields['gewicht'];
    $faktor = $fields['faktor'];
    $output = calculateFutterplan($gewicht, $faktor);
	
    
    $handler->add_response_data( true, $output );
}, 10, 2 );

?>