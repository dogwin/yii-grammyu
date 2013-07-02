var Amplifier2013 = Amplifier2013 || {};

$.extend(true, Amplifier2013, {

    radioPlayer: {

        // Radio Player settings - default options and settings
        settings: {
            autoPlay: false,                 // Autoplay first track
            continuePlayback: true,          // Continious playback
            preloadArtworks: 1,              // Number of artwork images to preload
            randomizeTracks: false,          // Ranomize play order
            searchInitialQuery: "music",     // Initial query to populate player
            searchFetchLimit: 50,            // Number of tracks to fetch at a time
            searchFetchQueryCount: 50,       // Number of tracks to fetch if launching standalone
            searchFetchCount: 0,             // Number of tracks currently fetched
            searchTypingDelay: 1000,         // Pause in typing before firing search (ms)
            connect: {
                api_url_artists: "http://grammyamplifier.tbwachiatdev.com/app/api/search",    // Artists URL    - local API
                api_url_genres: "http://grammyamplifier.tbwachiatdev.com/app/api/genres",     // Genres URL     - local API
                api_url_users: "http://api.soundcloud.com/users/",                            // Users URL      - soundCloud
                api_url_tracks: "http://api.soundcloud.com/tracks/",                          // Tracks URL     - soundCloud
                api_url_playlists: "http://api.soundcloud.com/playlists/",                    // Playlist URL   - soundCloud
                client_id: "930ff41296b619d88739a1ea190960ab"                                 // Client ID / API Key
            }
        },

        // Radio Player state - tracks active track, current progress and state
        state: {
            playerReady: false,        // Player state
            apiReady: false,           // SoundCloud API state
            isStandalone: false,       // Is currently popped out in separate window
            currentTrack: null,        // Current track
            currentTrackID: null,      // Current track ID
            currentTrackTime: 0,       // Current time played of track
            currentTrackPosition: 0,   // Current percentage played of track
            currentTracklist: null,    // The current tracklist/playlist being played
            currentSearchQuery: "",    // The most recent / current search query
            currentResultCount: 0,     // Amount of tracks returned in current search
            currentResultsPage: 0,     // Current page of results fetched
            volumePercent: 50,         // Int to represent current volume
            searchTimer: null,         // Timeout for keystrokes and firing search
            playingResults: false,     // Currently playing a track from the search results
            playerHeight: 0,           // Cache the players current height on init
            isDefaultTrack: true,      // Is playing the first/default track
            isChangingGenre: false     // Used to prevent tracks being loaded when genre changes
        },

        // Main initialization
        init: function () {

            var _radioPlayer = Amplifier2013.radioPlayer,
                _settings = _radioPlayer.settings,
                $player = _radioPlayer.elements;

            // Initialize SouncCloud Connect API
            SC.initialize({ client_id: _radioPlayer.settings.connect.client_id });

            // Set the player mode on the element
            $("body").attr("data-radioplayer-mode", _radioPlayer.utils.getParamFromURL("mode") == "standalone" ? "standalone" : "fixed");

            // Configure standalone player
            if (_radioPlayer.utils.getParamFromURL("mode") == "standalone") {
                this.isStandalone = true;
                this.currentTrackID = _radioPlayer.utils.getParamFromURL("track");
                this.currentTrackPosition = _radioPlayer.utils.getParamFromURL("position");
                this.currentSearchQuery = _radioPlayer.utils.getParamFromURL("query");
                _settings.autoPlay = _radioPlayer.utils.getParamFromURL("playing") == 0 ? false : true;
                _settings.searchInitialQuery = this.currentSearchQuery;
                _settings.searchFetchQueryCount = _radioPlayer.utils.getParamFromURL("count");
                //console.log("opener", window.opener);
            } else {
                // Set initial query
                this.currentSearchQuery = _settings.searchInitialQuery;
            }

            // Cache player elements to avoid unnecessary DOM querying
            $.extend(true, _radioPlayer, {
                elements: {
                    document: $(document),
                    html: $("html"),
                    body: $("body"),
                    self: $(".radioplayer-tracks"),
                    playerWrapper: $(".radioplayer-wrapper"),
                    playerControls: $(".radioplayer-controls"),
                    playerControlButtons: $(".radioplayer-control-buttons"),
                    playerControlPlay: $(".radioplayer-control-button[data-radioplayer='play']"),
                    playerControlVolume: $(".radioplayer-control-button[data-radioplayer='volume']"),
                    playerControlVolumeBar: $(".radioplayer-control-volume"),
                    playerControlSlider: $(".radioplayer-control-slider"),
                    amplifyFacebook: $(".radioplayer-amplify-facebook"),
                    amplifyTwitter: $(".radioplayer-amplify-twitter"),
                    searchForm: $(".radioplayer-search-form"),
                    searchResults: $(".radioplayer-search-results"),
                    searchResult: $(".radioplayer-result"),
                    searchLoading: $(".radioplayer-search-loading"),
                    searchInput: $(".radioplayer-search-input"),
                    searchFiltersForm: $(".radioplayer-search-filers-form"),
                    searchFilterSort: $(".radioplayer-filter-select-sort"),
                    searchFilterGenre: $(".radioplayer-filter-select-genre"),
                    standaloneLaunch: $(".radioplayer-standalone-launch"),
                    standaloneReturn: $(".radioplayer-standalone-return"),
                    displayText: $(".radioplayer-display-text"),
                    displayTitle: $(".radioplayer-display-title"),
                    displayArtist: $(".radioplayer-display-artist"),
                    displayAlbum: $(".radioplayer-display-album"),
                    displayAlbumTitle: $(".radioplayer-display-album-title"),
                    displayAlbumYear: $(".radioplayer-display-album-year"),
                    displayDuration: $(".radioplayer-display-album .sc-duration"),
                    displayAtwork: $(".radioplayer-display-artwork"),
                    displayWaveformImg: $(".radioplayer-display-waveform-img"),
                    displayPlayhead: $(".radioplayer-display-playhead"),
                    displayItems: $(".radioplayer-display-text, .radioplayer-display-artwork, .radioplayer-display-waveform, .radioplayer-display-playhead")
                }
            });

            // Update search text - TO DO: refactor
            _radioPlayer.elements.searchInput.attr("placeholder", (_radioPlayer.elements.html.hasClass("touch") ? "Browse tracks..." : "Click to search or browse tracks..."));

            // Display loading text
            _radioPlayer.elements.displayArtist.addClass("show");

            // Populate playlist
            _radioPlayer.search.getTracks(_radioPlayer.settings.searchInitialQuery);

            // Cache player's initial height
            _radioPlayer.state.playerHeight = _radioPlayer.elements.playerWrapper.outerHeight();

            // Bind click handlers and event listeners
            _radioPlayer.bindEvents();

        },

        // Bind events and handlers
        bindEvents: function () {

            var _radioPlayer = this,
                $player = _radioPlayer.elements;

            // Track next / prev control
            $player.playerControls.on("click", ".radioplayer-control-button", _radioPlayer.changeTrack);

            // Search input & submit
            $player.searchForm.on("keyup", ".radioplayer-search-input", _radioPlayer.search.update);
            $player.searchForm.on("click", ".radioplayer-search-input", _radioPlayer.search.invoke);
            $player.searchForm.on("click", ".radioplayer-search-submit", _radioPlayer.search.toggle);
            $player.searchForm.on("submit", _radioPlayer.search.submit);

            // Search result filters
            $player.searchFiltersForm.on("change", ".radioplayer-filter-select-sort", _radioPlayer.search.results.sortChange);
            $player.searchFiltersForm.on("change", ".radioplayer-filter-select-genre", _radioPlayer.search.results.genreChange);

            // Search result track click
            $player.searchResults.on("click", ".radioplayer-result", _radioPlayer.loadTrack);

            // Search detect scroll - lazy load results
            $player.searchResults.on("scroll", _radioPlayer.search.scroll);

            // Share / Amplify
            $player.amplifyFacebook.on("click", _radioPlayer.share.facebook);
            $player.amplifyTwitter.on("click", _radioPlayer.share.twitter);

            // Keyboard controls
            $player.document.on("keyup", _radioPlayer.keyboardHandler);

            // Player - On ready
            $player.document.on("onPlayerInit.scPlayer", _radioPlayer.onPlayerReady);
            // Player - Starts playing
            $player.document.on("onPlayerPlay.scPlayer", _radioPlayer.onPlay);
            // Player - While playing
            $player.document.on("onMediaTimeUpdate.scPlayer", _radioPlayer.onTimeUpdate);
            // Player - Pauses
            $player.document.on("onPlayerPause.scPlayer", _radioPlayer.onPause);
            // Player - Track update
            $player.document.on("onPlayerTrackSwitch.scPlayer", _radioPlayer.onTrackUpdate);

            // Standalone player toggle
            $player.standaloneLaunch.on("click", _radioPlayer.standaloneLaunch);
            $player.standaloneReturn.on("click", _radioPlayer.standaloneReturn);

            // Volume drag control
            _radioPlayer.volumeHandler();

            // Initialization complete
            $player.playerWrapper.addClass("loaded");

        },

        // Search tracks
        search: {
            // Update search
            update: function (e) {

                var _radioPlayer = Amplifier2013.radioPlayer,
                    _settings = _radioPlayer.settings,
                    _state = _radioPlayer.state,
                    $player = _radioPlayer.elements,
                    query = $player.searchInput.val() === "" ? _settings.searchInitialQuery : $player.searchInput.val(),
                    key = e.which || e.keyCode,
                    invokeSearch = (key == 37 || key == 38 || key == 39 || key == 40) ? ($player.playerWrapper.hasClass("searching") ? false : true) : true; // Ingore search if any of the arrow keys were pressed

                // Use CSS transitions to animate, fallback to javascript
                if ($("html").hasClass("csstransitions")) {
                    $player.playerWrapper.removeClass("searching").addClass($player.searchInput.val() !== "" ? "searching" : (invokeSearch ? "" : "searching"));
                } else {
                    //$player.playerWrapper.animate({ height: $player.searchInput.val() !== "" ? "50%" : (invokeSearch ? _state.playerHeight : "50%") }, 500);
                }

                // Only fire when player is expanded
                if ($player.playerWrapper.hasClass("searching") && query != _radioPlayer.state.currentSearchQuery && invokeSearch) {

                    // Show loading indicator
                    $player.searchLoading.find("span").text($player.searchLoading.find("span").data("radioplayer-search-loading"));
                    $player.searchLoading.fadeIn(200);

                    // Scroll the search results back to the top
                    _radioPlayer.search.resetScroll();

                    // Clear the timeout
                    clearTimeout(_radioPlayer.state.searchTimer);

                    // Wait for pause in typing
                    _radioPlayer.state.searchTimer = setTimeout(function () {
                        _radioPlayer.state.currentResultCount = 0; // Reset current count
                        _radioPlayer.search.getTracks(query);      // Search tracks
                    }, _settings.searchTypingDelay);

                }

            },

            // Get the tracks based on query
            getTracks: function (query) {

                var _radioPlayer = Amplifier2013.radioPlayer,
                    _state = _radioPlayer.state,
                    _settings = _radioPlayer.settings,
                    $player = _radioPlayer.elements;

                // Cache current query
                _radioPlayer.state.currentSearchQuery = query;

                // Query the API for tracks
                $.ajax({
                    type: "POST",
                    url: _settings.connect.api_url_artists,
                    data: { keyword: query, sort: "all", page: _state.currentResultsPage }
                }).done(function (data) {
                        _state.currentResultsPage++;
                        //console.dir($.parseJSON(data));
                        _radioPlayer.search.populateResults($.parseJSON(data));
                    });

                // Query SoundCloud for tracks
                /* // Removed, now using local API
                 SC.get('/tracks', {
                 q: query,
                 limit: _state.isStandalone && _state.isDefaultTrack ? _settings.searchFetchQueryCount : _settings.searchFetchLimit,
                 offset: _state.currentResultCount
                 }, _radioPlayer.search.populateResults);
                 */

            },

            // Create and populate the search result elements
            populateResults: function (results) {

                var _radioPlayer = Amplifier2013.radioPlayer,
                    _state = _radioPlayer.state,
                    $player = _radioPlayer.elements,
                    $loadingText = $player.searchLoading.find("span"),
                    $resultsHTML = "",
                    $genreOptionsHTML = $player.searchFilterGenre.html(),
                    $genresHTML,
                    timeout = 0;

                // No results are returned
                if (results === null) {

                    //console.log("getTracks: error getting tracks (null)");

                    // Results returned with errors
                } else if (results.errors) {

                    var errorMessage = "Cannot connect to API: " + results.errors[0].error_message;

                    if (_state.isDefaultTrack) {
                        // Show error message on player display
                        $player.displayTitle.text("");
                        $player.displayArtist.text(errorMessage);
                        $player.displayAlbum.text("");
                    } else {
                        // Show error message on searching/loading overlay
                        $player.searchLoading.find("span").text(errorMessage);
                    }

                    //console.log("getTracks: error getting tracks", results.errors);

                    // Results returned with success
                } else {

                    //console.log("getTracks success:", results);

                    // Create all elements before appending results
                    $.each(results, function (i, track) {

                        var copyLength = 35, // Max char length before truncation
                            title = track.track_title.length > copyLength ? $.trim(track.track_title).substring(0, copyLength).trim(this) + "..." : track.track_title,
                            artist = track.username.length > copyLength ? $.trim(track.username).substring(0, copyLength).trim(this) + "..." : track.username,
                            genre = track.track_genre === null ? "" : track.track_genre.toLowerCase().replace("\\", "");

                        // Result track element
                        $resultsHTML += '' +
                            '<li class="radioplayer-result" data-radioplayer-result-title="' + track.track_title + '" data-radioplayer-result-artist="' + track.username + '" data-radioplayer-result-permalink="' + track.bitly + '" data-radioplayer-result-genre="' + genre + '" data-radioplayer-result-points="' + track.points + '" data-radioplayer-result-id="' + track.track_id + '">' +
                            '   <a href="#" class="radioplayer-result-track" data-radioplayer-result-url="http://api.soundcloud.com/tracks/' + track.track_id + '">' +
                            '     <span class="radioplayer-result-artwork" style="' + (track.artwork_url ? "background-image: url('" + track.artwork_url + "')" : "") + '"></span>' + // !== null
                            '     <span class="radioplayer-result-title">' + title + '</span>' +
                            '     <span class="radioplayer-result-artist">' + artist + '</span>' +
                            '   </a>' +
                            '</li>';

                        // Result genre
                        if ($player.searchFilterGenre.find("option[value='" + genre + "']").length < 1
                            && $genreOptionsHTML.indexOf('value="' + genre + '"') < 0
                            && (track.track_genre !== null && track.track_genre !== "")) {
                            $genreOptionsHTML += '<option value="' + genre + '">' + track.track_genre + '</option>';
                            //$player.searchFilterGenre.append('<option value="' + track.track_genre + '">' + track.genre + '</option>');
                        }

                    });

                    // Update currentTracklist in state
                    _radioPlayer.state.currentTracklist = results;

                    // Alphabetize genres in select
                    $genresHTML = $($genreOptionsHTML).sort(function (a, b) {
                        return $(a).text() > $(b).text() ? 1 : -1;
                    });

                    // Append the result elements
                    if (_state.currentResultCount === 0) {
                        $player.searchResults.find("ul").html($resultsHTML);
                    } else {
                        $player.searchResults.find("ul").append($resultsHTML);
                    }

                    // Append the genre options
                    $player.searchFilterGenre.html($genresHTML);

                    // Update result count
                    _state.currentResultCount += results.length;

                    // All results have been returned
                    if (results.length === 0) {
                        timeout = 2000;
                        $loadingText.text($loadingText.data("radioplayer-search-found"));
                    }

                    // Either dismiss immediately or delay on screen for the user to read
                    setTimeout(function () {
                        // Fade out the loading indicator
                        $player.searchLoading.fadeOut(200);
                    }, timeout);

                    // Update the reference to all results
                    $player.searchResult = $(".radioplayer-result");

                    // Load first track into player
                    if (_radioPlayer.state.isDefaultTrack) {
                        _radioPlayer.search.results.randomizeTracks();
                    }

                    // Trigger change to only display tracks specified in the genre select
                    $player.searchFilterGenre.trigger("change");

                }

            },

            // Invoke the expanded radio player
            invoke: function (e) {

                var $player = Amplifier2013.radioPlayer.elements;

                // Expand the radio player
                $player.playerWrapper.addClass("searching");
                document.getElementById('amplifier-radioplayer').scrollIntoView(true);

            },

            // Dismiss the expanded radio player
            dismiss: function (e) {

                var $player = Amplifier2013.radioPlayer.elements;

                // Minimize the player
                $player.playerWrapper.removeClass("searching");
                $player.searchInput.val("");

            },

            // Toggle the radio player's expanded state
            toggle: function (e) {

                var _radioPlayer = Amplifier2013.radioPlayer,
                    $player = _radioPlayer.elements;

                // Toggle the player's state
                if ($player.playerWrapper.hasClass("searching")) {
                    _radioPlayer.search.dismiss();
                } else {
                    _radioPlayer.search.invoke();
                }

            },

            // On query submit
            submit: function (e) {
                e.preventDefault();
            },

            // While scrolling
            scroll: function (e) {

                var _radioPlayer = Amplifier2013.radioPlayer,
                    $player = _radioPlayer.elements,
                    $this = $(this);

                // Fire when scrolled to the bottom of the element and not currently changing genre
                if ($this.scrollTop() + $this.innerHeight() >= $this[0].scrollHeight && !_radioPlayer.state.isChangingGenre) {
                    // Show loading indicator
                    $player.searchLoading.fadeIn(200);
                    // Load more tracks in
                    _radioPlayer.search.getTracks($player.searchInput.val());
                }

                // Reset genre changing state
                _radioPlayer.state.isChangingGenre = false

            },

            // Reset scroll position
            resetScroll: function () {

                var _radioPlayer = Amplifier2013.radioPlayer,
                    $player = _radioPlayer.elements;

                // Reset scroll position
                //$player.searchResults.animate({ scrollTop: 0 }, 500);

            },

            // Search results
            results: {
                // Change current genre
                genreChange: function (e) {

                    var _radioPlayer = Amplifier2013.radioPlayer,
                        genre = $(this).val(),
                        $results = $(".radioplayer-result"),
                        $tracks;

                    _radioPlayer.state.isChangingGenre = true;

                    //_radioPlayer.search.resetScroll();
                    // Show tracks with selected genre only
                    $results.hide().filter(genre == "default-all" ? "[data-radioplayer-result-genre]" : "[data-radioplayer-result-genre='" + genre + "']").show();

                },

                // Change track sort order
                sortChange: function (e) {

                    var _radioPlayer = Amplifier2013.radioPlayer,
                        _this = _radioPlayer.search.results,
                        sort = $(this).val();

                    // Reset scroll position
                    _radioPlayer.search.resetScroll();

                    // Sort tracks based on selection
                    if (sort == "randomize") {
                        _this.randomizeTracks();
                    } else {
                        _this.sortTracks(sort);
                    }

                },

                // Sort tracks based on selection
                sortTracks: function (sort) {

                    var _radioPlayer = Amplifier2013.radioPlayer,
                        $player = _radioPlayer.elements,
                        $results = $(".radioplayer-result"),
                        $tracks;

                    // Sort tracks based on respective data attribute
                    $tracks = $results.sort(function (a, b) {
                        return $(a).data("radioplayer-result-" + sort) > $(b).data("radioplayer-result-" + sort) ? 1 : -1;
                    });

                    // Append tracls to results container
                    $player.searchResults.find("ul").html($tracks);

                },

                // Radomize the track order
                randomizeTracks: function () {

                    var _radioPlayer = Amplifier2013.radioPlayer,
                        $player = _radioPlayer.elements,
                        $results = $(".radioplayer-result"),
                        count = $results.length;

                    // Randomize the order of the tracks
                    for (var i = 0; i < count; i++) {
                        var x = Math.floor(Math.random() * count),
                            $temp = $results[i];
                        $results[i] = $results[x];
                        $results[x] = $temp;
                    }

                    // Append html to results container
                    $player.searchResults.find("ul").html($results);

                    // Recache the newly ordered track elements
                    $player.searchResult = $(".radioplayer-result");

                    // Load first track into player if initially loading
                    if (_radioPlayer.state.isDefaultTrack) {

                        // If is standalone / popped out
                        if (_radioPlayer.state.isStandalone) {
                            // Bring track to first position and reset the list
                            var $currentTrack = $player.searchResult.filter("[data-radioplayer-result-id='" + _radioPlayer.state.currentTrackID + "']");
                            $currentTrack.remove();
                            $player.searchResults.find("ul").prepend($currentTrack);
                            $player.searchResult = $(".radioplayer-result");
                        }

                        // Play first track
                        $player.searchResults.find("li:first").trigger("click");

                    }

                }
            }
        },

        // Load track
        loadTrack: function () {

            var _radioPlayer = Amplifier2013.radioPlayer,
                _settings = _radioPlayer.settings,
                $player = _radioPlayer.elements,
                $this = $(this);

            // Update state to reflect playing a track from the results
            _radioPlayer.state.playingResults = true;
            // Add the active state to the track element
            $(".radioplayer-result").removeClass("active").filter($this).addClass("active");

            // Initialize the player
            $(".radioplayer-tracks").scPlayer({ //$player.self
                links: [{
                    url: $this.find("a").data("radioplayer-result-url"),
                    title: $this.data("radioplayer-result-title")
                }],
                autoPlay: _radioPlayer.state.isStandalone ? _settings.autoPlay : (_radioPlayer.state.isDefaultTrack ? false : true),
                apiKey: _settings.connect.client_id,
                continuePlayback: _settings.continuePlayback,
                randomize: _settings.randomizeTracks,
                loadArtworks: _settings.preloadArtworks
            });

            if (_radioPlayer.state.isDefaultTrack) {
                // Set initial volume level
                $player.document.trigger({ type: 'scPlayer:onVolumeChange', volume: _radioPlayer.state.isStandalone ? 0 : 60 });
                // Set the player track state
                if (!_radioPlayer.state.isStandalone) {
                    _radioPlayer.state.isDefaultTrack = false;
                }
            }

            // Display track info
            $player.displayText.addClass("show");

        },

        // Change current track
        changeTrack: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                _state = _radioPlayer.state,
                control = $(this).data("radioplayer"),
                $track,
                $next;

            if (control == "next") {
                // Find the next track element (or first if already last track)
                $track = _state.playingResults ? $(".radioplayer-result.active").next("li") : $(".sc-trackslist li.active").next("li")
                $next = ($track.length) ? $track : (_state.playingResults ? $(".radioplayer-result:first") : $(".sc-trackslist li:first"));
                $next.trigger("click"); //$("html").hasClass("touch") ? "touchstart" :
            } else if (control == "prev") {
                // Find the previous track element (or the last track if already the first)
                $track = _state.playingResults ? $(".radioplayer-result.active").prev("li") : $(".sc-trackslist li.active").prev("li");
                $next = ($track.length) ? $track : (_state.playingResults ? $(".radioplayer-result:last") : $(".sc-trackslist li:last"));
                $next.trigger("click"); //$("html").hasClass("touch") ? "touchstart" :
            }

        },

        // When the track updates
        onTrackUpdate: function (e, track) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements;

            // If the requested track is not already playing or if the player is standalone and it is the first track
            if (track.id != _radioPlayer.state.currentTrackID || (_radioPlayer.state.isStandalone && _radioPlayer.state.isDefaultTrack)) {

                // Update state with current track info
                _radioPlayer.state.currentTrack = track;
                _radioPlayer.state.currentTrackID = track.id;

                // Fade elements out
                $player.displayItems.removeClass("show");

                setTimeout(function () {

                    // Set track title
                    $player.displayTitle.text(track.title);
                    // Set track artist / user
                    $player.displayArtist.text(track.user.username);
                    // Set track album / description
                    //$player.displayAlbum.text(track.description);

                    $player.displayAlbum.fadeIn(50);
                    // Set track duration
                    $player.displayDuration.text(_radioPlayer.utils.timecode(track.duration));
                    // Set track artwork
                    $player.displayAtwork.css({ backgroundImage: "url('" + (track.artwork_url !== null ? track.artwork_url : "/themes/grammys/img/radioplayer-artwork-default.jpg") + "')" }); // artwork_url
                    // Set track waveform
                    $player.displayWaveformImg.prop("src", track.waveform_url);
                    // Reset playhead
                    $player.displayPlayhead.css({ width: 0 });
                    // Fade elements back in
                    $player.displayItems.addClass("show");

                }, 350);

                //console.log("onPlayerTrackSwitch:", track);

            }

        },

        // When the player has initilized
        onPlayerReady: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements;

            _radioPlayer.state.playerReady = true;
            $player.playerControlPlay.addClass("sc-play");
            //console.log("onPlayerInit");

        },

        // When the player plays
        onPlay: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements;

            $player.playerControlPlay.addClass("playing");
            //console.log("onPlayerPlay", arguments);

        },

        // When the player pauses
        onPause: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements;

            $player.playerControlPlay.removeClass("playing");
            //console.log("onPlayerPause");

        },

        // When the track time updates
        onTimeUpdate: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements;

            // Update the progress bar
            $player.displayPlayhead.css({ width: (e.relative * 100) + "%" });

            // Cache current time played
            _radioPlayer.state.currentTrackTime = e.position;
            // Cache current percentage played
            _radioPlayer.state.currentTrackPosition = e.relative * 100;

            // Workaround for scrub click to play - TO DO: refactor
            if (!$player.playerControlPlay.hasClass("playing")) {
                $player.playerControlPlay.trigger("click");
            }

            // Seek to position if launching standalone player
            if (_radioPlayer.state.isDefaultTrack && _radioPlayer.state.isStandalone && e.position > 0) {

                var offsetLeft = $player.displayWaveformImg.offset().left,
                    position = (parseFloat(_radioPlayer.utils.getParamFromURL("position")) / 100) * $player.displayWaveformImg.width();

                // Seek to position based on param
                $player.displayWaveformImg.trigger(jQuery.Event("click", { pageX: offsetLeft + position, pageY: 10 }));
                // Reset volume level
                $player.document.trigger({ type: 'scPlayer:onVolumeChange', volume: 60 });
                // Reset initial track state
                _radioPlayer.state.isDefaultTrack = false;

            }

            //console.log("onMediaTimeUpdate: " + e.position + " of " + e.duration + " (" + (e.relative * 100) + "% of the total)");

        },

        // Sharing / Ampifiy functionality
        share: {
            // Share on Facebook
            facebook: function (e) {

                e.preventDefault();

                var $track = $(".radioplayer-result.active"),
                    facebookData = {
                        method: 'feed',
                        name: data.facebook.name,
                        link: data.facebook.link,
                        picture: data.facebook.picture,
                        caption: data.facebook.caption,
                        description: data.facebook.description
                    };

                // Pass Facebook share data to the API and listen for response
                FB.ui(facebookData,
                    function (response) {
                        if (response && response.post_id) {
                            // Add a play count to the track
                            addFacebookCounter(data.track_id, response.post_id);
                        }
                    }
                );

            },

            // Share on Twitter
            twitter: function (e) {

                e.preventDefault();

                var $track = $(".radioplayer-result.active");

                // Open the Twitter share URL with the track url appended
                window.open($(this).data("radioplayer-amplify-url") + $track.data("radioplayer-result-permalink"), "_blank", "location=0, menubar=0, height=500, width=400");
                // Add a play count to the track
                addTwitterCounter($track.data("radioplayer-result-id"));

            }
        },

        // Volume control handler
        volumeHandler: function () {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements,
                currentlyAdjusting = false,
                startPosition = 0,
                currentPosition = 0,
                endPosition = 60,
                maxPosition = $player.playerControlSlider.width();

            // Bind Events to document
            $player.document.on("mousedown touchstart", volumeDragStart);
            $player.document.on("mousemove touchmove", volumeDragMove);
            $player.document.on("mouseup touchend", volumeDragEnd);

            // On drag start
            function volumeDragStart(e) {
                // Only fire if volume control button is the target
                if (getEvent(e).target.className == "radioplayer-control-button" && getEvent(e).target.attributes["data-radioplayer"].nodeValue == "volume") {
                    // Get starting X position and update state
                    startPosition = getEvent(e).pageX;
                    currentlyAdjusting = true;
                    e.preventDefault();
                }
            }

            // On drag move
            function volumeDragMove(e) {
                // Only fire if volume control button is being dragged
                if (currentlyAdjusting) {
                    // Calculate offset from previous position
                    offsetPostition = (getEvent(e).pageX - startPosition) + endPosition;
                    // Ensure current position is within bounds of the slider
                    currentPosition = (offsetPostition < 0) ? 0 : ((offsetPostition > maxPosition) ? maxPosition : offsetPostition);
                    // Update the button position
                    $player.playerControlVolume.css({ left: currentPosition });
                    $player.playerControlVolumeBar.css({ right: (100 - Math.round(currentPosition / maxPosition * 100)) + "%" });
                    // Trigger volume update
                    $player.document.trigger({ type: 'scPlayer:onVolumeChange', volume: Math.round(currentPosition / maxPosition * 100) });
                    e.preventDefault();
                }
            }

            // On drag end
            function volumeDragEnd(e) {
                // Only fire if volume control button was being dragged
                if (currentlyAdjusting) {
                    // Get final X position and update state
                    endPosition = currentPosition;
                    currentlyAdjusting = false;
                }
            }

            // Return current gesture, either touch event or default
            function getEvent(e) {
                return e.originalEvent.touches !== undefined ? e.originalEvent.touches[0] : e.originalEvent;
            }

        },

        // Keyboard control handler
        keyboardHandler: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements,
                key = e.which || e.keyCode;

            // Esc key to collapse player
            if (key == 27) {
                Amplifier2013.radioPlayer.search.dismiss();
            }

            // if (key == 32 && $player.playerWrapper.hasClass("searching") && !$player.searchInput.is(":focus")) {
            //    $player.playerControlPlay.trigger("click");
            //    e.preventDefault();
            // }
        },

        // Standalone player launch
        standaloneLaunch: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements,
                windowWidth = $(window).width(),
                defaultWidth = 1150,
            // Parameters to pass in URL   - TO DO: Volume, Genre, Sort (Pass after creation?)
                playing = $player.playerControlPlay.hasClass("playing") ? 1 : 0,
                width = windowWidth < defaultWidth ? windowWidth : defaultWidth,
                track = _radioPlayer.state.currentTrackID,
                position = _radioPlayer.state.currentTrackPosition,
                query = encodeURIComponent(_radioPlayer.state.currentSearchQuery),
                count = _radioPlayer.state.currentResultCount;

            // Add class to minimize player
            //$player.playerWrapper.addClass("external");

            $player.body.attr("data-radioplayer-mode", "external");

            // Pause the player if it is playing
            if ($player.playerControlPlay.hasClass("playing")) {
                $player.playerControlPlay.trigger("click");
            }

            // Create new window and save a reference to it
            $.extend(true, Amplifier2013.radioPlayer, {
                // Open player in new window and append parameters to url
                standalonePlayerInstance:
                    window.open("/index.html?mode=standalone&track=" + track + "&position=" + position + "&query=" + query + "&count=" + count + "&playing=" + playing,
                        "_blank",
                        "location=0, menubar=0, height=360, width=" + width)
            });

            e.preventDefault();

        },

        // Standalone player return
        standaloneReturn: function (e) {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements;

            if ($player.body.data("radioplayer-mode") == "external") {
                // Close opened window and return player to default position
                _radioPlayer.standalonePlayerInstance.close()
                $player.body.attr("data-radioplayer-mode", "fixed");
            } else {
                // Close self and open player back in-page
                window.opener.Amplifier2013.radioPlayer.standaloneReturnCallback({
                    track: _radioPlayer.state.currentTrackID,
                    position: _radioPlayer.state.currentTrackPosition,
                    query: _radioPlayer.state.currentSearchQuery,
                    count: _radioPlayer.state.currentResultCount
                });
                window.self.close();
            }

        },

        // Standalone player return callback
        standaloneReturnCallback: function () {

            var _radioPlayer = Amplifier2013.radioPlayer,
                $player = _radioPlayer.elements;

            $player.body.attr("data-radioplayer-mode", "fixed");

        },

        // Utility / Helper functions
        utils: {
            // Get value of a url parameter by it's name
            getParamFromURL: function (name) {
                var regex = new RegExp("[\\?&]" + name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]") + "=([^&#]*)"),
                    results = regex.exec(window.location.search);
                // Return the value of the parameter based on it's name
                return (results === null) ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            },

            // Used to encode the track time
            timecode: function (ms) { // From soundcloud-player.js
                var tc = [],
                    hms = function (ms) {
                        return {
                            h: Math.floor(ms / (60 * 60 * 1000)),
                            m: Math.floor((ms / 60000) % 60),
                            s: Math.floor((ms / 1000) % 60)
                        };
                    } (ms); // Timecode array to be joined with '.'
                if (hms.h > 0) {
                    tc.push(hms.h);
                }
                tc.push((hms.m < 10 && hms.h > 0 ? "0" + hms.m : hms.m));
                tc.push((hms.s < 10 ? "0" + hms.s : hms.s));
                return tc.join('.');
            }
        }
    }

});

// Disable default initialization functionality
$.scPlayer.defaults.onDomReady = null

$(function () {
    // Initialize radio player
    Amplifier2013.radioPlayer.init();
});