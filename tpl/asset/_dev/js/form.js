/**
 * Just a stub for binding the needed submit handler
 */
function Form() {

}

Form.prototype.bindSubmit = function(sel, event) {
    $(sel).on('submit', function(e) {
        e.preventDefault();
        event(this, e);
    });
};