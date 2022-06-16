<?php
/** 
Plugin Name: Ultimate Anime Scraper
Plugin URI: //1.envato.market/coderevolution
Description: This plugin will scrape anime for you, day and night
Author: CodeRevolution
Version: 1.1.1
Author URI: //coderevolution.ro
License: Commercial. For personal use only. Not to give away or resell.
Text Domain: ultimate-anime-scraper
*/
/*
Copyright 2016 - 2022 CodeRevolution
*/
defined('ABSPATH') or die();
require_once (dirname(__FILE__) . "/res/other/plugin-dash.php"); 

function anime_load_textdomain() {
    if(!function_exists('ot_get_option'))
    {
        function ot_get_option($nname)
        {
            return get_option($nname);
        }
    }
    load_plugin_textdomain( 'ultimate-anime-scraper', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
}
add_action( 'init', 'anime_load_textdomain' );
use Aws\S3\S3Client;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Psr7\CachingStream;
$language_names = array(
    esc_html__("Disabled", 'ultimate-anime-scraper'),
    esc_html__("Afrikaans (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Albanian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Arabic (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Amharic (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Armenian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Belarusian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Bulgarian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Catalan (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Chinese Simplified (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Croatian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Czech (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Danish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Dutch (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("English (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Estonian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Filipino (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Finnish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("French (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Galician (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("German (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Greek (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Hebrew (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Hindi (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Hungarian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Icelandic (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Indonesian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Irish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Italian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Japanese (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Korean (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Latvian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Lithuanian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Norwegian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Macedonian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Malay (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Maltese (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Persian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Polish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Portuguese (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Romanian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Russian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Serbian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Slovak (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Slovenian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Spanish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Swahili (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Swedish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Thai (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Turkish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Ukrainian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Vietnamese (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Welsh (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Yiddish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Tamil (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Azerbaijani (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Kannada (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Basque (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Bengali (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Latin (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Chinese Traditional (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Esperanto (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Georgian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Telugu (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Gujarati (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Haitian Creole (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Urdu (Google Translate)", 'ultimate-anime-scraper'),
    
    esc_html__("Burmese (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Bosnian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Cebuano (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Chichewa (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Corsican (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Frisian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Scottish Gaelic (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Hausa (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Hawaian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Hmong (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Igbo (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Javanese (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Kazakh (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Khmer (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Kurdish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Kyrgyz (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Lao (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Luxembourgish (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Malagasy (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Malayalam (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Maori (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Marathi (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Mongolian (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Nepali (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Pashto (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Punjabi (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Samoan (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Sesotho (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Shona (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Sindhi (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Sinhala (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Somali (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Sundanese (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Swahili (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Tajik (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Uzbek (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Xhosa (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Yoruba (Google Translate)", 'ultimate-anime-scraper'),
    esc_html__("Zulu (Google Translate)", 'ultimate-anime-scraper')
);
$language_codes = array(
    "disabled",
    "af",
    "sq",
    "ar",
    "am",
    "hy",
    "be",
    "bg",
    "ca",
    "zh-CN",
    "hr",
    "cs",
    "da",
    "nl",
    "en",
    "et",
    "tl",
    "fi",
    "fr",
    "gl",
    "de",
    "el",
    "iw",
    "hi",
    "hu",
    "is",
    "id",
    "ga",
    "it",
    "ja",
    "ko",
    "lv",
    "lt",
    "no",
    "mk",
    "ms",
    "mt",
    "fa",
    "pl",
    "pt",
    "ro",
    "ru",
    "sr",
    "sk",
    "sl",
    "es",
    "sw",
    "sv",   
    "th",
    "tr",
    "uk",
    "vi",
    "cy",
    "yi",
    "ta",
    "az",
    "kn",
    "eu",
    "bn",
    "la",
    "zh-TW",
    "eo",
    "ka",
    "te",
    "gu",
    "ht",
    "ur",
    
    "my",
    "bs",
    "ceb",
    "ny",
    "co",
    "fy",
    "gd",
    "ha",
    "haw",
    "hmn",
    "ig",
    "jw",
    "kk",
    "km",
    "ku",
    "ky",
    "lo",
    "lb",
    "mg",
    "ml",
    "mi",
    "mr",
    "mn",
    "ne",
    "ps",
    "pa",
    "sm",
    "st",
    "sn",
    "sd",
    "si",
    "so",
    "su",
    "sw",
    "tg",
    "uz",
    "xh",
    "yo",
    "zu"
);
$language_names_deepl = array(
 "English (DeepL)",
 "German (DeepL)",
 "French (DeepL)",
 "Spanish (DeepL)",
 "Italian (DeepL)",
 "Dutch (DeepL)",
 "Polish (DeepL)",
 "Russian (DeepL)",
 "Portuguese (DeepL)",
 "Chinese (DeepL)",
 "Japanese (DeepL)",
 "Bulgarian (DeepL)",
 "Czech (DeepL)",
 "Danish (DeepL)",
 "Greek (DeepL)",
 "Estonian (DeepL)",
 "Finnish (DeepL)",
 "Hungarian (DeepL)",
 "Lithuanian (DeepL)",
 "Latvian (DeepL)",
 "Romanian (DeepL)",
 "Slovak (DeepL)",
 "Slovenian (DeepL)",
 "Swedish (DeepL)"
 );
 $language_codes_deepl = array(
     "EN-",
     "DE-",
     "FR-",
     "ES-",
     "IT-",
     "NL-",
     "PL-",
     "RU-",
     "PT-",
     "ZH-",
     "JA-",
     "BG-",
     "CS-",
     "DA-",
     "EL-",
     "ET-",
     "FI-",
     "HU-",
     "LT-",
     "LV-",
     "RO-",
     "SK-",
     "SL-",
     "SV-"
 );
 $language_names_bing = array(
  "English (Microsoft Translator)",
  "Arabic (Microsoft Translator)",
  "Bosnian (Latin) (Microsoft Translator)",
  "Bulgarian (Microsoft Translator)",
  "Catalan (Microsoft Translator)",
  "Chinese Simplified (Microsoft Translator)",
  "Chinese Traditional (Microsoft Translator)",
  "Croatian (Microsoft Translator)",
  "Czech (Microsoft Translator)",
  "Danish (Microsoft Translator)",
  "Dutch (Microsoft Translator)",
  "Estonian (Microsoft Translator)",
  "Finnish (Microsoft Translator)",
  "French (Microsoft Translator)",
  "German (Microsoft Translator)",
  "Greek (Microsoft Translator)",
  "Haitian Creole (Microsoft Translator)",
  "Hebrew (Microsoft Translator)",
  "Hindi (Microsoft Translator)",
  "Hmong Daw (Microsoft Translator)",
  "Hungarian (Microsoft Translator)",
  "Indonesian (Microsoft Translator)",
  "Italian (Microsoft Translator)",
  "Japanese (Microsoft Translator)",
  "Kiswahili (Microsoft Translator)",
  "Klingon (Microsoft Translator)",
  "Klingon (pIqaD) (Microsoft Translator)",
  "Korean (Microsoft Translator)",
  "Latvian (Microsoft Translator)",
  "Lithuanian (Microsoft Translator)",
  "Malay (Microsoft Translator)",
  "Maltese (Microsoft Translator)",
  "Norwegian (Microsoft Translator)",
  "Persian (Microsoft Translator)",
  "Polish (Microsoft Translator)",
  "Portuguese (Microsoft Translator)",
  "Queretaro Otomi (Microsoft Translator)",
  "Romanian (Microsoft Translator)",
  "Russian (Microsoft Translator)",
  "Serbian (Cyrillic) (Microsoft Translator)",
  "Serbian (Latin) (Microsoft Translator)",
  "Slovak (Microsoft Translator)",
  "Slovenian (Microsoft Translator)",
  "Spanish (Microsoft Translator)",
  "Swedish (Microsoft Translator)",
  "Thai (Microsoft Translator)",
  "Turkish (Microsoft Translator)",
  "Ukrainian (Microsoft Translator)",
  "Urdu (Microsoft Translator)",
  "Vietnamese (Microsoft Translator)",
  "Welsh (Microsoft Translator)",
  "Yucatec Maya (Microsoft Translator)"
  );
  $language_codes_bing = array(
      "en!",
      "ar!",
      "bs-Latn!",
      "bg!",
      "ca!",
      "zh-CHS!",
      "zh-CHT!",
      "hr!",
      "cs!",
      "da!",
      "nl!",
      "et!",
      "fi!",
      "fr!",
      "de!",
      "el!",
      "ht!",
      "he!",
      "hi!",
      "mww!",
      "hu!",
      "id!",
      "it!",
      "ja!",
      "sw!",
      "tlh!",
      "tlh-Qaak!",
      "ko!",
      "lv!",
      "lt!",
      "ms!",
      "mt!",
      "nor!",
      "fa!",
      "pl!",
      "pt!",
      "otq!",
      "ro!",
      "ru!",
      "sr-Cyrl!",
      "sr-Latn!",
      "sk!",
      "sl!",
      "es!",
      "sv!",
      "th!",
      "tr!",
      "uk!",
      "ur!",
      "vi!",
      "cy!",
      "yua!"
  );
function anime_get_random_user_agent($ua = '') {
	if($ua != '')
	{
		return $ua;
	}
	$agents = array(
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36",
		"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/603.3.8 (KHTML, like Gecko) Version/10.1.2 Safari/603.3.8",
		"Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36",
		"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0",
		"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:55.0) Gecko/20100101 Firefox/55.0",
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.90 Safari/537.36",
		"Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko",
		"Mozilla/5.0 (Windows NT 6.1; WOW64; rv:55.0) Gecko/20100101 Firefox/55.0",
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:55.0) Gecko/20100101 Firefox/55.0",
		"Mozilla/5.0 (Windows NT 6.3; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
		"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.116 Safari/537.36 Edge/15.15063",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10.12; rv:55.0) Gecko/20100101 Firefox/55.0",
		"Mozilla/5.0 (Windows NT 10.0; WOW64; rv:54.0) Gecko/20100101 Firefox/54.0",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
		"Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.113 Safari/537.36",
		"Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/60.0.3112.101 Safari/537.36"
	);
	$rand   = rand( 0, count( $agents ) - 1 );
	return trim( $agents[ $rand ] );
}
function anime_assign_var(&$target, $var, $root = false) {
	static $cnt = 0;
    $key = key($var);
    if(is_array($var[$key])) 
        anime_assign_var($target[$key], $var[$key], false);
    else {
        if($key==0)
		{
			if($cnt == 0 && $root == true)
			{
				$target['_animer_nonce'] = $var[$key];
				$cnt++;
			}
			elseif($cnt == 1 && $root == true)
			{
				$target['_wp_http_referer'] = $var[$key];
				$cnt++;
			}
			else
			{
				$target[] = $var[$key];
			}
		}
        else
		{
            $target[$key] = $var[$key];
		}
    }   
}

$plugin = plugin_basename(__FILE__);
if(is_admin())
{
    if($_SERVER["REQUEST_METHOD"]==="POST" && !empty($_POST["coderevolution_max_input_var_data"])) {
        $vars = explode("&", $_POST["coderevolution_max_input_var_data"]);
        $coderevolution_max_input_var_data = array();
        foreach($vars as $var) {
            parse_str($var, $variable);
            anime_assign_var($_POST, $variable, true);
        }
        unset($_POST["coderevolution_max_input_var_data"]);
    }
    $plugin_slug = explode('/', $plugin);
    $plugin_slug = $plugin_slug[0];
    if(isset($_POST[$plugin_slug . '_register']) && isset($_POST[$plugin_slug. '_register_code']) && trim($_POST[$plugin_slug . '_register_code']) != '')
    {
        $uoptions = array();
        $uoptions['item_id'] = '35457727';
        $uoptions['item_name'] = 'Ultimate Anime Scraper';
        $uoptions['created_at'] = '24.12.1974';
        $uoptions['buyer'] = 'Tom & Jerry';
        $uoptions['licence'] = 'extended';
        $uoptions['supported_until'] = '24.12.2038';
        update_option($plugin_slug . '_registration', $uoptions);
        update_option('coderevolution_settings_changed', 2);
    }
}
function anime_admin_enqueue_all()
{
    $reg_css_code = '.cr_auto_update{background-color:#fff8e5;margin:5px 20px 15px 20px;border-left:4px solid #fff;padding:12px 12px 12px 12px !important;border-left-color:#ffb900;}';
    wp_register_style( 'anime-plugin-reg-style', false );
    wp_enqueue_style( 'anime-plugin-reg-style' );
    wp_add_inline_style( 'anime-plugin-reg-style', $reg_css_code );
}
add_action('wp_enqueue_scripts', 'anime_wp_load_front_files');
function anime_wp_load_front_files()
{
    wp_enqueue_style('coderevolution-front-css', plugins_url('styles/coderevolution-front.css', __FILE__));
}
function anime_add_activation_link($links)
{
    $settings_link = '<a href="admin.php?page=anime_admin_settings">' . esc_html__('Activate Plugin License', 'ultimate-anime-scraper') . '</a>';
    array_push($links, $settings_link);
    return $links;
}
use \Eventviva\ImageResize;
$min_timeout = 1;

add_action('admin_menu', 'anime_register_my_custom_menu_page');
add_action('network_admin_menu', 'anime_register_my_custom_menu_page');
function anime_register_my_custom_menu_page()
{
    add_menu_page('Ultimate Anime Scraper', 'Ultimate Anime Scraper', 'manage_options', 'anime_admin_settings', 'anime_admin_settings', plugins_url('images/icon.png', __FILE__));
    $main = add_submenu_page('anime_admin_settings', esc_html__("Main Settings", 'ultimate-anime-scraper'), esc_html__("Main Settings", 'ultimate-anime-scraper'), 'manage_options', 'anime_admin_settings');
    add_action( 'load-' . $main, 'anime_load_all_admin_js' );
    add_action( 'load-' . $main, 'anime_load_main_admin_js' );
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (isset($anime_Main_Settings['anime_enabled']) && $anime_Main_Settings['anime_enabled'] == 'on') {
        $mangax = add_submenu_page('anime_admin_settings', esc_html__('Anime Scraper', 'ultimate-anime-scraper'), esc_html__('Anime Scraper', 'ultimate-anime-scraper'), 'manage_options', 'anime_text_panel', 'anime_text_panel');
        add_action( 'load-' . $mangax, 'anime_load_admin_js' );
        add_action( 'load-' . $mangax, 'anime_load_all_admin_js' );
        $logs = add_submenu_page('anime_admin_settings', esc_html__("Activity & Logging", 'ultimate-anime-scraper'), esc_html__("Activity & Logging", 'ultimate-anime-scraper'), 'manage_options', 'anime_logs', 'anime_logs');
        add_action( 'load-' . $logs, 'anime_load_all_admin_js' );
    }
}
function anime_load_admin_js(){
    add_action('admin_enqueue_scripts', 'anime_enqueue_admin_js');
}

function anime_enqueue_admin_js(){
    wp_enqueue_script('anime-footer-script', plugins_url('scripts/footer.js', __FILE__), array('jquery'), false, true);
    $cr_miv = ini_get('max_input_vars');
	if($cr_miv === null || $cr_miv === false || !is_numeric($cr_miv))
	{
        $cr_miv = '9999999';
    }
    $footer_conf_settings = array(
        'max_input_vars' => $cr_miv,
        'plugin_dir_url' => plugin_dir_url(__FILE__),
        'ajaxurl' => admin_url('admin-ajax.php')
    );
    wp_localize_script('anime-footer-script', 'mycustomsettings', $footer_conf_settings);
    wp_register_style('anime-rules-style', plugins_url('styles/anime-rules.css', __FILE__), false, '1.0.0');
    wp_enqueue_style('anime-rules-style');
}
function anime_load_main_admin_js(){
    add_action('admin_enqueue_scripts', 'anime_enqueue_main_admin_js');
}

function anime_enqueue_main_admin_js(){
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    wp_enqueue_script('anime-main-script', plugins_url('scripts/main.js', __FILE__), array('jquery'));
    if(!isset($anime_Main_Settings['best_user']))
    {
        $best_user = '';
    }
    else
    {
        $best_user = $anime_Main_Settings['best_user'];
    }
    if(!isset($anime_Main_Settings['best_password']))
    {
        $best_password = '';
    }
    else
    {
        $best_password = $anime_Main_Settings['best_password'];
    }
    $header_main_settings = array(
        'best_user' => $best_user,
        'best_password' => $best_password
    );
    wp_localize_script('anime-main-script', 'mycustommainsettings', $header_main_settings);
}
function anime_load_all_admin_js(){
    add_action('admin_enqueue_scripts', 'anime_admin_load_files');
}
function anime_add_rating_link($links)
{
    $settings_link = '<a href="//codecanyon.net/downloads" target="_blank" title="Rate">
            <i class="wdi-rate-stars"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="#ffb900" stroke="#ffb900" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg></i></a>';
    array_push($links, $settings_link);
    return $links;
}
add_filter("plugin_action_links_$plugin", 'anime_add_support_link');
function anime_add_support_link($links)
{
    $settings_link = '<a href="//coderevolution.ro/knowledge-base/" target="_blank">' . esc_html__('Support', 'ultimate-anime-scraper') . '</a>';
    array_push($links, $settings_link);
    return $links;
}
add_filter("plugin_action_links_$plugin", 'anime_add_settings_link');
add_filter("plugin_action_links_$plugin", 'anime_add_rating_link');
function anime_add_settings_link($links)
{
    $settings_link = '<a href="admin.php?page=anime_admin_settings">' . esc_html__('Settings', 'ultimate-anime-scraper') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

add_filter('cron_schedules', 'anime_add_cron_schedule');
function anime_add_cron_schedule($schedules)
{
    $schedules['anime_cron'] = array(
        'interval' => 3600,
        'display' => esc_html__('anime Cron', 'ultimate-anime-scraper')
    );
    $schedules['minutely'] = array(
        'interval' => 60,
        'display' => esc_html__('Once A Minute', 'ultimate-anime-scraper')
    );
    $schedules['weekly']        = array(
        'interval' => 604800,
        'display' => esc_html__('Once Weekly', 'ultimate-anime-scraper')
    );
    $schedules['monthly']       = array(
        'interval' => 2592000,
        'display' => esc_html__('Once Monthly', 'ultimate-anime-scraper')
    );
    return $schedules;
}
function anime_auto_clear_log()
{
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
       wp_filesystem($creds);
    }
    if ($wp_filesystem->exists(WP_CONTENT_DIR . '/anime_info.log')) {
        $wp_filesystem->delete(WP_CONTENT_DIR . '/anime_info.log');
    }
}

register_deactivation_hook(__FILE__, 'anime_my_deactivation');
function anime_my_deactivation()
{
    wp_clear_scheduled_hook('animeaction');
    wp_clear_scheduled_hook('animeactionclear');
    $running = array();
    update_option('anime_running_list', $running, false);
}
add_action('animeaction', 'anime_cron');
add_action('animeactionclear', 'anime_auto_clear_log');

function anime_cron_schedule()
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (isset($anime_Main_Settings['anime_enabled']) && $anime_Main_Settings['anime_enabled'] === 'on') {
        if (!wp_next_scheduled('animeaction')) {
            $unlocker = get_option('anime_minute_running_unlocked', false);
            if($unlocker == '1')
            {
                $rez = wp_schedule_event(time(), 'minutely', 'animeaction');
            }
            else
            {
                $rez = wp_schedule_event(time(), 'hourly', 'animeaction');
            }
            
            if ($rez === FALSE) {
                anime_log_to_file('[Scheduler] Failed to schedule animeaction to anime_cron!');
            }
        }
        
        if (isset($anime_Main_Settings['enable_logging']) && $anime_Main_Settings['enable_logging'] === 'on' && isset($anime_Main_Settings['auto_clear_logs']) && $anime_Main_Settings['auto_clear_logs'] !== 'No') {
            if (!wp_next_scheduled('animeactionclear')) {
                $rez = wp_schedule_event(time(), $anime_Main_Settings['auto_clear_logs'], 'animeactionclear');
                if ($rez === FALSE) {
                    anime_log_to_file('[Scheduler] Failed to schedule animeactionclear to ' . $anime_Main_Settings['auto_clear_logs'] . '!');
                }
                add_option('anime_schedule_time', $anime_Main_Settings['auto_clear_logs']);
            } else {
                if (!get_option('anime_schedule_time')) {
                    wp_clear_scheduled_hook('animeactionclear');
                    $rez = wp_schedule_event(time(), $anime_Main_Settings['auto_clear_logs'], 'animeactionclear');
                    add_option('anime_schedule_time', $anime_Main_Settings['auto_clear_logs']);
                    if ($rez === FALSE) {
                        anime_log_to_file('[Scheduler] Failed to schedule animeactionclear to ' . $anime_Main_Settings['auto_clear_logs'] . '!');
                    }
                } else {
                    $the_time = get_option('anime_schedule_time');
                    if ($the_time != $anime_Main_Settings['auto_clear_logs']) {
                        wp_clear_scheduled_hook('animeactionclear');
                        delete_option('anime_schedule_time');
                        $rez = wp_schedule_event(time(), $anime_Main_Settings['auto_clear_logs'], 'animeactionclear');
                        add_option('anime_schedule_time', $anime_Main_Settings['auto_clear_logs']);
                        if ($rez === FALSE) {
                            anime_log_to_file('[Scheduler] Failed to schedule animeactionclear to ' . $anime_Main_Settings['auto_clear_logs'] . '!');
                        }
                    }
                }
            }
        } else {
            if (!wp_next_scheduled('animeactionclear')) {
                delete_option('anime_schedule_time');
            } else {
                wp_clear_scheduled_hook('animeactionclear');
                delete_option('anime_schedule_time');
            }
        }
    } else {
        if (wp_next_scheduled('animeaction')) {
            wp_clear_scheduled_hook('animeaction');
        }
        
        if (!wp_next_scheduled('animeactionclear')) {
            delete_option('anime_schedule_time');
        } else {
            wp_clear_scheduled_hook('animeactionclear');
            delete_option('anime_schedule_time');
        }
    }
}
function anime_cron()
{
    $GLOBALS['wp_object_cache']->delete('anime_rules_list', 'options');
    if (!get_option('anime_rules_list')) {
        $rules = array();
    } else {
        $rules = get_option('anime_rules_list');
    }
    $unlocker = get_option('anime_minute_running_unlocked', false);
    if (!empty($rules)) {
        $cont = 0;
        foreach ($rules as $request => $bundle[]) {
            $bundle_values   = array_values($bundle);
            $myValues        = $bundle_values[$cont];
            $array_my_values = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
            $schedule        = isset($array_my_values[1]) ? $array_my_values[1] : '24';
            $active          = isset($array_my_values[2]) ? $array_my_values[2] : '0';
            $last_run        = isset($array_my_values[3]) ? $array_my_values[3] : anime_get_date_now();
            if ($active == '1') {
                $now                = anime_get_date_now();
                if($unlocker == '1')
                {
                    $nextrun        = anime_add_minute($last_run, $schedule);
                    $anime_hour_diff = (int) anime_minute_diff($now, $nextrun);
                }
                else
                {
                    $nextrun            = anime_add_hour($last_run, $schedule);
                    $anime_hour_diff = (int) anime_hour_diff($now, $nextrun);
                }
                if ($anime_hour_diff >= 0) {
                    anime_run_rule($cont, 0);
                }
            }
            $cont = $cont + 1;
        }
    }
    $GLOBALS['wp_object_cache']->delete('anime_text_list', 'options');
    if (!get_option('anime_text_list')) {
        $xrules = array();
    } else {
        $xrules = get_option('anime_text_list');
    }
    if (!empty($xrules)) {
        $xcont = 0;
        foreach ($xrules as $xrequest => $xbundle[]) {
            $xbundle_values   = array_values($xbundle);
            $xmyValues        = $xbundle_values[$xcont];
            $xarray_my_values = array_values($xmyValues);for($xiji=0;$xiji<count($xarray_my_values);++$xiji){if(is_string($xarray_my_values[$xiji])){$xarray_my_values[$xiji]=stripslashes($xarray_my_values[$xiji]);}}
            $xschedule        = isset($xarray_my_values[1]) ? $xarray_my_values[1] : '24';
            $xactive          = isset($xarray_my_values[2]) ? $xarray_my_values[2] : '0';
            $xlast_run        = isset($xarray_my_values[3]) ? $xarray_my_values[3] : anime_get_date_now();
            if ($xactive == '1') {
                $xnow                = anime_get_date_now();
                if($unlocker == '1')
                {
                    $xnextrun        = anime_add_minute($xlast_run, $xschedule);
                    $xanime_hour_diff = (int) anime_minute_diff($xnow, $xnextrun);
                }
                else
                {
                    $xnextrun            = anime_add_hour($xlast_run, $xschedule);
                    $xanime_hour_diff = (int) anime_hour_diff($xnow, $xnextrun);
                }
                if ($xanime_hour_diff >= 0) {
                    anime_run_rule($xcont, 1);
                }
            }
            $xcont = $xcont + 1;
        }
    }
    $running = array();
    update_option('anime_running_list', $running);
}

function anime_testFfmpeg()
{
    if(!function_exists('shell' . '_exec')) {
        return esc_html__('shell' . '_exec function is not enabled on your server! The plugin cannot run!', 'ultimate-anime-scraper');
    }
    $disabled = explode(',', ini_get('disable_functions'));
    if(in_array('shell' . '_exec', $disabled))
    {
        return esc_html__('shell' . '_exec function is disabled by your hosting provider! The plugin cannot run!', 'ultimate-anime-scraper');
    }
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (isset($anime_Main_Settings['ffmpeg_path']) && $anime_Main_Settings['ffmpeg_path'] != '') 
    {
        $ffmpeg_comm = $anime_Main_Settings['ffmpeg_path'] . ' ';
    }
    else
    {
        $ffmpeg_comm = 'ffmpeg ';
    }
    $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
    $cmdResult = $shefunc($ffmpeg_comm . '-version');
    if($cmdResult === null)
    {
        $cmdResult = $shefunc($ffmpeg_comm . '-version 2>&1');
        if($cmdResult === null)
        {
            anime_log_to_file('Error in testFfmpeg: ' . $ffmpeg_comm . '-version 2>&1' . ' --- got: [NULL]');
            return 'null';
        }
        anime_log_to_file('Error in testFfmpeg: ' . $ffmpeg_comm . '-version 2>&1' . ' --- got: [' . $cmdResult . ']');
        return $cmdResult;
    }
    $analysis = anime_analyseResult($cmdResult, 'ffmpeg version');
    if($analysis == 1)
    {
        return 1;
    }
    return $cmdResult;
}
function anime_analyseResult($cmdResult, $testable)
{
    if (stristr($cmdResult, $testable) !== false) {
            return 1;
    }
    return 0;
}
function anime_log_to_file($str)
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (isset($anime_Main_Settings['enable_logging']) && $anime_Main_Settings['enable_logging'] == 'on') {
        $d = date("j-M-Y H:i:s e", current_time( 'timestamp' ));
        error_log("[$d] " . $str . "<br/>\r\n", 3, WP_CONTENT_DIR . '/anime_info.log');
    }
}
function anime_delete_all_posts()
{
    $failed                 = false;
    $number                 = 0;
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    $post_list = array();
    $postsPerPage = 50000;
    $paged = 0;
    do
    {
        $postOffset = $paged * $postsPerPage;
        $query = array(
            'post_status' => array(
                'publish',
                'draft',
                'pending',
                'trash',
                'private',
                'future'
            ),
            'post_type' => array(
                'any'
            ),
            'numberposts' => $postsPerPage,
            'meta_key' => 'anime_parent_rule',
            'fields' => 'ids',
            'offset'  => $postOffset
        );
        $got_me = get_posts($query);
        $post_list = array_merge($post_list, $got_me);
        $paged++;
    }while(!empty($got_me));
    wp_suspend_cache_addition(true);
    foreach ($post_list as $post) {
        $index = get_post_meta($post, 'anime_parent_rule', true);
        if (isset($index) && $index !== '') {
            $args             = array(
                'post_parent' => $post
            );
            $post_attachments = get_children($args);
            if (isset($post_attachments) && !empty($post_attachments)) {
                foreach ($post_attachments as $attachment) {
                    wp_delete_attachment($attachment->ID, true);
                }
            }
            $res = wp_delete_post($post, true);
            if ($res === false) {
                $failed = true;
            } else {
                $number++;
            }
        }
    }
    wp_suspend_cache_addition(false);
    if ($failed === true) {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('[PostDelete] Failed to delete all posts!');
        }
    } else {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('[PostDelete] Successfuly deleted ' . esc_html($number) . ' posts!');
        }
    }
}
add_action('wp_ajax_anime_my_action', 'anime_my_action_callback');
function anime_my_action_callback()
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    $failed                 = false;
    $type                   = $_POST['type'];
    $del_id                 = $_POST['id'];
    $how                    = $_POST['how'];
    $force_delete           = true;
    $number                 = 0;
    if ($how == 'trash') {
        $force_delete = false;
    }
    else
    {
        $skip_posts_temp = get_option('anime_continue_search', array());
        $skip_posts_temp[$del_id] = '';
        update_option('anime_continue_search', $skip_posts_temp);
    }
    $post_list = array();
    $postsPerPage = 50000;
    $paged = 0;
    do
    {
        $postOffset = $paged * $postsPerPage;
        $query = array(
            'post_status' => array(
                'publish',
                'draft',
                'pending',
                'trash',
                'private',
                'future'
            ),
            'post_type' => array(
                'any'
            ),
            'numberposts' => $postsPerPage,
            'meta_key' => 'anime_parent_rule',
            'fields' => 'ids',
            'offset'  => $postOffset
        );
        $got_me = get_posts($query);
        $post_list = array_merge($post_list, $got_me);
        $paged++;
    }while(!empty($got_me));
    wp_suspend_cache_addition(true);
    foreach ($post_list as $post) {
        $index = get_post_meta($post, 'anime_parent_rule', true);
        if ($index == $type . '-' . $del_id) {
            $args             = array(
                'post_parent' => $post
            );
            $post_attachments = get_children($args);
            if (isset($post_attachments) && !empty($post_attachments)) {
                foreach ($post_attachments as $attachment) {
                    wp_delete_attachment($attachment->ID, true);
                }
            }
            $res = wp_delete_post($post, $force_delete);
            if ($res === false) {
                $failed = true;
            } else {
                $number++;
            }
        }
    }
    wp_suspend_cache_addition(false);
    if ($failed === true) {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('[PostDelete] Failed to delete all posts for rule id: ' . esc_html($del_id) . '!');
        }
        echo 'failed';
    } else {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('[PostDelete] Successfuly deleted ' . esc_html($number) . ' posts for rule id: ' . esc_html($del_id) . '!');
        }
        if ($number == 0) {
            echo 'nochange';
        } else {
            echo 'ok';
        }
    }
    die();
}
add_action('wp_ajax_anime_run_my_action', 'anime_run_my_action_callback');
function anime_run_my_action_callback()
{
    $run_id = $_POST['id'];
    $run_type = isset($_POST['type']) ? $_POST['type'] : 0;
    $rerun_count = isset($_POST['rerun_count']) ? $_POST['rerun_count'] : 0;
    echo anime_run_rule($run_id, $run_type, 0, $rerun_count);
    die();
}


function anime_clearFromList($param, $type)
{
    $GLOBALS['wp_object_cache']->delete('anime_running_list', 'options');
    $running = get_option('anime_running_list');
    if($running !== false)
    {
        $key = array_search(array(
            $param => $type
        ), $running);
        if ($key !== FALSE) {
            unset($running[$key]);
            update_option('anime_running_list', $running);
        }
    }
}

function anime_get_page_PuppeteerAPI($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $timeout = '')
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (!isset($anime_Main_Settings['headlessbrowserapi_key']) || trim($anime_Main_Settings['headlessbrowserapi_key']) == '')
    {
        anime_log_to_file('You need to add your HeadlessBrowserAPI key in the plugin\'s \'Main Settings\' before you can use this feature.');
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if($timeout != '')
    {
        $phantomjs_timeout = $timeout;
    }
    else
    {
        if (isset($anime_Main_Settings['phantom_timeout']) && $anime_Main_Settings['phantom_timeout'] != '') 
        {
            $phantomjs_timeout = ((int)$anime_Main_Settings['phantom_timeout']);
        }
        else
        {
            $phantomjs_timeout = 'default';
        }
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') 
    {
        $proxy_url = $anime_Main_Settings['proxy_url'];
        if(isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '')
        {
            $proxy_auth = $anime_Main_Settings['proxy_auth'];
        }
        else
        {
            $proxy_auth = 'default';
        }
    }
    else
    {
        $proxy_url = 'default';
        $proxy_auth = 'default';
    }
    
    $za_api_url = 'https://headlessbrowserapi.com/apis/scrape/v1/puppeteer?apikey=' . trim($anime_Main_Settings['headlessbrowserapi_key']) . '&url=' . urlencode($url) . '&custom_user_agent=' . urlencode($custom_user_agent) . '&custom_cookies=' . urlencode($custom_cookies) . '&user_pass=' . urlencode($user_pass) . '&timeout=' . urlencode($phantomjs_timeout) . '&proxy_url=' . urlencode($proxy_url) . '&proxy_auth=' . urlencode($proxy_auth);
    $api_timeout = 60;
    $args = array(
       'timeout'     => $api_timeout,
       'redirection' => 10,
       'blocking'    => true,
       'compress'    => false,
       'decompress'  => true,
       'sslverify'   => false,
       'stream'      => false
    );
    $ret_data = wp_remote_get($za_api_url, $args);
    $response_code       = wp_remote_retrieve_response_code( $ret_data );
    $response_message    = wp_remote_retrieve_response_message( $ret_data );    
    if ( 200 != $response_code ) {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) 
        {
            anime_log_to_file('Failed to get response from HeadlessBrowserAPI: ' . $za_api_url . ' code: ' . $response_code . ' message: ' . $response_message);
            if(isset($ret_data->errors['http_request_failed']))
            {
                foreach($ret_data->errors['http_request_failed'] as $errx)
                {
                    anime_log_to_file('Error message: ' . html_entity_decode($errx));
                }
            }
        }
        return false;
    } else {
        $cmdResult = wp_remote_retrieve_body( $ret_data );
    }
    $jcmdResult = json_decode($cmdResult, true);
    if($jcmdResult === false)
    {
        anime_log_to_file('Failed to decode response from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    $cmdResult = $jcmdResult;
    if(isset($cmdResult['apicalls']))
    {
        update_option('headless_calls', esc_html($cmdResult['apicalls']));
    }
    if(isset($cmdResult['error']))
    {
        anime_log_to_file('An error occurred while getting content from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult['error'], true));
        return false;
    }
    if(!isset($cmdResult['html']))
    {
        anime_log_to_file('Malformed data imported from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    return '<html><body>' . $cmdResult['html'] . '</body></html>';
}
function anime_get_page_TorAPI($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $timeout = '')
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (!isset($anime_Main_Settings['headlessbrowserapi_key']) || trim($anime_Main_Settings['headlessbrowserapi_key']) == '')
    {
        anime_log_to_file('You need to add your HeadlessBrowserAPI key in the plugin\'s \'Main Settings\' before you can use this feature.');
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if($timeout != '')
    {
        $phantomjs_timeout = $timeout;
    }
    else
    {
        if (isset($anime_Main_Settings['phantom_timeout']) && $anime_Main_Settings['phantom_timeout'] != '') 
        {
            $phantomjs_timeout = ((int)$anime_Main_Settings['phantom_timeout']);
        }
        else
        {
            $phantomjs_timeout = 'default';
        }
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') 
    {
        $proxy_url = $anime_Main_Settings['proxy_url'];
        if(isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '')
        {
            $proxy_auth = $anime_Main_Settings['proxy_auth'];
        }
        else
        {
            $proxy_auth = 'default';
        }
    }
    else
    {
        $proxy_url = 'default';
        $proxy_auth = 'default';
    }
    
    $za_api_url = 'https://headlessbrowserapi.com/apis/scrape/v1/tor?apikey=' . trim($anime_Main_Settings['headlessbrowserapi_key']) . '&url=' . urlencode($url) . '&custom_user_agent=' . urlencode($custom_user_agent) . '&custom_cookies=' . urlencode($custom_cookies) . '&user_pass=' . urlencode($user_pass) . '&timeout=' . urlencode($phantomjs_timeout) . '&proxy_url=' . urlencode($proxy_url) . '&proxy_auth=' . urlencode($proxy_auth);
    $api_timeout = 60;
    $args = array(
       'timeout'     => $api_timeout,
       'redirection' => 10,
       'blocking'    => true,
       'compress'    => false,
       'decompress'  => true,
       'sslverify'   => false,
       'stream'      => false
    );
    $ret_data = wp_remote_get($za_api_url, $args);
    $response_code       = wp_remote_retrieve_response_code( $ret_data );
    $response_message    = wp_remote_retrieve_response_message( $ret_data );    
    if ( 200 != $response_code ) {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) 
        {
            anime_log_to_file('Failed to get response from HeadlessBrowserAPI: ' . $za_api_url . ' code: ' . $response_code . ' message: ' . $response_message);
            if(isset($ret_data->errors['http_request_failed']))
            {
                foreach($ret_data->errors['http_request_failed'] as $errx)
                {
                    anime_log_to_file('Error message: ' . html_entity_decode($errx));
                }
            }
        }
        return false;
    } else {
        $cmdResult = wp_remote_retrieve_body( $ret_data );
    }
    $jcmdResult = json_decode($cmdResult, true);
    if($jcmdResult === false)
    {
        anime_log_to_file('Failed to decode response from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    $cmdResult = $jcmdResult;
    if(isset($cmdResult['apicalls']))
    {
        update_option('headless_calls', esc_html($cmdResult['apicalls']));
    }
    if(isset($cmdResult['error']))
    {
        anime_log_to_file('An error occurred while getting content from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult['error'], true));
        return false;
    }
    if(!isset($cmdResult['html']))
    {
        anime_log_to_file('Malformed data imported from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    return '<html><body>' . $cmdResult['html'] . '</body></html>';
}
function anime_get_page_PhantomJSAPI($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $timeout = '')
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (!isset($anime_Main_Settings['headlessbrowserapi_key']) || trim($anime_Main_Settings['headlessbrowserapi_key']) == '')
    {
        anime_log_to_file('You need to add your HeadlessBrowserAPI key in the plugin\'s \'Main Settings\' before you can use this feature.');
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if($timeout != '')
    {
        $phantomjs_timeout = $timeout;
    }
    else
    {
        if (isset($anime_Main_Settings['phantom_timeout']) && $anime_Main_Settings['phantom_timeout'] != '') 
        {
            $phantomjs_timeout = ((int)$anime_Main_Settings['phantom_timeout']);
        }
        else
        {
            $phantomjs_timeout = 'default';
        }
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') 
    {
        $proxy_url = $anime_Main_Settings['proxy_url'];
        if(isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '')
        {
            $proxy_auth = $anime_Main_Settings['proxy_auth'];
        }
        else
        {
            $proxy_auth = 'default';
        }
    }
    else
    {
        $proxy_url = 'default';
        $proxy_auth = 'default';
    }
    
    $za_api_url = 'https://headlessbrowserapi.com/apis/scrape/v1/phantomjs?apikey=' . trim($anime_Main_Settings['headlessbrowserapi_key']) . '&url=' . urlencode($url) . '&custom_user_agent=' . urlencode($custom_user_agent) . '&custom_cookies=' . urlencode($custom_cookies) . '&user_pass=' . urlencode($user_pass) . '&timeout=' . urlencode($phantomjs_timeout) . '&proxy_url=' . urlencode($proxy_url) . '&proxy_auth=' . urlencode($proxy_auth);
    $api_timeout = 60;
    $args = array(
       'timeout'     => $api_timeout,
       'redirection' => 10,
       'blocking'    => true,
       'compress'    => false,
       'decompress'  => true,
       'sslverify'   => false,
       'stream'      => false
    );
    $ret_data = wp_remote_get($za_api_url, $args);
    $response_code       = wp_remote_retrieve_response_code( $ret_data );
    $response_message    = wp_remote_retrieve_response_message( $ret_data );    
    if ( 200 != $response_code ) {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) 
        {
            anime_log_to_file('Failed to get response from HeadlessBrowserAPI: ' . $za_api_url . ' code: ' . $response_code . ' message: ' . $response_message);
            if(isset($ret_data->errors['http_request_failed']))
            {
                foreach($ret_data->errors['http_request_failed'] as $errx)
                {
                    anime_log_to_file('Error message: ' . html_entity_decode($errx));
                }
            }
        }
        return false;
    } else {
        $cmdResult = wp_remote_retrieve_body( $ret_data );
    }
    $jcmdResult = json_decode($cmdResult, true);
    if($jcmdResult === false)
    {
        anime_log_to_file('Failed to decode response from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    $cmdResult = $jcmdResult;
    if(isset($cmdResult['apicalls']))
    {
        update_option('headless_calls', esc_html($cmdResult['apicalls']));
    }
    if(isset($cmdResult['error']))
    {
        anime_log_to_file('An error occurred while getting content from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult['error'], true));
        return false;
    }
    if(!isset($cmdResult['html']))
    {
        anime_log_to_file('Malformed data imported from HeadlessBrowserAPI: ' . $za_api_url . ' - ' . print_r($cmdResult, true));
        return false;
    }
    return '<html><body>' . $cmdResult['html'] . '</body></html>';
}
function anime_get_web_page($url, $ua = '', $use_phantom = '0')
{
    if(anime_startsWith($url, '//'))
    {
        $url = 'http:' . $url;
    }
    $content = false;
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    $got_phantom = false;
    if($use_phantom == '1')
    {
        $content = anime_get_page_PhantomJS($url, '', anime_get_random_user_agent($ua), '1', '', '');
        if($content !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '2')
    {
        $content = anime_get_page_Puppeteer($url, '', anime_get_random_user_agent($ua), '1', '');
        if($content !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '4')
    {
        $content = anime_get_page_PuppeteerAPI($url, '', anime_get_random_user_agent($ua), '1', '', '');
        if($content !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '5')
    {
        $content = anime_get_page_TorAPI($url, '', anime_get_random_user_agent($ua), '1', '', '');
        if($content !== false)
        {
            $got_phantom = true;
        }
    }
    elseif($use_phantom == '6')
    {
        $content = anime_get_page_PhantomJSAPI($url, '', anime_get_random_user_agent($ua), '1', '', '');
        if($content !== false)
        {
            $got_phantom = true;
        }
    }
    if($got_phantom === false)
    {
        if (!isset($anime_Main_Settings['proxy_url']) || $anime_Main_Settings['proxy_url'] == '') {
            $args = array(
               'timeout'     => 10,
               'redirection' => 10,
               'user-agent'  => anime_get_random_user_agent($ua),
               'blocking'    => true,
               'compress'    => false,
               'decompress'  => true,
               'sslverify'   => false,
               'stream'      => false,
               'filename'    => null
            );
            $cookies = [];
            $cookies[] = new WP_Http_Cookie( array(
                'name'  => 'isAdult',
                'value' => '1',
            ));
            $args['cookies'] = $cookies;
            
            $ret_data            = wp_remote_get($url, $args);  
            $response_code       = wp_remote_retrieve_response_code( $ret_data );
            $response_message    = wp_remote_retrieve_response_message( $ret_data );        
            if ( 200 != $response_code ) {
            } else {
                $content = wp_remote_retrieve_body( $ret_data );
            }
        }
        if($content === false)
        {
            if(function_exists('curl_version') && filter_var($url, FILTER_VALIDATE_URL))
            {
                $user_agent = anime_get_random_user_agent($ua);
                $options    = array(
                    CURLOPT_CUSTOMREQUEST => "GET",
                    CURLOPT_COOKIEJAR => get_temp_dir() . 'animecookie.txt',
                    CURLOPT_COOKIEFILE => get_temp_dir() . 'animecookie.txt',
                    CURLOPT_USERAGENT => $user_agent,
                    CURLOPT_REFERER => 'http://www.google.com',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_ENCODING => "",
                    CURLOPT_CONNECTTIMEOUT => 10,
                    CURLOPT_TIMEOUT => 10,
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_SSL_VERIFYHOST => 0,
                    CURLOPT_SSL_VERIFYPEER => 0,
                    CURLOPT_COOKIE => ''
                );
                $ch         = curl_init($url);
                if (isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') {
                    curl_setopt($ch, CURLOPT_PROXY, $anime_Main_Settings['proxy_url']);
                    if (isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '') {
                        curl_setopt($ch, CURLOPT_PROXYUSERPWD, $anime_Main_Settings['proxy_auth']);
                    }
                }
                if ($ch === FALSE) {
                    return FALSE;
                }
                curl_setopt_array($ch, $options);
                $content = curl_exec($ch);
                curl_close($ch);
            }
            else
            {
                $allowUrlFopen = preg_match('/1|yes|on|true/i', ini_get('allow_url_fopen'));
                if ($allowUrlFopen) {
                    global $wp_filesystem;
                    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                        wp_filesystem($creds);
                    }
                    return $wp_filesystem->get_contents($url);
                }
            }
        }
    }
    return $content;
}


function anime_api_get_web_page($url)
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (isset($anime_Main_Settings['aniapi_keys']) && trim($anime_Main_Settings['aniapi_keys']) != '') 
    {
        $auth_token = trim($anime_Main_Settings['aniapi_keys']);
        $auth_token = preg_split('/\r\n|\r|\n/', $auth_token);
        $auth_token = $auth_token[array_rand($auth_token)];
    }
    else
    {
        $auth_token = '';
    }
    $headers = [
        'Authorization: Bearer ' . $auth_token
    ];
    $headers2 = [
        'Authorization' => 'Bearer ' . $auth_token
    ];
    if(anime_startsWith($url, '//'))
    {
        $url = 'http:' . $url;
    }
    $content = false;
    if (!isset($anime_Main_Settings['proxy_url']) || $anime_Main_Settings['proxy_url'] == '') {
        $args = array(
            'timeout'     => 10,
            'redirection' => 10,
            'blocking'    => true,
            'compress'    => false,
            'decompress'  => true,
            'sslverify'   => false,
            'stream'      => false,
            'filename'    => null,
            'headers'     => $headers2
        );
        
        $cookies = [];
        $cookies[] = new WP_Http_Cookie( array(
            'name'  => 'isAdult',
            'value' => '1',
        ));
        $args['cookies'] = $cookies;
        
        $ret_data            = wp_remote_get($url, $args);  
        $response_code       = wp_remote_retrieve_response_code( $ret_data );
        $response_message    = wp_remote_retrieve_response_message( $ret_data );        
        if ( 200 != $response_code ) {
        } else {
            $content = wp_remote_retrieve_body( $ret_data );
        }
    }
    if($content === false)
    {
        if(function_exists('curl_version') && filter_var($url, FILTER_VALIDATE_URL))
        {
            $options    = array(
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_COOKIEJAR => get_temp_dir() . 'animecookie.txt',
                CURLOPT_COOKIEFILE => get_temp_dir() . 'animecookie.txt',
                CURLOPT_REFERER => 'http://www.google.com',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_ENCODING => "",
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 10,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_COOKIE => ''
            );
            $ch         = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            if (isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') {
                curl_setopt($ch, CURLOPT_PROXY, $anime_Main_Settings['proxy_url']);
                if (isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '') {
                    curl_setopt($ch, CURLOPT_PROXYUSERPWD, $anime_Main_Settings['proxy_auth']);
                }
            }
            if ($ch === FALSE) {
                return FALSE;
            }
            curl_setopt_array($ch, $options);
            $content = curl_exec($ch);
            curl_close($ch);
        }
        else
        {
            $allowUrlFopen = preg_match('/1|yes|on|true/i', ini_get('allow_url_fopen'));
            if ($allowUrlFopen) {
                global $wp_filesystem;
                if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                    include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                    wp_filesystem($creds);
                }
                return $wp_filesystem->get_contents($url);
            }
        }
    }
    return $content;
}

function anime_utf8_encode($str)
{
    if(function_exists('mb_detect_encoding') && function_exists('mb_convert_encoding'))
    {
        $enc = mb_detect_encoding($str);
        if ($enc !== FALSE) {
            $str = mb_convert_encoding($str, 'UTF-8', $enc);
        } else {
            $str = mb_convert_encoding($str, 'UTF-8');
        }
    }
    return $str;
}
function anime_startsWith($haystack, $needle)
{
     $length = strlen($needle);
     return (substr($haystack, 0, $length) === $needle);
}
function anime_image_url_filter( $url ){

    $url = str_replace( 'https://', '', $url );
    $url = str_replace( 'http://', '', $url );
    $url = str_replace( '//', '', $url );
    $url = str_replace( 'http:', '', $url );
    if(strpos($url, '/') === false){
        $url = 'fanfox.net' . $url;
    }
    return "https://{$url}";
}
function anime_getFacebookButton($url)
{
    $button = '<a class="crf_twitt anime_facebook anime_btn button purchase" href="https://www.facebook.com/sharer/sharer.php?display=popup&ref=plugin&src=share_button&u=' . urlencode($url) . '" onclick="return !window.open(this.href, \'Facebook\', \'width=640,height=580\')"><img src="' . anime_get_file_url('images/facebook.png') . '" alt="Facebook" class="crf_social_img"></a>';
    return $button;
}
function anime_getTwitterButton($url, $item_title)
{
    $button = '<a class="crf_twitt anime_twitter anime_btn button purchase" href="https://twitter.com/intent/tweet?text=Check+out+%27' . urlencode($item_title) . '%27&url=' . urlencode(htmlspecialchars_decode($url)) . '" onclick="return !window.open(this.href, \'Twitter\', \'width=640,height=580\')"><img src="' . anime_get_file_url('images/twitter.png') . '" alt="Twitter" class="crf_social_img"></a>';
    return $button;
}
function anime_getPinterestButton($url, $item_title, $banner)
{
    $button = '<a class="crf_twitt anime_pinterest anime_btn button purchase" href="http://pinterest.com/pin/create/button?description=' . urlencode($item_title) . '&media=' . urlencode($banner) . '&url=' . urlencode(htmlspecialchars_decode($url)) . '" onclick="return !window.open(this.href, \'Pinterest\', \'width=640,height=580\')"><img src="' . anime_get_file_url('images/pinterest.png') . '" alt="Pinterest" class="crf_social_img"></a>';
    return $button;
}

function anime_get_page_Puppeteer($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass)
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if(!function_exists('shell' . '_exec')) {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('shel' . 'l_exec not found!');
        }
        return false;
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    $phantomjs_proxcomm = '"null"';
    if ($use_proxy == '1' && isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') 
    {
        $prx = explode(',', $anime_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        $phantomjs_proxcomm = '"' . trim($prx[$randomness]);
        if (isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $anime_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                $phantomjs_proxcomm .= ':' . trim($prx_auth[$randomness]);
            }
        }
        $phantomjs_proxcomm .= '"';
    }
    $disabled = explode(',', ini_get('disable_functions'));
    if(in_array('shell' . '_exec', $disabled))
    {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('shel' . 'l_exec disabled');
        }
        return false;
    }
    
    $puppeteer_comm = 'node ';
    $puppeteer_comm .= '"' . dirname(__FILE__) . '/res/puppeteer/puppeteer.js" "' . $url . '" ' . $phantomjs_proxcomm . '  "' . esc_html($custom_user_agent) . '" "' . esc_html($custom_cookies) . '" "' . esc_html($user_pass) . '"';
    $puppeteer_comm .= ' 2>&1';
    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
        anime_log_to_file('Puppeteer command: ' . $puppeteer_comm);
    }
    $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
    $cmdResult = $shefunc($puppeteer_comm);
    if($cmdResult === NULL || $cmdResult == '')
    {
        anime_log_to_file('puppeteer did not return usable info for: ' . $url);
        return false;
    }
    if(trim($cmdResult) === 'timeout')
    {
        anime_log_to_file('puppeteer timed out while getting page: ' . $url. ' - please increase timeout in Main Settings');
        return false;
    }
    if(stristr($cmdResult, 'sh: puppeteer: command not found') !== false)
    {
        anime_log_to_file('puppeteer not found, please install it on your server');
        return false;
    }
    if(stristr($cmdResult, 'res/puppeteer/puppeteer.js:') !== false)
    {
        anime_log_to_file('puppeteer failed to run, error: ' . $cmdResult);
        return false;
    }
    return $cmdResult;
}
function anime_get_page_PhantomJS($url, $custom_cookies, $custom_user_agent, $use_proxy, $user_pass, $phantom_wait)
{
    if(!function_exists('shell' . '_exec')) {
        return false;
    }
    $disabled = explode(',', ini_get('disable_functions'));
    if(in_array('shell' . '_exec', $disabled))
    {
        return false;
    }
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (isset($anime_Main_Settings['phantom_path']) && $anime_Main_Settings['phantom_path'] != '') 
    {
        $phantomjs_comm = $anime_Main_Settings['phantom_path'];
    }
    else
    {
        $phantomjs_comm = 'phantomjs';
    }
    if (isset($anime_Main_Settings['phantom_timeout']) && $anime_Main_Settings['phantom_timeout'] != '') 
    {
        $phantomjs_timeout = ((int)$anime_Main_Settings['phantom_timeout']);
    }
    else
    {
        $phantomjs_timeout = '15000';
    }
    if($custom_user_agent == '')
    {
        $custom_user_agent = 'default';
    }
    if($custom_cookies == '')
    {
        $custom_cookies = 'default';
    }
    if($user_pass == '')
    {
        $user_pass = 'default';
    }
    if ($use_proxy == '1' && isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') 
    {
        $prx = explode(',', $anime_Main_Settings['proxy_url']);
        $randomness = array_rand($prx);
        $phantomjs_comm .= ' --proxy=' . trim($prx[$randomness]);
        if (isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '') 
        {
            $prx_auth = explode(',', $anime_Main_Settings['proxy_auth']);
            if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
            {
                $phantomjs_comm .= ' --proxy-auth=' . trim($prx_auth[$randomness]);
            }
        }
    }
    $phantomjs_comm .= ' --ignore-ssl-errors=true ';
    $phantomjs_comm .= '"' . dirname(__FILE__) . '/res/phantomjs/phantom.js" "' . $url . '" "' . esc_html($phantomjs_timeout) . '" "' . esc_html($custom_user_agent) . '" "' . esc_html($custom_cookies) . '" "' . esc_html($user_pass) . '" "' . esc_html($phantom_wait) . '"';
    $phantomjs_comm .= ' 2>&1';
    $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
    $cmdResult = $shefunc($phantomjs_comm);
    if($cmdResult === NULL || $cmdResult == '')
    {
        anime_log_to_file('phantomjs did not return usable info for: ' . $url);
        return false;
    }
    if(trim($cmdResult) === 'timeout')
    {
        anime_log_to_file('phantomjs timed out while getting page: ' . $url. ' - please increase timeout in Main Settings');
        return false;
    }
    if(stristr($cmdResult, 'sh: phantomjs: command not found') !== false)
    {
        anime_log_to_file('phantomjs not found, please install it on your server');
        return false;
    }
    return $cmdResult;
}

function anime_testPhantom()
{
    if(!function_exists('shell' . '_exec')) {
        return -1;
    }
    $disabled = explode(',', ini_get('disable_functions'));
    if(in_array('shell' . '_exec', $disabled))
    {
        return -2;
    }
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if (isset($anime_Main_Settings['phantom_path']) && $anime_Main_Settings['phantom_path'] != '') 
    {
        $phantomjs_comm = $anime_Main_Settings['phantom_path'] . ' ';
    }
    else
    {
        $phantomjs_comm = 'phantomjs ';
    }
    $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
    $cmdResult = $shefunc($phantomjs_comm . '-h 2>&1');
    if(stristr($cmdResult, 'Usage') !== false)
    {
        return 1;
    }
    return 0;
}

function anime_wp_mcl_e_upload_file( $url, $post_id = 0 ){
    if($url == '' || $url == false)
    {
        return false;
    }
    include_once(ABSPATH . 'wp-admin/includes/image.php');
    require_once(ABSPATH . 'wp-admin/includes/media.php');
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
       wp_filesystem($creds);
    }
    $content = $wp_filesystem->get_contents( $url );

    $pathinfo = pathinfo( $url );

    if( ! $content ){
        return false;
    }

    $upload_dir = wp_upload_dir();
    $file_tmp_path = $upload_dir['basedir'] . '/' . $pathinfo['filename'] . '-' . $post_id . '.' . explode('?',$pathinfo['extension'])[0];

    $file = $wp_filesystem->put_contents( $file_tmp_path, $content );
    
    $wp_filetype = wp_check_filetype(basename($file_tmp_path), null );

    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => $post_id,
        'post_content' => '',
        'post_status' => 'inherit'
    );

    $attach_id = wp_insert_attachment( $attachment, $file_tmp_path );

    $imagenew = get_post( $attach_id );
    $fullsizepath = get_attached_file( $imagenew->ID );
    $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
    wp_update_attachment_metadata( $attach_id, $attach_data );
    
    return $attach_id;

}

function anime_update_post_ratings( $post_id, $ratings = array() ){

    if( empty( $ratings ) || !isset( $ratings['avg'] ) || !isset( $ratings['numbers'] ) ){
        return false;
    }

    extract( $ratings );

    $totals = intval( (float)trim($avg) * (float)$numbers );
    $int_avg_totals = intval( $avg ) * $numbers;

    $above_avg_numbers = $totals - $int_avg_totals;
    $int_avg_numbers = $numbers - $above_avg_numbers;

    $rates = array();

    for( $i = 1; $i <= $above_avg_numbers; $i++ ){
        $rates[] = intval( $avg + 1 );
    }

    for( $i = 1; $i <= $int_avg_numbers; $i++ ){
        $rates[] = intval( $avg );
    }

    update_post_meta( $post_id, '_manga_avarage_reviews', $avg );
    update_post_meta( $post_id, '_manga_reviews', $rates );

    return true;
}

function anime_update_post_views( $post_id, $views ){

    $month = date('m');

    update_post_meta( $post_id, '_wp_manga_month_views', array(
        'date' => $month,
        'views' => $views
    ) );
    
    update_post_meta( $post_id, '_wp_manga_views', $views );
    
    $new_year_views = array( 'views' => $views, 'date' => date('y') );
    update_post_meta( $post_id, '_wp_manga_year_views', $new_year_views );
    update_post_meta( $post_id, '_wp_manga_year_views_value', $views );

}
function anime_add_manga_terms( $post_id, $terms, $taxonomy ){

    $terms = explode(',', $terms);

    if( empty( $terms ) ){
        return false;
    }

    $taxonomy_obj = get_taxonomy( $taxonomy );

    if( is_object($taxonomy_obj) && $taxonomy_obj->hierarchical )
    {
        $output_terms = array();
        foreach( $terms as $current_term ){

            if( empty( $current_term ) ){
                continue;
            }
            $term = term_exists( $current_term, $taxonomy );
            if( ! $term || is_wp_error( $term ) ){
                $term = wp_insert_term( $current_term, $taxonomy );
                if( !is_wp_error( $term ) && isset( $term['term_id'] ) ){
                    $term = intval( $term['term_id'] );

                }else{
                    continue;
                }
            }else{
                $term = intval( $term['term_id'] );
            }

            $output_terms[] = $term;
        }

        $terms = $output_terms;
    }

    $resp = wp_set_post_terms( $post_id, $terms, $taxonomy );

    return $resp;

}
function anime_manga_url_filter( $url ){

    $url = str_replace( 'https://', '', $url );
    $url = str_replace( 'http://', '', $url );
    $url = str_replace( '//', '', $url );
    $url = str_replace( 'http:', '', $url );

    return "http://{$url}";
}
function anime_get_page_images( $page_url ){
				
    require_once (dirname(__FILE__) . "/res/simple_html_dom.php"); 
    $page_html = anime_get_web_page($page_url);
    $html = anime_str_get_html( $page_html );

    if( empty( $html ) ){
        anime_log_to_file("Cannot get page images from " . $page_url);
        return false;
    }

    $images = $html->find( '#viewer .read_img a > img' );
    
    if( empty( $images ) ){
        return false;
    }

    $images_url = array();

    foreach( $images as $image ){
        $images_url[] = $image->src;
    }

    return $images_url;

}
$anime_chapter_images = array();
function anime_url_file_name_filter( $name ){
    $name = explode('?', $name);
    return $name[0];
}
function anime_my_user_by_rand( $ua ) {
  remove_action('pre_user_query', 'anime_my_user_by_rand');
  $ua->query_orderby = str_replace( 'user_login ASC', 'RAND()', $ua->query_orderby );
}
function anime_get_upload_cloud_list($upload_cloud_file){
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
       wp_filesystem($creds);
    }
    if ($wp_filesystem->exists( $upload_cloud_file ) ){
        $content = $wp_filesystem->get_contents( $upload_cloud_file );

        return json_decode( $content, true );
    }

    return [];

}

function anime_display_random_user(){
    add_action('pre_user_query', 'anime_my_user_by_rand');
    $args = array(
      'orderby' => 'user_login', 'order' => 'ASC', 'number' => 1
    );
    $user_query = new WP_User_Query( $args );
    $user_query->query();
    $results = $user_query->results;
    if(empty($results))
    {
        return false;
    }
    return array_pop($results);
  }
function anime_put_upload_cloud_list( $item, $upload_cloud_file ){

    $list = anime_get_upload_cloud_list($upload_cloud_file);
    
    if( ! isset( $list[ $item['id'] ] ) )
    {
        global $wp_filesystem;
        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
            include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
            wp_filesystem($creds);
        }
        $list[ $item['id'] ] = $item;
        $wp_filesystem->put_contents( $upload_cloud_file, json_encode( $list, JSON_PRETTY_PRINT ) );
    }

    return true;
}

function anime_randomName() {
    $firstname = array(
        'Johnathon',
        'Anthony',
        'Erasmo',
        'Raleigh',
        'Nancie',
        'Tama',
        'Camellia',
        'Augustine',
        'Christeen',
        'Luz',
        'Diego',
        'Lyndia',
        'Thomas',
        'Georgianna',
        'Leigha',
        'Alejandro',
        'Marquis',
        'Joan',
        'Stephania',
        'Elroy',
        'Zonia',
        'Buffy',
        'Sharie',
        'Blythe',
        'Gaylene',
        'Elida',
        'Randy',
        'Margarete',
        'Margarett',
        'Dion',
        'Tomi',
        'Arden',
        'Clora',
        'Laine',
        'Becki',
        'Margherita',
        'Bong',
        'Jeanice',
        'Qiana',
        'Lawanda',
        'Rebecka',
        'Maribel',
        'Tami',
        'Yuri',
        'Michele',
        'Rubi',
        'Larisa',
        'Lloyd',
        'Tyisha',
        'Samatha',
    );

    $lastname = array(
        'Mischke',
        'Serna',
        'Pingree',
        'Mcnaught',
        'Pepper',
        'Schildgen',
        'Mongold',
        'Wrona',
        'Geddes',
        'Lanz',
        'Fetzer',
        'Schroeder',
        'Block',
        'Mayoral',
        'Fleishman',
        'Roberie',
        'Latson',
        'Lupo',
        'Motsinger',
        'Drews',
        'Coby',
        'Redner',
        'Culton',
        'Howe',
        'Stoval',
        'Michaud',
        'Mote',
        'Menjivar',
        'Wiers',
        'Paris',
        'Grisby',
        'Noren',
        'Damron',
        'Kazmierczak',
        'Haslett',
        'Guillemette',
        'Buresh',
        'Center',
        'Kucera',
        'Catt',
        'Badon',
        'Grumbles',
        'Antes',
        'Byron',
        'Volkman',
        'Klemp',
        'Pekar',
        'Pecora',
        'Schewe',
        'Ramage',
    );

    $name = $firstname[rand ( 0 , count($firstname) -1)];
    $name .= ' ';
    $name .= $lastname[rand ( 0 , count($lastname) -1)];

    return $name;
}
function anime_require_all($dir) {
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
        wp_filesystem($creds);
    }
    $scan = glob("$dir/*");
    foreach ($scan as $path) {
        if ($wp_filesystem->is_dir($path)) {
            anime_require_all($path);
        }
        elseif (preg_match('/\.php$/', $path)) {
            
            include_once $path;
        }
    }
}
function anime_run_rule($param, $type, $auto = 1, $rerun_count = 0)
{
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
        wp_filesystem($creds);
    }
    $theme = wp_get_theme();
    if ( 'Madara' != $theme->name && 'Madara' != $theme->parent_theme ) {
        anime_log_to_file('This plugin requires the Madara theme to work! Please install it from here: https://1.envato.market/madara');
        if($auto == 1)
        {
            anime_clearFromList($param, $type);
        }
        return 'fail';
    }
    if( ! class_exists('WP_MANGA_STORAGE') ) {
        anime_log_to_file('Madara Core Plugin is missing! Please install it from here: https://1.envato.market/madara');
        if($auto == 1)
        {
            anime_clearFromList($param, $type);
        }
        return 'fail';
    }
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    global $wp_embed;
    global $wp_manga;
    global $wp_manga_storage;
    global $wp_manga_chapter;
    global $wp_manga_volume;
    if($rerun_count == 0)
    {
        $f = fopen(get_temp_dir() . 'anime_' . $type . '_' . $param, 'w');
        if($f !== false)
        {
            $flock_disabled = explode(',', ini_get('disable_functions'));
            if(!in_array('flock', $flock_disabled))
            {
                if (!flock($f, LOCK_EX | LOCK_NB)) {
                    return 'nochange';
                }
            }
        }
        $GLOBALS['wp_object_cache']->delete('anime_running_list', 'options');
        if (!get_option('anime_running_list')) {
            $running = array();
        } else {
            $running = get_option('anime_running_list');
        }
        if (!empty($running)) {
            if (in_array(array(
                $param => $type
            ), $running))
            {
                if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                    anime_log_to_file('Only one instance of this rule is allowed. Rule is already running!');
                }
                return 'nochange';
            }
        }
        $running[] = array(
            $param => $type
        );
        update_option('anime_running_list', $running, false);
        register_shutdown_function('anime_clear_flag_at_shutdown', $param, $type);
        if (isset($anime_Main_Settings['rule_timeout']) && $anime_Main_Settings['rule_timeout'] != '') {
            $timeout = intval($anime_Main_Settings['rule_timeout']);
        } else {
            $timeout = 0;
        }
        ini_set('safe_mode', 'Off');
        ini_set('max_execution_time', $timeout);
        ini_set('ignore_user_abort', 1);
        ignore_user_abort(true);
        set_time_limit($timeout);
    }
    else
    {
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('Retrying to run rule, retry count: ' . $rerun_count);
        }
    }
    $anime_imported_chapters = 0;
    if (isset($anime_Main_Settings['anime_enabled']) && $anime_Main_Settings['anime_enabled'] == 'on') {
        try {
            $item_img         = '';
            $cont             = 0;
            $found            = 0;
            $schedule         = '';
            $max              = PHP_INT_MAX;
            $active           = '0';
            $last_run         = '';
            $first            = false;
            $others           = array();
            $list_item        = '';
            $default_category = '';
            $extra_categories = '';
            $posted_items    = array();
            $post_status     = 'publish';
            $accept_comments = 'closed';
            $post_user_name  = 1;
            $can_create_cat  = 'off';
            $item_create_tag = '';
            $can_create_tag  = 'disabled';
            $item_tags       = '';
            $auto_categories = 'disabled';
            $get_img         = '';
            $img_found       = false;
            $post_array      = array();
            $use_phantom     = '';
            $source_site     = 'fanfox.net';
            $manga_name      = '';
            $reverse_chapters= '';
            $result_type     = '';
            $manga_author    = '';
            $manga_artist    = '';
            $manga_genres    = '';
            $manga_exgenres  = '';
            $manga_year_after = '';
            $manga_year_before= '';
            $manga_min_rating = '';
            $manga_completed  = '';
            $manga_sorting    = '';
            $manga_direction  = '';
            $max_manga        = '';
            $continue_search  = '';
            $chapter_warning  = '';
            $enable_pingback  = '';
            $enable_comments  = '';
            $rule_translate   = '';
            $no_translate_title='';
            $get_date         = '';
            $anime_locale     = '';
            $prefer_dubbed    = '';
            $anime_format     = '';
            $anime_status     = '';
            $anime_year       = '';
            $anime_season     = '';
            $anime_genres     = '';
            $also_nsfw        = '';
            if($type == 0)
            {
                return 'fail';
            }
            elseif($type == 1)
            {
                $GLOBALS['wp_object_cache']->delete('anime_text_list', 'options');
                if (!get_option('anime_text_list')) {
                    $rules = array();
                } else {
                    $rules = get_option('anime_text_list');
                }
                if (!empty($rules)) {
                    foreach ($rules as $request => $bundle[]) {
                        if ($cont == $param) {
                            $bundle_values    = array_values($bundle);
                            $myValues         = $bundle_values[$cont];
                            $array_my_values  = array_values($myValues);for($iji=0;$iji<count($array_my_values);++$iji){if(is_string($array_my_values[$iji])){$array_my_values[$iji]=stripslashes($array_my_values[$iji]);}}
                            $manga_name       = isset($array_my_values[0]) ? $array_my_values[0] : '';
                            $schedule         = isset($array_my_values[1]) ? $array_my_values[1] : '';
                            $active           = isset($array_my_values[2]) ? $array_my_values[2] : '';
                            $last_run         = isset($array_my_values[3]) ? $array_my_values[3] : '';
                            $max              = isset($array_my_values[4]) ? $array_my_values[4] : '';
                            $post_status      = isset($array_my_values[5]) ? $array_my_values[5] : '';
                            $post_user_name   = isset($array_my_values[6]) ? $array_my_values[6] : '';
                            $item_create_tag  = isset($array_my_values[7]) ? $array_my_values[7] : '';
                            $default_category = isset($array_my_values[8]) ? $array_my_values[8] : '';
                            $auto_categories  = isset($array_my_values[9]) ? $array_my_values[9] : '';
                            $can_create_tag   = isset($array_my_values[10]) ? $array_my_values[10] : '';
                            $use_phantom      = isset($array_my_values[11]) ? $array_my_values[11] : '';
                            $max_manga        = isset($array_my_values[12]) ? $array_my_values[12] : '';
                            $chapter_warning  = isset($array_my_values[13]) ? $array_my_values[13] : '';
                            $enable_comments  = isset($array_my_values[14]) ? $array_my_values[14] : '';
                            $enable_pingback  = isset($array_my_values[15]) ? $array_my_values[15] : '';
                            $get_date         = isset($array_my_values[16]) ? $array_my_values[16] : '';
                            $rule_translate   = isset($array_my_values[17]) ? $array_my_values[17] : '';
                            $no_translate_title= isset($array_my_values[18]) ? $array_my_values[18] : '';
                            $anime_locale     = isset($array_my_values[19]) ? $array_my_values[19] : '';
                            $prefer_dubbed    = isset($array_my_values[20]) ? $array_my_values[20] : '';
                            $anime_format     = isset($array_my_values[21]) ? $array_my_values[21] : '';
                            $anime_status     = isset($array_my_values[22]) ? $array_my_values[22] : '';
                            $anime_year       = isset($array_my_values[23]) ? $array_my_values[23] : '';
                            $anime_season     = isset($array_my_values[24]) ? $array_my_values[24] : '';
                            $anime_genres     = isset($array_my_values[25]) ? $array_my_values[25] : '';
                            $also_nsfw        = isset($array_my_values[26]) ? $array_my_values[26] : '';
                            $found            = 1;
                            break;
                        }
                        $cont = $cont + 1;
                    }
                } else {
                    anime_log_to_file('No rules found for anime_text_list!');
                    if($auto == 1)
                    {
                        anime_clearFromList($param, $type);
                    }
                    return 'fail';
                }
                if ($found == 0) {
                    anime_log_to_file($param . ' not found in anime_text_list!');
                    if($auto == 1)
                    {
                        anime_clearFromList($param, $type);
                    }
                    return 'fail';
                } else {
                    if($rerun_count == 0)
                    {
                        $GLOBALS['wp_object_cache']->delete('anime_text_list', 'options');
                        $rules = get_option('anime_text_list');
                        $rules[$param][3] = anime_get_date_now();
                        update_option('anime_text_list', $rules, false);
                    }
                }
            }
            else
            {
                anime_log_to_file('Unrecognized rule type: ' . $type);
                if($auto == 1)
                {
                    anime_clearFromList($param, $type);
                }
                return 'fail';
            }                
            $ffmpeg_res = get_option('anime_ffmpeg_res', false);
            if($ffmpeg_res !== '1')
            {
                if(isset($anime_Main_Settings['enable_detailed_logging']) && $anime_Main_Settings['enable_detailed_logging'] == 'on')
                {
                    anime_log_to_file('FFMPEG not detected - please be sure you have it installed on your server. Also, please try to set the correct path, where the ffmpeg executable can be found on the server, in the "FFMPEG Path On Server" settings field from plugin\'s "Main Settings" menu.');
                }
                return;
            }
            $user_name_type = $post_user_name;
            if($type == 0)
            {
                return 'fail';
            }
            elseif($type == 1)
            {
                $api_url = 'https://api.aniapi.com/v1/anime';
                if(trim($manga_name) != '' && trim($manga_name) != '*')
                {
                    $api_url .= '?title=' . trim($manga_name);
                }
                if($anime_format != '' && $anime_format != 'any')
                {
                    if(strstr($api_url, '?') !== false)
                    {
                        $api_url .= '&format=' . $anime_format;
                    }
                    else
                    {
                        $api_url .= '?format=' . $anime_format;
                    }
                }
                if($anime_status != '' && $anime_status != 'any')
                {
                    if(strstr($api_url, '?') !== false)
                    {
                        $api_url .= '&status=' . $anime_status;
                    }
                    else
                    {
                        $api_url .= '?status=' . $anime_status;
                    }
                }
                if($anime_season != '' && $anime_season != 'any')
                {
                    if(strstr($api_url, '?') !== false)
                    {
                        $api_url .= '&season=' . $anime_season;
                    }
                    else
                    {
                        $api_url .= '?season=' . $anime_season;
                    }
                }
                if(trim($anime_genres) != '')
                {
                    if(strstr($api_url, '?') !== false)
                    {
                        $api_url .= '&genres=' . trim($anime_genres);
                    }
                    else
                    {
                        $api_url .= '?genres=' . trim($anime_genres);
                    }
                }
                if($also_nsfw == '1')
                {
                    if(strstr($api_url, '?') !== false)
                    {
                        $api_url .= '&nsfw=true';
                    }
                    else
                    {
                        $api_url .= '?nsfw=true';
                    }
                }
                else
                {
                    if(strstr($api_url, '?') !== false)
                    {
                        $api_url .= '&nsfw=false';
                    }
                    else
                    {
                        $api_url .= '?nsfw=false';
                    }
                }
                if(trim($anime_year) != '')
                {
                    if(strstr($api_url, '?') !== false)
                    {
                        $api_url .= '&year=' . trim($anime_year);
                    }
                    else
                    {
                        $api_url .= '?year=' . trim($anime_year);
                    }
                }
                $html_site = anime_api_get_web_page($api_url);
                if($html_site == false)
                {
                    anime_log_to_file('Failed to access aniapi database: ' . $api_url);
                    return 'fail';
                }
                $htmlx_site = json_decode($html_site);
                if($htmlx_site == false)
                {
                    anime_log_to_file('Failed to decode aniapi database: ' . $api_url . ' - ' . $html_site);
                    return 'fail';
                }
                if(!isset($htmlx_site->data->documents[0]->id))
                {
                    anime_log_to_file('Failed to parse aniapi database (when looking for anime): ' . $api_url . ' - ' . $html_site);
                    return 'fail';
                }
                if(trim($max_manga) != '')
                {
                    $get_max_manga = intval(trim($max_manga));
                }
                else
                {
                    $get_max_manga = 999;
                }
                $items = array();
                $page_increased = false;
                {
                    try
                    {
                        global $wp_filesystem;
                        if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                            include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                            wp_filesystem($creds);
                        }
                        $scraped_manga = 0;
                        
                        if(isset($anime_Main_Settings['storage']) && $anime_Main_Settings['storage'] != '')
                        {
                            $storage = $anime_Main_Settings['storage'];
                        }
                        else
                        {
                            $storage = 'local';
                        }
                        if($storage == 's3')
                        {
                            if(!isset($anime_Main_Settings['bucket_name']) || $anime_Main_Settings['bucket_name'] == '')
                            {
                                anime_log_to_file('If you select to store the anime files in the S3 Cloud, you need to enter a bucket name in the plugin\'s settings!');
                                return 'fail';
                            }
                            else
                            {
                                $bucket_name = trim($anime_Main_Settings['bucket_name']);
                            }
                            if(!isset($anime_Main_Settings['s3_user']) || $anime_Main_Settings['s3_user'] == '')
                            {
                                anime_log_to_file('If you select to store the anime files in the S3 Cloud, you need to enter a client name for S3 in the plugin\'s settings!');
                                return 'fail';
                            }
                            else
                            {
                                $s3_user = trim($anime_Main_Settings['s3_user']);
                            }
                            if(!isset($anime_Main_Settings['s3_pass']) || $anime_Main_Settings['s3_pass'] == '')
                            {
                                anime_log_to_file('If you select to store the anime files in the S3 Cloud, you need to enter a client secret for S3 in the plugin\'s settings!');
                                return 'fail';
                            }
                            else
                            {
                                $s3_pass = trim($anime_Main_Settings['s3_pass']);
                            }
                            if(!isset($anime_Main_Settings['bucket_region']) || $anime_Main_Settings['bucket_region'] == '')
                            {
                                $bucket_region = '';
                            }
                            else
                            {
                                $bucket_region = trim($anime_Main_Settings['bucket_region']);
                            }
                            if ('' == trim($bucket_region))
                            {
                                $bucket_region = 'eu-central-1';
                            }
                            if(!function_exists('GuzzleHttp\\Promise\\queue'))
                            {
                                anime_require_all(dirname(__FILE__) . "/res/Guzzle");
                            }
                            require_once(dirname(__FILE__) . '/res/aws/aws-autoloader.php');
                            try
                            {
                                $credentials = array('key' => $s3_user, 'secret' => $s3_pass);
                                $s3 = new S3Client([
                                    'version' => 'latest',
                                    'region'  => $bucket_region,
                                    'credentials' => $credentials
                                ]);
                            }
                            catch(Exception $e)
                            {
                                anime_log_to_file('Failed to initialize Amazon S3 API: ' . $e->getMessage());
                                return 'fail';
                            }
                        }
                        foreach($htmlx_site->data->documents as $current_anime)
                        {
                            $latest_chapter = '';
                            if($get_max_manga <= $scraped_manga)
                            {
                                break;
                            }
                            $my_slug = '';
                            if(isset($current_anime->titles->en))
                            {
                                $za_title = $current_anime->titles->en;
                            }
                            elseif(isset($current_anime->titles->jp))
                            {
                                $za_title = $current_anime->titles->jp;
                            }
                            elseif(isset($current_anime->titles->it))
                            {
                                $za_title = $current_anime->titles->it;
                            }
                            else
                            {
                                anime_log_to_file('Cannot find anime title: ' . print_r($current_anime, true));
                                continue;
                            }
                            $my_slug = sanitize_title($za_title);
                            $name_str = $za_title;
                            if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                anime_log_to_file('Processing anime: ' . $name_str);
                            }
                            $find_post = new WP_Query( array(
                                'title'     => $name_str,
                                'post_type' => 'wp-manga',
                            ) );
                            $existing_post_id = false;
                            if( $find_post->have_posts() )
                            {
                                $existing_post_id = $find_post->posts[0]->ID;
                            }
                            if($existing_post_id == false)
                            {
                                $args = array(
                                    'post_type'  => 'wp-manga',
                                    'meta_key'   => '_manga_import_slug',
                                    'meta_value' => $my_slug,
                                    'post_status' => array('publish','draft','pending','trash','private','future')
                                );
                                $query = new WP_Query( $args );
                                if( $query->have_posts() ){
                                    $existing_post_id = $query->posts[0]->ID;
                                }
                            }
                            $thumb = '';
                            $anime_id = '';
                            if($existing_post_id == false)
                            {
                                if(isset($current_anime->descriptions->en))
                                {
                                    $desc = $current_anime->descriptions->en;
                                }
                                elseif(isset($current_anime->descriptions->jp))
                                {
                                    $desc = $current_anime->descriptions->jp;
                                }
                                elseif(isset($current_anime->descriptions->it))
                                {
                                    $desc = $current_anime->descriptions->it;
                                }
                                else
                                {
                                    $desc = '';
                                }
                                if(isset($current_anime->cover_image))
                                {
                                    $thumb = $current_anime->cover_image;
                                }
                                if(isset($current_anime->status))
                                {
                                    $za_st = $current_anime->status;
                                    if($za_st == '0')
                                    {
                                        $status = 'end';
                                    }
                                    elseif($za_st == '1')
                                    {
                                        $status = 'on-going';
                                    }
                                    elseif($za_st == '2')
                                    {
                                        $status = 'upcoming';
                                    }
                                    elseif($za_st == '3')
                                    {
                                        $status = 'canceled';
                                    }
                                    else
                                    {
                                        $status = 'on-going';
                                    }
                                }
                                else
                                {
                                    $status = 'on-going';
                                }
                                if(isset($current_anime->titles->jp))
                                {
                                    $alter_name = $current_anime->titles->jp;
                                }
                                elseif(isset($current_anime->titles->it))
                                {
                                    $alter_name = $current_anime->titles->it;
                                }
                                else
                                {
                                    $alter_name = '';
                                }
                                $xtype = 'Anime';
                                $xrelease = '2021';
                                if(isset($current_anime->season_year))
                                {
                                    $xrelease = $current_anime->season_year;
                                }
                                $xauthor = '';
                                $author = '';
                                $xartists = '';
                                $xgenres = '';
                                if(isset($current_anime->genres))
                                {
                                    $xgenres = implode(',', $current_anime->genres);
                                }
                                $viewsm = rand(100, 1000);
                                $average_vote = '';
                                if(isset($current_anime->score))
                                {
                                    $average_vote = $current_anime->score;
                                    $average_vote = $average_vote / 20;
                                    $average_vote = number_format($average_vote, 1, '.', '');
                                }
                                $xrating = array(
                                    'avg'     => $average_vote,
                                    'numbers' => rand(100,1000)
                                );
                                $xtags = $xgenres;
                                $xtime_year = '';
                                if(isset($current_anime->start_date))
                                {
                                    $xtime_year = $current_anime->start_date;$time_year = strtotime($xrelease);
                                    if($time_year !== false)
                                    {
                                        $time_year = date("Y", $time_year);
                                        if($time_year !== false)
                                        {
                                            $xtime_year = $time_year;
                                        }
                                    }
                                }
                                $post_args = array(
                                    'manga_import_slug' => $my_slug,
                                    'title'             => $name_str,
                                    'post_status'       => $post_status,
                                    'description'       => $desc,
                                    'thumb'             => $thumb,
                                    'status'            => $status,
                                    'altername'         => strip_tags( $alter_name ),
                                    'type'              => strip_tags( $xtype ),
                                    'release'           => $xtime_year,
                                    'authors'           => strip_tags( $xauthor ),
                                    'artists'           => strip_tags( $xartists ),
                                );
                                $anime_id = $current_anime->id;
                                $post_args['anime_id'] = $anime_id;
                                $post_args['genres'] = strip_tags( $xgenres );
                                $post_args['views'] = strip_tags( $viewsm  );
                                $post_args['ratings'] = $xrating;
                                $post_args['tags'] = strip_tags( $xtags );
                                $arr = anime_spin_and_translate($post_args['title'], $post_args['description'], $rule_translate, 'ar');
                                if($arr === false)
                                {
                                    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                        anime_log_to_file('Skipping anime (because it failed to be translated): ' . print_r($post_args, true));
                                    }
                                }
                                if($no_translate_title != '1')
                                {
                                    $post_args['title']              = $arr[0];
                                }
                                $post_args['description']        = $arr[1];
                                $za_xpost_args = array(
                                    'post_title'   => !empty( $post_args['title'] ) ? $post_args['title'] : '',
                                    'post_content' => !empty( $post_args['description'] ) ? $post_args['description'] : '',
                                    'post_type'    => 'wp-manga',
                                    'post_status'  => isset( $post_args['post_status'] ) ? $post_args['post_status'] : 'pending',
                                );
                                $za_xpost_args['anime_id'] = $current_anime->id;
                                $existing_again = false;
                                if($za_xpost_args['post_title'] != '')
                                {
                                    $ex_page = get_page_by_title($za_xpost_args['post_title'], OBJECT, 'wp-manga');
                                    if(isset($ex_page->ID))
                                    {
                                        $existing_again = true;
                                        $existing_post_id = $ex_page->ID;
                                    }
                                }
                                if($existing_again == false)
                                {
                                    $accept_comments = 'closed';
                                    if ($enable_comments == '1') {
                                        $accept_comments = 'open';
                                    }
                                    $za_xpost_args['comment_status'] = $accept_comments;
                                    if ($enable_pingback == '1') 
                                    {
                                        $za_xpost_args['ping_status'] = 'open';
                                    } 
                                    else 
                                    {
                                        $za_xpost_args['ping_status'] = 'closed';
                                    }
                                    if($get_date == '1')
                                    {
                                        if(!empty($xrelease))
                                        {
                                            $postdatex = gmdate("Y-m-d H:i:s", strtotime($xrelease));
                                            $za_xpost_args['post_date_gmt'] = $postdatex;
                                        }
                                    }
                                    if($user_name_type == 'rand')
                                    {
                                        $randid = anime_display_random_user();
                                        if($randid === false)
                                        {
                                            $za_xpost_args['post_author']               = anime_randomName();
                                        }
                                        else
                                        {
                                            $za_xpost_args['post_author']               = $randid->ID;
                                        }
                                    }
                                    elseif($user_name_type == 'feed-news')
                                    {
                                        $sp_post_user_name = anime_randomName();
                                        if($author == '' || $author == '1' || $author == 'null')
                                        {
                                            $author = anime_randomName();
                                        }
                                        if($author != '')
                                        {
                                            $xauthor = sanitize_user( $author, true );
                                            $xauthor = apply_filters( 'pre_user_login', $xauthor );
                                            $xauthor = trim( $xauthor );
                                            if(username_exists( $xauthor ))
                                            {
                                                $user_id_t = get_user_by('login', $xauthor);
                                                if($user_id_t)
                                                {
                                                    $sp_post_user_name = $user_id_t->ID;
                                                }
                                            }
                                            else
                                            {
                                                $palphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^*()-+=_?><,.;:}{][';
                                                $ppass = '';
                                                $alphaLength = strlen($palphabet) - 1;
                                                for ($ipass = 0; $ipass < 8; $ipass++) 
                                                {
                                                    $npass = rand(0, $alphaLength);
                                                    $ppass .= $palphabet[$npass];
                                                }
                                                $curr_id = wp_create_user($author, $ppass, anime_generate_random_email());
                                                if ( is_int($curr_id) )
                                                {
                                                    $u = new WP_User($curr_id);
                                                    $u->remove_role('subscriber');
                                                    $u->add_role('author');
                                                    $sp_post_user_name               = $curr_id;
                                                }
                                            }
                                        }
                                        $za_xpost_args['post_author']               = anime_utf8_encode($sp_post_user_name);
                                    }
                                    else
                                    {
                                        $za_xpost_args['post_author']               = anime_utf8_encode($post_user_name);
                                    }
                                    
                                    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                        anime_log_to_file('Inserting new anime: ' . $za_xpost_args['post_title']);
                                    }
                                    $existing_post_id = wp_insert_post( $za_xpost_args );
                                    if( ! $existing_post_id && is_wp_error( $existing_post_id ) ){
                                        anime_log_to_file('Failed to insert anime into db: ' . $post_args['title']);
                                        continue;
                                    }
                                    add_post_meta($existing_post_id, 'anime_parent_rule', $type . '-' . $param);
                                    add_post_meta($existing_post_id, 'anime_id', $za_xpost_args['anime_id']);
                                    wp_set_object_terms( $existing_post_id, 'anime_' . $type . '_' . $param, 'coderevolution_post_source', true);
                                    if($thumb != '')
                                    {
                                        $thumb_id = anime_wp_mcl_e_upload_file( $post_args['thumb'], $existing_post_id );
                                        if($thumb_id === false)
                                        {
                                            include_once( ABSPATH . 'wp-admin/includes/image.php' );
                                            $thcontent = anime_get_web_page($thumb);
                                            $pathinfo = pathinfo( $thumb );
                                            if( $thcontent != false ){
                                                $upload_dir = wp_upload_dir();
                                                $file_tmp_path = $upload_dir['basedir'] . '/' . $pathinfo['filename'] . '-' . $existing_post_id . '.' . explode('?',$pathinfo['extension'])[0];
                                                $file = $wp_filesystem->put_contents( $file_tmp_path, $thcontent );
                                                $wp_filetype = wp_check_filetype(basename($file_tmp_path), null );
                                                $attachment = array(
                                                    'post_mime_type' => $wp_filetype['type'],
                                                    'post_title' => $existing_post_id,
                                                    'post_content' => '',
                                                    'post_status' => 'inherit'
                                                );
                                                $attach_id = wp_insert_attachment( $attachment, $file_tmp_path );
                                                $thumb_id = $attach_id;
                                                $imagenew = get_post( $attach_id );
                                                $fullsizepath = get_attached_file( $imagenew->ID );
                                                $attach_data = wp_generate_attachment_metadata( $attach_id, $fullsizepath );
                                                wp_update_attachment_metadata( $attach_id, $attach_data );
                                            }
                                        }
                                    }
                                    else
                                    {
                                        $thumb_id = false;
                                    }
                                    $meta_data = array(
                                        '_manga_import_slug'     => $my_slug,
                                        '_thumbnail_id'          => $thumb_id,
                                        '_wp_manga_alternative'  => strip_tags( $alter_name ),
                                        '_wp_manga_type'         => strip_tags( $xtype ),
                                        '_wp_manga_status'       => $status,
                                        '_wp_manga_chapter_type' => 'video',
                                        '_wp_manga_chapters_warning'=> $chapter_warning,
                                    );
                                    foreach( $meta_data as $key => $value ){
                                        if( !empty( $value ) ){
                                            update_post_meta( $existing_post_id, $key, $value );
                                        }
                                    }
                                    $manga_terms = array(
                                        'wp-manga-release'     => strip_tags( $xrelease ),
                                        'wp-manga-author'      => strip_tags( $xauthor ),
                                        'wp-manga-artist'      => strip_tags( $xartists ),
                                        'wp-manga-genre'       => strip_tags( $xgenres ),
                                        'wp-manga-tag'         => strip_tags( $xtags ),
                                    );
                                    foreach( $manga_terms as $tax => $term ){
                                        $resp = anime_add_manga_terms( $existing_post_id, $term, $tax );
                                    }
                                    anime_update_post_views( $existing_post_id, strip_tags( $viewsm ) );
                                    anime_update_post_ratings( $existing_post_id, $xrating );
                                }
                                else
                                {
                                    $anime_id = get_post_meta($existing_post_id, 'anime_id', true);
                                }
                            }

                            $skip_posts_temp = get_option('anime_continue_search', array());
                            if(isset($skip_posts_temp[$param]) && $skip_posts_temp[$param] != '')
                            {
                                if(stristr($skip_posts_temp[$param], $current_anime) === false)
                                {
                                    $skip_posts_temp[$param] = '';
                                    update_option('anime_continue_search', $skip_posts_temp);
                                    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                        anime_log_to_file('Anime URL changed: ' . $current_anime);
                                    }
                                }
                                else
                                {
                                    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                        anime_log_to_file('Loading last scraped chapter from memory: ' . $skip_posts_temp[$param] . ' (replacing: ' . $latest_chapter . ')');
                                    }
                                    $latest_chapter = $skip_posts_temp[$param];
                                }
                            }
                            $anime_max_chapters = $max;
                            $local_imported = 0;
                            if($anime_max_chapters <= $local_imported)
                            {
                                return 'nochange';
                            }
                            $new_chap = false;
                            $cc = 1;
                            if($anime_id == '')
                            {
                                $anime_id = $current_anime->id;
                            }
                            $anime_locale = trim($anime_locale);
                            if($anime_locale == '')
                            {
                                $anime_locale = 'en';
                            }
                            if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                anime_log_to_file('Getting episodes for: https://api.aniapi.com/v1/episode?anime_id=' . $anime_id . '&locale=' . $anime_locale);
                            }
                            $html_site = anime_api_get_web_page('https://api.aniapi.com/v1/episode?anime_id=' . $anime_id . '&locale=' . $anime_locale);
                            if($html_site == false)
                            {
                                anime_log_to_file('Failed to access aniapi database.');
                                return 'fail';
                            }
                            $htmlx_site = json_decode($html_site);
                            if($htmlx_site == false)
                            {
                                anime_log_to_file('Failed to decode aniapi database: ' . $html_site);
                                return 'fail';
                            }
                            if(!isset($htmlx_site->data->documents[0]->id))
                            {
                                if(!isset($htmlx_site->data->documents[0]->id))
                                {
                                    if(isset($htmlx_site->message))
                                    {
                                        anime_log_to_file('AniAPI returned: ' . $htmlx_site->message . ' - for: ' . $za_title);
                                        if (isset($anime_Main_Settings['delete_no_episodes']) && $anime_Main_Settings['delete_no_episodes'] == 'on')
                                        {
                                            wp_delete_post($existing_post_id, true);
                                        }
                                        else
                                        {
                                            $updatex['ID'] = $existing_post_id;
                                            $updatex['post_status'] = 'draft';
                                            wp_update_post($updatex);
                                        }
                                        continue;
                                    }
                                }
                                anime_log_to_file('Failed to parse aniapi database: ' . $html_site);
                                return 'fail';
                            }
                            foreach($htmlx_site->data->documents as $latest_chapter)
                            {
                                if($anime_max_chapters <= $local_imported)
                                {
                                    break;
                                }
                                if(!isset($latest_chapter->video))
                                {
                                    anime_log_to_file('Video file not found: ' . print_r($latest_chapter, true));
                                    continue;
                                }
                                if($prefer_dubbed == '1')
                                {
                                    if(stristr($latest_chapter->source, 'dub') === false)
                                    {
                                        anime_log_to_file('Skipping video, not dubbed: ' . print_r($latest_chapter->video, true));
                                        continue;
                                    }
                                }
                                if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                    anime_log_to_file('Processing anime episode: ' . print_r($latest_chapter, true));
                                }
                                $cname = 'Episode ' . $latest_chapter->number;
                                $slugified_name = $wp_manga_storage->slugify($cname);
                                $chapter_2 = $wp_manga_chapter->get_chapter_by_slug( $existing_post_id, $slugified_name );
                                if($chapter_2 && strtolower($chapter_2['chapter_slug']) == strtolower($slugified_name))
                                {
                                    anime_log_to_file('Chapter already published, skipping it: ' . $chapter_2['chapter_name']);
                                    continue;
                                }
                                $current_episode = $latest_chapter->video;

                                $upload_dir = wp_upload_dir();
                                $dir_name   = $upload_dir['basedir'] . '/anime-files';
                                $dir_url    = $upload_dir['baseurl'] . '/anime-files';
                                global $wp_filesystem;
                                if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
                                    include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
                                    wp_filesystem($creds);
                                }
                                if (!$wp_filesystem->exists($dir_name)) {
                                    wp_mkdir_p($dir_name);
                                }
                                if(isset($anime_Main_Settings['chunk_size']) && $anime_Main_Settings['chunk_size'] != '')
                                {
                                    $chunksize = $anime_Main_Settings['chunk_size'];
                                }
                                else
                                {
                                    $chunksize = 5 * (1024 * 1024);
                                }
                                $local_file_name = $dir_name . '/' . sanitize_title($name_str) . '-' . $latest_chapter->number . '.mp4';
                                $remote_file_name = $dir_url . '/' . sanitize_title($name_str) . '-' . $latest_chapter->number . '.mp4';
                                $name_x = sanitize_title($name_str) . '-' . $latest_chapter->number . '.mp4';

                                if (isset($anime_Main_Settings['ffmpeg_path']) && $anime_Main_Settings['ffmpeg_path'] != '') 
                                {
                                    $ffmpeg_comm = $anime_Main_Settings['ffmpeg_path'] . ' ';
                                }
                                else
                                {
                                    $ffmpeg_comm = 'ffmpeg ';
                                }
                                $vheaders = '';
                                if(isset($latest_chapter->video_headers))
                                {
                                    foreach($latest_chapter->video_headers as $vindex => $vhds)
                                    {
                                        $vheaders .= $vindex . ': ' . $vhds . '\r\n';
                                    }
                                }
                                if($vheaders != '')
                                {
                                    $ffmpeg_comm .= '-headers "' . $vheaders . '" ';
                                }
                                $ffmpeg_comm .= ' -i ' . $current_episode . ' ' . '-c copy ' . $local_file_name;

                                if(isset($anime_Main_Settings['enable_detailed_logging']) && $anime_Main_Settings['enable_detailed_logging'] == 'on')
                                {
                                    anime_log_to_file('Sending command to ffmpeg: ' . $ffmpeg_comm);
                                }
                                
                                $shefunc = trim(' s ') . trim(' h ') . 'ell' . '_exec';
                                $cmdResult = $shefunc($ffmpeg_comm . ' 2>&1');
                                if($cmdResult === null)
                                {
                                    anime_log_to_file ("Failed to create video (null result)! Cmd: " . $ffmpeg_comm);
                                    continue;
                                }
                                if(!$wp_filesystem->exists($local_file_name))
                                {
                                    anime_log_to_file ("Failed to create video (file not found)! Cmd: " . $ffmpeg_comm);
                                    continue;
                                }

                                if($storage == 's3')
                                {
                                    $current_episode_local = $local_file_name;
                                    if(isset($anime_Main_Settings['drive_directory']) && $anime_Main_Settings['drive_directory'] != '')
                                    {
                                        $drive_directory = $anime_Main_Settings['drive_directory'];
                                    }
                                    else
                                    {
                                        $drive_directory = '';
                                    }
                                    if ($drive_directory != '') {
                                        $s3_remote_path = trim($drive_directory, '/');
                                        $s3_remote_path = trailingslashit($s3_remote_path);
                                    }
                                    else
                                    {
                                        $s3_remote_path = '';
                                    }
                                    try 
                                    {
                                        $filesize = anime_retrieve_remote_file_size($current_episode_local);       
                                        if($filesize == false || $filesize == -1)
                                        {
                                            $filesize = anime_getRemoteFilesize($current_episode_local);
                                            if($filesize == false || $filesize == -1)
                                            {
                                                throw new Exception('Failed to get remote file size: ' . $current_episode_local);
                                            }
                                        }
                                        $read_file = fopen($current_episode_local, 'r');
                                        if($read_file === false)
                                        {
                                            throw new Exception('Failed to read file: ' . $current_episode_local);
                                        }
                                        $obj_arr = [
                                            'Bucket' => $bucket_name,
                                            'Key'    => $s3_remote_path . $existing_post_id . $cname,
                                            'Body'   => new CachingStream(
                                                new Stream($read_file)
                                            ),
                                            'ContentLength' => $filesize,
                                            'Content-Length' => $filesize,
                                        ];
                                        $obj_arr['ACL'] = 'public-read';    
                                        $amaz = $s3->putObject($obj_arr);
                                        $wp_filesystem->delete($local_file_name);
                                        if(isset($amaz['ObjectURL']))
                                        {
                                            $remote_file_name = $amaz['ObjectURL'];
                                        }
                                        else
                                        {
                                            anime_log_to_file('Failed to parser Amazon S3 Upload Result: ' . print_r($amaz, true));
                                            return 'fail';
                                        }
                                    } 
                                    catch (Aws\S3\Exception\S3Exception $e) 
                                    {
                                        $wp_filesystem->delete($local_file_name);
                                        anime_log_to_file ("There was an error uploading the file: " . $local_file_name . ' (deleted) - error: ' . $e->getMessage());
                                        return 'fail';
                                    }
                                }
                                else
                                {
                                    $wp_filetype = wp_check_filetype( $local_file_name, null );
                                    $attachment = array(
                                        'post_mime_type' => $wp_filetype['type'],
                                        'post_title' => sanitize_file_name( $name_x ),
                                        'post_content' => '',
                                        'post_status' => 'inherit'
                                    );
                                    $screens_attach_id = wp_insert_attachment($attachment, $local_file_name, $existing_post_id);
                                    require_once( ABSPATH . 'wp-admin/includes/image.php' );
                                    require_once( ABSPATH . 'wp-admin/includes/media.php' );
                                    $attach_data = wp_generate_attachment_metadata($screens_attach_id, $local_file_name);
                                    wp_update_attachment_metadata( $screens_attach_id, $attach_data );
                                }
                                $content = '[video src="' . $remote_file_name . '"';
                                if (isset($anime_Main_Settings['player_height']) && $anime_Main_Settings['player_height'] != '') {
                                    $content .= ' height="' . $anime_Main_Settings['player_height'] . '" ';
                                }
                                if (isset($anime_Main_Settings['player_width']) && $anime_Main_Settings['player_width'] != '') {
                                    $content .= ' width="' . $anime_Main_Settings['player_width'] . '" ';
                                }
                                $content .= ']';
                                
                                global $wp_manga_text_type;
                                $chapter_args = array(
                                    'post_id'             => $existing_post_id,
                                    'chapter_name'        => 'Episode ' . $latest_chapter->number,
                                    'chapter_name_extend' => '',
                                    'volume_id'           => '',
                                    'chapter_content'     => $content,
                                );
                                $chapter_id = $wp_manga_text_type->insert_chapter( $chapter_args );
                                if( $chapter_id ){
                                    if( is_wp_error( $chapter_id ) ){
                                        anime_log_to_file('Failed to insert chapter: ' . $cname . ' error: ' . $chapter_id->get_error_message());
                                    }
                                    else
                                    {
                                        $new_chap = true;
                                        $anime_imported_chapters++;
                                        $local_imported++;
                                    }
                                }
                                if (isset($anime_Main_Settings['request_timeout']) && $anime_Main_Settings['request_timeout'] != '') {
                                    $timeout = intval($anime_Main_Settings['request_timeout']);
                                } else {
                                    $timeout = 1;
                                }
                                sleep($timeout);
                            }
                            if($new_chap == true)
                            {
                                if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                                    anime_log_to_file('Total anime episodes scraped: ' . $local_imported);
                                }
                                $scraped_manga++;
                            }                            
                        }
                    }
                    catch(Exception $e)
                    {
                        anime_log_to_file('Importing failed: ' . $e->getMessage());
                        if($auto == 1)
                        {
                            anime_clearFromList($param, $type);
                        }
                        return 'fail';
                    }
                }
            }
        }
        catch (Exception $e) {
            if($continue_search == '1')
            {
                $skip_posts_temp[$param][$type] = 1;
                update_option('anime_continue_search', $skip_posts_temp);
            }
            anime_log_to_file('Exception thrown ' . esc_html($e->getMessage()) . '!');
            if($auto == 1)
            {
                anime_clearFromList($param, $type);
            }
            return 'fail';
        }
        
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('Rule ID ' . esc_html($param) . ' succesfully run! ' . esc_html($anime_imported_chapters) . ' chapters created!');
        }
    }
    if($type == 0)
    {
        if ($anime_imported_chapters == 0) 
        {
            if($continue_search == '1')
            {
                if($page_increased == false)
                {
                    if(trim($max_manga) != '')
                    {
                        if(isset($skip_posts_temp[$param][$type]))
                        {
                            $skip_posts_temp[$param][$type] += 1;
                        }
                        else
                        {
                            $skip_posts_temp[$param][$type] = 2;
                        }
                        update_option('anime_continue_search', $skip_posts_temp);
                    }
                    else
                    {
                        $skip_posts_temp[$param][$type] = 1;
                        update_option('anime_continue_search', $skip_posts_temp);
                    }
                }
            }
            if($auto == 1)
            {
                anime_clearFromList($param, $type);
            }
            return 'nochange';
        } 
        else 
        {
            if($auto == 1)
            {
                anime_clearFromList($param, $type);
            }
            return 'ok';
        }
    }
    else
    {
        if($auto == 1)
        {
            anime_clearFromList($param, $type);
        }
        if ($anime_imported_chapters == 0) 
        {
            return 'nochange';
        }
        else
        {
            return 'ok';
        }
    }
}

function anime_realFileSize($path)
{
    global $wp_filesystem;
    if ( ! is_a( $wp_filesystem, 'WP_Filesystem_Base') ){
        include_once(ABSPATH . 'wp-admin/includes/file.php');$creds = request_filesystem_credentials( site_url() );
        wp_filesystem($creds);
    }
    if (!$wp_filesystem->exists($path))
        return false;

    $size = $wp_filesystem->size($path);
    if($size === false)
    {
        return false;
    }
    if (!($file = fopen($path, 'rb')))
        return false;
    
    if ($size >= 0)
    {
        if (fseek($file, 0, SEEK_END) === 0)
        {
            fclose($file);
            return $size;
        }
    }
    $size = PHP_INT_MAX - 1;
    if (fseek($file, PHP_INT_MAX - 1) !== 0)
    {
        fclose($file);
        return false;
    }
    $length = 1024 * 1024;
    while (!feof($file))
    {
        $read = fread($file, $length);
        if(function_exists('bcadd'))
        {
            $size = bcadd($size, $length);
        }
        else
        {
            $size = $size + $length;
        }
    }
    if(function_exists('bcsub'))
    {
        $size = bcsub($size, $length);
    }
    else
    {
        $size = $size - $length;
    }
    if(function_exists('bcadd'))
    {
        $size = bcadd($size, strlen($read));
    }
    else
    {
        $size = $size + strlen($read);
    }
    fclose($file);
    return $size;
}
function anime_getRemoteFilesize($url, $formatSize = true, $useHead = true)
{
    if (false !== $useHead) {
        stream_context_set_default(array('http' => array('method' => 'HEAD')));
    }
    $head = array_change_key_case(get_headers($url, 1));
    // content-length of download (in bytes), read from Content-Length: field
    $clen = isset($head['content-length']) ? $head['content-length'] : 0;

    // cannot retrieve file size, return "-1"
    if (!$clen) {
        return -1;
    }

    if (!$formatSize) {
        return $clen; // return size in bytes
    }

    $size = $clen;
    switch ($clen) {
        case $clen < 1024:
            $size = $clen .' B'; break;
        case $clen < 1048576:
            $size = round($clen / 1024, 2) .' KiB'; break;
        case $clen < 1073741824:
            $size = round($clen / 1048576, 2) . ' MiB'; break;
        case $clen < 1099511627776:
            $size = round($clen / 1073741824, 2) . ' GiB'; break;
    }

    return $size; // return formatted size
}
function anime_retrieve_remote_file_size($url){
	 $anime_Main_Settings = get_option('anime_Main_Settings', false);
     $ch = curl_init($url);
	 if (isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '') {
			curl_setopt($ch, CURLOPT_PROXY, $anime_Main_Settings['proxy_url']);
			if (isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '') {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $anime_Main_Settings['proxy_auth']);
			}
	 }
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
     curl_setopt($ch, CURLOPT_HEADER, TRUE);
     curl_setopt($ch, CURLOPT_NOBODY, TRUE);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
     curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
     curl_setopt($ch, CURLOPT_TIMEOUT, 60);
     $data = curl_exec($ch);
     $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
     if($size == '' || $size == -1)
     {
        $content_length = -1;
        $status = 0;
        if( preg_match( "/^HTTP\/1\.[01] (\d\d\d)/", $data, $matches ) ) {
            $status = (int)$matches[1];
        }
    
        if( preg_match( "/Content-Length: (\d+)/", $data, $matches ) ) {
            $content_length = (int)$matches[1];
        }
        if( $status == 200 || ($status > 300 && $status <= 308) ) {
            $size = $content_length;
        }
     }
     curl_close($ch);
     return $size;
}
function anime_repairHTML($text)
{
    $text = htmlspecialchars_decode($text);
    $text = str_replace("< ", "<", $text);
    $text = str_replace(" >", ">", $text);
    $text = str_replace("= ", "=", $text);
    $text = str_replace(" =", "=", $text);
    $text = str_replace("\/ ", "\/", $text);
    $text = str_replace("</ iframe>", "</iframe>", $text);
    $text = str_replace("frameborder ", "frameborder=\"0\" allowfullscreen></iframe>", $text);
    $doc = new DOMDocument();
    $doc->substituteEntities = false;
    $internalErrors = libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="utf-8" ?>' . $text);
    $text = $doc->saveHTML();
                    libxml_use_internal_errors($internalErrors);
	$text = preg_replace('#<!DOCTYPE html PUBLIC "-\/\/W3C\/\/DTD HTML 4\.0 Transitional\/\/EN" "http:\/\/www\.w3\.org\/TR\/REC-html40\/loose\.dtd">(?:[^<]*)<\?xml encoding="utf-8" \?><html><body>(?:<p>)?#i', '', $text);
	$text = str_replace('</p></body></html>', '', $text);
    $text = str_replace('</body></html></p>', '', $text);
    $text = str_replace('</body></html>', '', $text);
    return $text;
}
function anime_replaceExecludes($article, &$htmlfounds, $opt = false, $no_nr = false)
{
    $htmlurls = array();$article = preg_replace('{data-image-description="(?:[^\"]*?)"}i', '', $article);
	if($opt === true){
		preg_match_all( "/<a\s[^>]*href=(\"??)([^\" >]*?)\\1[^>]*>(.*?)<\/a>/s" ,$article,$matches,PREG_PATTERN_ORDER);
		$htmlurls=$matches[0];
	}
	$urls_txt = array();
	if($opt === true){
		preg_match_all('/https?:\/\/[^<\s]+/', $article,$matches_urls_txt);
		$urls_txt = $matches_urls_txt[0];
	}
	preg_match_all("/<[^<>]+>/is",$article,$matches,PREG_PATTERN_ORDER);
	$htmlfounds=$matches[0];
	preg_match_all('{\[nospin\].*?\[/nospin\]}s', $article,$matches_ns);
	$nospin = $matches_ns[0];
	$pattern="\[.*?\]";
	preg_match_all("/".$pattern."/s",$article,$matches2,PREG_PATTERN_ORDER);
	$shortcodes=$matches2[0];
	preg_match_all("/<script.*?<\/script>/is",$article,$matches3,PREG_PATTERN_ORDER);
	$js=$matches3[0];
	if($no_nr == true)
    {
        $nospin_nums = array();
    }
    else
    {
        preg_match_all('/\d{2,}/s', $article,$matches_nums);
        $nospin_nums = $matches_nums[0];
        sort($nospin_nums);
        $nospin_nums = array_reverse($nospin_nums);
    }
    $capped = array();
	if($opt === true){
		preg_match_all("{\b[A-Z][a-z']+\b[,]?}", $article,$matches_cap);
		$capped = $matches_cap[0];
		sort($capped);
		$capped=array_reverse($capped);
	}
	$curly_quote = array();
	if($opt === true){
		preg_match_all('{???.*????}', $article, $matches_curly_txt);
		$curly_quote = $matches_curly_txt[0];
		preg_match_all('{???.*????}', $article, $matches_curly_txt_s);
		$single_curly_quote = $matches_curly_txt_s[0];
		preg_match_all('{&quot;.*?&quot;}', $article, $matches_curly_txt_s_and);
		$single_curly_quote_and = $matches_curly_txt_s_and[0];
		preg_match_all('{&#8220;.*?&#8221}', $article, $matches_curly_txt_s_and_num);
		$single_curly_quote_and_num = $matches_curly_txt_s_and_num[0];
		$curly_quote_regular = array();
		preg_match_all('{".*?"}', $article, $matches_curly_txt_regular);
        $curly_quote_regular = $matches_curly_txt_regular[0];
		$curly_quote = array_merge($curly_quote , $single_curly_quote ,$single_curly_quote_and,$single_curly_quote_and_num,$curly_quote_regular);
	}
	$htmlfounds = array_merge($nospin, $shortcodes, $js, $htmlurls, $htmlfounds, $curly_quote, $urls_txt, $nospin_nums, $capped);
	$htmlfounds = array_filter(array_unique($htmlfounds));
	$i=1;
	foreach($htmlfounds as $htmlfound){
		$article=str_replace($htmlfound,'('.str_repeat('*', $i).')',$article);	
		$i++;
	}
    $article = str_replace(':(*', ': (*', $article);
	return $article;
}
function anime_restoreExecludes($article, $htmlfounds){
	$i=1;
	foreach($htmlfounds as $htmlfound){
		$article=str_replace( '('.str_repeat('*', $i).')', $htmlfound, $article);
		$i++;
	}
	$article = str_replace(array('[nospin]','[/nospin]'), '', $article);
    $article = preg_replace('{\(?\*[\s*]+\)?}', '', $article);
	return $article;
}
function anime_spin_and_translate($post_title, $final_content, $rule_translate, $rule_translate_source)
{
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    if($rule_translate != '' && $rule_translate != 'disabled')
    {
        if (isset($rule_translate_source) && $rule_translate_source != 'disabled' && $rule_translate_source != '') {
            $tr = $rule_translate_source;
        }
        else
        {
            $tr = 'auto';
        }
        $htmlfounds = array();
        $final_content = anime_replaceExecludes($final_content, $htmlfounds, false, true);
        
        $translation = anime_translate($post_title, $final_content, $tr, $rule_translate);
        if (is_array($translation) && isset($translation[1]))
        {
            $translation[1] = preg_replace('#(?<=[\*(])\s+(?=[\*)])#', '', $translation[1]);
            $translation[1] = preg_replace('#([^(*\s]\s)\*+\)#', '$1', $translation[1]);
            $translation[1] = preg_replace('#\(\*+([\s][^)*\s])#', '$1', $translation[1]);
            $translation[1] = anime_restoreExecludes($translation[1], $htmlfounds);
        }
        else
        {
            $final_content = anime_restoreExecludes($final_content, $htmlfounds);
        }
        if ($translation !== FALSE) {
            if (is_array($translation) && isset($translation[0]) && isset($translation[1])) {
                $post_title    = $translation[0];
                $final_content = $translation[1];
                $final_content = str_replace('</ iframe>', '</iframe>', $final_content);
                if(stristr($final_content, '<head>') !== false)
                {
                    $d = new DOMDocument;
                    $mock = new DOMDocument;
                    $internalErrors = libxml_use_internal_errors(true);
                    $d->loadHTML('<?xml encoding="utf-8" ?>' . $final_content);
                    libxml_use_internal_errors($internalErrors);
                    $body = $d->getElementsByTagName('body')->item(0);
                    foreach ($body->childNodes as $child)
                    {
                        $mock->appendChild($mock->importNode($child, true));
                    }
                    $new_post_content_temp = $mock->saveHTML();
                    if($new_post_content_temp !== '' && $new_post_content_temp !== false)
                    {
						$new_post_content_temp = str_replace('<?xml encoding="utf-8" ?>', '', $new_post_content_temp);
                        $final_content = preg_replace("/_addload\(function\(\){([^<]*)/i", "", $new_post_content_temp); 
                    }
                }
                $final_content = anime_repairHTML($final_content);
                $final_content = str_replace('%20', '', $final_content);
                $final_content = str_replace('/V/', '/v/', $final_content);
                $final_content = str_replace('?Oh=', '?oh=', $final_content);
                $final_content = htmlspecialchars_decode($final_content);
                $final_content = str_replace('</ ', '</', $final_content);
                $final_content = str_replace(' />', '/>', $final_content);
                $final_content = str_replace('< br/>', '<br/>', $final_content);
                $final_content = str_replace('< / ', '</', $final_content);
                $final_content = str_replace(' / >', '/>', $final_content);
                $final_content = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $final_content);
                $post_title = preg_replace('{&\s*#\s*(\d+)\s*;}', '&#$1;', $post_title);
                $post_title = htmlspecialchars_decode($post_title);
                $post_title = str_replace('</ ', '</', $post_title);
                $post_title = str_replace(' />', '/>', $post_title);
                $post_title = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $post_title);
            } else {
                if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                    anime_log_to_file('Translation failed - malformed data!');
                }
                return false;
            }
        } else {
            if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                anime_log_to_file('Translation Failed - returned false!');
            }
            return false;
        }
    }
    else
    {
        if (isset($anime_Main_Settings['translate']) && $anime_Main_Settings['translate'] != 'disabled') {
            if (isset($anime_Main_Settings['translate_source']) && $anime_Main_Settings['translate_source'] != 'disabled') {
                $tr = $anime_Main_Settings['translate_source'];
            }
            else
            {
                $tr = 'auto';
            }
            $htmlfounds = array();
            $final_content = anime_replaceExecludes($final_content, $htmlfounds, false, true);
        
            $translation = anime_translate($post_title, $final_content, $tr, $anime_Main_Settings['translate']);
            if (is_array($translation) && isset($translation[1]))
            {
                $translation[1] = preg_replace('#(?<=[\*(])\s+(?=[\*)])#', '', $translation[1]);
                $translation[1] = preg_replace('#([^(*\s]\s)\*+\)#', '$1', $translation[1]);
                $translation[1] = preg_replace('#\(\*+([\s][^)*\s])#', '$1', $translation[1]);
                $translation[1] = anime_restoreExecludes($translation[1], $htmlfounds);
            }
            else
            {
                $final_content = anime_restoreExecludes($final_content, $htmlfounds);
            }
            if ($translation !== FALSE) {
                if (is_array($translation) && isset($translation[0]) && isset($translation[1])) {
                    $post_title    = $translation[0];
                    $final_content = $translation[1];
                    $final_content = str_replace('</ iframe>', '</iframe>', $final_content);
                    if(stristr($final_content, '<head>') !== false)
                    {
                        $d = new DOMDocument;
                        $mock = new DOMDocument;
                        $internalErrors = libxml_use_internal_errors(true);
                        $d->loadHTML('<?xml encoding="utf-8" ?>' . $final_content);
                    libxml_use_internal_errors($internalErrors);
                        $body = $d->getElementsByTagName('body')->item(0);
                        foreach ($body->childNodes as $child)
                        {
                            $mock->appendChild($mock->importNode($child, true));
                        }
                        $new_post_content_temp = $mock->saveHTML();
                        if($new_post_content_temp !== '' && $new_post_content_temp !== false)
                        {
                            $final_content = preg_replace("/_addload\(function\(\){([^<]*)/i", "", $new_post_content_temp); 
                        }
                    }
                    $final_content = anime_repairHTML($final_content);
                    $final_content = str_replace('%20', '', $final_content);
                    $final_content = str_replace('/V/', '/v/', $final_content);
                    $final_content = str_replace('?Oh=', '?oh=', $final_content);
                    $final_content = htmlspecialchars_decode($final_content);
                    $final_content = str_replace('</ ', '</', $final_content);
                    $final_content = str_replace(' />', '/>', $final_content);
                    $final_content = str_replace('< br/>', '<br/>', $final_content);
                    $final_content = str_replace('< / ', '</', $final_content);
                    $final_content = str_replace(' / >', '/>', $final_content);
                    $final_content = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $final_content);
                    $post_title = preg_replace('{&\s*#\s*(\d+)\s*;}', '&#$1;', $post_title);
                    $post_title = htmlspecialchars_decode($post_title);
                    $post_title = str_replace('</ ', '</', $post_title);
                    $post_title = str_replace(' />', '/>', $post_title);
                    $post_title = preg_replace('/[\x00-\x1F\x7F\xA0]/u', '', $post_title);
                } else {
                    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                        anime_log_to_file('Translation failed - malformed data!');
                    }
                    return false;
                }
            } else {
                if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                    anime_log_to_file('Translation Failed - returned false!');
                }
                return false;
            }
        }
    }
    return array(
        $post_title,
        $final_content
    );
}
function anime_translate($title, $content, $from, $to)
{
    $ch = FALSE;
    $anime_Main_Settings = get_option('anime_Main_Settings', false);
    try {
        if($from == 'disabled')
        {
            if(strstr($to, '-') !== false && $to != 'zh-CN' && $to != 'zh-TW')
            {
                $from = 'auto-';
            }
            else
            {
                $from = 'auto';
            }
        }
        if($from != 'ar' && $from != 'AR-' && $from != 'ar!' && $from == $to)
        {
            if(strstr($to, '-') !== false && $to != 'zh-CN' && $to != 'zh-TW')
            {
                $from = 'ar-';
            }
            else
            {
                $from = 'ar';
            }
        }
        elseif(($from == 'ar' || $from == 'AR-' || $from == 'ar!') && $from == $to)
        {
            return false;
        }
        if(strstr($to, '!') !== false)
        {
            if (!isset($anime_Main_Settings['bing_auth']) || trim($anime_Main_Settings['bing_auth']) == '')
            {
                throw new Exception('You must enter a Microsoft Translator API key from plugin settings, to use this feature!');
            }
            require_once (dirname(__FILE__) . "/res/anime-translator-microsoft.php");
            $options    = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 60,
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0
            );
            $ch = curl_init();
            if ($ch === FALSE) {
                anime_log_to_file ('Failed to init curl in Microsoft Translator');
				return false;
            }
            if (isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '' && $anime_Main_Settings['proxy_url'] != 'disable' && $anime_Main_Settings['proxy_url'] != 'disabled') {
				$prx = explode(',', $anime_Main_Settings['proxy_url']);
                $randomness = array_rand($prx);
                $options[CURLOPT_PROXY] = trim($prx[$randomness]);
                if (isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '') 
                {
                    $prx_auth = explode(',', $anime_Main_Settings['proxy_auth']);
                    if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
                    {
                        $options[CURLOPT_PROXYUSERPWD] = trim($prx_auth[$randomness]);
                    }
                }
            }
            curl_setopt_array($ch, $options);
			$MicrosoftTranslator = new MicrosoftTranslator ( $ch );	
			try 
            {
                if (!isset($anime_Main_Settings['bing_region']) || trim($anime_Main_Settings['bing_region']) == '')
                {
                    $mt_region = 'global';
                }
                else
                {
                    $mt_region = trim($anime_Main_Settings['bing_region']);
                }
                if($from == 'auto' || $from == 'auto-' || $from == 'disabled')
                {
                    $from = 'no';
                }
				$accessToken = $MicrosoftTranslator->getToken ( trim($anime_Main_Settings['bing_auth']) , $mt_region  );
                $from = trim($from, '!');
                $to = trim($to, '!');
				$translated = $MicrosoftTranslator->translateWrap ( $content, $from, $to );
                $translated_title = $MicrosoftTranslator->translateWrap ( $title, $from, $to );
                curl_close($ch);
			} 
            catch ( Exception $e ) 
            {
                curl_close($ch);
				anime_log_to_file ('Microsoft Translation error: ' . $e->getMessage());
				return false;
			}
        }
        elseif(strstr($to, '-') !== false && $to != 'zh-CN' && $to != 'zh-TW')
        {
            if (!isset($anime_Main_Settings['deepl_auth']) || trim($anime_Main_Settings['deepl_auth']) == '')
            {
                throw new Exception('You must enter a DeepL API key from plugin settings, to use this feature!');
            }
            $to = rtrim($to, '-');
            $from = rtrim($from, '-');
            if(strlen($content) > 30000)
            {
                $translated = '';
                while($content != '')
                {
                    $first30k = substr($content, 0, 30000);
                    $content = substr($content, 30000);
                    if (isset($anime_Main_Settings['deppl_free']) && trim($anime_Main_Settings['deppl_free']) == 'on')
                    {
                        $ch = curl_init('https://api-free.deepl.com/v2/translate');
                    }
                    else
                    {
                        $ch = curl_init('https://api.deepl.com/v2/translate');
                    }
                    if($ch !== false)
                    {
                        $data           = array();
                        $data['text']   = $first30k;
                        if($from != 'auto')
                        {
                            $data['source_lang']   = $from;
                        }
                        $data['tag_handling']  = 'xml';
                        $data['non_splitting_tags']  = 'div';
                        $data['preserve_formatting']  = '1';
                        $data['target_lang']   = $to;
                        $data['auth_key']   = trim($anime_Main_Settings['deepl_auth']);
                        $fdata = "";
                        foreach ($data as $key => $val) {
                            $fdata .= "$key=" . urlencode(trim($val)) . "&";
                        }
                        $headers = [
                            'Content-Type: application/x-www-form-urlencoded',
                            'Content-Length: ' . strlen($fdata)
                        ];
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                        curl_setopt($ch, CURLOPT_POST, 1);
                        curl_setopt($ch, CURLOPT_USERAGENT, anime_get_random_user_agent());
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                        $translated_temp = curl_exec($ch);
                        if($translated_temp === false)
                        {
                            throw new Exception('Failed to post to DeepL: ' . curl_error($ch));
                        }
                        curl_close($ch);
                    }
                    $trans_json = json_decode($translated_temp, true);
                    if($trans_json === false)
                    {
                        throw new Exception('Incorrect multipart response from DeepL: ' . $translated_temp);
                    }
                    if(!isset($trans_json['translations'][0]['text']))
                    {
                        throw new Exception('Unrecognized multipart response from DeepL: ' . $translated_temp);
                    }
                    $translated .= ' ' . $trans_json['translations'][0]['text'];
                }
            }
            else
            {
                if (isset($anime_Main_Settings['deppl_free']) && trim($anime_Main_Settings['deppl_free']) == 'on')
                {
                    $ch = curl_init('https://api-free.deepl.com/v2/translate');
                }
                else
                {
                    $ch = curl_init('https://api.deepl.com/v2/translate');
                }
                if($ch !== false)
                {
                    $data           = array();
                    $data['text']   = $content;
                    if($from != 'auto')
                    {
                        $data['source_lang']   = $from;
                    }
                    $data['tag_handling']  = 'xml';
                    $data['non_splitting_tags']  = 'div';
                    $data['preserve_formatting']  = '1';
                    $data['target_lang']   = $to;
                    $data['auth_key']   = trim($anime_Main_Settings['deepl_auth']);
                    $fdata = "";
                    foreach ($data as $key => $val) {
                        $fdata .= "$key=" . urlencode(trim($val)) . "&";
                    }
                    curl_setopt($ch, CURLOPT_POST, 1);
                    $headers = [
                        'Content-Type: application/x-www-form-urlencoded',
                        'Content-Length: ' . strlen($fdata)
                    ];
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $fdata);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_USERAGENT, anime_get_random_user_agent());
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                    $translated = curl_exec($ch);
                    if($translated === false)
                    {
                        throw new Exception('Failed to post to DeepL: ' . curl_error($ch));
                    }
                    curl_close($ch);
                }
                $trans_json = json_decode($translated, true);
                if($trans_json === false)
                {
                    throw new Exception('Incorrect text response from DeepL: ' . $translated);
                }
                if(!isset($trans_json['translations'][0]['text']))
                {
                    throw new Exception('Unrecognized text response from DeepL: ' . 'https://api.deepl.com/v2/translate?text=' . urlencode($content) . '&source_lang=' . $from . '&target_lang=' . $to . '&auth_key=' . trim($anime_Main_Settings['deepl_auth']) . '&tag_handling=xml&preserve_formatting=1' . ' --- ' . $translated);
                }
                $translated = $trans_json['translations'][0]['text'];
            }
            $translated = str_replace('<strong>', ' <strong>', $translated);
            $translated = str_replace('</strong>', '</strong> ', $translated);
            if($from != 'auto')
            {
                $from_from = '&source_lang=' . $from;
            }
            else
            {
                $from_from = '';
            }
            if (isset($anime_Main_Settings['deppl_free']) && trim($anime_Main_Settings['deppl_free']) == 'on')
            {
                $translated_title = anime_get_web_page('https://api-free.deepl.com/v2/translate?text=' . urlencode($title) . $from_from . '&target_lang=' . $to . '&auth_key=' . trim($anime_Main_Settings['deepl_auth']) . '&tag_handling=xml&preserve_formatting=1');
            }
            else
            {
                $translated_title = anime_get_web_page('https://api.deepl.com/v2/translate?text=' . urlencode($title) . $from_from . '&target_lang=' . $to . '&auth_key=' . trim($anime_Main_Settings['deepl_auth']) . '&tag_handling=xml&preserve_formatting=1');
            }
            $trans_json = json_decode($translated_title, true);
            if($trans_json === false)
            {
                throw new Exception('Incorrect title response from DeepL: ' . $translated_title);
            }
            if(!isset($trans_json['translations'][0]['text']))
            {
                throw new Exception('Unrecognized title response from DeepL: ' . $translated_title);
            }
            $translated_title = $trans_json['translations'][0]['text'];
        }
        else
        {
            if (isset($anime_Main_Settings['google_trans_auth']) && trim($anime_Main_Settings['google_trans_auth']) != '')
            {
                require_once(dirname(__FILE__) . "/res/translator-api.php");
                $ch = curl_init();
                if ($ch === FALSE) {
                    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                        anime_log_to_file('Failed to init cURL in translator!');
                    }
                    return false;
                }
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                $GoogleTranslatorAPI = new GoogleTranslatorAPI($ch, $anime_Main_Settings['google_trans_auth']);
                $translated = '';
                $translated_title = '';
                if($content != '')
                {
                    if(strlen($content) > 30000)
                    {
                        while($content != '')
                        {
                            $first30k = substr($content, 0, 30000);
                            $content = substr($content, 30000);
                            $translated_temp       = $GoogleTranslatorAPI->translateText($first30k, $from, $to);
                            $translated .= ' ' . $translated_temp;
                        }
                    }
                    else
                    {
                        $translated       = $GoogleTranslatorAPI->translateText($content, $from, $to);
                    }
                }
                if($title != '')
                {
                    $translated_title = $GoogleTranslatorAPI->translateText($title, $from, $to);
                }
                curl_close($ch);
            }
            else
            {
                require_once(dirname(__FILE__) . "/res/anime-translator.php");
                $ch = curl_init();
                if ($ch === FALSE) {
                    if (isset($anime_Main_Settings['enable_detailed_logging'])) {
                        anime_log_to_file('Failed to init cURL in translator!');
                    }
                    return false;
                }
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_USERAGENT, anime_get_random_user_agent());
				if (isset($anime_Main_Settings['proxy_url']) && $anime_Main_Settings['proxy_url'] != '' && $anime_Main_Settings['proxy_url'] != 'disable' && $anime_Main_Settings['proxy_url'] != 'disabled') {
					$prx = explode(',', $anime_Main_Settings['proxy_url']);
					$randomness = array_rand($prx);
					curl_setopt( $ch, CURLOPT_PROXY, trim($prx[$randomness]));
					if (isset($anime_Main_Settings['proxy_auth']) && $anime_Main_Settings['proxy_auth'] != '') 
					{
						$prx_auth = explode(',', $anime_Main_Settings['proxy_auth']);
						if(isset($prx_auth[$randomness]) && trim($prx_auth[$randomness]) != '')
						{
							curl_setopt( $ch, CURLOPT_PROXYUSERPWD, trim($prx_auth[$randomness]) );
						}
					}
				}
				$GoogleTranslator = new GoogleTranslator($ch);
                if(strlen($content) > 13000)
                {
                    $translated = '';
                    while($content != '')
                    {
                        $first30k = substr($content, 0, 13000);
                        $content = substr($content, 13000);
                        $translated_temp       = $GoogleTranslator->translateText($first30k, $from, $to);
                        if (strpos($translated, '<h2>The page you have attempted to translate is already in ') !== false) {
                            throw new Exception('Page content already in ' . $to);
                        }
                        if (strpos($translated, 'Error 400 (Bad Request)!!1') !== false) {
                            throw new Exception('Unexpected error while translating page!');
                        }
                        if(substr_compare($translated_temp, '</pre>', -strlen('</pre>')) === 0){$translated_temp = substr_replace($translated_temp ,"", -6);}if(substr( $translated_temp, 0, 5 ) === "<pre>"){$translated_temp = substr($translated_temp, 5);}
                        $translated .= ' ' . $translated_temp;
                    }
                }
                else
                {
                    $translated       = $GoogleTranslator->translateText($content, $from, $to);
                    if (strpos($translated, '<h2>The page you have attempted to translate is already in ') !== false) {
                        throw new Exception('Page content already in ' . $to);
                    }
                    if (strpos($translated, 'Error 400 (Bad Request)!!1') !== false) {
                        throw new Exception('Unexpected error while translating page!');
                    }
                }
                $translated_title = $GoogleTranslator->translateText($title, $from, $to);
                if (strpos($translated_title, '<h2>The page you have attempted to translate is already in ') !== false) {
                    throw new Exception('Page title already in ' . $to);
                }
                if (strpos($translated_title, 'Error 400 (Bad Request)!!1') !== false) {
                    throw new Exception('Unexpected error while translating page title!');
                }
                curl_close($ch);
            }
        }
    }
    catch (Exception $e) {
        if($ch !== false)
        {
            curl_close($ch);
        }
        if (isset($anime_Main_Settings['enable_detailed_logging'])) {
            anime_log_to_file('Exception thrown in Translator ' . $e);
        }
        return false;
    }
    if(substr_compare($translated_title, '</pre>', -strlen('</pre>')) === 0){$title = substr_replace($translated_title ,"", -6);}else{$title = $translated_title;}if(substr( $title, 0, 5 ) === "<pre>"){$title = substr($title, 5);}
    if(substr_compare($translated, '</pre>', -strlen('</pre>')) === 0){$text = substr_replace($translated ,"", -6);}else{$text = $translated;}if(substr( $text, 0, 5 ) === "<pre>"){$text = substr($text, 5);}
    $text  = preg_replace('/' . preg_quote('html lang=') . '.*?' . preg_quote('>') . '/', '', $text);
    $text  = preg_replace('/' . preg_quote('!DOCTYPE') . '.*?' . preg_quote('<') . '/', '', $text);
    $text = str_replace('%% item_cat %%', '%%item_cat%%', $text);
    $text = str_replace('%% item_tags %%', '%%item_tags%%', $text);
    $text = str_replace('%% item_url %%', '%%item_url%%', $text);
    $text = str_replace('%% item_read_more_button %%', '%%item_read_more_button%%', $text);
    $text = str_replace('%%item_read_more_button %%', '%%item_read_more_button%%', $text);
    $text = str_replace('%% item_read_more_button%%', '%%item_read_more_button%%', $text);
    $text = str_replace('%% item_image_URL %%', '%%item_image_URL%%', $text);
    $text = str_replace('%% author_link %%', '%%author_link%%', $text);
    $text = str_replace('%% custom_html2 %%', '%%custom_html2%%', $text);
    $text = str_replace('%% custom_html %%', '%%custom_html%%', $text);
    $text = str_replace('%% random_sentence %%', '%%random_sentence%%', $text);
    $text = str_replace('%% random_sentence2 %%', '%%random_sentence2%%', $text);
    $text = str_replace('%% item_title %%', '%%item_title%%', $text);
    $text = str_replace('%% item_content %%', '%%item_content%%', $text);
    $text = str_replace('%% item_original_content %%', '%%item_original_content%%', $text);
    $text = str_replace('%% item_content_plain_text %%', '%%item_content_plain_text%%', $text);
    $text = str_replace('%% item_description %%', '%%item_description%%', $text);
    $text = str_replace('%% author %%', '%%author%%', $text);
    $text = str_replace('%% item_media %%', '%%item_media%%', $text);
    $text = str_replace('%% item_date %%', '%%item_date%%', $text);
    $text = str_replace('&amp; # 039;', '\'', $text);
    $text = str_replace('%% %% item_read_more_button', '%%item_read_more_button%%', $text);
    $text = str_replace('&amp; ldquo;', '"', $text);
    $text = str_replace('&amp; rdquo;', '"', $text);
    $text = str_replace(' \' ', '\'', $text);
    $text = preg_replace('{<iframe src="https://translate.google.com/translate(?:.*?)></iframe>}i', "", html_entity_decode($text, ENT_QUOTES));
    $text = preg_replace('{<span class="google-src-text.*?>.*?</span>}', "", $text);
    $text = preg_replace('{<span class="notranslate.*?>(.*?)</span>}', "$1", $text);
    $title = str_replace('%% random_sentence %%', '%%random_sentence%%', $title);
    $title = str_replace('%% random_sentence2 %%', '%%random_sentence2%%', $title);
    $title = str_replace('%% item_title %%', '%%item_title%%', $title);
    $title = str_replace('%% item_description %%', '%%item_description%%', $title);
    $title = str_replace('%% item_url %%', '%%item_url%%', $title);
    $title = str_replace('%% item_date %%', '%%item_date%%', $title);
    $title = str_replace('%% author %%', '%%author%%', $title);
    $title = str_replace('%% item_cat %%', '%%item_cat%%', $title);
    $title = str_replace('%% item_tags %%', '%%item_tags%%', $title);
    $title = str_replace('&amp; # 039;', '\'', $title);
    $title = str_replace('&amp; ldquo;', '"', $title);
    $title = str_replace('&amp; rdquo;', '"', $title);
    $title = str_replace(' \' ', '\'', $title);

    return array(
        $title,
        $text
    );
}
function anime_generate_random_email()
{
    $tlds = array("com", "net", "gov", "org", "edu", "biz", "info");
    $char = "0123456789abcdefghijklmnopqrstuvwxyz";
    $ulen = mt_rand(5, 10);
    $dlen = mt_rand(7, 17);
    $a = "";
    for ($i = 1; $i <= $ulen; $i++) {
        $a .= substr($char, mt_rand(0, strlen($char)), 1);
    }
    $a .= "@";
    for ($i = 1; $i <= $dlen; $i++) {
        $a .= substr($char, mt_rand(0, strlen($char)), 1);
    }
    $a .= ".";
    $a .= $tlds[mt_rand(0, (sizeof($tlds)-1))];
    return $a;
}
$anime_fatal = false;
function anime_clear_flag_at_shutdown($param, $type)
{
    $error = error_get_last();
    if ($error !== null && $error['type'] === E_ERROR && $GLOBALS['anime_fatal'] === false) {
        $GLOBALS['anime_fatal'] = true;
        $running = array();
        update_option('anime_running_list', $running);
        anime_log_to_file('[FATAL] Exit error: ' . $error['message'] . ', file: ' . $error['file'] . ', line: ' . $error['line'] . ' - rule ID: ' . $param . '!');
        anime_clearFromList($param, $type);
    }
    else
    {
        anime_clearFromList($param, $type);
    }
}

function anime_hour_diff($date1, $date2)
{
    $date1 = new DateTime($date1, anime_get_blog_timezone());
    $date2 = new DateTime($date2, anime_get_blog_timezone());
    
    $number1 = (int) $date1->format('U');
    $number2 = (int) $date2->format('U');
    return ($number1 - $number2) / 60;
}

function anime_add_hour($date, $hour)
{
    $date1 = new DateTime($date, anime_get_blog_timezone());
    $date1->modify("$hour hours");
    $date1 = (array)$date1;
    foreach ($date1 as $key => $value) {
        if ($key == 'date') {
            return $value;
        }
    }
    return $date;
}

function anime_minute_diff($date1, $date2)
{
    $date1 = new DateTime($date1, anime_get_blog_timezone());
    $date2 = new DateTime($date2, anime_get_blog_timezone());
    
    $number1 = (int) $date1->format('U');
    $number2 = (int) $date2->format('U');
    return ($number1 - $number2);
}

function anime_add_minute($date, $minute)
{
    $date1 = new DateTime($date, anime_get_blog_timezone());
    $date1->modify("$minute minutes");
    $date1 = (array)$date1;
    foreach ($date1 as $key => $value) {
        if ($key == 'date') {
            return $value;
        }
    }
    return $date;
}

function anime_get_blog_timezone() {

    $tzstring = get_option( 'timezone_string' );
    $offset   = get_option( 'gmt_offset' );

    if( empty( $tzstring ) && 0 != $offset && floor( $offset ) == $offset ){
        $offset_st = $offset > 0 ? "-$offset" : '+'.absint( $offset );
        $tzstring  = 'Etc/GMT'.$offset_st;
    }
    if( empty( $tzstring ) ){
        $tzstring = 'UTC';
    }
    $timezone = new DateTimeZone( $tzstring );
    return $timezone; 
}
function anime_get_date_now($param = 'now')
{
    $date = new DateTime($param, anime_get_blog_timezone());
    $date = (array)$date;
    foreach ($date as $key => $value) {
        if ($key == 'date') {
            return $value;
        }
    }
    return '';
}

add_action('init', 'anime_create_taxonomy', 0);
add_action( 'enqueue_block_editor_assets', 'anime_enqueue_block_editor_assets' );
function anime_enqueue_block_editor_assets() {
	wp_register_style('anime-browser-style', plugins_url('styles/anime-browser.css', __FILE__), false, '1.0.0');
    wp_enqueue_style('anime-browser-style');
}
function anime_create_taxonomy()
{
    if(!taxonomy_exists('coderevolution_post_source'))
    {
        $labels = array(
            'name' => _x('Post Source', 'taxonomy general name', 'ultimate-anime-scraper'),
            'singular_name' => _x('Post Source', 'taxonomy singular name', 'ultimate-anime-scraper'),
            'search_items' => esc_html__('Search Post Source', 'ultimate-anime-scraper'),
            'popular_items' => esc_html__('Popular Post Source', 'ultimate-anime-scraper'),
            'all_items' => esc_html__('All Post Sources', 'ultimate-anime-scraper'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__('Edit Post Source', 'ultimate-anime-scraper'),
            'update_item' => esc_html__('Update Post Source', 'ultimate-anime-scraper'),
            'add_new_item' => esc_html__('Add New Post Source', 'ultimate-anime-scraper'),
            'new_item_name' => esc_html__('New Post Source Name', 'ultimate-anime-scraper'),
            'separate_items_with_commas' => esc_html__('Separate Post Source with commas', 'ultimate-anime-scraper'),
            'add_or_remove_items' => esc_html__('Add or remove Post Source', 'ultimate-anime-scraper'),
            'choose_from_most_used' => esc_html__('Choose from the most used Post Source', 'ultimate-anime-scraper'),
            'not_found' => esc_html__('No Post Sources found.', 'ultimate-anime-scraper'),
            'menu_name' => esc_html__('Post Source', 'ultimate-anime-scraper')
        );
        
        $args = array(
            'hierarchical' => false,
            'public' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'description' => 'Post Source',
            'labels' => $labels,
            'show_admin_column' => true,
            'update_count_callback' => '_update_post_term_count',
            'rewrite' => false
        );
        
        $add_post_type = array(
            'wp-manga'
        );
        $xargs = array(
            'public'   => true,
            '_builtin' => false
        );
        $output = 'names'; 
        $operator = 'and';
        register_taxonomy('coderevolution_post_source', $add_post_type, $args);
        add_action('pre_get_posts', function($qry) {
            if (is_admin()) return;
            if (is_tax('coderevolution_post_source')){
                $qry->set_404();
            }
        });
    }
}

register_activation_hook(__FILE__, 'anime_activation_callback');
function anime_activation_callback($defaults = FALSE)
{
    if (!get_option('anime_Main_Settings') || $defaults === TRUE) {
        $anime_Main_Settings = array(
            'anime_enabled' => 'on',
            'auto_clear_logs' => 'No',
            'delete_no_episodes' => 'on',
            'enable_logging' => 'on',
            'chunk_size' => '1048576',
            'drive_directory' => '',
            'bucket_name' => '',
            'bucket_region' => '',
            'storage' => 'local',
            's3_user' => '',
            's3_pass' => '',
            'player_height' => '',
            'player_width' => '',
            'enable_detailed_logging' => '',
            'request_timeout' => '1',
            'rule_timeout' => '3600',
            'proxy_auth' => '',
            'proxy_url' => '',
            'deppl_free' => '',
            'bing_region' => '',
            'bing_auth' => '',
            'deepl_auth' => '',
            'headlessbrowserapi_key' => '',
            'aniapi_keys' => ''
        );
        if ($defaults === FALSE) {
            add_option('anime_Main_Settings', $anime_Main_Settings);
        } else {
            update_option('anime_Main_Settings', $anime_Main_Settings);
        }
    }
}

register_activation_hook(__FILE__, 'anime_check_version');
function anime_check_version()
{
    if (!function_exists('curl_init')) {
        echo '<h3>'.esc_html__('Please enable curl PHP extension. Please contact your hosting provider\'s support to help you in this matter.', 'ultimate-anime-scraper').'</h3>';
        die;
    }
    global $wp_version;
    if (!current_user_can('activate_plugins')) {
        echo '<p>' . esc_html__('You are not allowed to activate plugins!', 'ultimate-anime-scraper') . '</p>';
        die;
    }
    $php_version_required = '5.0';
    $wp_version_required  = '2.7';
    
    if (version_compare(PHP_VERSION, $php_version_required, '<')) {
        deactivate_plugins(basename(__FILE__));
        echo '<p>' . sprintf(esc_html__('This plugin can not be activated because it requires a PHP version greater than %1$s. Please update your PHP version before you activate it.', 'ultimate-anime-scraper'), $php_version_required) . '</p>';
        die;
    }
    
    if (version_compare($wp_version, $wp_version_required, '<')) {
        deactivate_plugins(basename(__FILE__));
        echo '<p>' . sprintf(esc_html__('This plugin can not be activated because it requires a WordPress version greater than %1$s. Please go to Dashboard -> Updates to get the latest version of WordPress.', 'ultimate-anime-scraper'), $wp_version_required) . '</p>';
        die;
    }
}
add_action('admin_init', 'anime_register_mysettings');
function anime_register_mysettings()
{ 
    anime_cron_schedule();
    register_setting('anime_option_group', 'anime_Main_Settings');
    if (is_multisite()) {
        if (!get_option('anime_Main_Settings')) {
            anime_activation_callback(TRUE);
        }
    }
}

function anime_get_plugin_url()
{
    return plugins_url('', __FILE__);
}

function anime_get_file_url($url)
{
    return esc_url(anime_get_plugin_url() . '/' . $url);
}

function anime_admin_load_files()
{
    wp_register_style('anime-browser-style', plugins_url('styles/anime-browser.css', __FILE__), false, '1.0.0');
    wp_enqueue_style('anime-browser-style');
    wp_register_style('anime-custom-style', plugins_url('styles/coderevolution-style.css', __FILE__), false, '1.0.0');
    wp_enqueue_style('anime-custom-style');
    wp_enqueue_script('jquery');
    wp_enqueue_script('media-upload');
    wp_enqueue_script('thickbox');
    wp_enqueue_style('thickbox');
}

require(dirname(__FILE__) . "/res/anime-main.php");
require(dirname(__FILE__) . "/res/anime-text-list.php");
require(dirname(__FILE__) . "/res/anime-logs.php");
?>
