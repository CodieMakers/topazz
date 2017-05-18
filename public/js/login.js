/**
 * @author Lukáš
 * @version 1.0.0
 * @package Topazz
 */
$("#password-reveal").on("mousedown", function (event) {
    $("#password").attr("type", "text");
});

$("#password-reveal").on("mouseup", function (event) {
    $("#password").attr("type", "password");
});