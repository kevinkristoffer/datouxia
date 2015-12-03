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
    //气泡,可以在制定位置显示
    $.mikoTip = function (p)
    {
        return $.mikoui.run.call(null, "mikoTip", arguments);
    };

    //在指定Dom Element右侧显示气泡
    //target：将mikoui对象ID附加上
    $.fn.mikoTip = function (options)
    {
        this.each(function ()
        {
            var p = $.extend({}, $.mikoDefaults.ElementTip, options || {});
            p.target = p.target || this;
            //如果是自动模式：鼠标经过时显示，移开时关闭
            if (p.auto || options == undefined)
            {
                if (!p.content)
                {
                    p.content = this.title;
                    if (p.removeTitle)
                        $(this).removeAttr("title");
                }
                p.content = p.content || this.title;
                $(this).bind('mouseover.tip', function ()
                {
                    p.x = $(this).offset().left + $(this).width() + (p.distanceX || 0);
                    p.y = $(this).offset().top + (p.distanceY || 0);
                    $.mikoTip(p);
                }).bind('mouseout.tip', function ()
                {

                    var tipmanager = $.mikoui.managers[this.mikouitipid];
                    if (tipmanager)
                    {
                        tipmanager.remove();
                    }
                });
            }
            else
            {
                if (p.target.mikouitipid) return;
                p.x = $(this).offset().left + $(this).width() + (p.distanceX || 0);
                p.y = $(this).offset().top + (p.distanceY || 0);
                p.x = p.x || 0;
                p.y = p.y || 0;
                $.mikoTip(p);
            }
        });
        return $.mikoui.get(this, 'mikouitipid');
    };
    //关闭指定在Dom Element(附加了mikoui对象ID,属性名"mikouitipid")显示的气泡
    $.fn.mikoHideTip = function (options)
    {
        return this.each(function ()
        {
            var p = options || {};
            if (p.isLabel == undefined)
            {
                //如果是lable，将查找指定的input，并找到mikoui对象ID
                p.isLabel = this.tagName.toLowerCase() == "label" && $(this).attr("for") != null;
            }
            var target = this;
            if (p.isLabel)
            {
                var forele = $("#" + $(this).attr("for"));
                if (forele.length == 0) return;
                target = forele[0];
            }
            var tipmanager = $.mikoui.managers[target.mikouitipid];
            if (tipmanager)
            {
                tipmanager.remove();
            }
        }).unbind('mouseover.tip').unbind('mouseout.tip');
    };


    $.fn.mikoGetTipManager = function ()
    {
        return $.mikoui.get(this);
    };


    $.mikoDefaults = $.mikoDefaults || {};


    //隐藏气泡
    $.mikoDefaults.HideTip = {};

    //气泡
    $.mikoDefaults.Tip = {
        content: null,
        callback: null,
        width: 150,
        height: null,
        x: 0,
        y: 0,
        appendIdTo: null,       //保存ID到那一个对象(jQuery)(待移除)
        target: null,
        auto: null,             //是否自动模式，如果是，那么：鼠标经过时显示，移开时关闭,并且当content为空时自动读取attr[title]
        removeTitle: true        //自动模式时，默认是否移除掉title
    };

    //在指定Dom Element右侧显示气泡,通过$.fn.mikoTip调用
    $.mikoDefaults.ElementTip = {
        distanceX: 1,
        distanceY: -3,
        auto: null,
        removeTitle: true
    };

    $.mikoMethos.Tip = {};

    $.mikoui.controls.Tip = function (options)
    {
        $.mikoui.controls.Tip.base.constructor.call(this, null, options);
    };
    $.mikoui.controls.Tip.mikoExtend($.mikoui.core.UIComponent, {
        __getType: function ()
        {
            return 'Tip';
        },
        __idPrev: function ()
        {
            return 'Tip';
        },
        _extendMethods: function ()
        {
            return $.mikoMethos.Tip;
        },
        _render: function ()
        {
            var g = this, p = this.options;
            var tip = $('<div class="l-verify-tip"><div class="l-verify-tip-corner"></div><div class="l-verify-tip-content"></div></div>');
            g.tip = tip;
            g.tip.attr("id", g.id);
            if (p.content)
            {
                $("> .l-verify-tip-content:first", tip).html(p.content);
                tip.appendTo('body');
            }
            else
            {
                return;
            }
            tip.css({ left: p.x, top: p.y }).show();
            p.width && $("> .l-verify-tip-content:first", tip).width(p.width - 8);
            p.height && $("> .l-verify-tip-content:first", tip).width(p.height);
            eee = p.appendIdTo;
            if (p.appendIdTo)
            {
                p.appendIdTo.attr("mikoTipId", g.id);
            }
            if (p.target)
            {
                $(p.target).attr("mikoTipId", g.id);
                p.target.mikouitipid = g.id;
            }
            p.callback && p.callback(tip);
            g.set(p);
        },
        _setContent: function (content)
        {
            $("> .l-verify-tip-content:first", this.tip).html(content);
        },
        remove: function ()
        {
            if (this.options.appendIdTo)
            {
                this.options.appendIdTo.removeAttr("mikoTipId");
            }
            if (this.options.target)
            {
                $(this.options.target).removeAttr("mikoTipId");
                this.options.target.mikouitipid = null;
            }
            this.tip.remove();
        }
    });
})(jQuery);