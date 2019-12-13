/**
 * Created by MAT006 on 18-08-2017.
 */
(function(root, factory) {
    "use strict";
    if (typeof module !== "undefined" && module.exports) {
        module.exports = factory(require("jquery"))
    } else if (typeof define === "function" && define.amd) {
        define(["jquery"], function($) {
            return factory($)
        })
    } else {
        factory(root.jQuery)
    }
})(this, function($) {
    "use strict";
    var Typeahead = function(element, options) {
        this.$element = $(element);
        this.options = $.extend({}, $.fn.typeahead.defaults, options);
        this.matcher = this.options.matcher || this.matcher;
        this.sorter = this.options.sorter || this.sorter;
        this.select = this.options.select || this.select;
        this.autoSelect = typeof this.options.autoSelect == "boolean" ? this.options.autoSelect : true;
        this.highlighter = this.options.highlighter || this.highlighter;
        this.render = this.options.render || this.render;
        this.updater = this.options.updater || this.updater;
        this.displayText = this.options.displayText || this.displayText;
        this.source = this.options.source;
        this.delay = this.options.delay;
        this.$menu = $(this.options.menu);
        this.$appendTo = this.options.appendTo ? $(this.options.appendTo) : null;
        this.fitToElement = typeof this.options.fitToElement == "boolean" ? this.options.fitToElement : false;
        this.shown = false;
        this.listen();
        this.showHintOnFocus = typeof this.options.showHintOnFocus == "boolean" || this.options.showHintOnFocus === "all" ? this.options.showHintOnFocus : false;
        this.afterSelect = this.options.afterSelect;
        this.addItem = false;
        this.value = this.$element.val() || this.$element.text()
    };
    Typeahead.prototype = {
        constructor: Typeahead,
        select: function() {
            var val = this.$menu.find(".active").data("value");
            this.$element.data("active", val);
            if (this.autoSelect || val) {
                var newVal = this.updater(val);
                if (!newVal) {
                    newVal = ""
                }
                this.$element.val(this.displayText(newVal) || newVal).text(this.displayText(newVal) || newVal).change();
                this.afterSelect(newVal)
            }
            return this.hide()
        },
        updater: function(item) {
            return item
        },
        setSource: function(source) {
            this.source = source
        },
        show: function() {
            var pos = $.extend({}, this.$element.position(), {
                height: this.$element[0].offsetHeight
            });
            var scrollHeight = typeof this.options.scrollHeight == "function" ? this.options.scrollHeight.call() : this.options.scrollHeight;
            var element;
            if (this.shown) {
                element = this.$menu
            } else if (this.$appendTo) {
                element = this.$menu.appendTo(this.$appendTo);
                this.hasSameParent = this.$appendTo.is(this.$element.parent())
            } else {
                element = this.$menu.insertAfter(this.$element);
                this.hasSameParent = true
            }
            if (!this.hasSameParent) {
                element.css("position", "fixed");
                var offset = this.$element.offset();
                pos.top = offset.top;
                pos.left = offset.left
            }
            var dropup = $(element).parent().hasClass("dropup");
            var newTop = dropup ? "auto" : pos.top + pos.height + scrollHeight;
            var right = $(element).hasClass("dropdown-menu-right");
            var newLeft = right ? "auto" : pos.left;
            element.css({
                top: newTop,
                left: newLeft
            }).show();
            if (this.options.fitToElement === true) {
                element.css("width", this.$element.outerWidth() + "px")
            }
            this.shown = true;
            return this
        },
        hide: function() {
            this.$menu.hide();
            this.shown = false;
            return this
        },
        lookup: function(query) {
            var items;
            if (typeof query != "undefined" && query !== null) {
                this.query = query
            } else {
                this.query = this.$element.val() || this.$element.text() || ""
            }
            if (this.query.length < this.options.minLength && !this.options.showHintOnFocus) {
                return this.shown ? this.hide() : this
            }
            var worker = $.proxy(function() {
                if ($.isFunction(this.source)) {
                    this.source(this.query, $.proxy(this.process, this))
                } else if (this.source) {
                    this.process(this.source)
                }
            }, this);
            clearTimeout(this.lookupWorker);
            this.lookupWorker = setTimeout(worker, this.delay)
        },
        process: function(items) {
            var that = this;
            items = $.grep(items, function(item) {
                return that.matcher(item)
            });
            items = this.sorter(items);
            if (!items.length && !this.options.addItem) {
                return this.shown ? this.hide() : this
            }
            if (items.length > 0) {
                this.$element.data("active", items[0])
            } else {
                this.$element.data("active", null)
            }
            if (this.options.addItem) {
                items.push(this.options.addItem)
            }
            if (this.options.items == "all") {
                return this.render(items).show()
            } else {
                return this.render(items.slice(0, this.options.items)).show()
            }
        },
        matcher: function(item) {
            var it = this.displayText(item);
            return ~it.toLowerCase().indexOf(this.query.toLowerCase())
        },
        sorter: function(items) {
            var beginswith = [];
            var caseSensitive = [];
            var caseInsensitive = [];
            var item;
            while (item = items.shift()) {
                var it = this.displayText(item);
                if (!it.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(item);
                else if (~it.indexOf(this.query)) caseSensitive.push(item);
                else caseInsensitive.push(item)
            }
            return beginswith.concat(caseSensitive, caseInsensitive)
        },
        highlighter: function(item) {
            var html = $("<div></div>");
            var query = this.query;
            var i = item.toLowerCase().indexOf(query.toLowerCase());
            var len = query.length;
            var leftPart;
            var middlePart;
            var rightPart;
            var strong;
            if (len === 0) {
                return html.text(item).html()
            }
            while (i > -1) {
                leftPart = item.substr(0, i);
                middlePart = item.substr(i, len);
                rightPart = item.substr(i + len);
                strong = $("<strong></strong>").text(middlePart);
                html.append(document.createTextNode(leftPart)).append(strong);
                item = rightPart;
                i = item.toLowerCase().indexOf(query.toLowerCase())
            }
            return html.append(document.createTextNode(item)).html()
        },
        render: function(items) {
            var that = this;
            var self = this;
            var activeFound = false;
            var data = [];
            var _category = that.options.separator;
            $.each(items, function(key, value) {
                if (key > 0 && value[_category] !== items[key - 1][_category]) {
                    data.push({
                        __type: "divider"
                    })
                }
                if (value[_category] && (key === 0 || value[_category] !== items[key - 1][_category])) {
                    data.push({
                        __type: "category",
                        name: value[_category]
                    })
                }
                data.push(value)
            });
            items = $(data).map(function(i, item) {
                if ((item.__type || false) == "category") {
                    return $(that.options.headerHtml).text(item.name)[0]
                }
                if ((item.__type || false) == "divider") {
                    return $(that.options.headerDivider)[0]
                }
                var text = self.displayText(item);
                i = $(that.options.item).data("value", item);
                i.find("a").html(that.highlighter(text, item));
                if (text == self.$element.val()) {
                    i.addClass("active");
                    self.$element.data("active", item);
                    activeFound = true
                }
                return i[0]
            });
            if (this.autoSelect && !activeFound) {
                items.filter(":not(.dropdown-header)").first().addClass("active");
                this.$element.data("active", items.first().data("value"))
            }
            this.$menu.html(items);
            return this
        },
        displayText: function(item) {
            return typeof item !== "undefined" && typeof item.name != "undefined" && item.name || item
        },
        next: function(event) {
            var active = this.$menu.find(".active").removeClass("active");
            var next = active.next();
            if (!next.length) {
                next = $(this.$menu.find("li")[0])
            }
            next.addClass("active")
        },
        prev: function(event) {
            var active = this.$menu.find(".active").removeClass("active");
            var prev = active.prev();
            if (!prev.length) {
                prev = this.$menu.find("li").last()
            }
            prev.addClass("active")
        },
        listen: function() {
            this.$element.on("focus", $.proxy(this.focus, this)).on("blur", $.proxy(this.blur, this)).on("keypress", $.proxy(this.keypress, this)).on("input", $.proxy(this.input, this)).on("keyup", $.proxy(this.keyup, this));
            if (this.eventSupported("keydown")) {
                this.$element.on("keydown", $.proxy(this.keydown, this))
            }
            this.$menu.on("click", $.proxy(this.click, this)).on("mouseenter", "li", $.proxy(this.mouseenter, this)).on("mouseleave", "li", $.proxy(this.mouseleave, this)).on("mousedown", $.proxy(this.mousedown, this))
        },
        destroy: function() {
            this.$element.data("typeahead", null);
            this.$element.data("active", null);
            this.$element.off("focus").off("blur").off("keypress").off("input").off("keyup");
            if (this.eventSupported("keydown")) {
                this.$element.off("keydown")
            }
            this.$menu.remove();
            this.destroyed = true
        },
        eventSupported: function(eventName) {
            var isSupported = eventName in this.$element;
            if (!isSupported) {
                this.$element.setAttribute(eventName, "return;");
                isSupported = typeof this.$element[eventName] === "function"
            }
            return isSupported
        },
        move: function(e) {
            if (!this.shown) return;
            switch (e.keyCode) {
                case 9:
                case 13:
                case 27:
                    e.preventDefault();
                    break;
                case 38:
                    if (e.shiftKey) return;
                    e.preventDefault();
                    this.prev();
                    break;
                case 40:
                    if (e.shiftKey) return;
                    e.preventDefault();
                    this.next();
                    break
            }
        },
        keydown: function(e) {
            this.suppressKeyPressRepeat = ~$.inArray(e.keyCode, [40, 38, 9, 13, 27]);
            if (!this.shown && e.keyCode == 40) {
                this.lookup()
            } else {
                this.move(e)
            }
        },
        keypress: function(e) {
            if (this.suppressKeyPressRepeat) return;
            this.move(e)
        },
        input: function(e) {
            var currentValue = this.$element.val() || this.$element.text();
            if (this.value !== currentValue) {
                this.value = currentValue;
                this.lookup()
            }
        },
        keyup: function(e) {
            if (this.destroyed) {
                return
            }
            switch (e.keyCode) {
                case 40:
                case 38:
                case 16:
                case 17:
                case 18:
                    break;
                case 9:
                case 13:
                    if (!this.shown) return;
                    this.select();
                    break;
                case 27:
                    if (!this.shown) return;
                    this.hide();
                    break
            }
        },
        focus: function(e) {
            if (!this.focused) {
                this.focused = true;
                if (this.options.showHintOnFocus && this.skipShowHintOnFocus !== true) {
                    if (this.options.showHintOnFocus === "all") {
                        this.lookup("")
                    } else {
                        this.lookup()
                    }
                }
            }
            if (this.skipShowHintOnFocus) {
                this.skipShowHintOnFocus = false
            }
        },
        blur: function(e) {
            if (!this.mousedover && !this.mouseddown && this.shown) {
                this.hide();
                this.focused = false
            } else if (this.mouseddown) {
                this.skipShowHintOnFocus = true;
                this.$element.focus();
                this.mouseddown = false
            }
        },
        click: function(e) {
            e.preventDefault();
            this.skipShowHintOnFocus = true;
            this.select();
            this.$element.focus();
            this.hide()
        },
        mouseenter: function(e) {
            this.mousedover = true;
            this.$menu.find(".active").removeClass("active");
            $(e.currentTarget).addClass("active")
        },
        mouseleave: function(e) {
            this.mousedover = false;
            if (!this.focused && this.shown) this.hide()
        },
        mousedown: function(e) {
            this.mouseddown = true;
            this.$menu.one("mouseup", function(e) {
                this.mouseddown = false
            }.bind(this))
        }
    };
    var old = $.fn.typeahead;
    $.fn.typeahead = function(option) {
        var arg = arguments;
        if (typeof option == "string" && option == "getActive") {
            return this.data("active")
        }
        return this.each(function() {
            var $this = $(this);
            var data = $this.data("typeahead");
            var options = typeof option == "object" && option;
            if (!data) $this.data("typeahead", data = new Typeahead(this, options));
            if (typeof option == "string" && data[option]) {
                if (arg.length > 1) {
                    data[option].apply(data, Array.prototype.slice.call(arg, 1))
                } else {
                    data[option]()
                }
            }
        })
    };
    $.fn.typeahead.defaults = {
        source: [],
        items: 8,
        menu: '<ul class="typeahead dropdown-menu" role="listbox"></ul>',
        item: '<li><a class="dropdown-item" href="#" role="option"></a></li>',
        minLength: 1,
        scrollHeight: 0,
        autoSelect: true,
        afterSelect: $.noop,
        addItem: false,
        delay: 0,
        separator: "category",
        headerHtml: '<li class="dropdown-header"></li>',
        headerDivider: '<li class="divider" role="separator"></li>'
    };
    $.fn.typeahead.Constructor = Typeahead;
    $.fn.typeahead.noConflict = function() {
        $.fn.typeahead = old;
        return this
    };
    $(document).on("focus.typeahead.data-api", '[data-provide="typeahead"]', function(e) {
        var $this = $(this);
        if ($this.data("typeahead")) return;
        $this.typeahead($this.data())
    })
});