<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$options = get_option('loadgo_options');

// Check plugin visibility
$visibility = $options['loadgo-visibility']? $options['loadgo-visibility'] : 'admin';
if ($visibility === 'admin' && !current_user_can( 'manage_options' ))
  return;

?>

<?php

// Plugin options
$image = $options['loadgo-image']? $options['loadgo-image'] : plugin_dir_url( __FILE__ ) . '../img/example.png';
$bgcolor = $options['loadgo-bgcolor']? $options['loadgo-bgcolor'] : '#FFF';
$opacity = $options['loadgo-opacity']? $options['loadgo-opacity'] : '0.5';
$direction = $options['loadgo-direction']? $options['loadgo-direction'] : 'lr';
$size = $options['loadgo-size']? $options['loadgo-size'] : '100';
$progress = ($options['loadgo-progress'] === 'true');
$progress_color = $options['loadgo-progress-color']? $options['loadgo-progress-color'] : '#000';
$message = ($options['loadgo-message'] === 'true');

// Style from options
?>

  <!-- LoadGo for WP -->
  <style>
    #loadgo-logo-overlay {
      background-color:   <?php echo $bgcolor ?>;
    }
  </style>

<?php

// LoadGo
wp_enqueue_script( 'loadgojs' , plugins_url('../js/loadgo/loadgo-nojquery.js', __FILE__) );
wp_enqueue_style( 'loadgocss', plugins_url('../css/loadgo.css', __FILE__) );

// Logo overlay (styled in js/loadgo/loadgo.css file)
?>

  <!-- LoadGo for WP -->
  <div id="loadgo-logo-overlay">
    <div id="loadgo-internal">
      <img id="loadgo-logo" src="<?php echo $image ?>" alt="logo" style="display:none;" />
<?php if ($progress) : ?>
      <h3 id="loadgo-progress" style="display:none;color:<?php echo $progress_color; ?>">0%</h3>
<?php endif; ?>
<?php if ($message) : ?>
      <div id="loadgo-message" style="display:none;color:<?php echo $progress_color; ?>"><?php _e( 'Loader provided by LoadGo for WP' ) ?></div>
<?php endif; ?>
    </div>
  </div>

<?php
// We have to insert inline JS so PACE will work correctly
?>

  <!-- LoadGo for WP -->
  <script type="text/javascript">
    var LOADGO_LOGO = document.getElementById('loadgo-logo');
    document.getElementsByTagName('body')[0].style.overflow = 'hidden';
    LOADGO_LOGO.onload = function () {
    <?php if ($message) : ?>
      document.getElementById('loadgo-message').style.display = 'block';
    <?php endif; ?>
    <?php if ($progress) : ?>
      document.getElementById('loadgo-progress').style.display = 'block';
    <?php endif; ?>
      var interval = window.setInterval(function () {
        if (typeof Loadgo !== 'undefined') {
          LOADGO_LOGO.style.display = 'block';

          var overlayDOM = document.getElementById('loadgo-internal');

          var perc = <?php echo $size ?>,
              width = LOADGO_LOGO.width * (perc / 100),
             height = LOADGO_LOGO.height * (perc / 100);
          overlayDOM.style.width = width + 'px';
          overlayDOM.style.height = height + 'px';

          var loadgoParams = {
            bgcolor:      '<?php echo $bgcolor ?>',
            opacity:      '<?php echo $opacity ?>',
            direction:    '<?php echo $direction ?>',
            animated:     false
          };
          Loadgo.init(LOADGO_LOGO, loadgoParams);
          window.clearInterval(interval);
        }
      }, 100);

      // Dispatch resize event when LoadGo finish
      var resizeInterval = window.setInterval(function () {
        if (typeof Loadgo !== 'undefined') {
          if (Loadgo.getprogress(LOADGO_LOGO) === 100) {
            window.dispatchEvent(new Event('resize'));
            window.clearInterval(resizeInterval);
          }
        }
      }, 100);
    };
  </script>

<?php

// PACE
wp_enqueue_script( 'pace' , plugins_url('../js/pace.js', __FILE__) );
