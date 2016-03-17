<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Cherch un film</title>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">

  <!-- Optional theme -->
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">

  <!-- Latest compiled and minified JavaScript -->
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>

  <script src="https://apis.google.com/js/client.js?onload=googleApiClientReady"></script>
</head>
<body>

  <div class="jumbo">
    <div class="well">
      <form action="/search.php" method="post" class="search-form form-inline">
        <input type="text" class="form-control col-xs-10" name="film" value="" placeholder="Chercher">
        <input type="submit" class="btn btn-primary" value="Go">
        <span class="loading hidden">Loading...</span>
        <span class="user pull-right">Anonyme</span>
      </form>
    </div>
  </div>
  <div class="row result">
    
  </div>

  <script>

    var FBAppId = 388131744537974;

    window.fbAsyncInit = function() {
      FB.init({
          appId      : FBAppId,
          xfbml      : true,
          version    : 'v2.5'
        });

      FB.getLoginStatus(function(response) {
        if (response.status === 'connected') {
          FB.api("/me", function(data){
            $(".user").html(data.name);
          })
        }
        else {
          FB.login();
        }
      });
    };

    

    (function(d, s, id){
       var js, fjs = d.getElementsByTagName(s)[0];
       if (d.getElementById(id)) {return;}
       js = d.createElement(s); js.id = id;
       js.src = "//connect.facebook.net/en_US/sdk.js";
       fjs.parentNode.insertBefore(js, fjs);
     }(document, 'script', 'facebook-jssdk'));

    function loadResult(data){
      $('.loading').addClass('hidden');
      $(".result").empty();
      if(data.data.length > 0){

        $(".result").html("<div class='col-xs-5'>"+
                        "<img class='img-responsive' src='"+data.data[0].media_urls.poster.scale_480x640+"'>"+
                        "</div>"+
                        "<div class='col-xs-7'>"+
                        "<h1>"+data.data[0].title+"</h1>"+
                        "<p class='well'>"+data.data[0].synopsis+"</p>"+
                        "</div>"
                        );

        var request = gapi.client.youtube.search.list({
          q: data.data[0].title,
          part: 'snippet'
        });

        request.execute(function(response) {
          var str = JSON.stringify(response.result);
          console.log(str)
        });
      }else{
        $(".result").html("<div class='col-xs-4 col-xs-offset-4'><div class='alert alert-warning' role='alert'>Rien trouvé...</div></div>")
      }
    }

    $(document).on('submit', '.search-form', function(){
      var action = $(this).attr('action');
      var method = $(this).attr('method');
      $('.loading').removeClass('hidden');
      $.ajax({
        url: action,
        method: method,
        dataType: "jsonp",
        // jsonpCallback: "bouh",
        data: $(this).serialize(),
        success: loadResult
        })
      return false;
    })
  </script>
  
</body>
</html>
