/**
 * For handling style and response related things, mostly used as callbacks for
 * form api handling
 */
function Response(setting) {
    this.setting = $.extend({
        // defaults.
        debug: false,
        duration: 1000
    }, setting);
}

Response.prototype.bindHandler = function(context) {
    var $this = this;
    $('.close', context).on('click', function(e) {
        $this.close(context);
    });
};

Response.prototype.callbackBeforeSend = function() {
    this.triggerLogoAnimate();
};

Response.prototype.callbackSuccess = function(data) {
    this.triggerLogoAnimate();

    var $this = this;
    var response_tpl = $('#response');
    this.bindHandler(response_tpl);
    $('[data-dummy]', response_tpl).each(function(index) {
        switch($(this).data('dummy')) {
            case 'info-url':
                $(this).text(data.info.url);
                break;
            case 'info-domain':
                $(this).text(data.info.domain);
                break;
            case 'server-imap-host':
                $(this).text(data.server.imap.host);
                break;
            case 'server-imap-port':
                $(this).text(data.server.imap.port);
                break;
            case 'server-imap-socket':
                $(this).text(data.server.imap.socket);
                break;
            case 'server-smtp-host':
                $(this).text(data.server.smtp.host);
                break;
            case 'server-smtp-port':
                $(this).text(data.server.smtp.port);
                break;
            case 'server-smtp-socket':
                $(this).text(data.server.smtp.socket);
                break;
            case 'domain-required':
                $(this).text(data.domain_required);
                break;
            case 'login-name-required':
                $(this).text(data.login_name_required);
                break;
            default:
                if($this.setting.debug) {
                    console.log('Could not map response to tpl for item:' + $(this).data('dummy'));
                }
                break;
        }
    });
    this.open(response_tpl);
};

Response.prototype.callbackError = function() {
    this.triggerLogoAnimate();
};

Response.prototype.triggerLogoAnimate = function() {
    var head_logo = $('.head-logo');
    if(head_logo.is('.animate')) {
        head_logo.removeClass('animate');
    } else {
        head_logo.addClass('animate');
    }
};

Response.prototype.open = function(context) {
    $(context).css('display', 'block');
    $('.body', context).animate({opacity: '1'}, this.setting.duration);
    setTimeout(function() {
        $('.wrapper', context).addClass('darken');
    }, this.setting.duration / 2);
};

Response.prototype.close = function(context) {
    $('.body', context).animate({opacity: '0'}, this.setting.duration);
    $('.wrapper', context).removeClass('darken');
    setTimeout(function() {
        $(context).css('display', 'none');
    }, this.setting.duration);
};