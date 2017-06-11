/**
 * @author Lukáš
 * @version 1.0.0
 * @package Topazz
 */

// --- Password reveal ---
$(".password-reveal").click(function (event) {
    var passwordInput = $("#" + $(this).data("password"));
    var icon = $(this);
    if (!icon.is('i.fa')) {
        icon = icon.children('i.fa');
    }
    if (passwordInput.attr('type') === 'text') {
        passwordInput.attr('type', 'password');
        icon.removeClass('fa-eye-slash moonstone-blue').addClass('fa-eye');
    } else {
        passwordInput.attr("type", "text");
        icon.removeClass('fa-eye').addClass('fa-eye-slash moonstone-blue');
    }
});
