<?php

/**
 * Filtre AJAX by taxonomy
 */

function vb_filter_posts_mt_actualites()
{
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'webexprnews'))
        die('Permission denied');

    /**
     * Default response
     */
    $response = [
        'status'  => 500,
        'message' => 'Quelque chose ne va pas, veuillez réessayer plus tard ...',
        'content' => false,
        'found'   => 0
    ];

    /**
     * Variables
     */
    $all     = false;
    $terms   = $_POST['params']['terms'];
    $lesnoms  = $_POST['params']['name'];
    $mini   = $_POST['params']['minimum'];
    $maxi   = $_POST['params']['maximum'];
    $post_per_page = $_POST['params']['postperpage'];
    $posts_types = $_POST['params']['poststypes'];
    $posts_types = preg_split("/[\,]+/", $posts_types);
    $recherche   = $_POST['params']['search'];
    $page    = intval($_POST['params']['page']);
    $qty     = intval($_POST['params']['qty']);
    $pager   = isset($_POST['pager']) ? $_POST['pager'] : 'pager';
    $tax_qry = ['relation' => 'AND',];
    $msg     = '';
    $allfilters = '';

    /**
     * Check if terms
     */
    if (!is_array($terms)) :
        $response = [
            'status'  => 501,
            'content' => 0
        ];
        die(json_encode($response));
    else :
        foreach ($terms as $tax => $slugs) :
            if (in_array('all-terms', $slugs)) {
                $all = true;
            }
            $tax_qry[] = [
                'taxonomy' => $tax,
                'field'    => 'slug',
                'terms'    => $slugs,
                'operator' => 'IN',
            ];
        endforeach;
    endif;

    /**
     * Query
     */
    $args = [
        'paged'          => $page,
        'post_type'      => $posts_types,
        'post_status'    => 'publish',
        'posts_per_page' => $post_per_page,
        's'              => $recherche
    ];
    if ($tax_qry && !$all) :
        $args['tax_query'] = $tax_qry;
    endif;

    $date_query[] = [
        'before'    => array(
            'year'  => $maxi,
        ),
        'after'    => array(
            'year'  => $mini,
        ),
        'inclusive' => true,
    ];

    $args['date_query'] = $date_query;

    $qry = new WP_Query($args);
    global $post;
    ob_start();

    /**
     * Post loop
     */

    if ($qry->have_posts()) :
        while ($qry->have_posts()) : $qry->the_post(); ?>
            <?php include 'loop-post.php'; ?>
    <?php endwhile;

        /**
         * Pagination
         */

        if ($pager == 'pager')
            vb_mt_ajax_pager_actualites($qry, $page);

        /**
         * Debug
         */
        foreach ($tax_qry as $tax) :
            $msg .= 'Displaying terms: ';
            if ( is_array($tax) ) :
                if ( array_key_exists('terms',$tax) ) :
                    foreach ($tax['terms'] as $trm) :
                        $msg .= $trm . ', ';
                    endforeach;
                endif;
                if ( array_key_exists('taxonomy',$tax) ) :
                    $msg .= ' from taxonomy: ' . $tax['taxonomy'];
                endif;
            endif;
            $msg .= '. Found: ' . $qry->found_posts . ' posts';
        endforeach;

        foreach ($date_query as $date) :
            if ( is_array($date) && array_key_exists('after',$date) ) :
                $msg .= 'Displaying date: ';
                foreach ($date['after'] as $ladate) :
                    $msg .= $ladate . ', ';
                endforeach;
            endif;
        endforeach;

        foreach ($tax_qry as $tax) :
            if ( is_array($tax) && array_key_exists('terms',$tax) ) :
                foreach ($tax['terms'] as $trm) :
                    $allfilters .= $trm . ', ';
                endforeach;
            endif;
        endforeach;

        $response = [
            'status'  => 200,
            'found'   => $qry->found_posts,
            'message' => $msg,
            'allfilters' => $allfilters,
            'posts_per_page' => $post_per_page,
            'method'  => $pager,
            'next'    => $page + 1
        ];

    else :
        $response = [
            'status'  => 201,
            'message' => 'Aucun article trouvé',
            'next'    => 0
        ];
    endif;
    $response['content'] = ob_get_clean();
    die(json_encode($response));
}
add_action('wp_ajax_do_filter_posts_mt_actualites', 'vb_filter_posts_mt_actualites');
add_action('wp_ajax_nopriv_do_filter_posts_mt_actualites', 'vb_filter_posts_mt_actualites');


/**
 * Shortocde : Filter display + div of posts
 */

function vb_filter_posts_mt_sc_actualites($atts)
{



    $a = shortcode_atts(array(
        'ppp'      => '',
        'post_type'      => '',
        'fsearch'      => '',
        'fdate'      => '',
        'tcr'      => '',
        'tcd'      => '',
        'tlm'      => 'Voir plus',
        'alfs'      => '',
        'titlefilter'      => '',
        'ttlf'      => 'Tout',
        'tpd'      => 'Choisissez...',
        'hideempty' => false,
        'terms'    => false, // Get only specified taxonomies
        'active'   => false, // If taxonomy have post
        'pager'    => 'pager'
    ), $atts);

    $result = NULL;

    $unfiltre = vc_param_group_parse_atts($atts['filtres']);

    ob_start();
?>

    <div id="container-async-news" data-paged="<?= $a['per_page']; ?>" class="" data-ppp="<?= $a['ppp']; ?>" data-postypes="<?= $a['post_type']; ?>">
        <h2>Toutes les actualités</h2>

        <?php // <!-- Filters --> ?>
        <?php include 'all-filters.php'; ?>

        <?php // <!-- Container of post --> ?>
        <div class="content-news col-md-12"></div>

        <?php // <!-- Pagination --> ?>
        <?php if ($a['pager'] == 'infscr') : ?>
            <nav class="pagination infscr-pager col-md-12">
                <a href="#page-2" class="btn-infscr"><?php echo $a['tlm']; ?></a>
            </nav>
        <?php endif; ?>

    </div>

    <?php $result = ob_get_clean();

    return $result;
}
add_shortcode('ajax_filter_posts_mt_actualites', 'vb_filter_posts_mt_sc_actualites');

add_action('vc_before_init', 'ajax_filter_posts_mt_actualitesVC');

/**
 * Pagination
 */
function vb_mt_ajax_pager_actualites($query = null, $paged = 1)
{
    if (!$query)
        return;
    $paginate = paginate_links([
        'base'      => '%_%',
        'type'      => 'array',
        'total'     => $query->max_num_pages,
        'format'    => '#page=%#%',
        'current'   => max(1, $paged),
        'prev_text' => 'Précédent',
        'next_text' => 'Suivant'
    ]);
    if ($query->max_num_pages > 1) : ?>
        <ul class="pagination col-md-12">

            <?php foreach ($paginate as $page) : ?>
                <li><?php echo $page; ?></li>
            <?php endforeach; ?>
        </ul>
<?php endif;
}


function ajax_filter_posts_mt_actualitesVC()
{

    vc_map(array(
        "name" => __("Toutes les actualites", "webexpr"),
        "base" => "ajax_filter_posts_mt_actualites",
        "class" => "",
        "category" => __("WebexpR", "webexpr"),
        "icon" => get_brand_shortcode_icon(),
        "params" => array(
            array(
                "type" => "posttypes",
                "class" => "",
                "heading" => __("Post type", "webexpr"),
                "param_name" => "post_type",
                "value" => __("", "webexpr"),
                "group" => __("Options", "webexpr"),
            ),
            array(
                "type" => "textfield",
                "heading" => __("Posts par page", "webexpr"),
                "param_name" => "ppp",
                "value" => __("", "webexpr"),
                "description" => __("", "webexpr"),
                "group" => __("Options", "webexpr"),
            ),
            array(
                "type" => "checkbox",
                "heading" => __("Afficher les filtres sélectionnés", "webexpr"),
                "param_name" => "alfs",
                "value" => array('Activer' => 'actif'),
                "group" => __("Options", "webexpr"),
            ),
            array(
                "type" => "checkbox",
                "heading" => __("Load more", "webexpr"),
                "param_name" => "pager",
                "value" => array('Activer' => 'infscr'),
                "group" => __("Options", "webexpr"),
            ),
            array(
                "type" => "textfield",
                "heading" => __("Texte load more", "webexpr"),
                "param_name" => "tlm",
                "value" => __("", "webexpr"),
                "description" => __("", "webexpr"),
                "group" => __("Options", "webexpr"),
                "dependency" => array(
                    "element" => "pager",
                    "value" => "infscr"
                )
            ),
            array(
                "type" => "checkbox",
                "heading" => __("Champ recherche", "webexpr"),
                "param_name" => "fsearch",
                "value" => array('Activer' => 'actif'),
                "group" => __("Options", "webexpr"),
            ),
            array(
                "type" => "textfield",
                "heading" => __("Titre champ recherche", "webexpr"),
                "param_name" => "tcr",
                "value" => __("", "webexpr"),
                "description" => __("Ne rien remplir pour désactiver", "webexpr"),
                "group" => __("Options", "webexpr"),
                "dependency" => array(
                    "element" => "fsearch",
                    "value" => "actif"
                )
            ),
            array(
                "type" => "checkbox",
                "heading" => __("Champ date", "webexpr"),
                "param_name" => "fdate",
                "value" => array('Activer' => 'actif'),
                "group" => __("Options", "webexpr"),
            ),
            array(
                "type" => "textfield",
                "heading" => __("Titre champ date", "webexpr"),
                "param_name" => "tcd",
                "value" => __("", "webexpr"),
                "description" => __("", "webexpr"),
                "group" => __("Options", "webexpr"),
                "dependency" => array(
                    "element" => "fdate",
                    "value" => "actif"
                )
            ),
            array(
                'type' => 'param_group',
                'value' => '',
                "heading" => __("Les filtres", "webexpr"),
                'param_name' => 'filtres',
                "group" => __("Filtres", "webexpr"),
                'params' => array(
                    array(
                        "type" => "checkbox",
                        "heading" => __("Type de filtre", "webexpr"),
                        "param_name" => "tdf",
                        "value" => array('Checkbox' => 'checkbox', 'Radio' => 'radio', 'Select' => 'select'),
                    ),
                    array(
                        "type" => "checkbox",
                        "heading" => __("Cacher les catégories vide", "webexpr"),
                        "param_name" => "hideempty",
                        "value" => array('Activer' => 'true'),
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Taxonomy", "webexpr"),
                        "param_name" => "taxo",
                        "value" => __("", "webexpr"),
                        "description" => __("", "webexpr")
                    ),
                    array(
                        "type" => "textfield",
                        "holder" => "div",
                        "class" => "",
                        "heading" => __("Titre du filtre", "webexpr"),
                        "param_name" => "titlefilter",
                        "value" => __("", "webexpr"),
                        "description" => __("", "webexpr")
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Texte par défaut", "webexpr"),
                        "param_name" => "tpd",
                        "value" => __("Choisissez...", "webexpr"),
                        "description" => __("Texte si aucun filtre n'est sélectionné", "webexpr"),
                        "dependency" => array(
                            "element" => "tdf",
                            "value" => "select"
                        )
                    ),
                    array(
                        "type" => "textfield",
                        "heading" => __("Texte tous les filtres", "webexpr"),
                        "param_name" => "ttlf",
                        "value" => __("Tout", "webexpr"),
                        "description" => __("", "webexpr"),
                        "dependency" => array(
                            "element" => "tdf",
                            "value" => "select"
                        )
                    )
                )
            ),
        )

    ));
}


function news_assets()
{
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_script( 'webexpr-news-js', get_shortcode_dir('news/js/filters.js'), ['jquery'], null, true );
    wp_localize_script( 'webexpr-news-js', 'webexprnews', array(
        'nonce'    => wp_create_nonce('webexprnews'),
        'ajax_url' => admin_url('admin-ajax.php')
    ));
    wp_enqueue_style( 'jquery-ui-slider-css', '//cdn.jsdelivr.net/npm/jquery-ui-slider@1.12.1/jquery-ui.min.css', ['theme-style'], '1.12.1' );
}
add_action( 'wp_enqueue_scripts', 'news_assets', 100 );
