<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="apple-touch-icon icon shortcut" type="image/png" href="https://plugin.intuitcdn.net/sbg-web-shell-ui/6.3.0/shell/harmony/images/QBOlogo.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
       
    </head>
    <script>

        var url = '{{ $authUrl }}';

        var OAuthCode = function(url) {
            
            this.loginPopup = function (parameter) {
                this.loginPopupUri(parameter);
            }

            this.loginPopupUri = function (parameter) {
               
                // Launch Popup
                var parameters = "location=1,width=800,height=650";
                parameters += ",left=" + (screen.width - 800) / 2 + ",top=" + (screen.height - 650) / 2;

                var win = window.open(url, 'connectPopup', parameters);
                var pollOAuth = window.setInterval(function () {
                    try {

                        if (win.document.URL.indexOf("code") != -1) {
                            window.clearInterval(pollOAuth);
                            win.close();
                            location.reload();
                        }
                    } catch (e) {
                        console.log(e)
                    }
                }, 100);
            }
        }


        var apiCall = function() {
            this.getCompanyInfo = function() {
                /*
                AJAX Request to retrieve getCompanyInfo
                 */
                $.ajax({
                    type: "GET",
                    url: "apiCall.php",
                }).done(function( msg ) {
                    $( '#apiCall' ).html( msg );
                });
            }

            this.refreshToken = function() {
                $.ajax({
                    type: "POST",
                    url: "refreshToken.php",
                }).done(function( msg ) {

                });
            }
        }

        var oauth = new OAuthCode(url);
        var apiCall = new apiCall();
    </script>
</head>
<body>

<div class="container">

    <h1>
        <a href="http://developer.intuit.com">
            <img src="views/quickbooks_logo_horz.png" id="headerLogo">
        </a>

    </h1>

    <hr>

    <div class="well text-center">

        <h1>QuickBooks HelloWorld sample application</h1>
        <h2>Demonstrate Connect to QuickBooks flow and API Request</h2>

        <br>

    </div>
    <a href="{{$authUrl}}" >eijweogjweogijweogjw</a>

    <p>If there is no access token or the access token is invalid, click the <b>Connect to QuickBooks</b> button below.</p>
    <pre id="accessToken">
        <style="background-color:#efefef;overflow-x:scroll"><?php
    $displayString = isset($accessTokenJson) ? $accessTokenJson : "No Access Token Generated Yet";
    echo json_encode($displayString, JSON_PRETTY_PRINT); ?>
    </pre>
    <a class="imgLink" href="#" onclick="oauth.loginPopup()"><img src="views/C2QB_green_btn_lg_default.png" width="178" /></a>
    <hr />


    <h2>Make an API call</h2>
    <p>If there is no access token or the access token is invalid, click either the <b>Connect to QucikBooks</b> button above.</p>
    <pre id="apiCall"></pre>
    <button  type="button" class="btn btn-success" onclick="apiCall.getCompanyInfo()">Get Company Info</button>

    <hr />

</div>
</body>
</html>
