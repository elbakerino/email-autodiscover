$(function() {
    var api = new Api();
    console.log('hi');

    var form = new Form();
    form.bindSubmit('.trigger-submit', api.send);
});