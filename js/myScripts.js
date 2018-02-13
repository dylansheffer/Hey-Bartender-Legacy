/**
* This javascript file controls the application's functions within the UI layer.
*
* @author Dylan Sheffer
* @version 1.0
*/


/**
* jQuery code used to animate navbar and have it change for mobile
*/

// jQuery to collapse the navbar on scroll
function collapseNavbar() {
    if ($(".navbar").offset().top > 50) {
        $(".navbar-fixed-top").addClass("top-nav-collapse");
    } else {
        $(".navbar-fixed-top").removeClass("top-nav-collapse");
    }
}
$(window).scroll(collapseNavbar);
$(document).ready(collapseNavbar);


// jQuery for page scrolling feature - requires jQuery Easing plugin
$(function() {
    $('a.page-scroll').bind('click', function(event) {
        var $anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: $($anchor.attr('href')).offset()
                .top
        }, 1500, 'easeInOutExpo');
        event.preventDefault();
    });
});
// Closes the Responsive Menu on Menu Item Click
$('.navbar-collapse ul li a').click(function() {
    if ($(this).attr('class') != 'dropdown-toggle active' && $(this).attr(
        'class') != 'dropdown-toggle') {
        $('.navbar-toggle:visible').click();
    }
});
/** END jQuery navbar **/


/**
* Takes what is selected on the image buttons and places the value
* in the correct part of the array.
*
* @param attr The attribute the user selected
* @param type The type of attribute the user selected
*/
var attributes = [];

function addAttr(attr, type) {
        if (type == "location") {
            attributes[0] = attr;
            if (canProceed(attributes)) {
                postResults();
            }
        } else if (type == "spirit") {
            attributes[1] = attr;
            if (canProceed(attributes)) {
                postResults();
            }
        } else if (type == "style") {
            attributes[2] = attr;
            if (canProceed(attributes)) {
                postResults();
            }
        } else {
            console.log("Cannot Add Attributes");
        }
    }


/**
* The purpose was to check if all the attributes have recieved something, but javascript's length
* function still counts undefined positions as a value. It's current function is to aid in making
* sure that the results are not posted until at least style is selected.
*
* @param attributesParameter the attributes array
* @return T/F whether it can post the result or not
*/
function canProceed(attributesParameter) {
        if (attributesParameter.length == 3) {
            return true;
        } else {
            return false;
        }
    }


/**
* Sends the attributes in a string to results.php for processing via AJAX.
* Once the appropriate HTML code is generated, it is placed within the "page-top"
* div within the current HTML page. That way, it updates without a page refresh.
*/
function postResults() {
    var query = attributes.toString();
    xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("page-top").innerHTML = xmlhttp.responseText;
        }
    };
    xmlhttp.open("GET", "results.php?q=" + query, true);
    xmlhttp.send();
}