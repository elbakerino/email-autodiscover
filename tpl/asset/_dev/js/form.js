/**
 * Functions for the execution of just a sending of data to the PL api
 */
function Form() {

}

Form.prototype.bindSubmit = function(sel, event) {
    console.log(sel);
    $(sel).on('submit', function(e) {
        e.preventDefault();
        event(e);
    });
};