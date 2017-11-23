function Api(setting) {
    this.setting = $.extend({
        // defaults.
        debug: false,
        callbackBeforeSend: '',
        callbackSuccess: '',
        callbackError: ''
    }, setting);
}

Api.prototype.send = function(element, event) {
    var $this = this;
    $.ajax({
        type: 'POST',
        url: $(element).attr('action'),
        data: $(element).serialize(),
        beforeSend: function(jqXHR, option) {
            if($this.setting.debug) {
                console.log('in beforeSend');
            }

            if($this.setting.callbackBeforeSend) {
                $this.setting.callbackBeforeSend(jqXHR, option);
            }
        },
        success: function(data, textStatus, jqXHR) {
            if($this.setting.debug) {
                console.log('in success');
                console.log(data);
            }
            data = JSON.parse(data);
            if($this.checkResponse(data)) {
                if($this.setting.callbackSuccess) {
                    $this.setting.callbackSuccess(data);
                }
            } else {
                if($this.setting.callbackError) {
                    $this.setting.callbackError();
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            if($this.setting.debug) {
                console.log('in error');
            }
            if($this.setting.callbackError) {
                $this.setting.callbackError();
            }
        }
    });
};

Api.prototype.checkResponse = function(response) {
    return response.success;
};