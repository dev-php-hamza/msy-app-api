

// $(document).ready(function(){
	// $('#subMenu').click(function(){
	// 	$(this).parent().find('ul').slideToggle('fast')
	// })

	// jQuery(function($) {
 //     var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
 //     $('ul a').each(function() {
 //      if (this.href === path) {
 //       $(this).parent().addClass('active');
 //      }
 //     });
 //    });




 var CURRENT_URL = window.location.href.split('#')[0].split('?')[0],
    $BODY = $('body'),
    $SIDEBAR_MENU = $('#sidebar-menu'),
    $NAV_MENU = $('.nav_menu'),

// Sidebar

    openUpMenu = function () {
        // $SIDEBAR_MENU.find('li').removeClass('active active-sm');
        $SIDEBAR_MENU.find('li ul').slideUp();
    }

    $SIDEBAR_MENU.find('a').on('click', function (ev) {
        var $li = $(this).parent();
        // if ($li.find('a').is('.active')) {
        //     $li.removeClass('active active-sm');
        //     $('ul:first', $li).slideUp();
        // } else {
        //     // prevent closing menu if we are on child menu
        //     if (!$li.parent().is('.child_menu')) {
        //         openUpMenu();
        //     } else {
        //         if ($BODY.is('nav-sm')) {
        //             if (!$li.parent().is('child_menu')) {
        //                 openUpMenu();
        //             }
        //         }
        //     }
            $SIDEBAR_MENU.find('a').removeClass('active');
            $SIDEBAR_MENU.find('li ul').slideUp();
            $li.children('a').addClass('active');
            $('ul:first', $li).toggle(); 
        // }
    });

    // check active menu
    $SIDEBAR_MENU.find('a[href="' + CURRENT_URL + '"]').parent('li').addClass('current-page active');

    $SIDEBAR_MENU.find('a').filter(function () {
        return this.href == CURRENT_URL;
    }).parent('li').addClass('current-page').parents('ul').slideDown().siblings().addClass('active');
// })
