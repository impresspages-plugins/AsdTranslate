$(document).ready(function() {
    "use strict";
    $('.ipsAutosave').ipAsdModuleInlineManagementTranslate();
});


(function($) {
    "use strict";
    var methods = {init: function(options) {
            return this.each(function() {
                var $this = $(this);
                var data = $this.data('ipInlineManagementText');

                if (!data) {
                    $this.data('ipInlineManagementText', {translate: $this.data('translate'), type: $this.data('type'), name: $this.data('name'), language: $this.data('language')});
                    var translate = $this.data('ipInlineManagementText').translate;
                    var type = $this.data('ipInlineManagementText').type;
                    var name = $this.data('ipInlineManagementText').name;
                    var language = $this.data('ipInlineManagementText').language;

                    $this.on('change', function(e) {
                        save($(this).val(), translate, type, name, language);
                    });
                    $this.on('focus', function(e) {
                        $this.parent('td').append($('<a href="#" class="ipsSave">Save</a>'));
                    });
                    $this.on('blur', function(e) {
                        $this.parent('td').find('.ipsSave').remove();
                    });
                }
            });
        }};
    var save = function(html, translate, type, name, language) {
        var $this = $(this);
        var data = Object();
        data.aa = 'AsdTranslate.saveTranslation';
        data.securityToken = ip.securityToken;
        data.translate = translate;
        data.type = type;
        data.name = name;
        data.language = language;
        data['translation'] = html;
        $.ajax({type: 'POST', url: ip.baseUrl, data: data, context: $this, success: saveResponse, dataType: 'json'});
    };
    var saveResponse = function(answer) {
        var $this = this;
        if (answer && answer.status === 'success') {
            if (answer.stringHtml) {
                var $newElement = $(answer.stringHtml)
                $this.replaceWith($newElement);
                $newElement.ipModuleInlineManagementText();
            }
            $this.trigger('ipInlineManagement.stringConfirm');
            $('.ipModuleInlineManagementPopupText').dialog('close');
        }
    }
    $.fn.ipAsdModuleInlineManagementTranslate = function(method) {
        if (methods[method]) {
            return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
        } else if (typeof method === 'object' || !method) {
            return methods.init.apply(this, arguments);
        } else {
            $.error('Method ' + method + ' does not exist on jQuery.ipInlineManagementText');
        }
    };
})(jQuery);
