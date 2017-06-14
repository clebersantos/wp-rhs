<?php 

if(!function_exists('rhs_setup')) : 

    function rhs_setup() {

        if ( ! session_id() ) {
            session_start();
        }

        require_once('inc/custom-status.php');
        require_once('inc/uf-municipio/uf-municipio.php');
        require_once('inc/message/message.php');
        require_once('inc/uf-municipio/uf-municipio.php');
        require_once('inc/rewrite-rules/rewrite-rules.php');
        require_once('inc/captcha/captcha.php');
        require_once('inc/login/login.php');
        require_once('inc/lostpassword/lostpassword.php');
        require_once('inc/user/user.php');
        require_once('inc/perfil/perfil.php');
        require_once('inc/register/register.php');
        require_once('inc/ticket/ticket.php');
        
        require_once('inc/vote/vote.php');
        require_once('inc/vote/widget.php');
        require_once('inc/carrossel/carrossel.php');
        
        // Publicar posts - precisa ser carregado por último
        require_once('inc/post/post.php');

        //// Drupal 7 Password Check
        require_once('inc/drupal-password-check.php');

	    /**
        * Não aparecer o menu do administrador na pagina do site. Mesmo quando estiver logado!
        **/
        show_admin_bar( false );

        //Desabilita o FTP na instalação de Plugins
        define('FS_METHOD', 'direct');
        /**
        * Classe usada nos menus.
        * A mesma facilita o uso das classes usadas na tag nav do bootstrap com o wordpress.
        **/
        require_once('vendor/wp-bootstrap-navwalker.php');

        /**
        *
        * Registro de navegação personalizado com o painel admin
        * 
        **/
        register_nav_menus( array(
            'menuTopo' => __( 'menuTopo', 'rhs' ),
            'menuTopoDropDown' => __( 'menuTopoDropDown', 'rhs' ),
            'menuDropDownMobile' => __( 'menuDropDownMobile', 'rhs' ),
            'menuRodape' => __( 'menuRodape', 'rhs' ),
        ) );

        add_theme_support( 'post-thumbnails' );

        add_theme_support( 'html5', array( 'comment-list', 'comment-form' ) );
        
        add_image_size( 'carrossel', 408, 320, true );
        
    }

endif;

add_action( 'after_setup_theme', 'rhs_setup' );

/* 
* Desabilita os Emojis 
*/
function disable_wp_emojicons() {
  // all actions related to emojis
  remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
}
add_action( 'init', 'disable_wp_emojicons' );

/*
* Alterar 'usuario' para ser o URL base que você deseja usar
*/
function change_author_permalinks()  
{  
    global $wp_rewrite;  
    $wp_rewrite->author_base = 'usuario';
    $wp_rewrite->author_structure = '/' . $wp_rewrite->author_base. '/%author%';  
}  
add_action('init','change_author_permalinks');


/*
* Altera a forma que o wp_is_mobile diferenciam o mobile, ipad e desktop.
*/
function my_wp_is_mobile() {
    if (
        ! empty($_SERVER['HTTP_USER_AGENT'])
        
        //detecta o Ipad.
        && false !== strpos($_SERVER['HTTP_USER_AGENT'], 'iPad')
    ) return false;
    return wp_is_mobile();
}

/* 
* Incluir JavaScripts necessários no tema 
*/
function RHS_scripts() {
    wp_enqueue_script('bootstrap', get_template_directory_uri() . '/vendor/bootstrap/js/bootstrap.min.js', array('jquery'), '3.3.7', true);
    wp_enqueue_script('bootstrap-hover-dropdown', get_template_directory_uri() . '/vendor/js/bootstrap-hover-dropdown.min.js', array('jquery'), '2.2.1', true);

    /*JS Validar Registro*/
    wp_enqueue_script( 'JqueryValidate', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.15.0/jquery.validate.js', array('jquery'), '1.15.0', true );
    wp_enqueue_script('JqueryValidadeMethods', 'https://cdn.jsdelivr.net/jquery.validation/1.16.0/additional-methods.min.js', array('JqueryValidate'), '1.16.0', true );
    wp_enqueue_script('ValidarForm', get_template_directory_uri() . '/assets/js/valida-form-registro.js', array('JqueryValidate'),'1.0', true);

    wp_enqueue_script('FuncoesForm', get_template_directory_uri() . '/assets/js/functions.js', array('JqueryValidate'),'1.0', true);
    wp_enqueue_script('magicJS', get_template_directory_uri() . '/vendor/magicsuggest/magicsuggest-min.js','0.8.0', true);

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) { 
        // enqueue the javascript that performs in-link comment reply fanciness
        wp_enqueue_script( 'comment-reply' ); 
    }

    if (get_query_var('rhs_login_tpl') == RHSRewriteRules::POST_URL) {
        wp_enqueue_media ();
        wp_enqueue_script('PublicarPostagens', get_template_directory_uri() . '/assets/js/publicar_postagens.js','1.0', true);
    }



}
add_action('wp_enqueue_scripts', 'RHS_scripts');

function load_admin_style(){
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/vendor/font-awesome/css/font-awesome.min.css');
}

add_action( 'admin_enqueue_scripts', 'load_admin_style' );

/* 
* Incluir Styles CSS necessários no tema 
*/
function RHS_styles() {
    wp_enqueue_style('bootstrap', get_template_directory_uri() . '/vendor/bootstrap/css/bootstrap.min.css');
    wp_enqueue_style('fontawesome', get_template_directory_uri() . '/vendor/font-awesome/css/font-awesome.min.css');
    wp_enqueue_style('magicCSS', get_template_directory_uri() . '/vendor/magicsuggest/magicsuggest-min.css');
    wp_enqueue_style('style', get_stylesheet_uri(), array('bootstrap'));
}
add_action('wp_enqueue_scripts', 'RHS_styles');

/**
 * Exibir template para comentarios.
 */
if (!function_exists('RHS_Comentarios')) :
    function RHS_Comentarios($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;  
    ?>
    <section id="comment-<?php comment_ID(); ?>">
    <!-- First Comment -->
        <article>

            <figure class="comment-avatar">
              <?php echo get_avatar($comment, 50,'', '', array( 'class' => array( 'img-responsive', 'img-circle' ) ) ); ?>
            </figure>
            
            <header class="comment-box">
                <div class="comment-head">
                    <h6 class="comment-name by-author">Por 
                        <?php 
                            if ($comment->user_id) {
                                $user=get_userdata($comment->user_id);
                                echo '<a href="'.get_author_posts_url($comment->user_id).'">'.$user->display_name.'</a>';
                            } else { 
                                comment_author_link();
                            } 
                        ?>
                    </h6>
                    <time class="comment-date"><?php printf('%s às %s.', get_comment_date(), get_comment_time()); ?></time>
                    <?php comment_reply_link(array('depth' => $depth, 'max_depth' => $args['max_depth'], 'reply_text' => '<i class="fa fa-reply"></i>', 'login_text' => '<i class="fa fa-block"></i>')); ?>
                </div>
                <div class="comment-content">
                    <?php comment_text(); ?>
                </div>
            </header>
        </article>
    </section>
    <?php
}

endif;

/**
*
* Menu que fica no segundo nav da página.
*
* @param 'menu' => 'SegundoMenu' Seleciona o menu com este nome no painel admin.
* @param 'theme_location' => 'SegundoMenu' pega o menu que está setado em SegundoMenu
**/
function menuTopo(){
	wp_nav_menu( array(
        'menu'              => 'menuTopo',
        'theme_location'    => 'menuTopo',
        'depth'             => 0,
        'menu_class'        => 'nav navbar-nav navbar-right',
        'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
        'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
    );
}

/**
*
* Menu dropdown que fica no segundo nav da página
*
* @param 'menu' => 'MenuDropdDown' Seleciona o menu com este nome no painel admin.
* @param 'theme_location' => 'MenuDropdDown' pega o menu que está setado em MenuDropDown
*
**/
function menuTopoDropDown(){
	wp_nav_menu( array(
        'menu'              => 'menuTopoDropDown',
        'theme_location'    => 'menuTopoDropDown',
        'depth'             => 0,
        'container'         => false,
        'menu_class'        => 'dropdown-menu',
        'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
        'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
    );
}

/**
*
* Menu dropdown que fica no segundo nav da página
*
* @param 'menu' => 'MenuDropdDown' Seleciona o menu com este nome no painel admin.
* @param 'theme_location' => 'MenuDropdDown' pega o menu que está setado em MenuDropDown
*
**/
function menuDropDownMobile(){
    wp_nav_menu( array(
        'menu'              => 'menuDropDownMobile',
        'theme_location'    => 'menuDropDownMobile',
        'depth'             => 0,
        'menu_class'        => 'nav navbar-nav mobile-nav',
        'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
        'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
    );
}

/**
*
* Menu que fica no footer da página.
*
* @param 'menu' => 'MenuFundo' Seleciona o menu com este nome no painel admin.
* @param 'theme_location' => 'MenuFundo' pega o menu que está setado em MenuFundo
*
**/
function menuRodape(){
	wp_nav_menu( array(
	    'menu'              => 'menuRodape',
	    'theme_location'    => 'menuRodape',
	    'depth'             => 0,
        'container_class'   => 'col-xs-12',
	    'menu_class'        => 'nav navbar-nav',
	    'fallback_cb'       => 'WP_Bootstrap_Navwalker::fallback',
	    'walker'            => new WP_Bootstrap_Navwalker()) // Classe usada para compor o menu bootstrap com o WP
	);
}

/*
* Função personalizada da paginação.
* A mesma está com as classes do bootstrap
*/
function paginacao_personalizada() {
    global $wp_query;
    $big = 999999999;
    $pages = paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?page=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'mid_size' => 8,
        'prev_next' => false,
        'type' => 'array',
        'prev_next' => TRUE,
        'prev_text' => '&larr; Anterior',
        'next_text' => 'Próxima &rarr;',
    ));
    if (is_array($pages)) {
        $current_page = ( get_query_var('paged') == 0 ) ? 1 : get_query_var('paged');
        echo '<ul class="pagination">';
        foreach ($pages as $i => $page) {
            if ($current_page == 1 && $i == 0) {
                echo "<li class='active'>$page</li>";
            } else {
                if ($current_page != 1 && $current_page == $i) {
                    echo "<li class='active'>$page</li>";
                } else {
                    echo "<li>$page</li>";
                }
            }
        }
        echo '</ul>';
    }
}


/*
* cadastrando Widgets SideBar 
*/
function rhs_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Primary Sidebar', 'rhs' ),
        'id'            => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget'  => '</aside>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'rhs_widgets_init' );

/*
* Deixa ativo o paste_as_text por default 
*/
function change_paste_as_text($mceInit, $editor_id){
    $mceInit['paste_as_text'] = true;
    return $mceInit;
}
add_filter('tiny_mce_before_init', 'change_paste_as_text', 1, 2);

function change_p_for_br($string){

    $string = html_entity_decode($string);
    $string = str_replace('<p>', '',$string);
    $string = str_replace('</p>', '<br /><br />',$string);

    return $string;
}

// Esconde admin dos usuários comuns
/*
add_action('admin_init', 'tnb_so_admin_no_admin');
function tnb_so_admin_no_admin() {
    if ((!defined('DOING_AJAX') || false === DOING_AJAX) && !current_user_can('moderate_comments')) {
        wp_redirect(get_bloginfo('siteurl'));
        exit;
    }
}*/

add_filter( 'wp_terms_checklist_args', 'wpse_139269_term_radio_checklist' );

function wpse_139269_term_radio_checklist( $args ) {
    if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === RHSTicket::TAXONOMY /* <== Change to your required taxonomy */ ) {
        if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) { // Don't override 3rd party walkers.
            if ( ! class_exists( 'WPSE_139269_Walker_Category_Radio_Checklist' ) ) {
                /**
                 * Custom walker for switching checkbox inputs to radio.
                 *
                 * @see Walker_Category_Checklist
                 */
                class WPSE_139269_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
                    function walk( $elements, $max_depth, $args = array() ) {
                        $output = parent::walk( $elements, $max_depth, $args );
                        $output = str_replace(
                            array( 'type="checkbox"', "type='checkbox'" ),
                            array( 'type="radio"', "type='radio'" ),
                            $output
                        );

                        return $output;
                    }
                }
            }

            $args['walker'] = new WPSE_139269_Walker_Category_Radio_Checklist;
        }
    }

    return $args;
}


function wpdocs_custom_excerpt_length( $length ) {
    return 1200;
}
add_filter( 'excerpt_length', 'wpdocs_custom_excerpt_length', 999 );

function limitatexto($texto, $final, $limite){
    $result = $texto;
    $len_texto = strlen($texto);
    $len_final = strlen($final);
    
    if ($len_texto + $len_final > $limite){
        for ($i=$limite-$len_final;$i!==-1;$i--){
            if (substr($texto, $i, 1) == " " && substr($texto, $i-1, 1) !== " "){
                return substr($texto, 0, $i).$final;
                break;
            }
        }
    }
    return $texto;
}