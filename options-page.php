<?php
	
	/**
	 * Options page.
	 * 
	 * @package		WordPress
	 * @subpackage	Web Manifest
	 * @since		1.0.0
	 * @version		1.0.0
	 */
	
	
	// Do not allow directly accessing this file
	if( !defined('ABSPATH') ) exit;
	
	
	// Add options page
	function fwm_menu_item(){
		
		$page_title	=	__('Web Manifest Settings', 'web-manifest');
		$menu_title	=	__('Web Manifest', 'web-manifest');
		
		$hook = add_options_page($page_title, $menu_title, 'manage_options', 'web-manifest.php', 'fwm_page_content');
		
		add_action('admin_print_styles-' . $hook, 'fwm_page_styles');
		add_action('admin_print_scripts-' . $hook, 'fwm_page_scripts');
		
	}
	add_action('admin_menu', 'fwm_menu_item');
	
	
	// Page styles
	function fwm_page_styles(){
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('fwm-styles', plugins_url('/assets/css/style.css', __FILE__), array(), null);
	}
	
	
	// Page scripts
	function fwm_page_scripts(){
		
		wp_enqueue_script('wp-color-picker');
		
		wp_enqueue_script('fwm-scripts', plugins_url('/assets/js/scripts.js', __FILE__), array('jquery', 'wp-color-picker'), null, true);
		wp_localize_script( 'fwm-scripts', 'fwmResetMsg', __('Reset to default settings?', 'web-manifest') );
		wp_localize_script('fwm-scripts', 'fwmDefaults', array(
			'fwebmanifest_name'			=>	fwm_defaults('name'),
			'fwebmanifest_short_name'	=>	fwm_defaults('short_name'),
			'fwebmanifest_description'	=>	fwm_defaults('description'),
			'fwebmanifest_lang'			=>	fwm_defaults('lang'),
			'fwebmanifest_dir'			=>	fwm_defaults('dir'),
			'fwebmanifest_start_url'	=>	fwm_defaults('start_url'),
			'fwebmanifest_display'		=>	fwm_defaults('display'),
			'fwebmanifest_orientation'	=>	fwm_defaults('orientation'),
			'fwebmanifest_bgcolor'		=>	fwm_defaults('background_color'),
			'fwebmanifest_themecolor'	=>	fwm_defaults('theme_color')
		));
		
	}
	
	
	// Page content
	function fwm_page_content(){ ?>
		
		<div class="wrap">
		 <div id="fwm-params">
			
			
			<!-- Title -->
			<h1><?php _e('Web Manifest Settings', 'web-manifest'); ?></h1>
			
			<p>
				<?php _e('Web manifest provide the ability to save a site bookmark to a mobile device\'s home screen and define its appearance at launch, so it will looks like an application.', 'web-manifest'); ?>
				<a href="//developer.mozilla.org/en-US/docs/Web/Manifest" target="_blank" rel="noopener"><?php _e('More info', 'web-manifest'); ?></a>
			</p>
			
			
			<!-- Form start -->
			<form action="options.php" method="post">
				
				<?php settings_fields('fwebmanifest'); ?>
				<?php do_settings_sections('fwebmanifest'); ?>
				
				<table class="form-table">
					
					<!-- Name -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_name"><?php _e('Name', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<input type="text" name="fwebmanifest_name" id="fwebmanifest_name" class="regular-text" value="<?php form_option('fwebmanifest_name'); ?>" required aria-describedby="name-descr" />
							<p class="description" id="name-descr"><?php _e('The name to be displayed to the user, for example among a list of an applications or as a label for an icon. Recommended length is 45 characters or less.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Short Name -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_short_name"><?php _e('Short Name', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<input type="text" name="fwebmanifest_short_name" id="fwebmanifest_short_name" class="regular-text" value="<?php form_option('fwebmanifest_short_name'); ?>" aria-describedby="shortname-descr" />
							<p class="description" id="shortname-descr"><?php _e('A short name for use where there is insufficient space to display the full name. Recommended length is 10 characters or less.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Description -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_description"><?php _e('Description', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<textarea name="fwebmanifest_description" id="fwebmanifest_description" class="regular-text" rows="5" maxlength="1024" aria-describedby="description-descr"><?php echo esc_textarea( get_option('fwebmanifest_description') ); ?></textarea>
							<p class="description" id="description-descr"><?php _e('General description of the site. Maximum length is 1024 characters.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Language -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_lang"><?php _e('Language', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<input type="text" name="fwebmanifest_lang" id="fwebmanifest_lang" class="regular-text" value="<?php form_option('fwebmanifest_lang'); ?>" readonly="readonly" aria-describedby="lang-descr" />
							<p class="description" id="lang-descr"><?php _e('Primary language for the previous parameters. Taken from the site settings.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Direction -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_dir"><?php _e('Text Direction', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<input type="text" name="fwebmanifest_dir" id="fwebmanifest_dir" class="regular-text" value="<?php form_option('fwebmanifest_dir'); ?>" readonly="readonly" aria-describedby="dir-descr" />
							<p class="description" id="dir-descr"><?php _e('Text direction for the previous parameters. Based on the language parameter.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Icons -->
					<tr>
						
						<th scope="row">
							<label><?php _e('Icon', 'web-manifest'); ?></label>
						</th>
						
						<td>
							
							<?php if( has_site_icon() ) : ?>
								
								<a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=title_tagline') ); ?>" title="<?php _e('Change image', 'web-manifest'); ?>" id="fwebmanifest_icons">
									<img src="<?php echo get_site_icon_url(300); ?>" alt="<?php _e('Site icon preview.', 'web-manifest'); ?>" />
								</a>
								
							<?php else : ?>
								
								<p><?php _e('No image selected', 'web-manifest'); ?></p>
								
								<a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=title_tagline') ); ?>" title="<?php _e('Select image', 'web-manifest'); ?>" id="fwebmanifest_icons" class="button">
									<?php _e('Select image', 'web-manifest'); ?>
								</a>
								
							<?php endif; ?>
							
							<p class="description" id="icons-descr"><?php _e('Site icon (favicon).', 'web-manifest'); ?></p>
							
						</td>
						
					</tr>
					
					<!-- Start URL -->
					<?php
						$startURL		=	fwm_url_parse(get_option('fwebmanifest_start_url'));
						$urlBase		=	$startURL['base'];
						$urlQuery		=	$startURL['query'];
						$urlFragment	=	$startURL['fragment'];
					?>
					<tr>
						
						<th scope="row">
							<label for="start-url"><?php _e('Start URL', 'web-manifest'); ?></label>
						</th>
						
						<td>
							
							<select name="start_url" id="start-url" class="regular-text">
								
								<?php
									
									$allLinks = fwm_all_pages();
									
									foreach( $allLinks as $type => $links ){ ?>
										
										<optgroup label="<?php echo esc_attr($type); ?>">
											<?php for( $i = 0; $i < sizeof($links); $i++ ) : ?> <option value="<?php echo esc_attr( $links[$i] ); ?>" <?php selected($urlBase, $links[$i]); ?>><?php echo $links[$i]; ?></option> <?php endfor; ?>
										</optgroup>
										
									<?php }
									
									unset($allLinks);
									
								?>
								
							</select>
							
							<p class="description" id="starturl-descr"><?php _e('URL that loads when a user launches the site from a homescreen.', 'web-manifest'); ?></p>
							
							<!-- extra fields -->
							<div class="extra-fields">
								
								<div class="field-row">
									<label for="url-query"><?php _e('Query:', 'web-manifest'); ?></label>
									<span class="string-comp">?</span>
									<input type="text" name="url_query" id="url-query" class="regular-text" placeholder="utm_source=homescreen" value="<?php echo esc_attr($urlQuery); ?>" />
								</div>
								
								<div class="field-row">
									<label for="url-fragment"><?php _e('Fragment:', 'web-manifest'); ?></label>
									<span class="string-comp">#</span>
									<input type="text" name="url_fragment" id="url-fragment" class="regular-text" placeholder="main-content" value="<?php echo esc_attr($urlFragment); ?>" />
								</div>
								
								<div class="field-row">
									<label for="fwebmanifest_start_url"><?php _e('Full URL:', 'web-manifest'); ?></label>
									<span class="string-comp"></span>
									<input type="text" name="fwebmanifest_start_url" id="fwebmanifest_start_url" class="regular-text" value="<?php form_option('fwebmanifest_start_url'); ?>" readonly="readonly" aria-describedby="starturl-descr" />
								</div>
								
							</div>
							
						</td>
						
					</tr>
					<?php unset($startURL, $urlBase, $urlQuery, $urlFragment); ?>
					
					<!-- Display -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_display"><?php _e('Display', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<select name="fwebmanifest_display" id="fwebmanifest_display" class="regular-text" aria-describedby="display-descr">
								<option value="fullscreen" <?php selected(get_option('fwebmanifest_display'), 'fullscreen'); ?>>fullscreen</option>
								<option value="standalone" <?php selected(get_option('fwebmanifest_display'), 'standalone'); ?>>standalone</option>
								<option value="minimal-ui" <?php selected(get_option('fwebmanifest_display'), 'minimal-ui'); ?>>minimal-ui</option>
								<option value="browser" <?php selected(get_option('fwebmanifest_display'), 'browser'); ?>>browser</option>
							</select>
							<p class="description" id="display-descr"><?php _e('The preferred display mode for the site.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Orientation -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_orientation"><?php _e('Orientation', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<select name="fwebmanifest_orientation" id="fwebmanifest_orientation" class="regular-text" aria-describedby="orientation-descr">
								<option value="any" <?php selected(get_option('fwebmanifest_orientation'), 'any'); ?>>any</option>
								<option value="natural" <?php selected(get_option('fwebmanifest_orientation'), 'natural'); ?>>natural</option>
								<option value="landscape" <?php selected(get_option('fwebmanifest_orientation'), 'landscape'); ?>>landscape</option>
								<option value="landscape-primary" <?php selected(get_option('fwebmanifest_orientation'), 'landscape-primary'); ?>>landscape-primary</option>
								<option value="landscape-secondary" <?php selected(get_option('fwebmanifest_orientation'), 'landscape-secondary'); ?>>landscape-secondary</option>
								<option value="portrait" <?php selected(get_option('fwebmanifest_orientation'), 'portrait'); ?>>portrait</option>
								<option value="portrait-primary" <?php selected(get_option('fwebmanifest_orientation'), 'portrait-primary'); ?>>portrait-primary</option>
								<option value="portrait-secondary" <?php selected(get_option('fwebmanifest_orientation'), 'portrait-secondary'); ?>>portrait-secondary</option>
							</select>
							<p class="description" id="orientation-descr"><?php _e('Default site orientation.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Background Color -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_bgcolor"><?php _e('Background Color', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<input type="text" name="fwebmanifest_bgcolor" id="fwebmanifest_bgcolor" value="<?php form_option('fwebmanifest_bgcolor'); ?>" data-default-color="<?php echo esc_attr( fwm_defaults('background_color') ); ?>" aria-describedby="bgcolor-descr" />
							<p class="description" id="bgcolor-descr"><?php _e('The expected background color before the stylesheet has loaded.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
					<!-- Theme Color -->
					<tr>
						
						<th scope="row">
							<label for="fwebmanifest_themecolor"><?php _e('Theme Color', 'web-manifest'); ?></label>
						</th>
						
						<td>
							<input type="text" name="fwebmanifest_themecolor" id="fwebmanifest_themecolor" value="<?php form_option('fwebmanifest_themecolor'); ?>" data-default-color="<?php echo esc_attr( fwm_defaults('theme_color') ); ?>" aria-describedby="themecolor-descr" />
							<p class="description" id="themecolor-descr"><?php _e('Default theme color.', 'web-manifest'); ?></p>
						</td>
						
					</tr>
					
				</table>
				
				
				<!-- Preview start -->
				<div id="fwm-preview">
					
					<!-- Mockups -->
					<?php
						
						function mockup_markup($class = ''){ ?>
							
							<div class="mockup <?php echo esc_attr($class); ?>">
								
								<!-- design -->
								<div class="speaker"></div>
								<div class="camera"></div>
								<div class="controls"></div>
								
								<!-- screen -->
								<div class="screen">
									
									<div class="window-container">
										
										<!-- status bar -->
										<div class="status-bar">
											<svg class="icon-wifi" viewBox="0 0 66 50" role="img"><path d="M65.874,14.607a0.77,0.77,0,0,0-.086-0.111,44.329,44.329,0,0,0-65.573,0,0.81,0.81,0,0,0,0,1.1l4.794,5.118a0.692,0.692,0,0,0,1.024,0,36.422,36.422,0,0,1,53.94,0,0.692,0.692,0,0,0,1.024,0l4.794-5.117A0.8,0.8,0,0,0,66,15.043,0.811,0.811,0,0,0,65.874,14.607ZM33,13.533A32.431,32.431,0,0,0,9.188,24.077a0.81,0.81,0,0,0,0,1.093l4.779,5.1a0.692,0.692,0,0,0,1.024,0,24.319,24.319,0,0,1,36.016,0,0.692,0.692,0,0,0,1.024,0l4.78-5.1a0.81,0.81,0,0,0,0-1.093A32.431,32.431,0,0,0,33,13.533Zm0,13.533a20.225,20.225,0,0,0-14.85,6.576,0.81,0.81,0,0,0,0,1.093l4.78,5.1a0.692,0.692,0,0,0,1.024,0,12.218,12.218,0,0,1,18.094,0,0.692,0.692,0,0,0,1.024,0l4.779-5.1a0.81,0.81,0,0,0,0-1.093A20.224,20.224,0,0,0,33,27.067Zm0,14.34A7.053,7.053,0,0,0,27.819,43.7a0.81,0.81,0,0,0,0,1.093l4.669,4.984a0.692,0.692,0,0,0,1.024,0l4.669-4.984a0.81,0.81,0,0,0,0-1.093A7.053,7.053,0,0,0,33,41.406Z" /></svg>
											<svg class="icon-connection" viewBox="0 0 50 50" role="img"><path d="M40,50H50V0L40,10V50ZM26.667,50h10V13.333l-10,10V50ZM13.333,50h10V26.667l-10,10V50ZM0,50H10V40Z" /></svg>
											<svg class="icon-charge" viewBox="0 0 124 50" role="img">
												<path fill="none" stroke="currentColor" stroke-width="4px" d="M40,2h82V48H40V36.5H31.8v-23H40V2Z" />
												<path d="M56.1,33.437a11.336,11.336,0,0,1-2.058-.586l-0.544,3.4a11.324,11.324,0,0,0,2.394.733,13.19,13.19,0,0,0,2.506.25,8.7,8.7,0,0,0,6.35-2.439,8.934,8.934,0,0,0,2.49-6.69V22.583a9.961,9.961,0,0,0-2.226-6.923,8.016,8.016,0,0,0-11.419-.144,8.1,8.1,0,0,0-2.21,5.816,8.411,8.411,0,0,0,1.866,5.752,6.455,6.455,0,0,0,5.117,2.126,5.359,5.359,0,0,0,2.33-.513,5.2,5.2,0,0,0,1.866-1.508v1.155A5.877,5.877,0,0,1,61.4,32.255a3.758,3.758,0,0,1-3,1.367A12.939,12.939,0,0,1,56.1,33.437Zm0.753-8.888a5.754,5.754,0,0,1-.809-3.217,5.6,5.6,0,0,1,.889-3.209,2.676,2.676,0,0,1,2.3-1.315,2.793,2.793,0,0,1,2.434,1.332,6.993,6.993,0,0,1,.9,3.9v2.15a3.8,3.8,0,0,1-1.393,1.155,4.451,4.451,0,0,1-2.018.433A2.594,2.594,0,0,1,56.848,24.549Zm26.8-9.642a9.278,9.278,0,0,0-10.73,0,5.948,5.948,0,0,0-2.034,4.821,5.352,5.352,0,0,0,.913,3.088,6.08,6.08,0,0,0,2.546,2.094,6.925,6.925,0,0,0-2.939,2.254,5.506,5.506,0,0,0-1.065,3.345,5.977,5.977,0,0,0,2.2,4.982,9.038,9.038,0,0,0,5.774,1.741,8.952,8.952,0,0,0,5.734-1.741,5.987,5.987,0,0,0,2.194-4.982,5.49,5.49,0,0,0-1.073-3.337,6.861,6.861,0,0,0-2.931-2.246,6.134,6.134,0,0,0,2.538-2.1,5.357,5.357,0,0,0,.921-3.1A5.928,5.928,0,0,0,83.651,14.906ZM80.672,32.693a3.125,3.125,0,0,1-2.354.928,3.179,3.179,0,0,1-2.386-.92,3.457,3.457,0,0,1-.9-2.521,3.518,3.518,0,0,1,.889-2.513,3.091,3.091,0,0,1,2.362-.944,3.15,3.15,0,0,1,2.37.944,3.463,3.463,0,0,1,.913,2.513A3.465,3.465,0,0,1,80.672,32.693Zm-0.36-10.439a2.477,2.477,0,0,1-1.994.858,2.514,2.514,0,0,1-2.026-.858,3.463,3.463,0,0,1-.729-2.318,3.4,3.4,0,0,1,.721-2.286,2.493,2.493,0,0,1,2-.842,2.526,2.526,0,0,1,2.01.858,3.342,3.342,0,0,1,.745,2.27A3.465,3.465,0,0,1,80.311,22.254Zm8.913-2.992A4.689,4.689,0,0,0,90.569,22.7a5.026,5.026,0,0,0,3.748,1.364A4.95,4.95,0,0,0,98.033,22.7a4.719,4.719,0,0,0,1.329-3.433V18.027a4.768,4.768,0,0,0-1.329-3.457,5.76,5.76,0,0,0-7.471.008,4.759,4.759,0,0,0-1.337,3.449v1.235Zm3.107-1.235a2.5,2.5,0,0,1,.5-1.58,1.726,1.726,0,0,1,1.45-.65,1.749,1.749,0,0,1,1.457.65,2.474,2.474,0,0,1,.513,1.58v1.235a2.432,2.432,0,0,1-.5,1.564,1.726,1.726,0,0,1-1.433.634,1.784,1.784,0,0,1-1.473-.634,2.406,2.406,0,0,1-.512-1.564V18.027Zm8.04,14.375a4.7,4.7,0,0,0,1.353,3.441,5.037,5.037,0,0,0,3.756,1.372,4.951,4.951,0,0,0,3.716-1.364,4.74,4.74,0,0,0,1.329-3.449V31.167a4.73,4.73,0,0,0-1.338-3.441,5.759,5.759,0,0,0-7.471.008,4.723,4.723,0,0,0-1.345,3.433V32.4Zm3.107-1.235a2.476,2.476,0,0,1,.5-1.564,1.968,1.968,0,0,1,2.923,0,2.447,2.447,0,0,1,.513,1.564V32.4a2.637,2.637,0,0,1-.449,1.612,1.736,1.736,0,0,1-1.489.6,1.812,1.812,0,0,1-1.45-.65,2.336,2.336,0,0,1-.552-1.564V31.167Zm3-14.279-2.274-1.2L92.812,33.942l2.274,1.2Z" />
												<path d="M19.68,5.286L0,26.643H8.2L0,46.357,24.6,21.714H14.76Z" />
											</svg>
											<div class="time"><?php echo current_time('H:i'); ?></div>
										</div>
										
										<!-- browser bar -->
										<div class="browser-bar">
											<div class="address-bar"></div>
											<div class="browser-tabs">1</div>
											<div class="browser-menu"></div>
										</div>
										
										<!-- content -->
										<div class="window"></div>
									
									</div>
									
									<!-- navigation bar -->
									<div class="navigation-bar">
										<svg class="icon-back" viewBox="0 0 40 40" role="img"><path fill="none" stroke="currentColor" stroke-width="4px" d="M3.873,18.482l28.5-16.248A1.759,1.759,0,0,1,35,3.738V36.263a1.759,1.759,0,0,1-2.633,1.5L3.873,21.518A1.751,1.751,0,0,1,3.873,18.482Z" /></svg>
										<svg class="icon-home" viewBox="0 0 40 40" role="img"><circle fill="none" stroke="currentColor" stroke-width="4px" cx="19" cy="19" r="17"/></svg>
										<svg class="icon-recent" viewBox="0 0 40 40" role="img"><rect fill="none" stroke="currentColor" stroke-width="4px" width="32" height="32" rx="1" ry="1" transform="translate(4, 4)" /></svg>
									</div>
									
								</div>
								
							</div>
							
						<?php }
						
						mockup_markup('preview-load visible');
						mockup_markup('preview-site');
						
					?>
					
					<!-- Tablist -->
					<div class="tablist">
						<div class="tab button button-primary" data-for="preview-load">Load</div>
						<div id="bookmark"><div class="icon"></div><div class="name"></div></div>
						<div class="tab button" data-for="preview-site">Site</div>
					</div>
					
				</div>
				<!-- Preview end -->
				
				
				<!-- Submit -->
				<?php submit_button(null, 'primary', 'submit', false); ?>
				<input type="button" name="reset" id="reset" class="button" value="<?php _e('Reset', 'web-manifest'); ?>" />
				
			</form>
			<!-- Form end -->
			
			
		 </div>
		</div>
		
	<?php }
	
?>