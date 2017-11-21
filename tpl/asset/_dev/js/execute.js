/**
 * Combines the classes and triggers the execution
 */
function bindApiFormHandler() {
    var form_response = new Response({debug: false});

    var form_api = new Api({
        // defaults.
        debug: false,
        callbackBeforeSend: form_response.callbackBeforeSend.bind(form_response),
        callbackSuccess: form_response.callbackSuccess.bind(form_response),
        callbackError: form_response.callbackError.bind(form_response)
    });

    var form = new Form();
    form.bindSubmit('.trigger-submit', form_api.send.bind(form_api));
}

$(function() {
    bindApiFormHandler();
});