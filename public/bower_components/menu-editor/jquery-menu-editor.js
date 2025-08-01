function MenuEditor(e, s) {
    var t = $("#" + e).data("level", "0"),
        l = {
            labelEdit: '<i class="fa fa-edit clickable"></i>',
            labelRemove: '<i class="fa fa-trash clickable"></i>',
            labelLock: '<i class="fa fa-lock clickable"></i>',
            labelUnlock: '<i class="fa fa-unlock clickable"></i>',
            textConfirmDelete: "Item ini akan dihapus, apakah anda yakin ?",
            textConfirmShow: "Item ini akan aktifkan, apakah anda yakin ?",
            textConfirmUnshow: "Item ini akan nonaktifkan, apakah anda yakin ?",
            iconPicker: {
                cols: 4,
                rows: 4,
                footer: !1,
                iconset: "fontawesome5"
            },
            maxLevel: -1,
            listOptions: {
                hintCss: {
                    border: "1px dashed #13981D"
                },
                opener: {
                    as: "html",
                    close: '<i class="fa fa-minus"></i>',
                    open: '<i class="fa fa-plus"></i>',
                    openerCss: {
                        "margin-right": "10px",
                        float: "none"
                    },
                    openerClass: "btn btn-success btn-xs"
                },
                placeholderCss: {
                    "background-color": "gray"
                },
                ignoreClass: "clickable",
                listsClass: "pl-0",
                listsCss: {
                    "padding-top": "10px"
                },
                complete: function (e) {
                    return MenuEditor.updateButtons(t), t.updateLevels(0), !0
                },
                isAllowed: function (e, s, t) {
                    return v(e, t)
                }
            }
        };
    $.extend(!0, l, s);
    var n = null,
        o = !0,
        i = null,
        r = null,
        a = l.iconPicker,
        c = (s = l.listOptions, $("#" + e + "_icon").iconpicker(a));

    function d() {
        i[0].reset(), (c = c.iconpicker(a)).iconpicker("setIcon", "empty"), r.attr("disabled", !0), n = null
    }

    function p(e) {
        return $("<a>").addClass(e.classCss).addClass("clickable").attr("href", "#").html(e.text)
    }

    function u(event) {

        console.log(event)

    var e = $("<div>").addClass("btn-group pull-right"),
        s = p({
            classCss: "btn btn-primary btn-xs btnEdit",
            text: l.labelEdit
        }),
        t = p({
            classCss: "btn btn-danger btn-xs btnRemove",
            text: l.labelRemove
        }),
        isShow;

        // custom
        // Check the database field value
        if (event.is_show) {
            isShow = p({
                classCss: "btn btn-warning btn-xs btnShow",
                text: l.labelUnlock
            });
        } else {
            // When isShow field is 0 (hidden)
            isShow = p({
                classCss: "btn btn-dark btn-xs btnUnshow",
                text: l.labelLock
            });
        }

        var n = p({
                classCss: "btn btn-default btn-xs btnUp btnMove",
                text: '<i class="fa fa-angle-up clickable"></i>'
            }),
            o = p({
                classCss: "btn btn-default btn-xs btnDown btnMove",
                text: '<i class="fa fa-angle-down clickable"></i>'
            }),
            i = p({
                classCss: "btn btn-default btn-xs btnOut btnMove",
                text: '<i class="fa fa-level-down clickable"></i>'
            }),
            r = p({
                classCss: "btn btn-default btn-xs btnIn btnMove",
                text: '<i class="fa fa-level-up clickable"></i>'
            });

        return e.append(n).append(o).append(r).append(i).append(s).append(isShow).append(t), e;
    }

    // bagian list
    function f(e, s) {
        var l = void 0 === s ? 0 : s,
            n = 0 === l ? t : $("<ul>").addClass("pl-0").css("padding-top", "10px").data("level", l);
        return $.each(e, (function (e, s) {
            var t = void 0 !== s.children && $.isArray(s.children),
                o = {
                    text: "",
                    href: "",
                    icon: "empty",
                    target: "_self",
                    title: ""
                },
                i = $.extend({}, s);
            t && delete i.children, $.extend(o, i);
            var r = $("<li>").addClass("list-group-item pr-0");
            r.data(o);

            // custom
            var hrefText = s.href;

            // Check if the hrefText length is more than 50 characters
            if (hrefText.length > 40) {
                hrefText = hrefText.substring(0, 40) + "..."; // Truncate and add '...'
            }
            // end custom
            
            var a = $("<div>").addClass('d-flex'),
                c = $("<i>").addClass(s.icon),
                d = $("<span>").addClass("mr-auto txt").append(s.text),
                e = $("<span>").addClass("margin-50 href").text(hrefText).attr("title", s.href),
                p = u(s); //custom
            a.append(c).append("&nbsp;").append(d).append(e).append(p), r.append(a), t && r.append(f(s.children, l + 1)), n.append(r)
        })), n
    }

    function h(e) {
        var t = $("<span>").addClass("sortableListsOpener " + s.opener.openerClass).css(s.opener.openerCss).on("mousedown touchstart", (function (e) {
            var t = $(this).closest("li");
            return t.hasClass("sortableListsClosed") ? t.iconOpen(s) : t.iconClose(s), !1
        }));
        t.prependTo(e.children("div").first()), e.hasClass("sortableListsOpen") ? e.iconOpen(s) : e.iconClose(s)
    }

    function v(e, s) {
        if (l.maxLevel < 0) return !0;
        var t = 0,
            n = e.find("ul").length;
        return t = 0 == s.length ? 0 : parseInt(s.parent().data("level")) + 1, console.log(t + n), t + n <= l.maxLevel
    }
    t.sortableLists(l.listOptions), c.on("change", (function (e) {
        i.find("[name=icon]").val(e.icon)
    })), t.on("click", ".btnRemove", (function (s) {
        if (s.preventDefault(), confirm(l.textConfirmDelete)) {
            var n = $(this).closest("ul");
            $(this).closest("li").remove();
            var o = !1;
            void 0 !== n.attr("id") && (o = n.attr("id").toString() === e), n.children().length || o || (n.prev("div").children(".sortableListsOpener").first().remove(), n.remove()), MenuEditor.updateButtons(t)
        }
    })), t.on("click", ".btnEdit", (function (e) {
        e.preventDefault(),
        function (e) {
            var s = e.data();
                $.each(s, (function (e, s) {
                    i.find("[name=" + e + "]").val(s)
                })), i.find(".item-menu").first().focus(), s.hasOwnProperty("icon") ? c.iconpicker("setIcon", s.icon) : c.iconpicker("setIcon", "empty");
                r.removeAttr("disabled")
            }(n = $(this).closest("li"))
    })),t.on("click", ".btnShow", (function (s) {
        s.preventDefault();
        if (confirm(l.textConfirmUnshow)) {
            var n = $(this).closest("li");
            var data = n.data();  
            if (data.is_show) {
                data.is_show = false;
                n.data('is_show', false);

                $(this).removeClass("btn btn-warning btnShow").addClass("btn btn-dark btnUnshow").html(l.labelLock).attr("title", "Menu tidak aktif");
            }
        }
    })), t.on("click", ".btnUnshow", (function (s) {
        s.preventDefault();
        if (confirm(l.textConfirmShow)) {
            var n = $(this).closest("li");
            var data = n.data();  
            if (data.is_show === false) {
                data.is_show = true;
                n.data('is_show', true);

                $(this).removeClass("btn btn-danger btnUnshow").addClass("btn btn-warning btnShow").html(l.labelUnlock).attr("title", "Menu aktif");
            }
        }
    })),
    t.on("click", ".btnUp", (function (e) {
        e.preventDefault();
        var s = $(this).closest("li");
        s.prev("li").before(s), MenuEditor.updateButtons(t)
    })), t.on("click", ".btnDown", (function (e) {
        e.preventDefault();
        var s = $(this).closest("li");
        s.next("li").after(s), MenuEditor.updateButtons(t)
    })), t.on("click", ".btnOut", (function (e) {
        e.preventDefault();
        var s = $(this).closest("ul"),
            l = $(this).closest("li");
        l.closest("ul").closest("li").after(l), s.children().length <= 0 && (s.prev("div").children(".sortableListsOpener").first().remove(), s.remove()), MenuEditor.updateButtons(t), t.updateLevels()
    })), t.on("click", ".btnIn", (function (e) {
        e.preventDefault();
        var s = $(this).closest("li"),
            l = s.prev("li");
        if (!v(s, l)) return !1;
        if (l.length > 0)
            if ((n = l.children("ul")).length > 0) n.append(s);
            else {
                var n = $("<ul>").addClass("pl-0").css("padding-top", "10px");
                l.append(n), n.append(s), l.addClass("sortableListsOpen"), h(l)
            } MenuEditor.updateButtons(t), t.updateLevels()
    })), this.setForm = function (e) {
        i = e
    }, this.getForm = function () {
        return i
    }, this.setUpdateButton = function (e) {
        (r = e).attr("disabled", !0), n = null
    }, this.getUpdateButton = function () {
        return r
    }, this.getCurrentItem = function () {
        return n
    }, this.update = function () {
        var e = this.getCurrentItem();
        if (null !== e) {
            var s = e.data("icon");
            i.find(".item-menu").each((function () {
                e.data($(this).attr("name"), $(this).val())
            })), e.children().children("i").removeClass(s).addClass(e.data("icon")), e.find("span.txt").first().text(e.data("text")), e.find("span.href").first().text(e.data("href")), d()
        }
    }, this.add = function (source) {
        var e = {};
        i.find(".item-menu").each((function () {
            e[$(this).attr("name")] = $(this).val()
        }));

        // mendaftarkan field baru
        e.type = source;


        // custom
        var hrefText = e.href;

        // Check if the hrefText length is more than 50 characters
        if (hrefText.length > 40) {
            hrefText = hrefText.substring(0, 40) + "..."; // Truncate and add '...'
        }
        // end custom

        var s = u(e), //custom
            l = $("<span>").addClass("mr-auto txt").text(e.text),
            m = $("<span>").addClass("margin-50 href").text(hrefText).attr("title", s.href),
            n = $("<i>").addClass("pt-1").addClass(e.icon),
            o = $("<div>").addClass('d-flex').append(n).append("&nbsp;").append(l).append(m).append(s),
            r = $("<li>").data(e);
        r.addClass("list-group-item pr-0").append(o), t.append(r), MenuEditor.updateButtons(t), d()
    }, this.getString = function () {
        var e = t.sortableListsToJson();
        return JSON.stringify(e)
    }, this.setData = function (e) {
        var s = Array.isArray(e) ? e : function (e) {
            try {
                var s = JSON.parse(e)
            } catch (e) {
                return console.log("The string is not a json valid."), null
            }
            return s
        }(e);
        if (null !== s) {
            t.empty();
            var n = f(s);
            o ? t.find("li").each((function () {
                var e = $(this);
                e.children("ul").length && h(e)
            })) : (n.sortableLists(l.listOptions), o = !0), MenuEditor.updateButtons(t)
        }
    }
}! function (e) {
    /**
     * @desc jQuery plugin to sort html list also the tree structures
     * @version 1.4.0
     * @author VladimĆ­r Äamaj
     * @license MIT
     * @desc jQuery plugin
     * @param options
     * @returns this to unsure chaining
     */
    e.fn.sortableLists = function (s) {
        var t = e("body").css("position", "relative"),
            l = {
                currElClass: "",
                placeholderClass: "",
                placeholderCss: {
                    position: "relative",
                    padding: 0
                },
                hintClass: "",
                hintCss: {
                    display: "none",
                    position: "relative",
                    padding: 0
                },
                hintWrapperClass: "",
                hintWrapperCss: {},
                baseClass: "",
                baseCss: {
                    position: "absolute",
                    top: 0 - parseInt(t.css("margin-top")),
                    left: 0 - parseInt(t.css("margin-left")),
                    margin: 0,
                    padding: 0,
                    "z-index": 2500
                },
                opener: {
                    active: !1,
                    open: "",
                    close: "",
                    openerCss: {
                        float: "left",
                        display: "inline-block",
                        "background-position": "center center",
                        "background-repeat": "no-repeat"
                    },
                    openerClass: ""
                },
                listSelector: "ul",
                listsClass: "",
                listsCss: {},
                insertZone: 50,
                insertZonePlus: !1,
                scroll: 20,
                ignoreClass: "",
                isAllowed: function (e, s, t) {
                    return !0
                },
                onDragStart: function (e, s) {
                    return !0
                },
                onChange: function (e) {
                    return !0
                },
                complete: function (e) {
                    return !0
                }
            },
            n = e.extend(!0, {}, l, s),
            o = e("<" + n.listSelector + " />").prependTo(t).attr("id", "sortableListsBase").css(n.baseCss).addClass(n.listsClass + " " + n.baseClass),
            i = e("<li />").attr("id", "sortableListsPlaceholder").css(n.placeholderCss).addClass(n.placeholderClass),
            r = e("<li />").attr("id", "sortableListsHint").css(n.hintCss).addClass(n.hintClass),
            a = e("<" + n.listSelector + " />").attr("id", "sortableListsHintWrapper").addClass(n.listsClass + " " + n.hintWrapperClass).css(n.listsCss).css(n.hintWrapperCss),
            c = e("<span />").addClass("sortableListsOpener " + n.opener.openerClass).css(n.opener.openerCss).on("mousedown touchstart", (function (s) {
                var t = e(this).closest("li");
                return t.hasClass("sortableListsClosed") ? L(t) : E(t), !1
            }));
        "class" == n.opener.as ? c.addClass(n.opener.close) : "html" == n.opener.as ? c.html(n.opener.close) : console.error("Invalid setting for opener.as");
        var d = {
            isDragged: !1,
            isRelEFP: null,
            oEl: null,
            rootEl: null,
            cEl: null,
            upScroll: !1,
            downScroll: !1,
            pX: 0,
            pY: 0,
            cX: 0,
            cY: 0,
            isAllowed: !0,
            e: {
                pageX: 0,
                pageY: 0,
                clientX: 0,
                clientY: 0
            },
            doc: e(document),
            win: e(window)
        };
        if (n.opener.active) {
            if (!n.opener.open) throw "Opener.open value is not defined. It should be valid url, html or css class.";
            if (!n.opener.close) throw "Opener.close value is not defined. It should be valid url, html or css class.";
            e(this).find("li").each((function () {
                var s = e(this);
                s.children(n.listSelector).length && (c.clone(!0).prependTo(s.children("div").first()), s.hasClass("sortableListsOpen") ? L(s) : E(s))
            }))
        }
        return this.on("mousedown touchstart", (function (s) {
            var t = e(s.target);
            if (!(!1 !== d.isDragged || n.ignoreClass && t.hasClass(n.ignoreClass))) {
                s.preventDefault(), "touchstart" === s.type && v(s);
                var l = t.closest("li"),
                    a = e(this);
                l[0] && (n.onDragStart(s, l), function (s, t, l) {
                    d.isDragged = !0;
                    var a = parseInt(t.css("margin-top")),
                        c = parseInt(t.css("margin-bottom")),
                        f = parseInt(t.css("margin-left")),
                        h = parseInt(t.css("margin-right")),
                        v = t.offset(),
                        b = t.innerHeight();
                    d.rootEl = {
                        el: l,
                        offset: l.offset(),
                        rootElClass: l.attr("class")
                    }, d.cEl = {
                        el: t,
                        mT: a,
                        mL: f,
                        mB: c,
                        mR: h,
                        offset: v
                    }, d.cEl.xyOffsetDiff = {
                        X: s.pageX - d.cEl.offset.left,
                        Y: s.pageY - d.cEl.offset.top
                    }, d.cEl.el.addClass("sortableListsCurrent " + n.currElClass), t.before(i);
                    var g = d.placeholderNode = e("#sortableListsPlaceholder");
                    t.css({
                        width: t.width(),
                        position: "absolute",
                        top: v.top - a,
                        left: v.left - f
                    }).prependTo(o), g.css({
                        display: "block",
                        height: b
                    }), r.css("height", b), d.doc.on("mousemove touchmove", p).on("mouseup touchend touchcancel", u)
                }(s, l, a))
            }
        }));

        function p(s) {
            if (d.isDragged) {
                var t = d.cEl,
                    l = d.doc,
                    o = d.win;
                "touchmove" === s.type && v(s), s.pageX || function (e) {
                        e.pageY = d.pY, e.pageX = d.pX, e.clientY = d.cY, e.clientX = d.cX
                    }(s), l.scrollTop() > d.rootEl.offset.top - 10 && s.clientY < 50 ? d.upScroll ? (s.pageY = s.pageY - n.scroll, e("html, body").each((function (s) {
                        e(this).scrollTop(e(this).scrollTop() - n.scroll)
                    })), f(s)) : function (e) {
                        if (d.upScroll) return;
                        d.upScroll = setInterval((function () {
                            d.doc.trigger("mousemove")
                        }), 50)
                    }() : l.scrollTop() + o.height() < d.rootEl.offset.top + d.rootEl.el.outerHeight(!1) + 10 && o.height() - s.clientY < 50 ? d.downScroll ? (s.pageY = s.pageY + n.scroll, e("html, body").each((function (s) {
                        e(this).scrollTop(e(this).scrollTop() + n.scroll)
                    })), f(s)) : function (e) {
                        if (d.downScroll) return;
                        d.downScroll = setInterval((function () {
                            d.doc.trigger("mousemove")
                        }), 50)
                    }() : h(d), d.oElOld = d.oEl, t.el[0].style.visibility = "hidden", d.oEl = oEl = function (s, t) {
                        if (!document.elementFromPoint) return null;
                        var l = d.isRelEFP;
                        if (null === l) {
                            var n, o;
                            (n = d.doc.scrollTop()) > 0 && (l = null == (o = document.elementFromPoint(0, n + e(window).height() - 1)) || "HTML" == o.tagName.toUpperCase()), (n = d.doc.scrollLeft()) > 0 && (l = null == (o = document.elementFromPoint(n + e(window).width() - 1, 0)) || "HTML" == o.tagName.toUpperCase())
                        }
                        l && (s -= d.doc.scrollLeft(), t -= d.doc.scrollTop());
                        var i = e(document.elementFromPoint(s, t));
                        if (!d.rootEl.el.find(i).length) return null;
                        if (i.is("#sortableListsPlaceholder") || i.is("#sortableListsHint")) return null;
                        if (!i.is("li")) return (i = i.closest("li"))[0] ? i : null;
                        if (i.is("li")) return i
                    }(s.pageX, s.pageY), t.el[0].style.visibility = "visible",
                    function (e, s) {
                        var t = s.oEl;
                        if (!t || !s.oElOld) return;
                        var l = t.outerHeight(!1),
                            o = e.pageY - t.offset().top;
                        n.insertZonePlus ? 14 > o ? g(e, t, 7 > o) : l - 14 < o && C(e, t, l - 7 < o) : 5 > o ? b(e, t) : l - 5 < o && m(e, t)
                    }(s, d),
                    function (e, s) {
                        var t = s.cEl;
                        t.el.css({
                            top: e.pageY - t.xyOffsetDiff.Y - t.mT,
                            left: e.pageX - t.xyOffsetDiff.X - t.mL
                        })
                    }(s, d)
            }
        }

        function u(s) {
            var t = d.cEl,
                l = e("#sortableListsHint", d.rootEl.el),
                o = r[0].style,
                i = null,
                a = !1,
                f = e("#sortableListsHintWrapper");
            "touchend" !== s.type && "touchcancel" !== s.type || v(s), "block" == o.display && l.length && d.isAllowed ? (i = l, a = !0) : (i = d.placeholderNode, a = !1), offset = i.offset(), t.el.animate({
                left: offset.left - d.cEl.mL,
                top: offset.top - d.cEl.mT
            }, 250, (function () {
                ! function (e) {
                    var s = e.el[0].style;
                    e.el.removeClass(n.currElClass + " sortableListsCurrent"), s.top = "0", s.left = "0", s.position = "relative", s.width = "auto"
                }(t), i.after(t.el[0]), i[0].style.display = "none", o.display = "none", l.remove(), f.removeAttr("id").removeClass(n.hintWrapperClass), f.length && f.prev("div").prepend(c.clone(!0)), a ? d.placeholderNode.slideUp(150, (function () {
                    d.placeholderNode.remove(), y(), n.onChange(t.el), n.complete(t.el), d.isDragged = !1
                })) : (d.placeholderNode.remove(), y(), n.complete(t.el), d.isDragged = !1)
            })), h(d), d.doc.unbind("mousemove touchmove", p).unbind("mouseup touchend touchcancel", u)
        }

        function f(e) {
            d.pY = e.pageY, d.pX = e.pageX, d.cY = e.clientY, d.cX = e.clientX
        }

        function h(e) {
            clearInterval(e.upScroll), clearInterval(e.downScroll), e.upScroll = e.downScroll = !1
        }

        function v(e) {
            e.pageX = e.originalEvent.changedTouches[0].pageX, e.pageY = e.originalEvent.changedTouches[0].pageY, e.screenX = e.originalEvent.changedTouches[0].screenX, e.screenY = e.originalEvent.changedTouches[0].screenY
        }

        function b(s, t) {
            if (e("#sortableListsHintWrapper", d.rootEl.el).length && r.unwrap(), s.pageX - t.offset().left < n.insertZone) {
                if (t.prev("#sortableListsPlaceholder").length) return void r.css("display", "none");
                t.before(r)
            } else {
                var l = t.children(),
                    o = t.children(n.listSelector).first();
                if (o.children().first().is("#sortableListsPlaceholder")) return void r.css("display", "none");
                o.length ? o.prepend(r) : (l.first().after(r), r.wrap(a)), d.oEl && L(t)
            }
            r.css("display", "block"), d.isAllowed = n.isAllowed(d.cEl.el, r, r.parents("li").first())
        }

        function g(s, t, l) {
            if (e("#sortableListsHintWrapper", d.rootEl.el).length && r.unwrap(), !l && s.pageX - t.offset().left > n.insertZone) {
                var o = t.children(),
                    i = t.children(n.listSelector).first();
                if (i.children().first().is("#sortableListsPlaceholder")) return void r.css("display", "none");
                i.length ? i.prepend(r) : (o.first().after(r), r.wrap(a)), d.oEl && L(t)
            } else {
                if (t.prev("#sortableListsPlaceholder").length) return void r.css("display", "none");
                t.before(r)
            }
            r.css("display", "block"), d.isAllowed = n.isAllowed(d.cEl.el, r, r.parents("li").first())
        }

        function m(s, t) {
            if (e("#sortableListsHintWrapper", d.rootEl.el).length && r.unwrap(), s.pageX - t.offset().left < n.insertZone) {
                if (t.next("#sortableListsPlaceholder").length) return void r.css("display", "none");
                t.after(r)
            } else {
                var l = t.children(),
                    o = t.children(n.listSelector).last();
                if (o.children().last().is("#sortableListsPlaceholder")) return void r.css("display", "none");
                o.length ? l.last().append(r) : (t.append(r), r.wrap(a)), d.oEl && L(t)
            }
            r.css("display", "block"), d.isAllowed = n.isAllowed(d.cEl.el, r, r.parents("li").first())
        }

        function C(s, t, l) {
            if (e("#sortableListsHintWrapper", d.rootEl.el).length && r.unwrap(), !l && s.pageX - t.offset().left > n.insertZone) {
                var o = t.children(),
                    i = t.children(n.listSelector).last();
                if (i.children().last().is("#sortableListsPlaceholder")) return void r.css("display", "none");
                i.length ? o.last().append(r) : (t.append(r), r.wrap(a)), d.oEl && L(t)
            } else {
                if (t.next("#sortableListsPlaceholder").length) return void r.css("display", "none");
                t.after(r)
            }
            r.css("display", "block"), d.isAllowed = n.isAllowed(d.cEl.el, r, r.parents("li").first())
        }

        function L(e) {
            e.removeClass("sortableListsClosed").addClass("sortableListsOpen"), e.children(n.listSelector).css("display", "block");
            var s = e.children("div").children(".sortableListsOpener").first();
            "html" == n.opener.as ? s.html(n.opener.close) : "class" == n.opener.as ? s.addClass(n.opener.close).removeClass(n.opener.open) : s.css("background-image", "url(" + n.opener.close + ")")
        }

        function E(e) {
            e.removeClass("sortableListsOpen").addClass("sortableListsClosed"), e.children(n.listSelector).css("display", "none");
            var s = e.children("div").children(".sortableListsOpener").first();
            "html" == n.opener.as ? s.html(n.opener.open) : "class" == n.opener.as ? s.addClass(n.opener.open).removeClass(n.opener.close) : s.css("background-image", "url(" + n.opener.open + ")")
        }

        function y() {
            e(n.listSelector, d.rootEl.el).each((function (s) {
                e(this).children().length || (e(this).prev("div").children(".sortableListsOpener").first().remove(), e(this).remove())
            }))
        }
    }, e.fn.iconOpen = function (e) {
        this.removeClass("sortableListsClosed").addClass("sortableListsOpen"), this.children("ul").css("display", "block");
        var s = this.children("div").children(".sortableListsOpener").first();
        "html" === e.opener.as ? s.html(e.opener.close) : "class" === e.opener.as && s.addClass(e.opener.close).removeClass(e.opener.open)
    }, e.fn.iconClose = function (e) {
        this.removeClass("sortableListsOpen").addClass("sortableListsClosed"), this.children("ul").css("display", "none");
        var s = this.children("div").children(".sortableListsOpener").first();
        "html" === e.opener.as ? s.html(e.opener.open) : "class" === e.opener.as && s.addClass(e.opener.open).removeClass(e.opener.close)
    }, e.fn.sortableListsToJson = function () {
        var s = [];
        return e(this).children("li").each((function () {
            var t = e(this),
                l = t.data();
            s.push(l);
            var n = t.children("ul,ol").sortableListsToJson();
            n.length > 0 ? l.children = n : delete l.children
        })), s
    }, e.fn.updateLevels = function (s) {
        var t = void 0 === s ? 0 : s;
        e(this).children("li").each((function () {
            var s = e(this).children("ul");
            s.length > 0 && (s.data("level", t + 1), s.updateLevels(t + 1))
        }))
    }, e.fn.updateButtons = function (s) {
        var t = void 0 === s ? 0 : s,
            l = ["Up", "In"],
            n = ["Down"];
        0 === t && (l.push("Out"), n.push("Out"), e(this).children("li").hideButtons(["Out"])), e(this).children("li").each((function () {
            var s = e(this).children("ul");
            s.length > 0 && s.updateButtons(t + 1)
        })), e(this).children("li:first").hideButtons(l), e(this).children("li:last").hideButtons(n)
    }, e.fn.hideButtons = function (s) {
        for (var t = 0; t < s.length; t++) e(this).find(".btn-group:first").children(".btn" + s[t]).hide()
    }
}(jQuery), MenuEditor.updateButtons = function (e) {
    e.find(".btnMove").show(), e.updateButtons()
};
