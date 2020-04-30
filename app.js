/* Author:  Brandon Hall */

/**
 * This is an example of a basic node.js script that performs
 * the Authorization Code oAuth2 flow to authenticate against
 * the Spotify Accounts.
 *
 * For more information, read
 * https://developer.spotify.com/web-api/authorization-guide/#authorization_code_flow
 */

var express = require('express'); // Express web server framework
var request = require('request'); // "Request" library
var cors = require('cors');
var querystring = require('querystring');
var cookieParser = require('cookie-parser');
var spotifyWebApi = require('spotify-web-api-node')
var client_id = '7c68944f23b34762ae5a2ed48244d94a'; // Your client id
var client_secret = '3091e73cb68e4705970b0a658c545d6b'; // Your secret
var redirect_uri = 'http://localhost:8888/callback'; // Your redirect uri

var spotifyApi = new spotifyWebApi({
    clientId: client_id,
    client_secret: client_secret,
    redirectUri: redirect_uri
})

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
            res.send({
                'access_token': access_token
            });
        }
    });
});

app.get('/getinfoon', function(req, res) {
    spotifyApi.setAccessToken(req.query.access_token);
    spotifyApi.getArtistAlbums('43ZHCT0cAZBISjO8DG9PnE', { limit: 1, offset: 1}).then(
        function(data) {
            console.log('Artist albums', data.body.items[0].name);
            res.send(data.body.items[0].name)
        },
        function(err) {
            console.error(err)
        });
    console.log('we are in getinfoon in the app.js')
    // res.send({
    //     'info': 'we got this sent'
    // })
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

// search based on artist
app.get('/usertracks', function (req, res) {
    spotifyApi.setAccessToken(req.query.access_token);
    spotifyApi.getMySavedTracks({
        limit : 10,
        offset: 1
    })
        .then(function(data) {
            let i;
            let songNames;
            // example uri spotify:track:6k01AfRXrSqNzXGdFovdeQ
            let songURIs;
            for (i in data.body.items) {
                console.log(data.body.items[i].track.name);
                songNames[i] = data.body.items[i].track.name
                songURIs[i] = data.body.items[i].track.uri
            }
            res.send({
                'song_names': songNames,
                'song_uris': songURIs
            })
            console.log('Done!');
            //console.log(data.body.items)
        }, function(err) {
            console.log('Something went wrong!', err);
        });
});
// search by keyword




console.log('Listening on 8888');
app.listen(8888);
