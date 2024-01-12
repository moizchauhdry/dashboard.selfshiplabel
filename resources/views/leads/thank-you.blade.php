<!DOCTYPE html>
<html lang="eng">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="https://summitstocks.com/wp-content/uploads/2021/04/cropped-android-chrome-192x192-1-32x32.png" sizes="32x32" />
    <link rel="icon" href="https://summitstocks.com/wp-content/uploads/2021/04/cropped-android-chrome-192x192-1-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://summitstocks.com/wp-content/uploads/2021/04/cropped-android-chrome-192x192-1-180x180.png" />

    <link rel="stylesheet" href="{{ asset('/assets/lib/bootstrap/css/bootstrap.min.css') }}" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
      integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
      integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw=="
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      type="text/css"
      href="{{ asset('/assets/lib/font-awesome/css/font-awesome.min.css') }}"
    />
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/font-face.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}" />

    <title>Summit Stocks</title>
  </head>
  <body>

    <nav id="top-navbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container">
        <a class="navbar-brand" href="#">
          <img class="img-fluid" src="{{ asset('/assets/images/Logo.png') }}" alt="logo" />
        </a>
        <button
          class="navbar-toggler"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbar"
          aria-controls="navbar"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbar">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link act-btn" href="#">
                <svg class="icon icon-bar-chart-line" width="16" height="16">
                  <use href="{{ asset('/assets/images/sprite.svg#bar-chart-line') }}"></use>
                </svg>
                <span>Act Now</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link contact-btn" href="#">
                <svg class="icon icon-telephone" width="16" height="16">
                  <use href="{{ asset('/assets/images/sprite.svg#telephone') }}"></use>
                </svg>
                <span>(855) 692-3665</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <section class="hero position-relative">
      <div class="container">
        <div class="hero-content text-center">

        <h1>Thankyou For Trusting Summit Stocks Advisor</h1>
        </div>
      </div>
    </section>

    <!-- STOCK IMAGE -->
    <div class="stock-img" style="margin-top: -420px;">
      <div class="container">
        <img class="img-fluid" src="{{ asset('/assets/images/mac-img.png') }}" alt="stock" />
      </div>
    </div>
    <!-- STOCK IMAGE -->


    <!-- STOCK ADVISOR -->
    <section class="stock-advisor">
      <div class="container">
        <h3 class="ss-heading">Stock Advisor is Fast and Easy</h3>
        <p class="ss-para">
          Stock Advisor members gain unlimited access to our library of expert
          stock recommendations inside the service, each carefully aimed at
          multiplying your net worth.
        </p>
        <div class="row">
          <div class="col-md-4">
            <div class="stock-advisor-content text-center">
              <img
                class="img-fluid"
                src="https://summitstocks.com/wp-content/uploads/2021/05/meric-dagli-XR5Fudiw1Z4-unsplash.jpg"
                alt=""
              />
              <p>
              NASDAQ, S&P 500 ready to set a new high record this week!
              </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="stock-advisor-content text-center">
              <img
                class="img-fluid"
                src="https://summitstocks.com/wp-content/uploads/2021/04/lloyd-blunk-vrSKrUEZsDY-unsplash-1.jpg"
                alt=""
              />
              <p>
              Wall Street – Stock Market Opens Mixed
              </p>
            </div>
          </div>
          <div class="col-md-4">
            <div class="stock-advisor-content text-center">
              <img
                class="img-fluid"
                src="https://summitstocks.com/wp-content/uploads/2021/04/sharon-mccutcheon-ZihPQeQR2wM-unsplash-1.jpg"
                alt=""
              />
              <p>
              e-Yuan is not a threat to replace Dollar: China
              </p>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END STOCK ADVISOR -->

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">
              Welcome To Summit Stocks <br />
              <span>To get you know better, Please select the option</span>
            </h5>
          </div>
          <div class="modal-body">
            <h3>Experience:</h3>
            <ul class="list-group exp-options">
              <li class="list-group-item">I have experience investing , having bought 5 or more stocks before</li>
              <li class="list-group-item">I have limited experience , having bought fewer than 5 stocks before</li>
              <li class="list-group-item">I have no experience investing , having never bought a stock</li> 
              <input type="hidden" id="experience" />
            </ul>            
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" onclick="saveExperience()">Summit</button>
          </div>
        </div>
      </div>
    </div>    



    <!-- FOOTER -->
    <footer>
      <div class="container">
        <div class="footer-content text-center">
          <div class="footer-logo">
            <img class="img-fluid" src="{{ asset('/assets/images/Logo.png') }}" alt="logo" />
          </div>
          <div class="footer-meta">
            <p>
              Problems Ordering? Email us at membersupport@summitstocks.com, or call
              (888) 665-3665, 9:30-4 ET, M-F.
            </p>
            <p>Return Policy | Help | Contact Us</p>
          </div>
          <div class="copyrights">
            <p>
              Legal Information. ©1995-2021 The Summit Stocks. All rights
              reserved.
            </p>
          </div>
        </div>
      </div>
    </footer>
    <!-- END FOOTER -->

    <!-- OPTIONAL JAVASCRIPT -->
    <script src="{{ asset('/assets/js/jquery-3.4.1.min.js') }}"></script>
    <script src="{{ asset('/assets/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
      integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
      crossorigin="anonymous"
    ></script>
    <script src="{{ asset('/assets/js/app.js') }}"></script>
    <script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>

    <script type="text/javascript">
      $(document).ready(function(){
        var base_url = {!! json_encode(url('/')) !!};

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

        $('#exampleModal').modal('show');

        // $( ".exp-options li" ).mouseenter( 
        //   function(){
        //     $( this ).addClass( "active" );
        //   }
        //  ).mouseleave( 
        //     function(){
        //       $( this ).removeClass( "active" );
        //     }           
        //   );

          $( ".exp-options li" ).click( 
          function(){
            $( ".exp-options li" ).removeClass( "active" );
            $( this ).addClass( "active" );
            $('#experience').val( $( this ).html() );
          }
         );

          

      });

      function saveExperience(){
        experience = $('#experience').val();
        var base_url = {!! json_encode(url('/')) !!};
        if(experience == ''){  alert('Select an option'); return; }

        $.ajax({
          type: 'Post',
          url : "{{ route('update_experience') }}",
        data: {id:<?= $id; ?>,experience:experience},
          success: function(){
            $('#exampleModal').modal('hide');
            location.replace(base_url);

          }
        })

      }
    </script>

  </body>
</html>
