// global bootstrap, MMSF.date

( function( $ ) {

    $( '.neoyatai-teaser' ).each( function() {
        var dynm = $( this ).data( 'sort' ),
            cars = $( '.neoyatai-content' ).filter( '[data-sort="' + dynm + '"]' );
        $( this ).find( '.num span' ).text( cars.length );
    } );

    /**
     * neoyatai-content hover action
     */
    $( '.neoyatai-content' )
        .on( 'mouseover', function() {
            $( this ).addClass( 'focus' )
            .siblings( '.neoyatai-content' ).removeClass( 'focus' );
        } )
        .on( 'mouseout', function() {
            $( this ).removeClass( 'focus' );
        } )
        .on( 'click', '.food-thumb', function( e ) {
            e.preventDefault();
            var html = $( this ).parent().parent().html();
            console.log( html );
        } );

    /**
     * Bootstrap tabs
     */
    var $tab = $( '#weekly-tabs' ),
        expf = MMSF.date.getDayname(), // Today's dayname.
        $exp = $tab.find( '[data-sort="' + expf + '"]' ),
        $def = $tab.find( '[href="#calendar"]' );

    // Initial shown tab
    if ( $exp.length ) {
        $exp.tab( 'show' );
        $( '#week' ).find( '.neoyatai-content[data-sort="' + expf + '"]' )
            .each( function() {
                dailyInit( $( this ) );
            } );
        var ltlg = _latlng.split( ',' );
        jsonOpenWetherMap( ltlg[0], ltlg[1] );
    } else {
        $def.tab( 'show' );
        calendarInit();
    }

    // Tab's 'click' action
    $tab.find( 'a' ).on( 'click', function( e ) {
        e.preventDefault();
        $( this ).tab( 'show' );
    } );

    // Tab's 'click' action, in calendar
    $( '#calendar' ).on( 'click', '.calendar-date > a', function(e) {
        e.preventDefault();
        var sort = $( this ).data( 'sort' );
        $tab.find( '[data-sort="' + sort + '"]' ).tab( 'show' );
    });

    // On show.bs.tab event
    $( 'a[href="#week"]' ).on( 'show.bs.tab', function( e ) {
        var show = $( e.target ).data( 'sort' );
        $( '#week' ).find( '[data-sort="' + show + '"]' )
            .show()
            .filter( '.neoyatai-content' ).each( function() {
                dailyInit( $( this ) );
            } );
        var hide = $( e.relatedTarget ).data( 'sort' );
        if ( undefined !== hide )
            $( '#week' ).find( '[data-sort="' + hide + '"]' ).hide();
    } );
    $( 'a[href="#calendar"]' ).on( 'show.bs.tab', function() {
        $( '#week' ).children().hide();
        calendarInit();
    } );

    /**
     * Daily neoyatai
     */
    function dailyInit( jq ) {
        if ( jq.data( 'drawn' ) )
            return false;

        _init( jq ).done( function( jq ) {
            setTimeout( function() {
                jq.removeClass( 'waiting' );
            }, 250 );
        } );

        function _init( jq ) {

            var $this = jq,
                dfd = $.Deferred();

            var _id = $this.data( 'postid' ),
                _param = $this.data( 'sort' );

            menuContentJson( _id, 1, _param ).done( function( d ) {
                var _status = d['status'],
                    _name = d['name'];

                if ( 'Absence' === _status ) {
                    $this.addClass( 'muted' );
                    _name = 'お休みします <del>' + _name + '</del>';
                } else if ( 'Pending' === _status ) {
                    _name = _name + ' <small>(予定)</small>';
                }

                $( '<figure />', {
                    html: [
                        $( '<img />', {
                            src: d.image1['src'],
                            class: d.image1['class'],
                            'data-aspect': d.image1['aspect']
                        } ),
                        $( '<figcaption />', {
                            html: $('<h4 />', {
                                html: _name
                            } )
                        } )
                    ]
                } )
                .appendTo( $this );

                if ( 'Absence' !== _status ) {
                    var str = '';
                    $.each( d.genres, function( i, v ) {
                        str += '<span class="label label-default">' + v + '</span>';
                    } );
                    $.each( d.items, function( i, v ) {
                        str += v + ', ';
                    } );
                    str = str.slice( 0, -2 );
                    $this.children( 'figure' ).children( 'figcaption' ).append(
                        $( '<div />', {
                            html: [
                                $( '<a />', { class: 'food-thumb', href: d.image2.url,
                                    html: $('<img />', { src: d.image2['src'] } )
                                } ),
                                $( '<p />', { class: 'content-items',
                                    html: str
                                } ),
                                $( '<p />', { class: 'content-text',
                                    html: d['text']
                                } )
                            ]
                        } )
                    );
                }
                $this.data( 'drawn', 1 );
                dfd.resolve( $this );
            } );
            return dfd.promise();
        }

    }

    /**
     * Calendar
     */
    function calendarInit() {

        var $calendar = $( '#calendar' );

        if ( $calendar.data( 'done' ) )
            return false;

        var data = _NEOYATAI_CONTENTS,
            $pasts = [];
        $.each( data, function( i, v ) {
            var ymd = v['Ymd'],
                cnt = v['contents'];
            $calendar.find( '[data-date="' + ymd + '"]' ).each( function() {
                var $box = $( this );
                if ( $box.css( 'display' ) === 'none' )
                    return true;
                if ( $( this ).hasClass( 'calendar-present' ) ) {
                    _init( ymd, cnt );
                } else if ( $( this ).hasClass( 'calendar-past' ) ) {
                    $pasts.push( { ymd: ymd, cnt: cnt } );
                }
            } );
        } );
        if ( $pasts.length ) {
            $pasts.reverse();
            setTimeout( function() {
                $.each( $pasts, function( i, v ) {
                    _init( v.ymd, v.cnt );
                } );
            }, 250 );
        }
        $calendar.data( 'done', 1 );

        function _init( ymd, cnt ) {
            var $this = $( '#calendar-' + ymd );
            if ( undefined !== cnt['off'] ) {
                $this.text( 'OFF' );
            } else {
                $.each( cnt, function( i, v ) {
                    menuContentJson( v, 0, '' )
                    .done( function( d ) {
                        _html( d, $this )
                        .done( function( jq ) {
                            setTimeout( function() {
                                jq.closest( '.calendar-box' )
                                    .removeClass( 'waiting' );
                            }, 250 );
                        } );
                    } );
                } );
            }
        }

        function _html( d, jq ) {
            var dfd = $.Deferred();
            $.each( d, function( i, v ) {
                var propP = {}
                    propA = { href: v['url'], title: v['name'] };
                if ( 'Active' === v['status'] ) {
                    propA.html = v['name'];
                    propP.class = 'active-kitchencar';
                } else if ( 'Absence' === v['status'] ) {
                    propA.html = 'お休み <del>' + v['name'] + '</del>';
                    propP.class = 'absence-kitchencar';
                } else if ( 'Pending' === v['status'] ) {
                    propA.html = '<small>(予定)</small> ' + v['name'];
                    propP.class = 'pending-kitchencar';
                }
                propP.html = $( '<a />', propA );
                jq.append( $( '<p />', propP ) );
                dfd.resolve( jq );
            } );
            return dfd.promise();
        }

    }

    /**
     * Ajax function
     */
    function menuContentJson( id, content, param ) {
        var defer = $.Deferred();
        $.ajax( {
            url: _AJAX_ENDPOINT,
            type: 'POST',
            dataType: 'json',
            data: {
                action: 'json_neoyatai_menu_content',
                postid: id,
                param: param,
                content: content
            },
            success: defer.resolve
        } );
        return defer.promise();
    }

    function jsonOpenWetherMap( lat, lng ) {
        var url = '//api.openweathermap.org/data/2.5/forecast';
        $.getJSON( url, { lat: lat, lon: lng, cnt: 7 }, function( j, s ) {
            console.log( j );
            var list = j.list;
            $.each( list, function( i, v ) {
                console.log( v.dt );
                var d = new Date( v.dt * 1000 );
                console.log( d );
                console.log( d.getMonth() + 1 );
                console.log( d.getDate() );
                console.log( d.getHours() );
            } );
        } );
    }

} )( jQuery );