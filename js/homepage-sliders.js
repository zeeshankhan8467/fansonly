$( function() {

    $( "#slider-audience" ).slider({
      range: "max",
      min: AUDIENCE_MIN,
      max: AUDIENCE_MAX,
      value: AUDIENCE_PREDEFINED_NO,
      step: AUDIENCE_SL_STEP,
      slide: function( event, ui ) {
        var n = parseInt(ui.value);
        $( ".audience-size" ).html( n.toLocaleString() );
      },
      stop: function(event, ui) {
        computeEarnings();
      }
    });

    $( ".audience-size" ).html( $( "#slider-audience" ).slider( "value" ) );

    $( "#slider-package" ).slider({
      range: "max",
      min: MEMBERSHIP_FEE_MIN,
      max: MEMBERSHIP_FEE_MAX,
      value: MEMBERSHIP_FEE_PRESET,
      step: MEMBERSHIP_FEE_STEP,
      slide: function( event, ui ) {
        $( ".package-price" ).html( currencySymbol + ui.value );
      }, 
      stop: function(event, ui) {
        computeEarnings();
      }
    });

    $( ".package-price" ).html( currencySymbol + $( "#slider-package" ).slider( "value" ) );

    function computeEarnings() {

      var membersCount = parseInt($('#slider-audience').slider("option", "value"));
      var monthlyPrice = parseInt($('#slider-package').slider("option", "value"));
      var perMonth     = $( '.per-month' );

      var computePrice = membersCount * monthlyPrice;
      var feeAmount = (computePrice*platformFee)/100;
      var finalEarnings = computePrice-feeAmount;

      console.log( computePrice,  platformFee, finalEarnings );

      perMonth.html( currencySymbol + finalEarnings.toLocaleString()  );

    }

    computeEarnings();

});