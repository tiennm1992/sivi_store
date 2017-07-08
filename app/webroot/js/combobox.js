$.widget( "ui.combobox", {
    _create: function() {
        var self = this;
        var select = this.element.hide(),
            selected = select.children( ":selected" ),
            value = selected.val() ? selected.text() : "";
        var wapperElement = $("<span>").addClass('custom-combobox').insertAfter(select);
        var input = $( "<input />" )
            .appendTo(wapperElement)
            .val( value )
            .autocomplete({
                delay: 0,
                minLength: 0,
                source: function(request, response) {
                    var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
                    response( select.children("option" ).map(function() {
                        var text = $( this ).text();
                        if ( this.value && ( !request.term || matcher.test(text) ) )
                            return {
                                label: text.replace(
                                    new RegExp(
                                        "(?![^&;]+;)(?!<[^<>]*)(" +
                                        $.ui.autocomplete.escapeRegex(request.term) +
                                        ")(?![^<>]*>)(?![^&;]+;)", "gi"),
                                    "<strong>$1</strong>"),
                                value: text,
                                option: this
                            };
                    }) );
                },
                select: function( event, ui ) {
                    ui.item.option.selected = true;
                    self._trigger( "selected", event, {
                        item: ui.item.option
                    });
                    select.trigger("change");
                },
                change: function(event, ui) {
                    if ( !ui.item ) {
                        var matcher = new RegExp( "^" + $.ui.autocomplete.escapeRegex( $(this).val() ) + "$", "i" ),
                            valid = false;
                        select.children( "option" ).each(function() {
                            if ( this.value.match( matcher ) ) {
                                this.selected = valid = true;
                                return false;
                            }
                        });
                        if ( !valid ) {
                            // remove invalid value, as it didn't match anything
                            $( this ).val( "" );
                            select.val( "" );
                            return false;
                        }
                    }
                }
            })
            .addClass("custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left");

        input.data("ui-autocomplete")._renderItem = function(ul, item) {
            return $("<li>")
                .data("item.ui-autocomplete", item)
                .append( "<a>" + item.label + "</a>" )
                .appendTo(ul);
        };

        $("<a>")
            .attr("tabIndex", -1)
            .attr("title", "Show All Items")
            .tooltip()
            .appendTo( wapperElement )
            .button({
                icons: {
                    primary: "ui-icon-triangle-1-s"
                },
                text: false
            })
            .removeClass("ui-corner-all")
            .addClass("custom-combobox-toggle ui-corner-right")
            .mousedown(function () {
                wasOpen = input.autocomplete("widget").is(":visible");
            })
            .click(function () {
                input.focus();
// Close if already visible
                if (wasOpen) {
                    return;
                }
// Pass empty string as value to search for, displaying all results
                input.autocomplete("search", "");
            });
    }
});
$(function () {
    $(".combobox").combobox();
    $("#toggle").click(function () {
        $(".combobox").toggle();
    });
});