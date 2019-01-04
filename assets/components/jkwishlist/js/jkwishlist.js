(function (window, document, $, jkwConfig) {
    var jkw = jkw || {};

    jkw.setup = function () {
        // selectors & $objects
        this.actionName = 'jkw_action';
        this.action = ':submit[name=' + this.actionName + ']';
        this.form = '.jkw_form';
        this.$doc = $(document);

        this.sendData = {
            $form: null,
            action: null,
            formData: null
        };
    };
    jkw.initialize = function () {

        jkw.setup();
        jkw.$doc.on('submit', jkw.form, function (e) {
            e.preventDefault();
            var $form = $(this);
            var action = $form.find(jkw.action).val();

            if (action) {
                var formData = $form.serializeArray();
                formData.push({
                    name: jkw.actionName,
                    value: action
                });
                jkw.sendData = {
                    $form: $form,
                    action: action,
                    formData: formData
                };
                jkw.controller();
            }
        });

    };
    jkw.controller = function () {
        var self = this;
        switch (self.sendData.action) {
            case 'wishlist/add':
                jkw.add();
                break;
            case 'wishlist/remove':
                jkw.remove();
                break;
            default:
                return;
        }
    };
    jkw.add = function () {
        var url = jkwConfig.actionUrl || '/assets/components/jkwishlist/action.php';

        $.ajax({
            type: 'POST',
            url: url,
            data: jkw.sendData.formData,
            error: function (jqXHR, textStatus, errorThrown) {alert(textStatus);},
            success: function(response){
                jkw.Message.success('Добавлено в список желаний');

            }
        });

    };
    jkw.remove = function () {
        var url = jkwConfig.actionUrl || '/assets/components/jkwishlist/action.php';

        $.ajax({
            type: 'POST',
            url: url,
            data: jkw.sendData.formData,
            error: function (jqXHR, textStatus, errorThrown) {alert(textStatus);},
            success: function(response){
                var dataid = jkw.sendData.formData[0]['value'];
                $('.product-block[data-id="'+ dataid + '"]').remove();
                jkw.Message.success('Удалено из списка желаний');
            }
        });

    };

    jkw.Message = {
        initialize : function(){
            if (typeof($.fn.jGrowl) != 'function') {
                $.getScript(miniShop2Config.jsUrl + 'lib/jquery.jgrowl.min.js', function () {
                    jkw.Message.initialize();
                });
            }
            else {
                $.jGrowl.defaults.closerTemplate = '<div>[ '+ 'Закрыть все' +' ]</div>';
                jkw.Message.close = function () {
                    $.jGrowl('close');
                };
                jkw.Message.show = function (message, options) {
                    if (message != '') {
                        $.jGrowl(message, options);
                    }
                }
            }
        },
        success: function (message) {
            jkw.Message.show(message, {
                theme: 'ms2-message-success',
                sticky: false
            });
        },
        error: function (message) {
            jkw.Message.show(message, {
                theme: 'ms2-message-error',
                sticky: false
            });
        },
        info: function (message) {
            jkw.Message.show(message, {
                theme: 'ms2-message-info',
                sticky: false
            });
        }

    };

    $(document).ready(function ($) {
        jkw.initialize();
        jkw.Message.initialize();


    });
})(window, document, jQuery, jkwConfig);

