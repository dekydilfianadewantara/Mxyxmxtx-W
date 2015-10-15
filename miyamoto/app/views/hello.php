
<!DOCTYPE html>
<html>
  <head>
    <title>jQuery TourBus</title>

    <meta charset="utf-8">
    <meta name="author" content="Ryan Funduk">
    <meta name="description" content="">

    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
 
    <script src='http://ryanfunduk.com/jquery-tourbus/deps/jquery.min.js'></script>
    <script src='http://ryanfunduk.com/jquery-tourbus/jquery-tourbus.js'></script>
  <script type="text/javascript">
$(document).ready( function() {

    var info = $('#tour-info');

    $('#tourbus-demo-1').tourbus( {
      // debug: true,
      // autoDepart: true,
      onLegStart: function( leg, bus ) {
        info.html("Intro tour is on leg: " + (leg.index+1));

        // auto-progress where required
        if( leg.rawData.autoProgress ) {
          var currentIndex = leg.index;
          setTimeout(
            function() {
              if( bus.currentLegIndex != currentIndex ) { return; }
              bus.next();
            },
            leg.rawData.autoProgress
          );
        }

        // highlight where required
        if( leg.rawData.highlight ) {
          leg.$target.addClass('intro-tour-highlight');
          $('.intro-tour-overlay').show();
        }

        // fade/slide in first leg
        if( leg.index == 0 ) {
          leg.$el
            .css( { visibility: 'visible', opacity: 0, top: leg.options.top / 2 } )
            .animate( { top: leg.options.top, opacity: 1.0 }, 500,
                      function() { leg.show(); } );
          return false;
        }
      },
      onLegEnd: function( leg ) {
        // remove highlight when leaving this leg
        if( leg.rawData.highlight ) {
          leg.$target.removeClass('intro-tour-highlight');
          $('.intro-tour-overlay').hide();
        }
      },
      onDepart: function() {
        info.html("Intro tour started!");
      },
      onStop: function() {
        info.html("Intro tour is inactive...");
      }
    } );

    var docsBus = $.tourbus( '#tourbus-demo-2' );

    $(document).on( 'click', '.docs-tour, .go-to-docs', function() {
      $('#tourbus-demo-1').trigger('stop.tourbus');
      docsBus.depart();
    } );
    $(document).on( 'click', '.start-intro-tour', function() {
      $('#tourbus-demo-1').trigger('depart.tourbus');
    } );

  $('script.highlight').each( function() {
    var block = $(this);
    var code = $.trim( block.html() ).escape();
    var language = block.data('language');
    block = $("<pre class='language-" + language + "'><code>" + code + "</code></pre>").insertAfter(block);
    hljs.highlightBlock( block[0] );
  } );

} );

String.prototype.escape = function() {
  var tagsToReplace = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;'
  };
  return this.replace( /[&<>]/g, function( tag ) {
    return tagsToReplace[tag] || tag;
  } );
};
  </script>
 
    <link href='http://ryanfunduk.com/jquery-tourbus/jquery-tourbus.css' media='all' rel='stylesheet' type='text/css' />
    <link href='http://ryanfunduk.com/jquery-tourbus/demo.css' media='all' rel='stylesheet' type='text/css' />
  </head>
  <body>
    <div class="container">
      <div class='ten columns offset-by-three'>
        <div id='header'>
          <a class='start-intro-tour button'>Demo <span>&raquo;</span></a>
          <a href="https://github.com/rfunduk/jquery-tourbus" class='button'>Code <span>&raquo;</span></a>
          <a class='go-to-docs button'>Docs <span>&raquo;</span></a>
          <h1>jQuery TourBus</h1>
        </div>

        <p>
          Holy crap it is definitely <em>yet another</em>
          <a id='link' href='http://coffeescript.org' target='_blank'>jQuery</a>
          tour/walkthrough plugin!
        </p>

        <p>
          Current Version:
          <a href='https://github.com/rfunduk/jquery-tourbus/blob/master/CHANGELOG.md'><strong>0.4.3</strong></a>
        </p>

        <p>
          I wanted a simpler, less things-happen-automatically
          toolkit for making tours. If I need auto-advance/progress between 'legs'
          (<small>get it, legs of the tour :)</small>) I just need some callbacks where
          I can write my <tt>setTimeout</tt>s. If I want to highlight some elements,
          I just write some styles to do that and apply them appropriately.
          I much prefer this approach, maybe you do too :)
        </p>

        <div id='tour-info'>
          Intro tour is inactive...
        </div>

        <div class='row'>
          <div class='five columns alpha'>
            <a href='http://hiddenstash.deviantart.com/art/The-Merry-Pranksters-Bus-182763579' target='_blank'>
              <img id='img' src="pranksters.jpg" class='five columns alpha' />
            </a>
          </div>
          <div class='five columns omega'>
            <table>
              <tr>
                <th nowrap>Download Source:</th>
                <td>
                  <a class='button half-bottom' target='_blank' id='coffee-dl' href='https://raw.github.com/rfunduk/jquery-tourbus/master/src/jquery-tourbus.coffee'>coffeescript</a>
                  <br/>
                  <a class='button half-bottom' target='_blank' id='plain-dl' href='https://github.com/rfunduk/jquery-tourbus/raw/master/dist/jquery-tourbus.js'>javascript (18k)</a>
                  <br/>
                  <a class='button half-bottom' target='_blank' id='minified-dl' href='https://raw.github.com/rfunduk/jquery-tourbus/master/dist/jquery-tourbus.min.js'>minified (11k)</a>
                </td>
              </tr>
              <tr class='sep'>
                <th nowrap>Download Styles:</th>
                <td id='css'>
                  <a class='button half-bottom' target='_blank' href='https://raw.github.com/rfunduk/jquery-tourbus/master/src/jquery-tourbus.less'>less</a>
                  <br/>
                  <a class='button half-bottom' target='_blank' href='https://github.com/rfunduk/jquery-tourbus/raw/master/dist/jquery-tourbus.css'>css</a>
                  <br/>
                  <a class='button half-bottom' target='_blank' href='https://github.com/rfunduk/jquery-tourbus/raw/master/dist/jquery-tourbus.min.css'>minified</a>
                </td>
              </tr>
              <tr class='sep'>
                <th nowrap>Dependencies:</th>
                <td id='deps'>
                  <a class='button half-bottom' target='_blank' href='http://jquery.com/'>jQuery</a>
                  <br/>
                  <a class='button half-bottom optional' target='_blank' href='http://flesler.blogspot.ca/2007/10/jqueryscrollto.html'>jQuery-scrollTo</a>
                  <br/>
                  <a class='button half-bottom optional' target='_blank' href='http://desandro.github.com/imagesloaded/'>jQuery-imagesLoaded</a>
                </td>
              </tr>
            </table>
          </div>
        </div>

        <p>
          A word about dependencies:
          <a href='http://jquery.com/' target='_blank'>jQuery</a>
          is the only hard dependency, and I expect
          it will be fairly straight-forward to support
          <a href='' target='_blank'>Zepto</a> as well. <tt>scrollTo</tt>
          and <tt>imagesLoaded</tt> are optional and depend on what you're trying
          to do and how your tour legs are implemented. See the
          <a href='javascript:void(0);' class='docs-tour'>docs</a> for more on that.
        </p>

        <h4>Shameless Plug</h4>
        <p>
          This plugin was written by me, <a href='http://ryanfunduk.com'>Ryan Funduk</a>, as
          a weekend project. I've bootstrapped 2 commercial applications
          (<a href='https://coursecraft.net' target='_blank'>CourseCraft</a> and
          <a href='https://bugrocket.com' target='_blank'>Bugrocket</a>)
          and work on tons of other stuff on the side (like this!).
        </p>
        <p>
          You should subscribe to my (infrequent) newsletter for awesome stuff about
          software development, bootstrapping products and learning new things!
        </p>

        <form class='list-signup' action='http://list.ryanfunduk.com/t/d/s/jydtjh/' method='post'>
          <input type='hidden' name='cm-f-adjji' value='ryanfunduk.com/jquery-tourbus' />
          <input spellcheck='false' placeholder='your@email.com' name='cm-jydtjh-jydtjh' type='email' required='true' /><button type='submit'>Subscribe &check;</button>
          <small>No spam. Unsubscribe anytime.</small>
        </form>

        <p style='text-align: center;'>
          ...or just
          <a class='start-intro-tour button'>start the demo tour <span>&raquo;</span></a>
        </p>

        <div style='margin-top: 1500px;' id='docs'>
          <h1>Documentation</h1>
          <div id='inner-docs'>
            <p>
              jQuery TourBus takes more of a toolkit approach
              than some of the alternatives that try to have focusing elements, auto-progress
              with timers and indicators, on and on, all built in... too much stuff!
              <strong>Less is more!</strong>
            </p>

            <div class='row' id='docs-container'>
              <div class='five columns alpha'>
                <ul class='docs-nav'>
                  <lh>Basic Usage</lh>
                  <li><a href='#markup'>Markup</a></li>
                  <li><a href='#tourbus-events'>TourBus Events</a></li>
                  <li><a href='#bus-methods'>Bus Methods</a></li>
                  <li><a href='#leg-methods'>Leg Methods</a></li>
                </ul>
                <ul class='docs-nav'>
                  <lh>Miscellaneous</lh>
                  <li><a href='#convenience-classes'>Leg Convenience Classes</a></li>
                  <li><a href='#css-stuff'>Leg CSS</a></li>
                  <li><a href='#images'>Notes about Images</a></li>
                </ul>
              </div>
              <div class='five columns omega'>
                <ul class='docs-nav'>
                  <lh>Customization</lh>
                  <li><a href='#global-options'>Bus Options</a></li>
                  <li><a href='#leg-options'>Leg Options</a></li>
                </ul>
                <ul class='docs-nav'>
                  <lh>Advanced Usage</lh>
                  <li><a href='#custom-properties'>Custom Leg Properties</a></li>
                  <li><a href='#internals'>Bus/Leg Internals</a></li>
                  <li><a href='#monkey-patching'>Monkey Patching</a></li>
                  <li><a href='#example-1'>Example: Auto-progress</a></li>
                  <li><a href='#example-2'>Example: Show Multiple</a></li>
                  <li><a href='#example-3'>Example: Animate in/out</a></li>
                  <li><a href='#example-4'>Example: Highlight Target</a></li>
                </ul>
              </div>
            </div>

            <a name='markup'></a>
            <h2>Basic Usage</h2>
            <p>
              Start by including the jQuery plugin and base styles on your page, of course.
              Then we can define some 'legs' of our tour. Imagine you have a page
              with some stuff you want to lead the user through, maybe some elements
              like <tt>#nav</tt> and <tt>#sell</tt>...
            </p>

            <script class='highlight' data-language='html' type='text/highlight'>
<ol class='tourbus-legs' id='my-tour-id'>

  <li data-orientation='centered'>
    <h2>This is a modal-like tour leg</h2>
    <p>It isn't attached to a specific element.</p>
    <a href='javascript:void(0);' class='tourbus-next'>Next...</a>
  </li>

  <li data-el='#nav' data-orientation='left' data-width='400'>
    <h2>Navigation</h2>
    <p>Here's where I explain how our navigation works...</p>
    <a href='javascript:void(0);' class='tourbus-next'>Next...</a>
  </li>

  <li data-el='#sell' data-orientation='bottom' data-width='300'>
    <p>Buy now, etc, etc</p>
    <a href='javascript:void(0);' class='tourbus-stop'>Done!</a>
  </li>

</ol>
            </script>

            <p>
              This is the general idea, and there are quite a lot of properties
              you can specify on your legs to customize the behavior (we'll
              get to that stuff in a bit).
              Next let's 'schedule' the tour in our JavaScript
              (<abbr title='Do you see what I did there!?'>DYSWIDT</abbr>):
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$(window).load( function() {
  var tour = $('#my-tour-id').tourbus( {} );
} );
            </script>

            <p>
              Now the <tt>tour</tt> variable has the ordered
              list of legs we defined above. You can trigger events on this
              element:
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
tour.trigger('depart.tourbus');
            </script>

            <a name='tourbus-events'></a>
            <p>
              Here are the events it responds to:
            </p>

            <table class='docs'>
              <tr>
                <th width='25%'><tt>depart.tourbus</tt></th>
                <td>Starts this tour from the beginning.</td>
              </tr>
              <tr>
                <th><tt>stop.tourbus</tt></th>
                <td>Stop the tour and take everyone home :(</td>
              </tr>
              <tr>
                <th><tt>next.tourbus</tt></th>
                <td>Advance to the next leg of the tour.</td>
              </tr>
              <tr>
                <th><tt>prev.tourbus</tt></th>
                <td>Go back to the previous leg.</td>
              </tr>
            </table>

            <p>
              If you prefer, you can get a reference to the <tt>Bus</tt>
              instance itself and call methods directly. You can build it the same
              way as above and grab the instance:
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
tour.data('tourbus').depart();
            </script>

            <p>
              Or you can build it directly:
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$(window).load( function() {
  var tour = $.tourbus( '#my-tour-id', {} );
  //  or:    $.tourbus( $('#my-tour-id'), {} );
  //  or:    $.tourbus( 'build', '#my-tour-id', {} );
} );
            </script>

            <a name='bus-methods'></a>
            <p>
              Here are its methods:
            </p>

            <table class='docs'>
              <tr>
                <th width='25%'><tt>depart</tt></th>
                <td>Starts this tour from the beginning.</td>
              </tr>
              <tr>
                <th><tt>stop</tt></th>
                <td>Stop the tour and take everyone home :(</td>
              </tr>
              <tr>
                <th><tt>next</tt></th>
                <td>Advance to the next leg of the tour.</td>
              </tr>
              <tr>
                <th><tt>prev</tt></th>
                <td>Go back to the previous leg.</td>
              </tr>
              <tr>
                <th><tt>currentLeg</tt></th>
                <td>The <tt>Leg</tt> instance of the current leg.</td>
              </tr>
              <tr>
                <th><tt>showLeg( [index] )</tt></th>
                <td>
                  Forcibly show the current leg of the tour, or
                  the leg at an optional index.
                </td>
              </tr>
              <tr>
                <th><tt>hideLeg( [index] )</tt></th>
                <td>
                  Forcibly hide the current leg of the tour,
                  or the leg at an optional index.
                </td>
              </tr>
              <tr>
                <th><tt>repositionLegs</tt></th>
                <td>
                  Set/reset the position of all legs (physical positions
                  on the page).
                </td>
              </tr>
              <tr>
                <th><tt>destroy</tt></th>
                <td>
                  <tt>destroy</tt> all legs,
                  unbind all event handlers. Tour is over!
                </td>
              </tr>
            </table>

            <a name='leg-methods'></a>
            <p>
              A tour is made up of 'legs', so there is an instance
              for each of those as well. Here are the methods on <tt>Leg</tt>.
              <br/><small><strong>Note:</strong> You should not need to call
              these methods yourself unless doing custom stuff. See
              <a href='#convenience-classes'>convenience classes</a>.</small>
            </p>

            <table class='docs'>
              <tr>
                <th width='25%'><tt>render</tt></th>
                <td>
                  Generate markup for this leg and insert it into the
                  DOM, replacing any that already exists.
                </td>
              </tr>
              <tr>
                <th><tt>destroy</tt></th>
                <td>
                  Remove this leg from the DOM and unbind event handlers.
                </td>
              </tr>
              <tr>
                <th><tt>reposition</tt></th>
                <td>
                  Set/reset the position of this leg (its physical position
                  on the page).
                </td>
              </tr>
              <tr>
                <th><tt>scrollIntoView</tt></th>
                <td>
                  Scroll the page, if possible, so that this leg is in view,
                  and according to its various settings (such as <tt>context</tt>).
                </td>
              </tr>
              <tr>
                <th><tt>show</tt></th>
                <td>
                  Set visibility, opacity and z-index
                  such that this leg is visible.
                </td>
              </tr>
              <tr>
                <th><tt>hide</tt></th>
                <td>
                  Set visibility, opacity and z-index
                  such that this leg is invisible.
                </td>
              </tr>
            </table>


            <a name='global-options'></a>
            <h2>Customization</h2>
            <p>
              The <tt>tourbus</tt> plugin constructors
              take some global options as an object like you would expect.
              They are:
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
{
  // outputs a lot of stuff to the console
  debug: false,

  // auto start when initialized
  autoDepart: false,

  // specify a container for the leg markup
  container: 'body',

  // the depart-point of the tour
  startAt: 0,

  // a class to apply to the tour container
  class: '',

  // called when the tour starts
  onDepart: function( tourbus ) {},
  // called when the tour is stopped for any reason
  onStop: function( tourbus ) {},
  // called before switching to a leg
  onLegStart: function( leg, tourbus ) {},
  // called before switching _from_ a leg
  onLegEnd: function( leg, tourbus ) {},

  // global leg defaults
  leg: {
    // override scroll position (0 for top of page, etc)
    scrollTo: null,
    // duration of scroll animation
    scrollSpeed: 150,
    // how much space to leave above the leg when scrolled to
    scrollContext: 100,

    // a class to apply to every leg... more useful as a per-leg setting
    class: '',

    // position of leg in relation to target
    // supports top/right/bottom/left
    orientation: 'bottom',

    // alignment of leg to target
    // supports:
    //   left/right/center for orientation of top/bottom
    //   top/bottom/center for orientation of left/right
    align: 'left',

    // width of leg
    width: 'auto',

    // z-index of the leg
    zindex: 9999,

    // space between leg and target
    margin: 10,

    // forcibly override top/left positioning of leg
    top: null,
    left: null,

    // location of arrow, if applicable
    // if you pass a string (like "50%") it will be used
    // verbatim, if you pass a number, it will be in pixels
    arrow: "50%"
  }
}
            </script>

            <a name='leg-options'></a>
            <p>
              You don't often want to only use globals that affect
              every single leg... so you can specify the above 'leg' options
              on a per-leg basis. This is done in the markup with
              <tt>data-*</tt> attributes. Here's all of them,
              they match up with the global defaults above pretty obviously.
              Only the first one is special and usually required, it
              defines the target of the leg (what it should point to/be near):
            </p>

            <table class='docs'>
              <tr>
                <th width='25%'><tt>data-el</tt></th>
                <td>
                  The jQuery selector of the element that this leg
                  should 'target' on-page. If you leave this off, it will
                  assume you want a modal, and it will be centered inside
                  the <tt>container</tt> of the tour
                  (or you can override it with <tt>data-top</tt>,
                  as seen below).
                </td>
              </tr>
              <tr>
                <th><tt>data-scroll-to</tt></th>
                <td>
                  Override scroll position (0 for top of page, etc) for this
                  leg (normally it would be computed based on the offset and context).
                </td>
              </tr>
              <tr>
                <th><tt>data-scroll-speed</tt></th>
                <td>
                  The duration of the scrolling animation (can be 0 for instant,
                  or just don't include <tt>jQuery-scrollTo</tt>
                  on your page).
                </td>
              </tr>
              <tr>
                <th><tt>data-scroll-context</tt></th>
                <td>How much space to leave above the leg when scrolled to (if scrolled to).</td>
              </tr>
              <tr>
                <th><tt>data-class</tt></th>
                <td>A class to give this leg.</td>
              </tr>
              <tr>
                <th><tt>data-orientation</tt></th>
                <td>
                  Position of this leg in relation to its target.
                  Default is <tt>bottom</tt>, can be any of
                  <tt>top</tt>, <tt>right</tt>, <tt>bottom</tt>, <tt>left</tt>
                  or <tt>centered</tt> to force a modal regardless of
                  <tt>data-el</tt>.
                </td>
              </tr>
              <tr>
                <th><tt>data-align</tt></th>
                <td>
                  Alignment of leg in relation to its target.
                  Default is <tt>left</tt> for orientations of top/bottom
                  or <tt>top</tt> for orientations of left/right.
                  Valid values (again depending on orientation):
                  <tt>top</tt>, <tt>right</tt>, <tt>bottom</tt>,
                  <tt>left</tt>, <tt>center</tt>.
                </td>
              </tr>
              <tr>
                <th><tt>data-width</tt></th>
                <td>Specify a static width for this leg.</td>
              </tr>
              <tr>
                <th><tt>data-margin</tt></th>
                <td>Extra space between the leg and its target.</td>
              </tr>
              <tr>
                <th><tt>data-top</tt></th>
                <td>Override the <tt>top</tt> offset of this leg.</td>
              </tr>
              <tr>
                <th><tt>data-left</tt></th>
                <td>Override the <tt>left</tt> offset of this leg.</td>
              </tr>
              <tr>
                <th><tt>data-arrow</tt></th>
                <td>
                  Specifies where the arrow/pointer should be shown
                  (if applicable). Defaults to '50%' and can be any
                  valid CSS value. If you only pass in a number, it
                  will assume pixels.
                </td>
              </tr>
              <tr>
                <th><tt>data-zindex</tt></th>
                <td>
                  Specify the z-index of the leg. Defaults to '9999'.
                  This is useful if you have other elements you need
                  to be on top of the leg.
                </td>
              </tr>
            </table>

            <p>
              Likewise, you can set <tt>data-*</tt> attributes on the
              containing <tt>ol</tt> instead of setting the options in your code:
            </p>

            <table class='docs'>
              <tr>
                <th width='25%'><tt>data-debug</tt></th>
                <td>
                  Makes the tour logging more verbose.
                </td>
              </tr>
              <tr>
                <th width='25%'><tt>data-auto-depart</tt></th>
                <td>
                  Automatically starts the tour right away.
                </td>
              </tr>
              <tr>
                <th width='25%'><tt>data-start-at</tt></th>
                <td>
                  Which leg to start on. Normally this is <tt>0</tt>,
                  but sometimes you might want to have a back-and-forth thing
                  going on where you start in the middle, or show the first
                  two legs on init, etc.
                </td>
              </tr>
              <tr>
                <th width='25%'><tt>data-container</tt></th>
                <td>
                  The jQuery selector of the element that this
                  tour will be added to. An element with a class of
                  <tt>tourbus-container</tt> and a unique id will be added.
                </td>
              </tr>
              <tr>
                <th width='25%'><tt>data-class</tt></th>
                <td>
                  A class to add to the tour container element.
                  Whatever you specify here, it will always have a class of
                  <tt>tourbus-container</tt> which might suffice for most
                  styling requirements.
                </td>
              </tr>
            </table>

            <p>
              <em>Note:</em> At the moment the callbacks (eg, <tt>onDepart</tt>)
              need to be implemented in code, not as data attributes.
            </p>

            <h2>Miscellaneous</h2>
            <p>
              So you know how to define legs, how to customize them, how
              to setup globals, how to start/stop the tour, etc.
              This section covers some other bits that don't fit anywhere
              else.
            </p>

            <a name='convenience-classes'></a>
            <h3>Convenience Classes</h3>

            <p>
              Any element with one of these convenience classes will perform the
              associated action when clicked &ndash; easy.
            <p>

            <table class='docs'>
              <tr>
                <th width='25%'><tt>tourbus-stop</tt></th>
                <td>Stop the tour and take everyone home :(</td>
              </tr>
              <tr>
                <th><tt>tourbus-next</tt></th>
                <td>Advance to the next leg of the tour.</td>
              </tr>
              <tr>
                <th><tt>tourbus-prev</tt></th>
                <td>Go back to the previous leg.</td>
              </tr>
            </table>

            <p>
              For more fine-grained control, simply bind your own
              event handlers.
            <p>
              <strong>Note:</strong>
              However, since the elements you want to bind event handlers to
              aren't in the DOM yet, you'll probably want to use this handy
              helper:
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
var tourbus = $('#my-tour').data('tourbus');
tourbus.on( 'click', 'selector', function() {
  // handle click on element matching selector
  // _inside any tour leg_
} );
            </script>


            <a name='css-stuff'></a>
            <h3>Leg CSS</h3>

            <p>
              You will probably want to modify the look/feel
              of the leg popups (including the arrow it
              displays, etc). In <tt>jquery-tourbus.css</tt>
              you'll find the basics of
              the leg styles (padding, background color, etc)
              which you can either edit or override in your own stylesheets.
            </p>

            <p>
              One thing that is a bit tricky is the arrow. You'll
              need to look for the following styles:
            </p>

            <script class='highlight' data-language='css' type='text/highlight'>
.tourbus-arrow:after { /* ... */ border-width: 14px; }
.tourbus-arrow:before { /* ... */ border-width: 16px; }
            </script>

            <p>
              Notice they have different border widths, that's because
              the 2px difference results in a 2px border, colored in the styles
              on the following lines.
            </p>

            <p>
              When you change these values, the same values need to be changed
              in the rest of the arrow styles as well, eg:
            </p>

            <script class='highlight' data-language='css' type='text/highlight'>
.tourbus-arrow-right:after { /* ... */ margin-top: -14px; }
.tourbus-arrow-right:before { /* ... */ margin-top: -16px; }
            </script>

            <p>
              So, if you changed <tt>14px</tt> or <tt>16px</tt> above then they
              also need to be changed
              everywhere else those values appear... except they need
              to be <em>negative</em>!
            </p>

            <p>
              Hopefully changing colors here is self explanatory.
              Even better would be to use the LESS version as it
              uses variables for colors (and the margins described above)
              and is more succinct and easier to read.
            </p>


            <a name='images'></a>
            <h3>Notes about Using Images</h3>

            <p>
              At times you'll want to put images either in your legs
              themselves, or in your document and target them with legs.
              If you do, you'll notice that the positioning is probably
              all wrong, and that's because at the time of the calculations
              for positioning the popups the images are not likely to be
              loaded yet.
            </p>

            <p>
              I recommend you use
              <a href='http://desandro.github.com/imagesloaded/' target='_blank'>jQuery-imagesLoaded</a>
              to solve this problem. Simply wrap your entire tour initialization
              in a call to <tt>imagesLoaded</tt>:
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$(window).load( function() {
  $('img').imagesLoaded( function() {
    $('#my-tour-id').tourbus( {} );
  } );
} );
            </script>

            <p>
              Now your tour won't get setup at all until the images are loaded.
              You will probably need this any time the layout of the page is determined
              by the size of the loading images. If you've got more of an application
              where things are static sizes/positions and images just load into that
              layout (icons and the like) then you're probably ok.
            </p>


            <h2>Advanced Usage</h2>

            <p>
              Here's some example code and suggestions on how
              you might implement some advanced use-cases.
            </p>

            <a name='custom-properties'></a>
            <h3>Custom Properties</h3>
            <p>
              The first thing you need to know is that <tt>data-*</tt>
              attributes unused/unknown to TourBus will be available on
              your legs and the tour itself as <tt>rawData</tt>.
            </p>

            <p>
              This uses jQuery conventions. The property
              <tt>data-some-custom-property</tt> will be available in
              <tt>rawData</tt> as <tt>rawData.someCustomProperty</tt>.
            </p>

            <p>
              There are a few examples of this below, such as
              <a href='#example-1'>auto-progress</a> and <a href='#example-4'>highlight/expose</a>.
            </p>

            <a name='internals'></a>
            <h3>Useful Internal Properties</h3>
            <p>
              Internal properties of <tt>Leg</tt> and <tt>Bus</tt>
              instances that you will find useful for advanced use-cases.
            </p>

            <table class='docs'>
              <tr class='section'>
                <th colspan='2'><tt><strong>Bus</strong></tt> instances</th>
              </tr>
              <tr>
                <th width='25%'><tt>currentLegIndex</tt></th>
                <td>
                  The index of the current leg. If you modify this
                  then the next calls to <tt>showLeg</tt>, <tt>next</tt>
                  and so on will use this index. Eg. you could use this
                  to skip N legs by binding a click handler that increases
                  this value by N and calling <tt>showLeg</tt>.
                </td>
              </tr>
              <tr>
                <th><tt>options</tt></th>
                <td>
                  All of the merged options (from the constructor
                  and defaults).
                </td>
              </tr>
              <tr>
                <th><tt>rawData</tt></th>
                <td>
                  All <tt>data-*</tt> attributes used on the top-level <tt>ol</tt>.
                </td>
              </tr>
              <tr>
                <th><tt>running</tt></th>
                <td>Whether this tour is running or not right now.</td>
              </tr>
              <tr>
                <th><tt>legs</tt></th>
                <td>
                  An array of <tt>Leg</tt> instances, in the order
                  they were defined in the DOM.
                  <br/>
                  <small>
                    <strong>Note:</strong>
                    The legs are not actually inserted all at once, but on demand.
                    A leg will be built and inserted if it is requested to be shown
                    or it is coming up next (<tt>currentLegIndex+1</tt>). You generally
                    don't need to build legs by hand because an unbuilt leg is detected
                    and handled in <tt>showLeg</tt>. If you must build a leg by hand,
                    call <tt>buildLeg(index)</tt> on a <tt>Bus</tt>.
                  </small>
                </td>
              </tr>
              <tr>
                <th><tt>$container</tt></th>
                <td>
                  jQuery wrapped element where the legs will
                  be inserted.
                  <br/>
                  <small><strong>Note:</strong>
                  As above, legs are lazily instantiated/inserted, so
                  don't expect to find everything in here (eg, to target
                  an element in a leg for something) until it's been built by
                  hand or by being shown.</small>
                </td>
              </tr>
              <tr>
                <th><tt>$el</tt></th>
                <td>
                  The container element for the legs
                  (inserted into <tt>$container</tt>).
                </td>
              </tr>
              <tr>
                <th><tt>$original</tt></th>
                <td>
                  jQuery wrapped element which contains
                  the steps. This is the original element you
                  called <tt>tourbus</tt> on.
                </td>
              </tr>
            </table>
            <table class='docs'>
              <tr class='section'>
                <th colspan='2'><tt><strong>Leg</strong></tt> instances</th>
              </tr>
              <tr>
                <th><tt>bus</tt></th>
                <td>The <tt>Bus</tt> this leg belongs to.</td>
              </tr>
              <tr>
                <th width='25%'><tt>rawData</tt></th>
                <td>All the <tt>data-*</tt> attributes on this leg.</td>
              </tr>
              <tr>
                <th><tt>index</tt></th>
                <td>The index of this leg in its tour.</td>
              </tr>
              <tr>
                <th><tt>$target</tt></th>
                <td>The element this leg targets (will be positioned near, etc).</td>
              </tr>
              <tr>
                <th><tt>$el</tt></th>
                <td>The leg element in the DOM.</td>
              </tr>
              <tr>
                <th><tt>$original</tt></th>
                <td>The original leg definition element.</td>
              </tr>
              <tr>
                <th><tt>content</tt></th>
                <td>The innerHTML content of this leg.</td>
              </tr>
            </table>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$('#my-tour').tourbus( {
  customProp: 5,
  onLegStart: function( leg, bus ) {
    console.log( "Leg", leg.index, ":", leg.rawData );
    console.log( "TourBus customProp:", bus.rawData.customProp );
  }
} );
            </script>

            <p>
              This is how you can implement custom behavior easily, simply
              give your legs individual custom properties, or give them
              to TourBus as global options.
            </p>


            <a name='monkey-patching'></a>
            <h3>Monkey Patching</h3>

            <p>
              If you're doing something seriously weird, you might
              want access to the core <tt>Bus</tt> and <tt>Leg</tt> classes.
              They are entirely hidden in the plugin's scope, so you
              can't really get at them. <tt>expose</tt> to the rescue!
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$.tourbus( 'expose', window );
// You now have access!
//   window.tourbus.Bus
//   window.tourbus.Leg
            </script>

            <p>
              <tt>expose</tt> takes any object-like
              as a parameter, here we just use <tt>window</tt>.
              Now you can easily monkey patch methods or add methods to the
              prototypes all you like.
            </p>

            <a name='example-1'></a>
            <h3>Auto-progress from 1 leg to the next.</h3>

            <p>
              The simplest case of auto-progression would look like this.
              First, the markup for a leg which should auto-progress to
              the next leg after 3 seconds:
            </p>

            <script class='highlight' data-language='html' type='text/highlight'>
<li data-el='#header' data-auto-progress='3000'>
  <p>This is the header...</p>
</li>
<li data-el='#footer'>
  <p>...and this is the footer</p>
</li>
            </script>

            <p>And the JavaScript...</p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$('#my-tour').tourbus( {
  onLegStart: function( leg, bus ) {
    if( leg.rawData.autoProgress ) {
      var currentIndex = leg.index;
      setTimeout(
        function() {
          if( bus.currentLegIndex != currentIndex ) { return; }
          bus.next();
        },
        leg.rawData.autoProgress
      );
    }
  }
} );
            </script>

            <p>
              Simple! This also includes a check so that we don't
              progress to the next leg automatically unless we are
              still on the original leg when the timeout fires.
              You could skip that if it was unnecessary.
            </p>


            <a name='example-2'></a>
            <h3>Show multiple legs at once.</h3>

            <p>Even easier!</p>

            <script class='highlight' data-language='html' type='text/highlight'>
<li data-el='#header' data-and-next='true'>
  <p>This is the header...</p>
</li>
<li data-el='#footer'>
  <p>...and this is the footer</p>
</li>
            </script>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$('#my-tour').tourbus( {
  onLegStart: function( leg, bus ) {
    if( leg.rawData.andNext ) {
      bus.currentLegIndex++;
      bus.showLeg();
    }
  }
} );
            </script>

            <p>
              You can also show a specific leg without actually
              progressing further through the stack by passing
              an index to <tt>showLeg</tt>. For example, if you wanted
              to always show the first leg (a banner at the top of the page
              or similar), you could call <tt>showLeg(0)</tt> at the
              beginning of every <tt>onLegStart</tt>.
            </p>

            <a name='example-3'></a>
            <h3>Animate in or out a leg.</h3>

            <p>
              Say we want to fade in or do some fancy sliding and
              stuff of a leg. We can use the <tt>onLegStart</tt> for
              animating <em>in</em> and <tt>onLegEnd</tt> for animating
              <em>out</em>.
            </p>

            <p>
              Here we use the fact that returning <tt>false</tt> from
              <tt>onLegStart</tt> (or <tt>onLegEnd</tt>) will prevent
              the normal behavior, and we just implement it ourselves.
              <tt>$el</tt> has the actual step element in it...
            </p>

            <script class='highlight' data-language='javascript' type='text/highlight'>
$('#my-tour').tourbus( {
  onLegStart: function( leg, bus ) {
    leg.$el
      .css( { visibility: 'visible', opacity: 0, zIndex: 9999 } )
      .animate( { opacity: 1.0 }, 1000,
                function() { leg.show(); } );
    return false;
  }
} );
            </script>


            <a name='example-4'></a>
            <h3>Highlight/Expose Target Element</h3>

            <p>
              Lastly, maybe you want to draw a lot more attention to
              a specific target element as the tour is going on. We want
              the rest of the page to gray out, and only the element pointed
              to by this leg to be visible as usual. Easy.
            </p>

            <p>
              Let's start with some styles:
            </p>

            <script class='highlight' data-language='css' type='text/highlight'>
.my-tour-overlay {
  display: none;
  background: #666;
  opacity: 0.5;
  z-index: 9997;
  min-height: 100%;
  height: 100%;
  position: fixed;
  top: 0; right: 0; bottom: 0; left: 0;
}
.my-tour-highlight {
  background: white;
  position: relative;
  border-radius: 4px;
  box-shadow: inset 0 0 2px rgba( 0, 0, 0, 0.2 );
  z-index: 9998;
}
            </script>

            <p>
              Next we just write an <tt>onLegStart</tt> and
              <tt>onLegEnd</tt> handler that applies these styles.
            </p>


            <script class='highlight' data-language='javascript' type='text/highlight'>
$('#my-tour').tourbus( {
  onLegStart: function( leg, bus ) {
    if( leg.rawData.highlight ) {
      leg.$target.addClass('my-tour-highlight');
      $('.my-tour-overlay').show();
    }
  },
  onLegEnd: function( leg, bus ) {
    if( leg.rawData.highlight ) {
      leg.$target.removeClass('my-tour-highlight');
      $('.my-tour-overlay').hide();
    }
  }
} );
            </script>

            <p>
              That's it. Simple.
            </p>

          </div>
        </div>

        <div style='margin-top: 2000px; margin-bottom: 350px;'>
          <p id='it-scrolls' style='text-align: center;'>
            It also scrolls for you, if you have <tt>jQuery.scrollTo</tt>
            included on your page.
          </p>
        </div>

        <ol class='tourbus-legs' id='tourbus-demo-1'>
          <!-- INTRO -->
          <li data-scroll-to='0' data-width='500' data-orientation='centered' data-top='150'>
            <h3>This is a Tour!</h3>
            <p>
              That's right, you're looking at a real tour right now.
              Well, sort of 'real'. It's more like a <em>demo</em> of
              a tour with silly nonsense in it instead of actual content.
              Later on there is some documentation... that's useful content,
              but it's not part of this tour.
            </p>
            <div class='buttons'>
              <a href='javascript:void(0);' style='float: right;' class='button remove-bottom tourbus-next'>Tell me more <span>&raquo;</span></a>
              <a href='javascript:void(0);' class='button remove-bottom tourbus-stop'><span>&times;</span> Not interested...</a>
              <a href='javascript:void(0);' class='button remove-bottom docs-tour'>Got any docs?</a>
            </div>
          </li>

          <!-- Link -->
          <li data-el='#link' data-scroll-to='false' data-width='220' data-orientation='bottom' data-align='center'>
            <a class='button remove-bottom tourbus-next' style='margin-top: 2px; float: right;' href='javascript:void(0);'>cool story <span>&raquo;</span></a>
            <h4 class='remove-bottom'>A link!</h4>
          </li>

          <!-- Caps -->
          <li data-el='#img' data-scroll-to='false' data-width='320' data-orientation='right' data-margin='10' data-align='top'>
            <a style='margin-top: 2px; float: right;' href='javascript:void(0);' class='tourbus-next button remove-bottom'>sweet <span>&raquo;</span></a>
            <h4 class='remove-bottom'>The Pranksters Bus</h4>
          </li>

          <!-- Caps Center -->
          <li data-el='#img' data-scroll-to='false' data-width='320' data-orientation='right' data-align='center' data-margin='10'>
            <h3>No, seriously...</h3>
            <p>This is a pretty cool bus...<br/><small>I'm aligned 'center', btw...</small></p>
            <a href='javascript:void(0);' class='tourbus-prev button remove-bottom' onclick='$(this).hide();'><span>&laquo;</span> wait, what was that?</a>
            <a href='javascript:void(0);' style='float: right;' class='tourbus-next button remove-bottom'>gotcha <span>&raquo;</span></a>
          </li>

          <!-- Caps Bottom (TODO) -->
          <li data-el='#img' data-scroll-to='false' data-width='250' data-orientation='right' data-margin='10' data-align='bottom'>
            <a style='margin-top: 2px; float: right;' href='javascript:void(0);' class='tourbus-next button remove-bottom'>I get it...</a>
            <h4>Finally...</h4>
            <p class='remove-bottom'>This is the last leg about the bus.</p>
          </li>

          <!-- Coffeescript -->
          <li data-el='#coffee-dl' data-width='315' data-scroll-to='false' data-orientation='left' data-align='center'>
            <div style='float: right;'>
              <a href='javascript:void(0);' style='display: block;margin-bottom: 5px;' class='button tourbus-next'>nah</a>
              <a href='javascript:void(0);' style='display: block;' class='button remove-bottom tourbus-next'>cool</a>
            </div>
            <h2 class='remove-bottom' style='padding-top: 9px;'>CoffeeScript!</h2>
          </li>

          <!-- Plain JS -->
          <li data-el='#plain-dl' data-width='315' data-scroll-to='false' data-orientation='left' data-align='center'>
            <a href='javascript:void(0);' style='margin-top: 6px; float: right;' class='button remove-bottom tourbus-next'>alrighty</a>
            <h3>or, Plain JS</h3>
            <p class='remove-bottom'>
              It was written in CoffeeScript, so the plain JavaScript
              may not be to your liking. If that's the case, maybe you could
              just get the
              <a href='https://raw.github.com/rfunduk/jquery-tourbus/master/dist/jquery-tourbus.min.js' target='_blank'>minified version</a>!
            </p>
          </li>

          <!-- CSS -->
          <li data-el='#css' data-highlight='true' data-width='315' data-orientation='left' data-align='center' data-scroll-to='false'>
            <a href='javascript:void(0);' style='margin-top: 6px; float: right;' class='button remove-bottom tourbus-next'>yep <span>&raquo;</span></a>
            <h3 class='remove-bottom'>Get the CSS...</h3>
          </li>

          <!-- Deps -->
          <li data-el='#deps' data-width='315' data-orientation='left' data-align='center' data-scroll-to='false'>
            <a href='javascript:void(0);' style='margin-top: 6px; float: right;' class='button remove-bottom tourbus-next'>got it <span>&raquo;</span></a>
            <h3>Dependencies</h3>
            <p class='remove-bottom'>
              The only hard dependency is jQuery. If you want to scroll to
              your legs you need <tt>jQuery-scrollTo</tt>, and
              if you want positioning and stuff to behave predictably when images
              are involved you'll need <tt>jQuery-imagesLoaded</tt>.
            </p>
          </li>

          <!-- Scroll -->
          <li data-el='#it-scrolls' data-scroll-speed='1500' data-auto-progress='3500' data-orientation='top' data-width='200' data-margin='20'>
            <small>One more thing...</small>
          </li>
          <li data-el='#it-scrolls' data-orientation='bottom' data-margin='20' data-align='right'>
            <a href='javascript:void(0);' class='button remove-bottom tourbus-next'>Awesomesauce <span>&raquo;</span></a>
          </li>

          <!-- End -->
          <li data-orientation='centered' data-width='425' data-scroll-to='0'>
            <div style='float: right; width: 160px;'>
              <a href='javascript:void(0);' style='display: block;' class='button half-bottom tourbus-next'>goodbye</a>
              <a href='javascript:void(0);' style='display: block;' class='button remove-bottom docs-tour'>wait, didn't I see some docs fly by...?</a>
            </div>
            <h2>Thanks!</h2>
            <p class='remove-bottom'>This tour is over. Goodbye.</p>
          </li>
        </ol>

        <ol class='tourbus-legs' id='tourbus-demo-2'>
          <!-- INTRO -->
          <li data-el='#inner-docs' data-width='500' data-orientation='top' data-margin='15' data-scroll-context='25'>
            <a href='javascript:void(0);' class='button remove-bottom tourbus-next' style='float: right; margin-top: 8px;'>I'm ecstatic.</a>
            <h2 class='remove-bottom'>Documentation</h2>
          </li>
        </ol>
      </div>
    </div>

    <div class='intro-tour-overlay'></div>

    <script type="text/javascript">

      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-3195024-3']);
      _gaq.push(['_trackPageview']);

      (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
      })();

    </script>
  </body>
</html>
