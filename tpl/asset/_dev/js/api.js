/**
 * Functions for the execution of just a sending of data to the PL api
 */
function Api() {

}

Api.prototype.send = function(event) {
    console.log(event);
    console.log($(event).attr('action'));
    //console.log('href' + event.href);
    //console.log(JSON.stringify($(event).serializeArray()));
    $.ajax({
        type: 'POST',
        url: $(event).attr('action'),
        data: {
            data: JSON.stringify($(event).serializeArray())
        },
        success: function(data, textStatus, jqXHR) {
            console.log('in success');
            console.log('data: ' + data);
            console.log('textStatus: ' + textStatus);
            console.log('jqXHR: ' + jqXHR);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus);
            console.log(errorThrown);
        }
    });
};