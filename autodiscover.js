//import { autodiscover } from 'autodiscover-activesync';
var autodiscover = require("autodiscover-activesync");

autodiscover.default({
    username: 'youremail@domain.tld',
    emailAddress: 'youremail@domain.tld',
    password: 'yourpassword',
    debug: true // if you want to inspect what it is checking
});