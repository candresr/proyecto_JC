/******************* Playlist Cycle Player v1.0  ***************

Developed by: Regalado Montoya © 2014
Contact: regalado.montoya@gmail.com

****************************************************************/

$(document).ready(function(){
    var song;
    var timeleft = $('.repro-time');
    var tracker = $('.repro-bar');
    var dwd = $('.repro-download');
    dwd.hide();

    initPlaylist($('.sono-podcasts li:first-child'));

    function initPlaylist (elem) {
        $('.repro-autor').text('');
        $('.repro-titulo').text('');
        $('.repro-autor').text(elem.attr('autor'));
        $('.repro-titulo').text(elem.text());

        $('.sono-podcasts li').removeClass('repro-active');
        elem.addClass('repro-active');
        status('Cargando lista de reproduccion');
        
        var trackList = elem.attr('data-list');
        $.get(trackList, function( data ) {
            $( ".lista-temas" ).html(data);
            
            initAudio($( ".lista-temas li:first-child"));
            if (data == '' || data == 'undefined') {
                status('La lista de temas no está disponible.');
                tracker.width(0 + '%');
            }
            /*Play Track*/
            $('.lista-temas li').click(function() {
                stopAudio();
                initAudio($(this));
            });
        })
        .fail(function() {
            status('Error al cargar lista de temas. Por favor seleccione otra lista de reproducción.');
            standby();
        });
    }

    function initAudio(elem) {
        var url = elem.attr('audiourl');
        song = new Audio(url);
        song.volume = 0.8;

        timeleft.text('');

        $('.lista-temas li').removeClass('track-active');
        elem.addClass('track-active');

        song.addEventListener('timeupdate',function (){
            var rem = parseInt(song.currentTime, 10),
                pos = (song.currentTime / song.duration) * 100,
                mins = Math.floor(rem/60,10),
                secs = rem - mins*60;
            var tot = parseInt(song.duration,10),
                tmin = Math.floor(tot/60,10),
                tsecs = tot - tmin*60;
            var totaltime = tmin + ':' + (tsecs > 9 ? tsecs : '0' + tsecs);
            var curtime = mins + ':' + (secs > 9 ? secs : '0' + secs);

            if (song.buffered != undefined && (song.buffered.length != 0)) {
                timeleft.text(curtime + ' - ' + totaltime);
            }

            tracker.width(pos + '%');

            if (curtime == totaltime) {
                stopAudio();
                var next = $('.lista-temas li.track-active').next();
                if (next.length == 0) {
                    var nextPlaylist = $('.sono-podcasts li.repro-active').next();
                    initPlaylist(nextPlaylist);
                    console.log('desastre-a');
                } else {
                    initAudio(next);
                    playAudio();
                }
            }
        });

        song.addEventListener("canplay",function() {
            $(".repro-buffer").hide();
        });

        song.addEventListener("loadstart",function() {
            $(".repro-buffer").show();
            for(i=0;i<5;i++){
                $(".repro-buffer").fadeTo("fast",.5).fadeTo("fast",1);
            }
        });

        playAudio();
    }

    function playAudio() {
        song.play();
        $('.repro-play').addClass('repro-playing');
    }

    function stopAudio() {
        song.pause();
        $('.repro-play').removeClass('repro-playing');
    }

    function status(msg) {
        $( ".lista-temas" ).html('<li><span class="tema">' + msg + '</span></li>');
    }

    function standby() {
        tracker.width(0 + '%');
        timeleft.text('');
    }

    $('.repro-play').click(function (e) {
        e.preventDefault();
        if (song.paused) {
            playAudio();
        } else {
            stopAudio();
        }
    });

    $('.repro-next').click(function (e) {
        e.preventDefault();
        stopAudio();
        standby();
        
        var next = $('.lista-temas li.track-active').next();
        if (next.length == 0) {
            var nextPlaylist = $('.sono-podcasts li.repro-active').next();
            if (nextPlaylist.length != 0) {
                initPlaylist(nextPlaylist);
            } else {
                stopAudio();
                initPlaylist($('.sono-podcasts li:first-child'));
            }
        } else {
            initAudio(next);
            playAudio();
        }
    });

    $('.repro-prev').click(function (e) {
        e.preventDefault();
        stopAudio();
        standby();
        
        var prev = $('.lista-temas li.track-active').prev();
        if (prev.length != 0) {
            initAudio(prev);
            playAudio();
        } else {
            var prevPlaylist = $('.sono-podcasts li.repro-active').prev();
            if (prevPlaylist.length != 0) {
                initPlaylist(prevPlaylist);
            } else {
                initAudio($('.lista-temas li.track-active'));
            }
        }
    });

    /*Load Playlist*/
    $('.sono-podcasts li').click(function () {
        stopAudio();
        initPlaylist($(this));
    });

    /* keyboard control */
    $('body.sono-avila').keydown(function(event) {
        if (event.which == 32) {
            event.preventDefault();
            $('.repro-play').trigger('click');
        } else if(event.which == 37) {
            event.preventDefault();
            $('.repro-prev').trigger('click');
        } else if(event.which == 39) {
            event.preventDefault();
            $('.repro-next').trigger('click');
            console.log(event.which);
        }
    });
});