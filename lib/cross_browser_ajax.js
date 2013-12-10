
/* ================================================================ */
/*                                                                  */
/*                     CROSS BROWSER AJAX                           */
/*           Enables IE8 & IE9 ajax calls using jQuery              */
/*                   bryan@fiftyandifty.org                         */
/*                                                                  */
/* ================================================================ */

jQuery(document).ready(function($){

    "use strict";

    window.FF_AJAX = {};
    var FF = window.FF_AJAX;

    FF.isIE8orIE9 = function() {
        return !!( ( (/msie 8./i).test(navigator.appVersion) || (/msie 9./i).test(navigator.appVersion)  ) && !(/opera/i).test(navigator.userAgent) && window.ActiveXObject && XDomainRequest && !window.msPerformance );
    };

    FF.make_ajax_request = function(url, data, success_function, error_function, request_type){
        if( data === undefined ){ return false;}
        if( request_type === undefined ){ request_type = 'POST'; }
        var result;
        if ( FF.isIE8orIE9() ) {
            var xdr = new XDomainRequest();
            xdr.open(request_type, url);
            xdr.onerror = function() {
                FF.display_errors(response.error);
            }
            xdr.ontimeout = function() { /* only needed for IE9 support */ }
            xdr.onprogress = function() { /* only needed for IE9 support */ }
            xdr.onload = function() {
                var dom = new ActiveXObject("Microsoft.XMLDOM");
                dom.async = false;
                dom.loadXML(xdr.responseText);
                var response = JSON.parse(dom.parseError.srcText);
                if(response.success){ FF.handle_response(xdr.responseText, success_function); }
                else{ FF.handle_response(xdr.responseText, error_function, true); }
            };
            xdr.send(jQuery.param(data));
        }
        else {
            jQuery.ajax({
                'type'       : request_type,
                'url'        : url,
                'data'       : data,
                'async'      : false,
                'success'    : function(response) { FF.handle_response(response, success_function); },
                'error'      : function(response) { FF.handle_response(response, error_function, true); }
            })
        }
    };

    FF.display_errors = function(message){
        console.log('ajax error', message);
        alert('There was a network error.\n\nPlease try again.');
    };

    FF.handle_response = function(response, result_function, error){
        if(result_function === undefined){result_function = null;}
        if(error === undefined){error = false;}
        try{
            var r = JSON.parse(response);
        }
        catch(e){
            var r = response;
        }
        if(error){
            if( result_function ){ eval( result_function ); }
            else{ FF.display_errors(response); }
        }
        else{
            if( result_function ){ eval( result_function ); }
        }
    };

});
