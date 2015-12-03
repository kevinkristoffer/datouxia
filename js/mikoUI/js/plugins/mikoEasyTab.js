/**
* jQuery mikoUI 1.2.3
* 
* http://mikoui.com
*  
* Author daomi 2014 [ gd_star@163.com ] 
* 
*/
(function ($)
{
    $.fn.mikoEasyTab = function ()
    {
        return $.mikoui.run.call(this, "mikoEasyTab", arguments);
    };
    $.fn.mikoGetEasyTabManager = function ()
    {
        return $.mikoui.run.call(this, "mikoGetEasyTabManager", arguments);
    };

    $.mikoDefaults.EasyTab = {};

    $.mikoMethos.EasyTab = {};

    $.mikoui.controls.EasyTab = function (element, options)
    {
        $.mikoui.controls.EasyTab.base.constructor.call(this, element, options);
    };
    $.mikoui.controls.EasyTab.mikoExtend($.mikoui.core.UIComponent, {
        __getType: function ()
        {
            return 'EasyTab';
        },
        __idPrev: function ()
        {
            return 'EasyTab';
        },
        _extendMethods: function ()
        {
            return $.mikoMethos.EasyTab;
        },
        _render: function ()
        {
            var g = this, p = this.options;
            g.tabs = $(this.element);
            g.tabs.addClass("l-easytab");
            var selectedIndex = 0;
            if ($("> div[lselected=true]", g.tabs).length > 0)
                selectedIndex = $("> div", g.tabs).index($("> div[lselected=true]", g.tabs));
            g.tabs.ul = $('<ul class="l-easytab-header"></ul>');
            $("> div", g.tabs).each(function (i, box)
            {
                var li = $('<li><span></span></li>');
                if (i == selectedIndex)
                    $("span", li).addClass("l-selected");
                if ($(box).attr("title"))
                    $("span", li).html($(box).attr("title"));
                g.tabs.ul.append(li);
                if (!$(box).hasClass("l-easytab-panelbox")) $(box).addClass("l-easytab-panelbox");
            });
            g.tabs.ul.prependTo(g.tabs);
            //init  
            $(".l-easytab-panelbox:eq(" + selectedIndex + ")", g.tabs).show().siblings(".l-easytab-panelbox").hide();
            //add even 
            $("> ul:first span", g.tabs).click(function ()
            {
                if ($(this).hasClass("l-selected")) return;
                var i = $("> ul:first span", g.tabs).index(this);
                $(this).addClass("l-selected").parent().siblings().find("span.l-selected").removeClass("l-selected");
                $(".l-easytab-panelbox:eq(" + i + ")", g.tabs).show().siblings(".l-easytab-panelbox").hide();
            }).not("l-selected").hover(function ()
            {
                $(this).addClass("l-over");
            }, function ()
            {
                $(this).removeClass("l-over");
            });
            g.set(p);
        }
    });

})(jQuery);