/*
 * SearchNavigator - based on the Autocomplete jQuery plugin.
 *
 * Copyright (c) 2007 Dylan Verheul, Dan G. Switzer, Anjesh Tuladhar, Joern Zaefferer
 *
 * The code has been used under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Version: $Id: sn.min.js,v 1.16 2009-05-27 08:45:46 plancaster Exp $
 *
 * Modified on 2009/08/07 to avoid naming conflicts with REAL jquery.autocomplete.min.js:
 *     - Autocompleter        -> snAutocompleter
 *     - autocomplete        -> snAutocomplete
 *     - ac_*                -> snac_* (CSS classes)
 *
 * Modified on 2009/08/21
 *  - De-minified for readability
 *
 * Modified on 2009/08/21
 *  - Added autocompleteEnabled() method to toggle display without having
 *    to re-initialize the whole plugin as with unSnAutocomplete()
 *  - Added onShow/onHide options to handle those events with user-specific functions
 *
 */
; (function($) {
    $.fn.extend({
        snAutocomplete: function(urlOrData, options) {
            var isUrl = typeof urlOrData == "string";
            options = $.extend({}, $.snAutocompleter.defaults, {
                url: isUrl ? urlOrData: null,
                data: isUrl ? null: urlOrData,
                delay: isUrl ? $.snAutocompleter.defaults.delay: 10,
                max: options && !options.scroll ? $.snAutocompleter.defaults.max: 50
            }, options);
            options.highlight = options.highlight || function(value) {
                return value;
            };
            return this.each(function() {
                new $.snAutocompleter(this, options);
            });
        },
        result: function(handler) {
            return this.bind("result", handler);
        },
        search: function(handler) {
            return this.trigger("search", [handler]);
        },
        triggerSearch: function(query) {
            return this.trigger("triggerSearch", [query]);
        },
        flushCache: function() {
            return this.trigger("flushCache");
        },
        setOptions: function(options) {
            return this.trigger("setOptions", [options]);
        },
        unSnAutocomplete: function() {
            return this.trigger("unSnAutocomplete");
        },
        autocompleteEnabled: function(status) {
            return this.trigger("autocompleteEnabled", [status]);
        }
    });
    $.snAutocompleter = function(input, options) {
        var KEY = {
            UP: 38,
            DOWN: 40,
            DEL: 46,
            TAB: 9,
            RETURN: 13,
            ESC: 27,
            COMMA: 188,
            PAGEUP: 33,
            PAGEDOWN: 34,
            BACKSPACE: 8
        };
        var mousepos = {
            X: 0,
            Y: 0
        };
        $().mousemove(function(e) {
            mousepos.X = e.pageX;
            mousepos.Y = e.pageY;
        });
        var $input = $(input).attr("autocomplete", "off").addClass(options.inputClass);
        var timeout;
        var previousValue = "";
        var cache = $.snAutocompleter.Cache(options);
        var hasFocus = 0;
        var stopBlur = false;
        var lastKeyPressCode;
        var config = {
            mouseDownOnSelect: false
        };
        var select = $.snAutocompleter.Select(options, input, selectCurrent, config);
        var blockSubmit;
        $.browser.opera && $(input.form).bind("submit.snAutocomplete", function() {
            if (blockSubmit) {
                blockSubmit = false;
                return false;
            }
        });
        $input.bind(($.browser.opera ? "keypress": "keydown") + ".snAutocomplete", function(event) {
            lastKeyPressCode = event.keyCode;
            switch (event.keyCode) {
            case KEY.UP:
                event.preventDefault();
                if (select.visible()) {
                    select.prev();
                } else {
                    onChange(0, true);
                }
                break;
            case KEY.DOWN:
                event.preventDefault();
                if (select.visible()) {
                    select.next();
                } else {
                    onChange(0, true);
                }
                break;
            case KEY.PAGEUP:
                event.preventDefault();
                if (select.visible()) {
                    select.pageUp();
                } else {
                    onChange(0, true);
                }
                break;
            case KEY.PAGEDOWN:
                event.preventDefault();
                if (select.visible()) {
                    select.pageDown();
                } else {
                    onChange(0, true);
                }
                break;
            case KEY.TAB:
            case KEY.RETURN:
                if (select.visible() && selectCurrent()) {
                    event.preventDefault();
                    blockSubmit = true;
                    return false;
                }
                break;
            case KEY.ESC:
                select.hide();
                break;
            default:
                clearTimeout(timeout);
                timeout = setTimeout(onChange, options.delay);
                break;
            }
        }).keypress(function() {}).focus(function() {
            hasFocus++;
        }).blur(function() {
            hasFocus = 0;
            if (options.scroll) {
                var offset = select.boxOffset();
                if (!offset) {
                    hideResults();
                    return;
                }
                var leftMax = offset.left + offset.width;
                var topMax = offset.top + offset.height;
                if ((mousepos.X > offset.left && mousepos.X < leftMax) && (mousepos.Y > offset.top && mousepos.Y < topMax)) {
                    $input.focus();
                } else if (document.activeElement && document.activeElement.tagName == 'HTML') {
                    $input.focus();
                } else {
                    if (!config.mouseDownOnSelect) {
                        hideResults();
                    }
                }
            } else if (document.activeElement && document.activeElement.tagName == 'HTML') {
                $input.focus();
            } else {
                if (!config.mouseDownOnSelect) {
                    hideResults();
                }
            }
        }).bind("stopBlur", function() {
            clearTimeout(timeout);
            timeout = setTimeout(refocus, 50);
        }).click(function() {
            if (hasFocus++>1 && !select.visible()) {
                onChange(0, true);
            }
        }).bind("search", function() {
            var fn = (arguments.length > 1) ? arguments[1] : null;
            function findValueCallback(q, data) {
                var result;
                if (data && data.length) {
                    for (var i = 0; i < data.length; i++) {
                        if (data[i].result.toLowerCase() == q.toLowerCase()) {
                            result = data[i];
                            break;
                        }
                    }
                }
                if (typeof fn == "function")
                    fn(result);
                else
                    $input.trigger("result", result && [result.data, result.value]);
            }
            $.each(trimWords($input.val()), function(i, value) {
                request(value, findValueCallback, findValueCallback);
            });
        }).bind("triggerSearch", function() {
            if (select.visible()) {
                $input.blur();
                return;
            }
            var term = (arguments.length > 1) ? arguments[1] : null;
            if (term) {
                $input.focus();
                $input.addClass(options.loadingClass);
                request(term, receiveData, hideResultsNow);
            }
        }).bind("flushCache", function() {
            cache.flush();
        }).bind("setOptions", function() {
            $.extend(options, arguments[1]);
            if ("data" in arguments[1])
                cache.populate();
        }).bind("unSnAutocomplete", function() {
            select.unbind();
            $input.unbind();
            $input.unbind(".snAutocomplete");
            $(input.form).unbind(".snAutocomplete");
        }).bind("autocompleteEnabled", function() {
            options.enabled = (arguments.length > 1) ? arguments[1] : true;
        });
        function refocus() {
            $input.focus();
            select.show();
        }
        function selectCurrent() {
            var selected = select.selected();
            if (!selected)
                return false;
            var v = selected.result;
            previousValue = v;
            $input.val(v);
            hideResultsNow();
            $input.trigger("result", [selected.data, selected.value]);
            return true;
        }
        function onChange(crap, skipPrevCheck) {
            if (!options.enabled) {
                return;
            }
            if (lastKeyPressCode == KEY.DEL) {
                select.hide();
                return;
            }
            var currentValue = $input.val();
            if (!skipPrevCheck && currentValue == previousValue)
                return;
            previousValue = currentValue;
            currentValue = lastWord(currentValue);
            if (currentValue.length >= options.minChars) {
                $input.addClass(options.loadingClass);
                request(currentValue, receiveData, hideResultsNow);
            } else {
                stopLoading();
                select.hide();
            }
        };
        function trimWords(value) {
            if (!value) {
                return [""];
            }
            return [value];
        }
        function lastWord(value) {
            var words = trimWords(value);
            return words[words.length - 1];
        }
        function autoFill(q, sValue) {
            if (options.autoFill && (lastWord($input.val()).toLowerCase() == q.toLowerCase()) && lastKeyPressCode != KEY.BACKSPACE) {
                $input.val($input.val() + sValue.substring(lastWord(previousValue).length));
                $.snAutocompleter.Selection(input, previousValue.length, previousValue.length + sValue.length);
            }
        };
        function hideResults() {
            clearTimeout(timeout);
            timeout = setTimeout(hideResultsNow, 200);
        };
        function hideResultsNow() {
            select.hide();
            clearTimeout(timeout);
            stopLoading();
        };
        function receiveData(q, data) {
            if (data && data.length && hasFocus) {
                stopLoading();
                select.display(data, q);
                select.show();
            } else {
                hideResultsNow();
            }
        };
        function request(term, success, failure) {
            var data = cache.load(term);
            if (data && data.length) {
                success(term, data);
            } else if ((typeof options.url == "string") && (options.url.length > 0)) {
                var extraParams = {
                    limit: options.max
                };
                $.each(options.extraParams, function(key, param) {
                    extraParams[key] = typeof param == "function" ? param() : param;
                });
                $.each(options.searchFields, function(i, key) {
                    extraParams[key] = term;
                });
                $.ajax({
                    mode: "abort",
                    port: "snAutocomplete" + input.name,
                    dataType: options.dataType,
                    cache: options.httpCaching,
                    url: options.url,
                    data: extraParams,
                    success: function(data) {
                        var parsed = data;
                        if (options.dataType == "text") {
                            parsed = options.parse && options.parse(data) || parse(data);
                        }
                        cache.add(term, parsed);
                        success(term, parsed);
                    }
                });
            } else {
                select.emptyList();
                failure(term);
            }
        };
        function parse(data) {
            if (typeof(JSON) != "undefined")
                return JSON.parse(data);
            return eval(data);
        };
        function stopLoading() {
            $input.removeClass(options.loadingClass);
        };
    };
    $.snAutocompleter.defaults = {
        inputClass: "snac_input",
        windowClass: "snac_box",
        borderClass: "snac_border",
        headerClass: "snac_header",
        footerClass: "snac_footer",
        logoClass: "snac_logo",
        resultsClass: "snac_results",
        loadingClass: "snac_loading",
        treeviewClass: "treeview-famfamfam",
        showLogo: true,
        treeview: false,
        selectableCats: false,
        minChars: 2,
        delay: 300,
        httpCaching: false,
        cacheLength: 0,
        max: 15,
        dataType: "text",
        extraParams: {},
        searchFields: ["q"],
        displayField: "",
        selectFirst: false,
        formatHeader: null,
        formatFooter: null,
        formatItem: function(row, i, count, dispVal, term) {
            return dispVal;
        },
        width: 0,
        leftOffset: 0,
        topOffset: 0,
        highlight: function(value, term) {
            return value.replace(/\$/, "<strong>").replace(/\$/, "</strong>");
        },
        scroll: false,
        scrollHeight: 180,
        enabled: true,
        onShow: function() {},
        onHide: function() {}
    };
    $.snAutocompleter.Cache = function(options) {
        var data = {};
        var length = 0;
        function add(q, value) {
            if (length > options.cacheLength) {
                flush();
            }
            if (!data[q]) {
                length++;
            }
            data[q] = value;
        }
        function populate() {
            if (!options.data)
                return false;
            var stMatchSets = {},
            nullData = 0;
            if (!options.url)
                options.cacheLength = 1;
            stMatchSets[""] = [];
            for (var i = 0, ol = options.data.length; i < ol; i++) {
                var rawValue = options.data[i];
                rawValue = (typeof rawValue == "string") ? [rawValue] : rawValue;
                var value = options.formatItem(rawValue, i + 1, options.data.length);
                if (value === false)
                    continue;
                var firstChar = value.charAt(0).toLowerCase();
                if (!stMatchSets[firstChar])
                    stMatchSets[firstChar] = [];
                var row = {
                    value: value,
                    data: rawValue,
                    result: options.formatResult && options.formatResult(rawValue) || value
                };
                stMatchSets[firstChar].push(row);
                if (nullData++<options.max) {
                    stMatchSets[""].push(row);
                }
            };
            $.each(stMatchSets, function(i, value) {
                options.cacheLength++;
                add(i, value);
            });
        }
        setTimeout(populate, 25);
        function flush() {
            data = {};
            length = 0;
        }
        return {
            flush: flush,
            add: add,
            populate: populate,
            load: function(q) {
                if (!options.cacheLength || !length)
                    return null;
                if (data[q])
                    return data[q];
                return null;
            }
        };
    };
    $.snAutocompleter.Select = function(options, input, select, config) {
        var CLASSES = {
            ACTIVE: "snac_over",
            ITEM: "snac_item",
            CATEGORY: "snac_cat"
        };
        var listItems,
        active = -1,
        data,
        rowNr = 0,
        rowCount = 0,
        term = "",
        needsInit = true,
        element,
        header,
        footer,
        list;
        function init() {
            if (!needsInit)
                return;
            element = $("<div/>").hide().addClass(options.windowClass).css("position", "absolute").appendTo(document.body);
            if (options.width > 0)
                element.css("width", options.width);
            var topBorder = $("<div/>").addClass(options.borderClass).html("<span><!-- fix for IE --></span>").appendTo(element);
            if (options.width > 0)
                topBorder.css("width", options.width);
            if (options.formatHeader) {
                header = $("<div/>").addClass(options.headerClass).html(options.formatHeader()).appendTo(element);
            }
            var resultDiv = $("<div/>").addClass(options.resultsClass).appendTo(element);
            list = $("<ul>").appendTo(resultDiv);
            if (options.treeview)
                $(list).treeview({
                collapsed: true
            });
            if (options.formatFooter) {
                footer = $("<div/>").addClass(options.footerClass).html(options.formatFooter()).appendTo(element);
            }
            if (options.showLogo) {
                $("<div/>").appendTo(element).addClass(options.logoClass).html('<span class="exo_logo">Powered by: <img alt="exorbyte" src="img/exlogo_tiny.gif" /></span>').click(function() {
                    window.location.assign("http://www.exorbyte.com/");
                    return false;
                });
            }
            var bottomBorder = $("<div/>").addClass(options.borderClass).html("<span><!-- fix for IE --></span>").appendTo(element);
            if (options.width > 0)
                bottomBorder.css("width", options.width);
            needsInit = false;
        }
        function target(event) {
            var element = event.target;
            while (element && element.tagName == "SPAN" && element.parentNode && element.parentNode.tagName != "LI")
                element = element.parentNode;
            if (!element)
                return [];
            return element;
        }
        function moveSelect(step) {
            listItems.slice(active, active + 1).removeClass(CLASSES.ACTIVE);
            movePosition(step);
            var activeItem = listItems.slice(active, active + 1).addClass(CLASSES.ACTIVE);
            if (options.scroll) {
                var offset = 0;
                listItems.slice(0, active).each(function() {
                    offset += this.offsetHeight;
                });
                if ((offset + activeItem[0].offsetHeight - list.scrollTop()) > list[0].clientHeight) {
                    list.scrollTop(offset + activeItem[0].offsetHeight - list.innerHeight());
                } else if (offset < list.scrollTop()) {
                    list.scrollTop(offset);
                }
            }
        };
        function movePosition(step) {
            active += step;
            if (active < 0) {
                active = listItems.size() - 1;
            } else if (active >= listItems.size()) {
                active = 0;
            }
        }
        function limitNumberOfItems(available) {
            return options.max && options.max < available ? options.max: available;
        }
        function createNode(parent) {
            var dispVal = options.displayField ? this.data[options.displayField] : this.text;
            var formatted = options.formatItem(this.data, rowNr + 1, rowCount, dispVal, term);
            if (formatted === false) {
                rowNr++;
                return true;
            }
            var current = $("<li/>").attr("id", this.id || rowNr).appendTo(parent);
            var item = $("<span/>").addClass(CLASSES.ITEM).html(options.highlight(formatted, term) + "&nbsp;").appendTo(current);
            $.data(item[0], "snac_data", {
                data: this.data,
                value: dispVal,
                result: dispVal
            });
            rowNr++;
            if (this.data.SN_type.split("_")[0] == "cat") {
                current.children("span").addClass(CLASSES.CATEGORY);
            }
            if (options.treeview && this.expanded) {
                current.addClass("open");
            }
            if (this.hasChildren || this.children && this.children.length) {
                var branch = $("<ul/>").appendTo(current);
                if (this.children && this.children.length) {
                    $.each(this.children, createNode, [branch])
                    }
            }
        }
        function addListEvents() {
            listItems = list.find("span." + CLASSES.ITEM)
                if (!options.selectableCats) {
                listItems = listItems.not("span." + CLASSES.CATEGORY);
            }
            listItems.mouseover(function(event) {
                var tgt = target(event);
                while (listItems.index(tgt) < 0) {
                    tgt = tgt.parentNode;
                }
                active = listItems.removeClass(CLASSES.ACTIVE).index(tgt);
                $(tgt).addClass(CLASSES.ACTIVE);
            }).click(function(event) {
                $(target(event)).addClass(CLASSES.ACTIVE);
                select();
                input.focus();
                return false;
            }).mousedown(function() {
                config.mouseDownOnSelect = true;
            }).mouseup(function() {
                config.mouseDownOnSelect = false;
            });
        }
        function fillList() {
            list.empty();
            rowNr = 0;
            rowCount = data.length;
            child = list;
            $.each(data, createNode, [child]);
            if (options.treeview) {
                $(list).addClass(options.treeviewClass);
                $(list).treeview({
                    add: child
                });
                list.find("div." + $.fn.treeview.classes.hitarea).click(function() {
                    $(input).trigger("stopBlur");
                });
            }
            addListEvents();
            if (options.selectFirst && !options.treeview) {
                listItems.slice(0, 1).addClass(CLASSES.ACTIVE);
                active = 0;
            }
        }
        return {
            display: function(d, q) {
                init();
                data = d;
                term = q;
                fillList();
                if (options.formatFooter) {
                    footer.html(options.formatFooter());
                }
                if (options.formatHeader) {
                    header.html(options.formatHeader());
                }
            },
            next: function() {
                moveSelect(1);
            },
            prev: function() {
                moveSelect( - 1);
            },
            pageUp: function() {
                if (active != 0 && active - 8 < 0) {
                    moveSelect( - active);
                } else {
                    moveSelect( - 8);
                }
            },
            pageDown: function() {
                if (active != listItems.size() - 1 && active + 8 > listItems.size()) {
                    moveSelect(listItems.size() - 1 - active);
                } else {
                    moveSelect(8);
                }
            },
            hide: function() {
                options.onHide();
                element && element.hide();
                active = -1;
            },
            visible: function() {
                return element && element.is(":visible");
            },
            current: function() {
                return this.visible() && (listItems.filter("." + CLASSES.ACTIVE)[0] || options.selectFirst && listItems[0]);
            },
            boxOffset: function() {
                var offset = element && element.offset();
                return element && {
                    width: element.innerWidth(),
                    height: element.innerHeight(),
                    top: offset.top,
                    left: offset.left
                };
            },
            show: function() {
                options.onShow();
                var offset = $(input).offset();
                if ($.fn.bgiframe)
                    element.bgiframe();
                element.css({
                    width: typeof options.width == "string" || options.width > 0 ? options.width: $(input).width(),
                    top: offset.top + input.offsetHeight + options.topOffset,
                    left: offset.left + options.leftOffset
                }).show();
                if (options.scroll) {
                    list.scrollTop(0);
                    list.css({
                        maxHeight: options.scrollHeight,
                        overflowY: 'auto'
                    });
                    if ($.browser.msie && typeof document.body.style.maxHeight === "undefined") {
                        var listHeight = 0;
                        listItems.each(function() {
                            listHeight += this.offsetHeight;
                        });
                        var scrollbarsVisible = listHeight > options.scrollHeight;
                        list.css('height', scrollbarsVisible ? options.scrollHeight: listHeight);
                        if (!scrollbarsVisible) {
                            listItems.width(list.width() - parseInt(listItems.css("padding-left")) - parseInt(listItems.css("padding-right")));
                        }
                    }
                }
            },
            selected: function() {
                var selected = listItems && listItems.filter("." + CLASSES.ACTIVE).removeClass(CLASSES.ACTIVE);
                if (selected && selected.length)
                    var it = $.data(selected[0], "snac_data");
                return selected && selected.length && $.data(selected[0], "snac_data");
            },
            emptyList: function() {
                list && list.empty();
            },
            unbind: function() {
                element && element.remove();
            }
        };
    };
    $.snAutocompleter.Selection = function(field, start, end) {
        if (field.createTextRange) {
            var selRange = field.createTextRange();
            selRange.collapse(true);
            selRange.moveStart("character", start);
            selRange.moveEnd("character", end);
            selRange.select();
        } else if (field.setSelectionRange) {
            field.setSelectionRange(start, end);
        } else {
            if (field.selectionStart) {
                field.selectionStart = start;
                field.selectionEnd = end;
            }
        }
        field.focus();
    };
})(jQuery);