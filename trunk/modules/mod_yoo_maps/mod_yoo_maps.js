var YOOmaps = new Class({
    initialize: function (container, options) {
        this.setOptions({
            lat: 53.553407,
            lng: 9.992196,
            popup: false,
            text: '',
            zoom: 13,
            mapCtrl: 2,
            zoomWhl: true,
            mapType: 0,
            typeCtrl: true,
            overviewCtrl: true,
            directions: true,
            directionsDestUpdate: false,
            locale: 'en',
            mainIcon: 'red-dot',
            otherIcon: 'blue-dot',
            iconUrl: 'http://maps.google.com/mapfiles/ms/micons/',
            msgFrommainIconAddress: 'From address: ',
            msgGetDirections: 'Get directions',
            msgEmpty: 'Please fill in your address.',
            msgNotFound: 'Sorry, address not found!',
            msgAddressNotFound: ', not found!',
            arr:'',
            customiconurl: ''
        }, options);
        if (GBrowserIsCompatible()) {
            this.container = $(container);
            this.map = new GMap2(this.container);
            this.geocoder = new GClientGeocoder();
            this.baseIcon = new GIcon();
            this.baseIcon.shadow = this.options.iconUrl + "msmarker.shadow.png";
            this.baseIcon.iconSize = new GSize(32, 32);
            this.baseIcon.shadowSize = new GSize(56, 32);
            this.baseIcon.iconAnchor = new GPoint(16, 32);
            this.baseIcon.infoWindowAnchor = new GPoint(16, 0);
            this.baseIcon.infoShadowAnchor = new GPoint(18, 25);
            this.setupMap();
            if (this.options.directions)this.setupDirections();
        }
    },
    setupMap: function () {
        this.addMarkerLatLng(this.options.lat, this.options.lng, this.options.text, true,'img_sherwood_logo.png');
        if (this.options.mapCtrl == 1) this.map.addControl(new GSmallMapControl());
        if (this.options.mapCtrl == 2) this.map.addControl(new GLargeMapControl());
        if (this.options.zoomWhl) this.map.enableScrollWheelZoom();
        if (this.options.mapType == 1) this.map.setMapType(G_SATELLITE_MAP);
        if (this.options.mapType == 2) this.map.setMapType(G_HYBRID_MAP);
        if (this.options.typeCtrl) this.map.addControl(new GMapTypeControl());
        if (this.options.overviewCtrl) this.map.addControl(new GOverviewMapControl());
        if (typeof GUnload === 'function') {
            $E('body').addEvent('unload', GUnload)
        }
    },
    createMarker: function (point, text, iconimage, flag) {
        var customIcon = new GIcon(this.baseIcon);
        if(flag == 0)
        	customIcon.image = this.options.iconUrl + iconimage + ".png";
        else
        	customIcon.image = iconimage;
        if (iconimage.match("pushpin")) customIcon.shadow = this.options.iconUrl + "pushpin_shadow.png";
        var marker = new GMarker(point, {
            'icon': customIcon
        });
        if (text || this.options.directionsDestUpdate) {
            GEvent.addListener(marker, 'click', function () {
                if (text) {
                    marker.openInfoWindowHtml(text);
                }
                if (this.options.directionsDestUpdate) {
                    this.options.lat = marker.getLatLng().lat();
                    this.options.lng = marker.getLatLng().lng();
                }
            }.bind(this))
        }
        return marker;
    },
    addMarkerLatLng: function (lat, lng, text, center, custom_icon) {
        var map = this.map;
        if(custom_icon == ''){
        	var icon = this.options.otherIcon;
        	 if (center) 
        		 icon = this.options.mainIcon;
             var point = new GLatLng(lat, lng);
             var marker = this.createMarker(point, text, icon, 0);
        }
        else{
        	var icon = this.options.customiconurl + custom_icon;
            var point = new GLatLng(lat, lng);
            var marker = this.createMarker(point, text, icon, 1);
        }
        if (center) map.setCenter(point, this.options.zoom);
        map.addOverlay(marker);
        if (center && text && this.options.popup)marker.openInfoWindowHtml(text);
    },
    sideClick: function (i){
    	var json = this.options.arr;
    	myData = JSON.parse(json);
    	text = myData.json[i].text;
    	text = decodeURIComponent(text.replace(/\+/g, '%20'));
    	var map = this.map;
    	var point = new GLatLng(myData.json[i].lat, myData.json[i].lng);
    	var icon = this.options.otherIcon;
    	var marker = this.createMarker(point, text, icon);
    	map.setCenter(point, this.options.zoom);
       	map.addOverlay(marker);
        marker.openInfoWindowHtml(text);
    },
    utf8_decode : function (utftext){
		var string = "";
		var i = 0;
		var c = c1 = c2 = 0;
		while ( i < utftext.length ) {
			c = utftext.charCodeAt(i);
			if (c < 128) {
				string += String.fromCharCode(c);
				i++;
			}
			else if((c > 191) && (c < 224)) {
				c2 = utftext.charCodeAt(i+1);
				string += String.fromCharCode(((c & 31) << 6) | (c2 & 63));
				i += 2;
			}
			else {
				c2 = utftext.charCodeAt(i+1);
				c3 = utftext.charCodeAt(i+2);
				string += String.fromCharCode(((c & 15) << 12) | ((c2 & 63) << 6) | (c3 & 63));
				i += 3;
			}
		}
		return string;
	},
    setupDirections: function () {
        var dcontainer = new Element('div', {
            'id': 'directions'
        }).injectAfter(this.container);
        this.directions = new GDirections(this.map, dcontainer);
        GEvent.addListener(this.directions, 'error', function () {
            this.showAlert(this.options.msgNotFound)
        }.bind(this));
        var dform = new Element('form', {
            'method': 'get',
            'action': '#',
            'events': {'submit': this.setDirections.bindWithEvent(this) }
        });
        var html = '<p><label for="from-address">' + this.options.msgFromAddress + '</label><input id="from-address" type="text" name="address" style="margin:0 5px;" /><button type="submit">' + this.options.msgGetDirections + '</button></p>';
        dform.setHTML(html).injectAfter(this.container);
    },
    setDirections: function (event) {
        new Event(event).stop();
        this.clearAlert();
        var from = $('from-address').getValue();
        if (from === '') {
            this.showAlert(this.options.msgEmpty);
        } 
		else {
            this.directions.load('from: ' + from + ' to: ' + this.options.lat + ',' + this.options.lng, {
                'locale': this.options.locale
            })
        }
    },
    showAlert: function (msg) {
        var div = new Element('div', {
            'class': 'alert'
        });
        var elm = new Element('strong').setText(msg).injectInside(div);
        div.injectAfter(this.container)
    },
    clearAlert: function () {
        this.container.getParent().getElements('div.alert').each(function (div) {
            div.remove();
        })
    }
});
YOOmaps.implement(new Options);