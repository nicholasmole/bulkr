<?php
/**
 * Plugin Name: Bulk Redirects
 * Description: Makes redirects for directories
 * Version: 0.1.0
 * Author: Nick Mole
 * Text Domain: bulkr-bulk-redirects
 */

include_once('bulkclass.php');

function load_bulkrsection_assets($adminpage)
{
	//if ($adminpage == 'settings_page_bulkr_unique_slug')
	//{
        wp_enqueue_style( 'bootstyle', plugin_dir_url( __FILE__ ) . 'bootstrap-3.3.4.css', false, '1.0.0' );
        wp_enqueue_style( 'style', plugin_dir_url( __FILE__ ) . 'style.css', false, '1.0.0' );
	    wp_enqueue_style( 'bootstrap-3.3.4', plugin_dir_url( __FILE__ ) . 'bootstrap-3.3.4.js', array(), '1.0.0', true );
	//}
}

add_action( 'admin_enqueue_scripts', 'load_bulkrsection_assets' );

add_action( 'admin_menu', 'bulkr_menu' );

//Add the side menu options for fields
function bulkr_menu() {
	//add_options_page( 'Add User Fields', 'Add User Fields', 'manage_options', 'add_user_fields_unique_slug', 'my_plugin_options' );
	add_options_page( 'BULKR Redirects', 'BULKR Redirects', 'manage_options', 'bulkr_unique_slug', 'my_bulkr_plugin_options' );
}

function get_bulkr_custom(){

    return get_option( 'wpse_bulkr_custom' ); 
    
}

function my_bulkr_plugin_options() {
    $redirects = new BulkRedir;
    //Collect this for post actions - located in UpdateList
    if(isset($_POST)){
        if($_POST['_bulkr_set_redirects']){
           //$error = bulkr_update_value($_POST['action']);
           if($error == 55){ print_r('<large>Cant end on a comma or contain blanks!</large>');}
           if($error == 56){ print_r('cant have bnalks!!!');}
           $the_link = $_POST['action'];
           $redirects->edit($the_link);
        }
        if($_POST['_bulkr_del_redirects']){
            //$error = bulkr_update_value($_POST['action']);
            $custom_id = $_POST['action'];
            //$redirects->edit($the_link);
            $redirects->remove($custom_id);
		   
         }
    }
    

    ?>
    <div class="updated" style="border-color:#fff;">
        <h1>Bulk Redirect Values</h1>
        <p>Here is where you save the wordpress and custom fields used in uufp.</p>
        <small> Warning. Not all fields are compatable yet</small>
            <div class="updated" style="border-color:#fff; background: rgba(105,105,105,0.1) ;font-size: 24px;veritcal-align:middle;">
            
            <table class="table table-striped table-bordered">
            <tbody>
                <div>
                    <tr>
                        <?php //created the standard USer Fields sections to front page ?>
                       

                        
                    </tr>
                    <?php $cur_UUFP_WP = explode( ',', get_bulkr_custom()) ;
                    foreach($redirects->getAll() as $custom_id){

						$fields = $redirects->getFields($custom_id);
                        
                        ?>
                    <tr>
                        <form method="post" action="?page=bulkr_unique_slug">
                            <td>
                                <div class="col-md-4"><?php echo $fields['the_link']; ?> </div>
                            </td>
                            <td>
                                <input  type="hidden" name="_bulkr_del_redirects" value="_bulkr_del_redirects">
                                <input  type="hidden" name="action" value="<?php echo $custom_id; ?>">
                                <div class="col-md-4">
                                    <button href="?page=bulkr_unique_slug" type="submit" class="button button-large alert-btn" value="DeleteUpdate">Delete</button>
                                </div>
                            </td>
                        </form>
                    </tr>
                    <?php } ?>
                    <tr>
                    <form method="post" action="?page=bulkr_unique_slug">
                        <td>
                            <input name="action" value="" >
                            <input type="hidden" name="_bulkr_set_redirects" value="_bulkr_set_redirects">
                        </td>
                        <td>
                            <button href="?page=bulkr_unique_slug" type="submit" class="button button-primary button-large" value="DeleteUpdate">ADD</button>
                        </td>

                        </form>
                    </tr>
            </tbody>
            </table>
    </div>

    <?php
}

//Update the values
function bulkr_update_value($new_value){
    $addition = get_bulkr_custom();
    $cur_UUFP_WP = explode( ',', $new_value) ;
    foreach($cur_UUFP_WP as $uufp) { 
        if($uufp=='') return 55 ; 
        
    }
    //check if not null, is not empty and is 
    if( (!is_null($new_value)) && ($new_value !== '') ){
        $addition = $new_value;
    } else {
        return;
    }
    $addition = $new_value;
    update_option( 'wpse_bulkr_custom', $addition);
}

function bulkr_getUrl_() {
    $url  = @( $_SERVER["HTTPS"] != 'on' ) ? 'http://'.$_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] :  'https://'. $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
    return $url;
}

$siteurl = get_bloginfo('url');

if(!empty(get_option( 'wpse_bulkr_custom' ))): $all_redirectshere = explode( ',', get_bulkr_custom());endif;

$all_redirectshere = $GLOBALS['bulk_redirectsplugins']->getAll();
if($all_redirectshere){
    foreach($all_redirectshere as $redirect_id){
        $la_redirect = $GLOBALS['bulk_redirectsplugins']->getFields($redirect_id);

        if (strpos(bulkr_getUrl_(),$_SERVER["HTTP_HOST"].'/'.$la_redirect['the_link']) !== false){
            header("Location: " . $siteurl , true, 301);
            die();
        }
    }
}


?>