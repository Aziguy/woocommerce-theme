<?php

namespace App;

use Timber\Site;
use Timber\Timber;

/**
 * Class StarterSite
 */
class StarterSite extends Site
{
	public function __construct()
	{
		add_action('after_setup_theme', array($this, 'theme_supports'));
		add_action('init', array($this, 'register_post_types'));
		add_action('init', array($this, 'register_taxonomies'));
		// Register menus on init
        add_action('init', array($this, 'register_my_menus'));
		// Initialize the action hook for enqueueing scripts and styles when the theme is constructed
        add_action('wp_enqueue_scripts', array($this, 'enqueue_theme_assets'));

		add_filter('timber/context', array($this, 'add_to_context'));
		add_filter('timber/twig', array($this, 'add_to_twig'));
		add_filter('timber/twig/environment/options', [$this, 'update_twig_environment_options']);

		parent::__construct();
	}

	/**
	 * This is where you can register custom post types.
	 */
	public function register_post_types()
	{
	}

	/**
	 * This is where you can register custom taxonomies.
	 */
	public function register_taxonomies()
	{
	}

	/** 
	 * Function to register menus 
	 */
    public function register_my_menus() {
        register_nav_menus(
            array(
				'top-menu' => __('Top Menu'),
                'main-menu' => __('Main Menu'),
                'social-menu' => __('Social Menu'),
            )
        );
    }


	/**
	 * This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context($context)
	{
		//$context['breadcrumbs'] = $breadcrumbs;
		$context['custom_wp_title'] = wp_title('', false);
		$context['logo'] = wp_get_attachment_image_src(get_theme_mod('custom_logo'), 'full');
		$context['tagline'] = get_bloginfo('description');

		$context['foo']   = 'bar';
		$context['stuff'] = 'I am a value set in your functions.php file';
		$context['notes'] = 'These values are available everytime you call Timber::context();';
		$context['menu']  = Timber::get_menu();
		$context['site']  = $this;

		return $context;
	}

	public function theme_supports()
	{
		// Add default posts and comments RSS feed links to head.
		add_theme_support('automatic-feed-links');

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support('title-tag');

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support('post-thumbnails');

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support('menus');

		/*
		 * Set the height and width of our logo.
		 *
		 */
		add_theme_support('custom-logo', array(
            'height'      => 83,   // Set the desired height here
            'width'       => 203, // Set the desired width here
            'flex-height' => true,
            'flex-width'  => true,
        ));

		/*
		 * Enable support for Woocommerce.
		 *
		 * See: https://timber.github.io/docs/v2/guides/woocommerce/
		 */
		add_theme_support('woocommerce');

	}


	public function enqueue_theme_assets() {
		// Enqueue CSS file
    	wp_enqueue_style('woo-style', get_template_directory_uri() . '/assets/styles/style.css', array(), '1.0', 'all');
		wp_enqueue_style('woo-responsive', get_template_directory_uri() . '/assets/styles/responsive.css', array(), '1.0', 'all');

    	// Enqueue JavaScript file
		wp_deregister_script('jquery');
		wp_enqueue_script('jquery', get_template_directory_uri() . '/assets/scripts/jquery-2.1.4.js', '2.1.4', true);
		wp_enqueue_script('bootstrap-min', get_template_directory_uri() . '/assets/scripts/bootstrap.min.js', array('jquery'), '3.3.5', true);
		wp_enqueue_script('owl-carousel-min', get_template_directory_uri() . '/assets/scripts/owl.carousel.min.js', array('jquery'), '', true);
		wp_enqueue_script('jquery-ui-min', get_template_directory_uri() . '/assets/scripts/jquery-ui.min.js', array('jquery'), '1.11.4', true);
		wp_enqueue_script('menuzord', get_template_directory_uri() . '/assets/scripts/menuzord.js', array('jquery'), '', true);

		// Enqueue revolution scripts
		wp_enqueue_script('jquery-themepunch-tools', get_template_directory_uri() . '/assets/vendor/revolution/jquery.themepunch.tools.min.js', array('jquery'), '1.0', true);
		wp_enqueue_script('jquery-themepunch-revolution', get_template_directory_uri() . '/assets/vendor/revolution/jquery.themepunch.revolution.min.js', array('jquery'), '5.1.4', true);
		wp_enqueue_script('revolution-extension-slideanims', get_template_directory_uri() . '/assets/vendor/revolution/revolution.extension.slideanims.min.js', array('jquery'), '5.0', true);
		wp_enqueue_script('revolution-extension-layeranimation', get_template_directory_uri() . '/assets/vendor/revolution/revolution.extension.layeranimation.min.js', array('jquery'), '5.0', true);
		wp_enqueue_script('revolution-extension-navigation', get_template_directory_uri() . '/assets/vendor/revolution/revolution.extension.navigation.min.js', array('jquery'), '5.0', true);
		wp_enqueue_script('revolution-extension-kenburn', get_template_directory_uri() . '/assets/vendor/revolution/revolution.extension.kenburn.min.js', array('jquery'), '5.0', true);
		wp_enqueue_script('revolution-extension-actions', get_template_directory_uri() . '/assets/vendor/revolution/revolution.extension.actions.min.js', array('jquery'), '1.1', true);
		wp_enqueue_script('revolution-extension-parallax', get_template_directory_uri() . '/assets/vendor/revolution/revolution.extension.parallax.min.js', array('jquery'), '1.1.1', true);
		wp_enqueue_script('revolution-extension-migration', get_template_directory_uri() . '/assets/vendor/revolution/revolution.extension.migration.min.js', array('jquery'), '1.0.1', true);
		wp_enqueue_script('polyglot-language-switcher', get_template_directory_uri() . '/assets/scripts/jquery.polyglot.language.switcher.js', array('jquery'), '2.2', true);
		wp_enqueue_script('jquery-fancybox-pack', get_template_directory_uri() . '/assets/scripts/jquery.fancybox.pack.js', array('jquery'), '2.1.5', true);
		wp_enqueue_script('jquery-appear', get_template_directory_uri() . '/assets/scripts/jquery.appear.js', array('jquery'), '1.0', true);
		wp_enqueue_script('count-to', get_template_directory_uri() . '/assets/scripts/jquery.countTo.js', array('jquery'), '1.0', true);
		wp_enqueue_script('wow-min', get_template_directory_uri() . '/assets/scripts/wow.min.js', array('jquery'), '1.1.3', true);
		wp_enqueue_script('smooth-scroll', get_template_directory_uri() . '/assets/scripts/SmoothScroll.js', array('jquery'), '1.4.4', true);
		wp_enqueue_script('bootstrap-select-min', get_template_directory_uri() . '/assets/scripts/bootstrap-select.min.js', array('jquery'), '1.11.0', true);
		wp_enqueue_script('jquery-mixitup-min', get_template_directory_uri() . '/assets/scripts/jquery.mixitup.min.js', array('jquery'), '2.1.11', true);
		wp_enqueue_script('theme', get_template_directory_uri() . '/assets/scripts/theme.js', array('jquery'), '', true);

		wp_enqueue_script('html5shiv', get_template_directory_uri() . '/assets/scripts/html5shiv.js', array('jquery'), '3.7.3', true);
		wp_enqueue_script('isotope', get_template_directory_uri() . '/assets/scripts/isotope.js', array('jquery'), '2.1.1', true);
		wp_enqueue_script('isotope-pkgd-min', get_template_directory_uri() . '/assets/scripts/isotope.pkgd.min.js', array('jquery'), '2.1.1', true);
		wp_enqueue_script('jquery-fancybox-media', get_template_directory_uri() . '/assets/scripts/jquery.fancybox-media.js', array('jquery'), '1.0.6', true);
		wp_enqueue_script('style-switcher-min', get_template_directory_uri() . '/assets/scripts/jQuery.style.switcher.min.js', array('jquery'), '2.2', true);
		wp_enqueue_script('social-share', get_template_directory_uri() . '/assets/scripts/social-share.js', array('jquery'), '', true);
		wp_enqueue_script('validate', get_template_directory_uri() . '/assets/scripts/validate.js', array('jquery'), '1.11.0', true);
	

		// Pass data to our JavaScript file
		wp_localize_script('woo-scripts', 'themeData', array(
			'isHomePage' => is_front_page(), // We check if it's the home page
		));
    	

    	// Enqueue Fonts if need
    	//wp_enqueue_style('veg-fonts', get_template_directory_uri() . '/assets/fonts/fonts.css', array(), '1.0', 'all');

        // Enqueue Images (if needed for inline CSS background images, etc.)
        // Note: We typically don't enqueue images, but this is an example if necessary (You can comment this line if don't need)
        //wp_enqueue_style('veg-images', get_template_directory_uri() . '/assets/img/images.css', array(), '1.0', 'all');
    }

	/**
	 * This would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo($text)
	{
		$text .= ' bar!';
		return $text;
	}

	/**
	 * This is where you can add your own functions to twig.
	 *
	 * @param Twig\Environment $twig get extension.
	 */
	public function add_to_twig($twig)
	{
		/**
		 * Required when you want to use Twig’s template_from_string.
		 * @link https://twig.symfony.com/doc/3.x/functions/template_from_string.html
		 */
		// $twig->addExtension( new Twig\Extension\StringLoaderExtension() );

		$twig->addFilter(new \Twig\TwigFilter('myfoo', [$this, 'myfoo']));

		return $twig;
	}

	/**
	 * Updates Twig environment options.
	 *
	 * @link https://twig.symfony.com/doc/2.x/api.html#environment-options
	 *
	 * @param array $options An array of environment options.
	 *
	 * @return array
	 */
	function update_twig_environment_options($options)
	{
		// $options['autoescape'] = true;

		return $options;
	}
}