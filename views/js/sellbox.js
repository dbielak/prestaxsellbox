function sellbox()
{
    this.categoryForm();
    this.bootstrap = sellbox_bootstrap;
}

sellbox.prototype = {

    itemForm: function()
    {
        var self = this;

        $("input[id^='item_count_'], textarea[id^='item_count_']").each(function(){
            $(this).on('input xchange ', function()
            {
                self.countChars($(this));
            });

            self.countChars($(this));
        });
    },

    countChars: function(input)
    {
        var strlen = function(string, sizeLenght)
        {
            var customChars = {
                '<': 4,
                '>': 4,
                '&': 5,
                '"': 6
            };

            var size = string.length;
            var count = 0;

            for (var key in sizeLenght) {
                count = string.split(key).length - 1;
                size += (count*(customChars[key]-1));
            }

            return size;
        };

        var sizeChar = input.data('size');

        var size = strlen(input.prop('value'),sizeChar);

        while (size > sizeChar) {
            input.prop('value', input.prop('value').slice(0,-1));
            size = strlen(input.prop('value'));
        }

        input.parent().find('.counter').html(size);
    },

    productActions: function()
    {
        $('body').delegate('.remove-ad', 'click', function(){
            confirm("Czy na pewno chcesz usunąć ogłoszenie?");
        });
    },

    addItemCategoryForm: function()
    {
        var self = this;

        $('.item_category').on('change', 'select', function()
        {
            var fieldsBox = $(this).closest('.apperience-box');
            var categoryId = $(this).prop('value');

            if (categoryId == 0) {
                return;
            }

            var selects = fieldsBox.find('select');
            var selects_l = selects.length;
            var index = selects.index($(this));

            for (var i = index + 1; i < selects_l; i++) {
                selects.eq(i).remove();
            }

            self.changeAddFormCategory(categoryId, $(this), 0, fieldsBox);
        });

        $('#page-header-desc-sellbox_product_status-sellbox_add').on('click', function(e){
            e.preventDefault();
            var href = $(this).attr('href');

            $('.required').each(function(){
                if(!$(this).val().length > 0 || $(this).val() == 0){
                    $(this).addClass('error-submit');
                } else {
                    $(this).removeClass('error-submit');
                }
            });

            $('.cat-required').each(function(){
                if(!$(this).val().length > 0){
                    $(this).parent().find('select').addClass('error-submit');
                } else {
                    $(this).parent().find('select').removeClass('error-submit');
                }
            });

            if($('.error-submit').length == 0)
            {
                $('#sellbox_form').submit();
            } else {
                alert('Prosimy poprawić błędy w formularzu zaznaczone na czerwono');
            }
        });
    },

    categoryForm: function()
    {
        var self = this;
        var fieldsBox = (this.bootstrap && $('#fieldset_1_1').length ? $('#fieldset_1_1') : $('#fieldset_1'));

        $('#fieldset_0').on('change', 'select', function()
        {
            var categoryId = $(this).prop('value');

            if (categoryId == 0) {
                return;
            }

            var cat_name = '';
            $('#fieldset_0 select').each(function(){
                var prefix = '';
                if(cat_name != ''){
                    prefix = ' > ';
                }
                cat_name += prefix+$(this).find(':selected').text();
            });

            var selects = $('#fieldset_0').find('select');
            var selects_l = selects.length;
            var index = selects.index($(this));

            for (var i = index + 1; i < selects_l; i++) {
                selects.eq(i).remove();
            }

            self.changeCategory(categoryId, cat_name, 0, $(this), fieldsBox);
        });

        $('button[type=submit]').on('click', function(e){
            e.preventDefault();

            $('.required').each(function(){
                if(!$(this).val().length > 0){
                    $(this).addClass('error-submit');
                } else {
                    $(this).removeClass('error-submit');
                }
            });

            $('.cat-required').each(function(){
                if(!$(this).val().length > 0){
                    $(this).parent().find('select').addClass('error-submit');
                } else {
                    $(this).parent().find('select').removeClass('error-submit');
                }
            });

            if($('.error-submit').length == 0) {
                $(this).closest('form').submit();
            } else {
                alert('Prosimy poprawić błędy w formularzu zaznaczone na czerwono');
            }
        });

    },

    changeCategory: function(categoryId, cat_name, fullPath, select, fieldsBox)
    {
        var self = this;
        var individual = $('div[x-name=product_category_fields]').length > 0;

        if (typeof fullPath === 'undefined' || !fullPath) {
            fullPath = 0;
        }

        $('input[name=id_sellbox_category]').val(categoryId);
        $('input[name=sellbox_cat_name]').val(cat_name);

        ajaxPOST({
            action: 'getCategories',
            id_sellbox_category: categoryId,
            cat_name: cat_name,
            full_path: fullPath

        }, function()
        {
            if (self.bootstrap) {
                select.closest('.panel').fadeTo('fast', 0.2);
                fieldsBox.fadeTo('fast', 0.2);
                fieldsBox.find('.form-wrapper').remove();
            } else {
                fieldsBox.hide().find('legend').nextAll().remove();
            }

            $('.category-input-error').remove();

        }, function(data)
        {

            if (data['categories'].length)
            {
                var element = select.clone().insertAfter(select).hide();
                $("option[value!='0']", element).remove();

                fillSelect(data['categories'], element);
                element.show();
            }

            if (data['fields'])
            {
                var html = $(data['fields']).find('#fieldset_1').html();

                if (self.bootstrap) {
                    fieldsBox.find('form').remove();
                    fieldsBox.find('.panel-heading').after(html);
                    fieldsBox.fadeTo('fast', 1);
                } else {
                    fieldsBox.append(data.fields).show();
                }
            }

            if (data['last_node'])
            {
                if (!data['fields'])
                {
                    if (self.bootstrap) {
                        fieldsBox.find('.panel-heading').after('<p>Brak dodatkowych pól dla wybranej kategorii</p>');
                        fieldsBox.fadeTo('fast', 1);
                    } else {
                        fieldsBox.append('<p>Brak dodatkowych pól dla wybranej kategorii</p>').show();
                    }
                }
            }

            if (self.bootstrap) {
                select.closest('.panel').fadeTo('fast', 1);
            }

            function fillSelect(categories, element)
            {
                $.each(categories, function(index, category) {
                    element.append(new Option(category['cat_name'], category['cat_id']));
                });
            }
        });
    },

    changeAddFormCategory: function(categoryId, element, fullPath, fieldsBox)
    {
        var self = this;
        var productId = fieldsBox.data('product-id');

        var iteration = productId.split('-');
        if(iteration[1])
        {
            iteration = iteration[1];
        }
        ajaxPOST({
            action: 'getCategories',
            id_sellbox_category: categoryId,
            full_path: fullPath,
            iteration: iteration

        }, function()
        {
            if (self.bootstrap) {
                element.closest('.item_category').fadeTo('fast', 0.2);
                fieldsBox.find('.custom_attributes .inside').empty();
            } else {
                fieldsBox.hide().find('legend').nextAll().remove();
            }

            $(".item-sellbox-cat-"+productId).val('');

            $('.category-input-error').remove();

        }, function(data)
        {
            if (data['categories'].length)
            {
                var ele = element.clone().insertAfter(element).hide();
                $("option[value!='0']", ele).remove();
                fillSelect(data['categories'], ele);
                ele.show();
            }

            element.closest('.item_category').fadeTo('fast', 1);

            if (data['last_node'])
            {
                element.parent().find('select').removeClass('error-submit');

                $(".item-sellbox-cat-"+productId).val(categoryId);

                if (data['fields'])
                {
                    if (self.bootstrap) {
                        fieldsBox.find('.custom_attributes .inside').html($(data['fields']).html());
                        fieldsBox.fadeTo('fast', 1);
                    } else {
                        fieldsBox.append(data.fields).show();
                    }
                }

                if (!data['fields'])
                {
                    if (self.bootstrap) {
                        fieldsBox.find('.panel-heading').after('<p>Brak dodatkowych pól dla wybranej kategorii</p>');
                        fieldsBox.fadeTo('fast', 1);
                    } else {
                        fieldsBox.append('<p>Brak dodatkowych pól dla wybranej kategorii</p>').show();
                    }
                }
            }

            function fillSelect(categories, ele)
            {
                $.each(categories, function(index, category) {
                    ele.append(new Option(category['cat_name'], category['cat_id']));
                });
            }

        });
    }
};

var ajaxPOST = function(data, beforeSend, success, error)
{
    var defautData = {
        'token': sellbox_token,
        'ajax': true
    };

    $.ajax({
        url: currentIndex,
        method: 'POST',
        async: true,
        dataType: 'json',
        data: $.extend(defautData, data),
        beforeSend: beforeSend,
        success: function(json) {
            if (json && json.apiError) {
                $.each(json.messages, function (index, message) {
                    showErrorMessage(message);
                });
            }
            else if (success) {
                success(json);
            }
        },
        error: error
    });
};