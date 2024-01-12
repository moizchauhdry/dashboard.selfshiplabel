<?php 
date_default_timezone_set('America/New_York');
?>
<!DOCTYPE html>
<html lang="eng">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="https://summitstocks.com/wp-content/uploads/2021/04/cropped-android-chrome-192x192-1-32x32.png" sizes="32x32" />
    <link rel="icon" href="https://summitstocks.com/wp-content/uploads/2021/04/cropped-android-chrome-192x192-1-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://summitstocks.com/wp-content/uploads/2021/04/cropped-android-chrome-192x192-1-180x180.png" />

    <link rel="stylesheet" href="{{ asset('assets/lib/bootstrap/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"
      integrity="sha512-tS3S5qG0BlhnQROyJXvNjeEM4UpMXHrQfTGmbQ1gKmelCxlSEBUaxhRBj/EFTzpbP4RVSrpEikbmdJobCvhE3g=="
      crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"
      integrity="sha512-sMXtMNL1zRzolHYKEujM2AqCLUR9F2C4/05cdbxjjLSRvMQIciEPCQZo++nk7go3BtSuK9kfa/s+a4f4i5pLkw==" crossorigin="anonymous" />
    <link rel="stylesheet" type="text/css"
      href="{{ asset('assets/lib/font-awesome/css/font-awesome.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/font-face.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/style.css') }}" />

    <title>Summit Stocks</title>
  </head>
  <body>
    <style>
      #count-navbar div { color: #fff; text-align: center; }
      .act-now{ background-color: #EE5050; line-height: 60px;}      
      #show-countdown span {background-color: #EE5050; height: 40px; width: 40px; margin: 2px 10px; border-radius: 50%;
          font-size: 16px; font-weight: bold; padding-top: 10px; display: inline-block;}
    </style>
    <nav id="count-navbar" class="navbar navbar-expand-lg navbar-dark fixed-top">
      <div class="container">
        <div class="col-md-6">
          <h5>Don't miss out. Get these recommended stock picks before the next market close.</h5>
        </div>
        <div class="col-md-4" id="show-countdown">
          <span id="hours"></span>
          <span id="mins"></span>
          <span id="secs"></span>
          <span id="milli-secs"></span>
        </div>
        <div class="col-md-2 act-now">
          <strong><a href="javascript:void" class="scrolldown" style="color:#fff;">Act Now</a></strong>
        </div>
      </div>
    </nav>
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
              <a class="nav-link act-btn scrolldown" href="javascript:void(0)">
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

        <h1>Invest Better with Summit Stocks Advisor</h1>
				<p>Summit Stocks Advisor’s recommendations have beaten the market over the past 18 years by staying true to our investing philosophy.
					We believe your best chance to succeed in the stock market is to buy at least 15 stocks and hold them for at least 5 years. It's important to understand that stocks can and do go down,
					but investing is stillthe best way we know to build long-term wealth.</p>

          <h3>Join now for ONLY $99 – that’s just $1.90 a week!</h3>
        </div>
      </div>
    </section>

    <!-- STOCK IMAGE -->
    <div class="stock-img">
      <div class="container">
        <img class="img-fluid" src="{{ asset('/assets/images/mac-img.png') }}" alt="stock" />
      </div>
    </div>
    <!-- STOCK IMAGE -->

    <!-- STOCK DESCRIPTION -->
    <section class="stock-description" id="scroll-to">
      <div class="container">
        <div class="stock-box text-center">
          <div class="stock-text">
          Summit Stocks Advisor has 5X’ed the S&P 500 over the last 17 years
          </div>
          <p>
          The Summit Stocks Advisor team has outperformed the market 5-to-1 by rigorously combing every corner of every industry for overlooked companies poised to shatter the market – often when these businesses are flying under Wall Street’s radar.
          </p>
        </div>
      </div>
    </section>
    <!-- END STOCK DESCRIPTION -->

    <!-- STOCK RECOMMENDATIONS AND RETURNS -->
    <section class="stock-recommendation">
      <div class="container">
        <h3 class="ss-heading">219 Stock Recommendations with 100%+ Returns</h3>
        <p class="ss-para">
        Summit Stocks Advisor members gain unlimited access to our library of expert stock recommendations inside the service, each carefully aimed at multiplying your net worth.
        </p>
        <div class="stock-returns-block">
          <div
            class="stock-return-list d-flex justify-content-between position-relative"
          >
            <p>Recommendations Return</p>
            <p>S&P Return</p>
            <p>VS. S&P Return</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+21,031%</p>
            <p>+240%</p>
            <p>+386002%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+23,044%</p>
            <p>+298%</p>
            <p>+316662%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+19,018%</p>
            <p>+186%</p>
            <p>+29034%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+26,055%</p>
            <p>+410%</p>
            <p>+240065%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+24,088%</p>
            <p>+315%</p>
            <p>+32065%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+29,077%</p>
            <p>+348%</p>
            <p>+22331%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+23,055%</p>
            <p>+392%</p>
            <p>+288881%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+29,099%</p>
            <p>+510%</p>
            <p>+32115%</p>
          </div>
          <div class="stock-return-meta d-flex justify-content-between">
            <p>+33,144%</p>
            <p>+412%</p>
            <p>+476882%</p>
          </div>
        </div>
        <div class="row mt-5">
        <div class="col-md-4">
        <iframe frameBorder='0' scrolling='no' width='300' height='150' src='https://api.stockdio.com/visualization/financial/charts/v1/SingleQuote?app-key=CDF079823C204B178444A96BBF7EE543&symbol=ETSY&includeDateTime=true&palette=Financial-Light'></iframe>      
        </div>
        <div class="col-md-4">
        <iframe frameBorder='0' scrolling='no' width='300' height='150' src='https://api.stockdio.com/visualization/financial/charts/v1/SingleQuote?app-key=CDF079823C204B178444A96BBF7EE543&symbol=PYPL&includeDateTime=true&palette=Financial-Light'></iframe>
          </div>
        <div class="col-md-4">
        <iframe frameBorder='0' scrolling='no' width='300' height='150' src='https://api.stockdio.com/visualization/financial/charts/v1/SingleQuote?app-key=CDF079823C204B178444A96BBF7EE543&symbol=ZG&includeDateTime=true&palette=Financial-Light'></iframe>
          </div>
        <div class="col-md-4">
        <iframe frameBorder='0' scrolling='no' width='300' height='150' src='https://api.stockdio.com/visualization/financial/charts/v1/SingleQuote?app-key=CDF079823C204B178444A96BBF7EE543&symbol=TDOC&includeDateTime=true&palette=Financial-Light'></iframe>
          </div>
        <div class="col-md-4">
        <iframe frameBorder='0' scrolling='no' width='300' height='150' src='https://api.stockdio.com/visualization/financial/charts/v1/SingleQuote?app-key=CDF079823C204B178444A96BBF7EE543&symbol=SQ&includeDateTime=true&palette=Financial-Light'></iframe>
          </div>
        <div class="col-md-4">
        <iframe frameBorder='0' scrolling='no' width='300' height='150' src='https://api.stockdio.com/visualization/financial/charts/v1/SingleQuote?app-key=CDF079823C204B178444A96BBF7EE543&symbol=RDFN&includeDateTime=true&palette=Financial-Light'></iframe>
          </div>
        </div>

      </div>
    </section>
    <!-- END STOCK RECOMMENDATIONS AND RETURNS -->

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

    <!-- STOCK LOYAL MEMBERS -->
    <section class="stock-loyal-members">
      <div class="container">
        <h3 class="ss-heading">Stock Advisor is Fast and Easy</h3>
        <p class="ss-para">
          Stock Advisor members gain unlimited access to our library of expert
          stock recommendations inside the service, each carefully aimed at
          multiplying your net worth.
        </p>
        <div class="loyal-members">
          <div class="owl-carousel owl-theme">

            <div class="item">
              <div class="loyal-members-content d-flex text-center">

                <div class="loyal-members-meta">
                  <img class="img-fluid" src="{{ asset('assets/images/Logo-01.png') }}" alt="" />
                  <p class="fs-16 fw-300">
                  I must say that Summit Stocks is the best advisory company. I have been using its services for 8 months and trust me this is the best decision so far that I have made in a long time. It will surely change my life as I can see my stocks are going up in S&P 500. My stock advisor is highly professional and vigilant. He gave me the best recommendations and I trust him completely. I hope my dream of owning a house is going to be true soon. 
                  </p>
                  <span>Robert R. Ordway</span>
                </div>
              </div>
            </div>

            <div class="item">
              <div class="loyal-members-content d-flex text-center">

                <div class="loyal-members-meta">
                  <img class="img-fluid" src="{{ asset('/assets/images/Logo-01.png') }}" alt="" />
                  <p>
                  Summit Stocks provide you the best bang for your buck. I have been with this company since 2 years and I have gained almost 10% plus on all stocks I have invested on their recommendations. The services are affordable and worth investing in. Because of the Summit Stocks I am able to get rid of my long term debt. 
                  </p>
                  <span>David B. Summerville</span>
                </div>
              </div>
            </div>

          </div>
        </div>
        <div class="stock-order-pricing text-center">
          <div class="row gx-0">
            <div class="col-md-4">
              <div class="stock-order">
                <p>50%</p>
                <p>Off List Price</p>
                <p>When You Order Today</p>
              </div>
            </div>
            <div class="col-md-8">
              <div class="stock-pricing">
                <span>$199 $99</span>
                <p>
                  Only $99 for a year of full access – that’s just $1.90 a week.
                  Backed by our 30-Day membership fee back guarantee
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <!-- END STOCK LOYAL MEMBERS -->

    <!-- STOCK SUBSCRIPTION -->
    <form method="post" action="{{ url('/leads/save') }}">
      {{ csrf_field() }}
    <section class="stock-subscription">
      <div class="container">
        <h3 class="ss-heading">Select Your Subscription</h3>
        <p class="ss-para">
          Best of all, when you join Stock Advisor today, you’ll receive
          IMMEDIATE access to our latest stock picks. And if you give Stock
          Advisor a try and decide it’s not for you, that’s fine too. Simply
          cancel within 30 days and you’ll receive every penny of your
          membership fee back.
        </p>
        <div class="stock-subscription-form">
          <div class="row">
            <div class="col-md-6">
              <div class="stock-membership text-center">
                <div class="radio-button d-flex justify-content-center">
                  <label>
                    <input type="radio" name="subscription" value="99" checked />
                  </label>
                </div>
                <div class="membership d-flex justify-content-center">
                  <p>$99/</p>
                  <p>1 year</p>
                </div>
                <div class="membership-meta">
                  <h4>Summit Stocks Advisor</h4>
                  <p>
                    Backed by our 30-day 100% membership fee-back guarantee
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="stock-membership text-center md-mrgn">
                <div class="radio-button d-flex justify-content-center">
                  <label>
                    <input type="radio" name="subscription" value="39" checked />
                  </label>
                </div>
                <div class="membership d-flex justify-content-center">
                  <p>$39/</p>
                  <p>1 month</p>
                </div>
                <div class="membership-meta monthly-membership">
                  <h4>Summit Stocks Advisor</h4>
                  <p>
                    No refunds available for monthly memberships, cancel anytime
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="renew-membership text-center">
            <p>
              Your subscription will automatically renew at the then current
              price.
            </p>
          </div>
          
            <div class="order-form">
              <h5>Personal Info</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="first_name" id="first_name" type="text" class="form-control" placeholder="First Name" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="last_name" id="last_name" type="text" class="form-control" placeholder="Last Name" required />
                  </div>
                </div>
              </div>
            </div>
            <div class="billing-form">
              <h5>Billing Address</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="address1" id="address1" type="text" class="form-control" placeholder="Address 1" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="address2" id="address2" type="text" class="form-control" placeholder="Address 2" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input name="city" id="city" type="text" class="form-control" placeholder="City" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <select name="state" id="state" class="form-select"  required>
                      <option selected>Select State</option>                      
                      <option value="AL">Alabama</option><option value="AK">Alaska</option>
                      <option value="AS">American Samoa</option><option value="AZ">Arizona</option>
                      <option value="AR">Arkansas</option><option value="AA">Armed Forces Americas</option>
                      <option value="AE">Armed Forces Europe</option><option value="AP">Armed Forces Pacific</option>
                      <option value="CA">California</option><option value="CO">Colorado</option>
                      <option value="CT">Connecticut</option><option value="DE">Delaware</option>
                      <option value="DC">District of Columbia</option><option value="FL">Florida</option>
                      <option value="GA">Georgia</option><option value="GU">Guam</option>
                      <option value="HI">Hawaii</option><option value="ID">Idaho</option>
                      <option value="IL">Illinois</option><option value="IN">Indiana</option>
                      <option value="IA">Iowa</option><option value="KS">Kansas</option>
                      <option value="KY">Kentucky</option><option value="LA">Louisiana</option>
                      <option value="ME">Maine</option><option value="MD">Maryland</option>
                      <option value="MA">Massachusetts</option><option value="MI">Michigan</option>
                      <option value="MN">Minnesota</option><option value="MS">Mississippi</option>
                      <option value="MO">Missouri</option><option value="MT">Montana</option>
                      <option value="NE">Nebraska</option><option value="NV">Nevada</option>
                      <option value="NH">New Hampshire</option><option value="NJ">New Jersey</option>
                      <option value="NM">New Mexico</option><option value="NY">New York</option>
                      <option value="NC">North Carolina</option><option value="ND">North Dakota</option>
                      <option value="MP">Northern Mariana Islands</option><option value="OH">Ohio</option>
                      <option value="OK">Oklahoma</option><option value="OR">Oregon</option>
                      <option value="PA">Pennsylvania</option><option value="PR">Puerto Rico</option>
                      <option value="RI">Rhode Island</option><option value="SC">South Carolina</option>
                      <option value="SD">South Dakota</option><option value="TN">Tennessee</option>
                      <option value="TX">Texas</option><option value="UT">Utah</option>
                      <option value="VT">Vermont</option><option value="VI">Virgin Islands</option>
                      <option value="VA">Virginia</option>
                      <option value="WA">Washington</option><option value="WV">West Virginia</option>
                      <option value="WI">Wisconsin</option><option value="WY">Wyoming</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <select name="country" id="country" class="form-select"  required>
                      <option selected>Country</option>
                      <option value="USA">United States Of America</option>
                      <option value="CA">Canada</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="postal_code" name="postal_code" class="form-control"  placeholder="Postal Code" required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="phone_no" name="phone_no" class="form-control" placeholder="Phone No"  required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="email" name="email_address" id="email_address" class="form-control" placeholder="Email Address" required  />
                  </div>
                </div>
              </div>


            </div>
            <div class="credit-form">
              <h5>Credit Card</h5>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" class="form-control" id="name_on_card" name="name_on_card" placeholder="Name on Card" required />
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <input type="text" id="ccnumber" name="ccnumber" data-inputmask="'mask': '9999 9999 9999 9999'" class="form-control" placeholder="Card Number"  required />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-4">
                  <div class="form-group">
                    <select id="exp_month" name="exp_month" class="form-select" required>
                      <option selected>Expiration Month</option>
                      <option value="01">Jan</option><option value="02">Feb</option>
                      <option value="03">Mar</option><option value="04">Apr</option>
                      <option value="05">May</option><option value="06">Jun</option>
                      <option value="07">Jul</option><option value="08">Aug</option>
                      <option value="09">Sep</option><option value="10">Oct</option>
                      <option value="11">Nov</option><option value="12">Dec</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <select id="exp_year"  name="exp_year" class="form-select" required>
                      <option selected>Expiration Year</option>
                      <?php $year = date('Y');
                        while($year < 2030){ echo "<option value='$year'>$year</option>"; $year++; }
                      ?> 
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <div class="form-group">
                    <input name="cvv" id="cvv" type="text" data-inputmask="'mask': '9999'" class="form-control" placeholder="CVV" required />
                  </div>
                </div>
              </div>
            </div>
            <div class="form-meta">
              <h5>Important Notes</h5>
              <p>
                By clicking Submit Order, you are agreeing to our
                <a href="javascript:void()">Terms and Conditions</a> and
                <a href="javascript:void()">Privacy Policy</a>.
              </p>
              <p>
                Sales tax may apply. For more information, please see our tax
                <a href="javascript:void()">FAQs.</a>
              </p>
              <p>
                Your subscription will automatically renew at the then current
                price, using the most recent credit card we have on file.
                Subscriptions purchased with Apple Pay will renew using Apple
                Pay. All prices quoted are in U.S. dollars.
              </p>
              <p>
                If there is no refund offer or credit transfer offer stated on
                this order page, none will apply for the purchase of this
                product.
              </p>
              <p>
                To cancel or request changes to your subscription please contact
                us here. Please note that some subscriptions can be managed
                online via your My Account page.
              </p>
              <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" required />
                <label class="form-check-label" for="exampleCheck1">I agree to The Summit Stock
                  <a href="javascript:void()">Terms and Conditions</a> and
                  <a href="javascript:void()">Privacy Policy</a>.</label
                >
              </div>
            </div>
            <div class="order-button text-center">
              <input type="submit" value="Submit my order" class="btn btn-danger" />
            </div>
            <div class="security-encrypted d-flex align-items-center justify-content-center">
              <svg class="icon icon-lock" width="12" height="12">
                <use href="{{ asset('/assets/images/sprite.svg#lock') }}"></use>
              </svg>
              <p>
                Your order information is securely encrypted.
              </p>
            </div>
            <div class="call-us text-center">
              <a href="#" title="call us"
                >Need assistance? Call us toll-free at (855) 692-3665.</a
              >
            </div>
          </form>
        </div>
      </div>
    </section>
    <!-- END STOCK SUBSCRIPTION -->

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
    <script src="{{ asset('assets/lib/bootstrap/js/bootstrap.min.js') }}"></script>
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"
      integrity="sha512-bPs7Ae6pVvhOSiIcyUClR7/q2OAsRiovw4vAkX+zJbw3ShAeeqezq50RIIcIURq7Oa20rW2n2q+fyXBNcU9lrw=="
      crossorigin="anonymous"
    ></script>
    <script src="{{ asset('/assets/js/app.js') }}"></script>
    <script type="text/javascript" src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/3/jquery.inputmask.bundle.js"></script>

	<script>

    $(".scrolldown").click(function() {
        $('html, body').animate({
            scrollTop: $(".stock-subscription").offset().top
        }, 1000);
    });

    $(document).ready(function(){
      $(":input").inputmask();	
    });

    <?php 
      $today=(int) date('N');
      $hour=(int) date('G');
      if( $today > 0 && $today < 6 && $hour > 9 && $hour < 16):
    ?>

    $(window).scroll(function() {
      var hT = $('#scroll-to').offset().top,
       hH = $('#scroll-to').outerHeight(),
       wH = $(window).height(),
       wS = $(this).scrollTop();
      if (wS > (hT+hH-wH)){
          $('#count-navbar').show();
          $('#top-navbar').hide();
          
      }else{
        $('#count-navbar').hide();
        $('#top-navbar').show();
      }
    });

    <?php 
    endif;
    ?>

	</script>

<script>

const deadline = '<?= date('F j y'); ?> 15:59:59 GMT-0500';
function getTimeRemaining(endtime){
  const total = Date.parse(endtime) - Date.parse(new Date());
  const seconds = Math.floor( (total/1000) % 60 );
  const minutes = Math.floor( (total/1000/60) % 60 );
  const hours = Math.floor( (total/(1000*60*60)) % 24 );
  const days = Math.floor( total/(1000*60*60*24) );

  return {
    total,
    days,
    hours,
    minutes,
    seconds
  };
}

function initializeClock(id, endtime) {
  const clock = document.getElementById(id);
  const timeinterval = setInterval(() => {
    const t = getTimeRemaining(endtime);
     
    document.getElementById('hours').innerHTML =  t.hours;
    document.getElementById('mins').innerHTML =  t.minutes;
    document.getElementById('secs').innerHTML =  t.seconds;

    if (t.total <= 0) {
      clearInterval(timeinterval);
    }
  },1000);
}


initializeClock('clockdiv',deadline);

var counter = setInterval(timer, 50); //10 will  run it every 100th of a second

var milsecs = 100;

function timer(){
  if(milsecs < 0 || milsecs == 0)
    milsecs = 100;
  
    milsecs = milsecs - 10;
    document.getElementById('milli-secs').innerHTML =  milsecs;

}

</script>  

  </body>
</html>
