// Brandon
// this is the backend for gathering information via spotify

var express = require('express'); // Express web server framework
var request = require('request'); // "Request" library
var cors = require('cors');
var querystring = require('querystring');
var cookieParser = require('cookie-parser');
var spotifyWebApi = require('spotify-web-api-node');
var client_id = '7c68944f23b34762ae5a2ed48244d94a'; // Your client id
var client_secret = '3091e73cb68e4705970b0a658c545d6b'; // Your secret
var redirect_uri = 'http://localhost:8888/callback'; // Your redirect uri
var user_access_token = ""; // for use outside of index.html

var spotifyApi = new spotifyWebApi({
  clientId: client_id,
  client_secret: client_secret,
  redirectUri: redirect_uri
});

/**
 * Generates a random string containing numbers and letters
 * @param  {number} length The length of the string
 * @return {string} The generated string
 */
var generateRandomString = function(length) {
  var text = '';
  var possible = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

  for (var i = 0; i < length; i++) {
    text += possible.charAt(Math.floor(Math.random() * possible.length));
  }
  return text;
};

var stateKey = 'spotify_auth_state';

var app = express();

app.use(express.static(__dirname + '/public'))
   .use(cors())
   .use(cookieParser());

app.get('/login', function(req, res) {

  var state = generateRandomString(16);
  res.cookie(stateKey, state);

  // your application requests authorization
  var scope = 'user-read-private user-read-email playlist-read-private user-library-read';
  res.redirect('https://accounts.spotify.com/authorize?' +
    querystring.stringify({
      response_type: 'code',
      client_id: client_id,
      scope: scope,
      redirect_uri: redirect_uri,
      state: state
    }));
});

app.get('/callback', function(req, res) {

  // your application requests refresh and access tokens
  // after checking the state parameter

  var code = req.query.code || null;
  var state = req.query.state || null;
  var storedState = req.cookies ? req.cookies[stateKey] : null;

  if (state === null || state !== storedState) {
    res.redirect('/#' +
      querystring.stringify({
        error: 'state_mismatch'
      }));
  } else {
    res.clearCookie(stateKey);
    var authOptions = {
      url: 'https://accounts.spotify.com/api/token',
      form: {
        code: code,
        redirect_uri: redirect_uri,
        grant_type: 'authorization_code'
      },
      headers: {
        'Authorization': 'Basic ' + (new Buffer(client_id + ':' + client_secret).toString('base64'))
      },
      json: true
    };

    request.post(authOptions, function(error, response, body) {
      if (!error && response.statusCode === 200) {

        var access_token = body.access_token,
            refresh_token = body.refresh_token;
        spotifyApi.setAccessToken(access_token);
        user_access_token = access_token;

        var options = {
          url: 'https://api.spotify.com/v1/me',
          headers: { 'Authorization': 'Bearer ' + access_token },
          json: true
        };

        // use the access token to access the Spotify Web API
        request.get(options, function(error, response, body) {
          console.log(body);
        });

        // we can also pass the token to the browser to make requests from there
        res.redirect('/#' +
          querystring.stringify({
            access_token: access_token,
            refresh_token: refresh_token
          }));
      } else {
        res.redirect('/#' +
          querystring.stringify({
            error: 'invalid_token'
          }));
      }
    });
  }
});

// lets us get the user a new access token in case the old one expires
app.get('/refresh_token', function(req, res) {

  // requesting access token from refresh token
  var refresh_token = req.query.refresh_token;
  var authOptions = {
    url: 'https://accounts.spotify.com/api/token',
    headers: { 'Authorization': 'Basic ' + (new Buffer(client_id + ':' + client_secret).toString('base64')) },
    form: {
      grant_type: 'refresh_token',
      refresh_token: refresh_token
    },
    json: true
  };

  request.post(authOptions, function(error, response, body) {
    if (!error && response.statusCode === 200) {
      var access_token = body.access_token;
      spotifyApi.setAccessToken(access_token);
      user_access_token = access_token;
      res.send({
        'access_token': access_token
      });
    }
  });
});

// get first album of an artist
app.get('/getinfoon', function(req, res) {
  //spotifyApi.setAccessToken(req.query.access_token);
  spotifyApi.getArtistAlbums(req.query.artistid, { limit: 1, offset: 1}).then(
      function(data) {
        console.log('Artist albums', data.body.items[0].name);
        res.send(data.body.items[0].name)
      },
      function(err) {
        console.error(err)
      });
});

// get user's playlists
app.get('/userplaylists', function (req, res) {
  spotifyApi.setAccessToken(req.query.access_token);
  let currUser;
  spotifyApi.getMe()
      .then(function (data) {
        console.log('here');
        currUser = data.body.id
      }),
      function (err) {
        console.error(err)
      };
  spotifyApi.getUserPlaylists(currUser)
      .then(function (data) {
        res.send(data)
      })
});

// collect the tracks a user has saved and prep them for import
app.get('/usertracks', function (req, res) {
    let songNames = [];
    let songURIs = [];
  //spotifyApi.setAccessToken(req.query.access_token);
    spotifyApi.getMySavedTracks({
        limit: 20
    })
        .then(function(data) {
            // example uri spotify:track:6k01AfRXrSqNzXGdFovdeQ
            // console.log(data.body.items[1].track.external_urls.spotify);
            // console.log(data.body.items.length);
            for (i = 0; i < data.body.items.length; i++) {
                let songName = data.body.items[i].track.name;
                let songURI = data.body.items[i].track.uri;
                console.log(songName);
                console.log(songURI);
                songNames.push(songName);
                songURIs.push(songURI);
                // sends the data to addtracks.php so it can be added to a person's library
                $.ajax({
                    url: 'addtracks.php',
                    type: 'post',
                    data: {
                        songName: songName,
                        songURI: songURI
                    },
                    success: function (res) {
                        console.log("completed import :)")
                    }
                })
            }
            // this sends it back to index to share imported data
            res.send({
                'song_names': songNames,
                'song_uris': songURIs
            });
            console.log('Done!');
            //console.log(data.body.items)
        }, function(err) {
            console.log('Something went wrong!', err);
        });
});


// send the access token to player.html
app.get('/getaccesstoken', function (req, res) {
    res.send({
        'access_token': user_access_token
    })
});
// search by keyword




console.log('Listening on 8888');
app.listen(8888);
