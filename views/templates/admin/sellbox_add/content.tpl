{if isset($products) && count($products)}
    <form id="sellbox_form" class="bootstrap" action="{$currentIndex}&token={$token}&add_products=true" method="post">
        <div class="row">
            <div class="panel col-lg-12">
                <fieldset>
                    <div class="panel-heading">Wybrane Produkty</div>

                        {foreach from=$products name=products key=index item=product}
                            {assign var=product value=$product.0}

                            <table class="table apperience-box" data-product-id="product-{$smarty.foreach.products.iteration}">
                                <colgroup>
                                    <col width="5px">
                                    <col width="300px">
                                    <col width="600px">
                                    <col width="300px">
                                    <col>
                                    <col width="140px">
                                    <col width="130px">
                                </colgroup>

                            <tbody>
                                <tr class="exposed{if $smarty.foreach.products.iteration is odd} alt_row odd{/if}">
                                    <td class="center" style="border-bottom: 1px dashed #DDD;vertical-align:middle;padding-top:8px;padding-bottom:8px;">
                                        <input type="checkbox" checked="checked" name="item[{$smarty.foreach.products.iteration}][enabled]" value="1" class="noborder" />
                                        <input type="hidden" name="item[{$smarty.foreach.products.iteration}][product_url]" value="{$product.url}" />
                                    </td>
                                    <td colspan="7" style="border-bottom: 1px dashed #DDD;vertical-align:middle;">
                                        <p style="float:left; margin:0;font-size: 16px;">{$product.name.1} - <strong>{$product.price|string_format:"%.2f"} zł</strong></p>
                                    </td>
                                </tr>

                                <tr class="{if $smarty.foreach.products.iteration is odd}alt_row odd{/if}">
                                    <td></td>
                                    <td style="vertical-align: top; padding-top:10px;border-right: 1px solid #e1e1e1">
                                        <p><label>PODSTAWOWE INFORMACJE</label></p>
                                        <input type="hidden" name="item[{$smarty.foreach.products.iteration}][id_product]" value="{$product.id}" />

                                        <div style="float: left; width: 100%" class="panel">
                                            <div>
                                                <label>Tytuł</label>
                                                <input type="text" id="item_count_title_{$smarty.foreach.products.iteration}" class="required" data-size="70" name="item[{$smarty.foreach.products.iteration}][title]" value="{$product.name.1}" size="70"  />
                                                <p>Ilość znaków: <strong><span class="counter">70</span>/70</strong></p>
                                            </div>

                                            <div>
                                                <label>Opis</label>
                                                <textarea id="item_count_description_{$smarty.foreach.products.iteration}" class="required" name="item[{$smarty.foreach.products.iteration}][description]" data-size="4098" rows="10">{$product.description.1|strip_tags:false:'<br>'}</textarea>
                                                <p>Ilość znaków: <strong><span class="counter">70</span>/4098</strong></p>
                                            </div>
                                            <hr>
                                            <label for="item_price_{$smarty.foreach.products.iteration}" class="t">Cena (zł)</label>
                                            <input type="text" id="item_price_{$smarty.foreach.products.iteration}" class="required" name="item[{$smarty.foreach.products.iteration}][price]" value="{$product.price|string_format:"%.2f"}" />
                                            <p></p>
                                            <input type="checkbox" id="price_negotiation_{$smarty.foreach.products.iteration}" name="item[{$smarty.foreach.products.iteration}][negotiate]" value="1" />
                                            <label for="price_negotiation_{$smarty.foreach.products.iteration}" class="t">Do negocjacji</label>
                                            <p></p>
                                            <hr>
                                            <label for="item_manufacturer_{$smarty.foreach.products.iteration}">Producent</label>
                                            <input type="text" id="item_manufacturer_{$smarty.foreach.products.iteration}" name="item[{$smarty.foreach.products.iteration}][manufacturer]" value="{$product.manufacturer_name}" />
                                            <p></p>
                                            <label>Stan</label>
                                            <select name="item[{$smarty.foreach.products.iteration}][condition]" class="required">
                                                <option {if $product.show_condition == 0 || $product.condition == 'new'}selected="selected"{/if} value="new">Nowy</option>
                                                <option {if $product.show_condition == 1 && $product.condition == 'used'}selected="selected"{/if} value="used">Używany</option>
                                            </select>
                                        </div>
                                        <p></p>
                                    </td>
                                    <td style="vertical-align: top;padding-top:10px;border-right: 1px solid #e1e1e1">
                                        <div class="clearfix item_category">
                                            <p><label>KATEGORIA SELLBOX</label></p>
                                            <div style="float: left; width: 100%" class="panel">
                                                {if $product.categories}
                                                    {assign var=lastCat value=''}
                                                    {$parent_id = 0}
                                                    {foreach $product.categories as $cat}
                                                        <select class="sellbox-addproduct-select">
                                                            {foreach $allcategories as $allc}
                                                                {if $parent_id == $allc['cat_parent_id']}
                                                                    <option{if $cat['cat_id'] == $allc['cat_id']} selected="selected"{/if} value="{$allc.cat_id}">{$allc.cat_name}</option>
                                                                    {if $cat['cat_id'] == $allc['cat_id']} {assign var=lastCat value=$allc['cat_id']} {/if}
                                                                {/if}
                                                            {/foreach}
                                                        </select>

                                                        {$parent_id = $cat['cat_id']}
                                                    {/foreach}
                                                    <input type="hidden" class="cat-required item-sellbox-cat-product-{$smarty.foreach.products.iteration}" name="item[{$smarty.foreach.products.iteration}][sellbox_cat_id]" value="{$lastCat}">
                                                {else}
                                                    <select class="sellbox-addproduct-select">
                                                        <option value="0">-- Wybierz --</option>
                                                        {foreach $allcategories as $allc}
                                                            {if !$allc.cat_parent_id}
                                                                <option value="{$allc.cat_id}">{$allc.cat_name}</option>
                                                            {/if}
                                                        {/foreach}
                                                    </select>
                                                    <input type="hidden" class="cat-required item-sellbox-cat-product-{$smarty.foreach.products.iteration}" name="item[{$smarty.foreach.products.iteration}][sellbox_cat_id]" value="">
                                                {/if}
                                                <p style="padding: 0;margin: 0;font-size: 10px;color: #999;width: 100%;float: left;text-align: left">Aby zmapować kategorię wejdź w "mapowanie kategorii"</p>
                                            </div>
                                        </div>

                                        <div class="clearfix custom_attributes">
                                            <p><label>POLA DODATKOWE</label></p>
                                            <div class="inside">
                                                <div style="display: grid" class="panel">
                                                    <div class="form-wrapper">
                                                        {if $product.fields}
                                                            {foreach $product.fields as $field}
                                                                {if $field.field_type == "DROPDOWN"}
                                                                    <div class="form-group">
                                                                        <label class="control-label col-lg-3">
                                                                            {$field.field_name}
                                                                        </label>

                                                                        <div class="col-lg-9">
                                                                            {assign var=optionsArray value=","|explode:$field.field_options}
                                                                            <select name="item[{$smarty.foreach.products.iteration}][attr][{$field.field_id}]" class="sellbox-addproduct-select required">
                                                                                <option value="0">-- Wybierz --</option>
                                                                                {foreach $optionsArray as $option}
                                                                                    <option value="{$option}" {if $field.value === $option} selected="selected"{/if}>{$option}</option>
                                                                                {/foreach}
                                                                            </select>
                                                                        </div>
                                                                    </div>
                                                                {elseif $field.field_type == 'TEXT'}
                                                                    <div class="form-group">
                                                                        <label class="control-label col-lg-3">
                                                                            {$field.field_name}
                                                                        </label>

                                                                        <div class="col-lg-9">
                                                                            <input type="text" class="sellbox-addproduct-select required" name="item[{$smarty.foreach.products.iteration}][attr][{$field.field_id}]" value="{$field.value}">
                                                                        </div>
                                                                    </div>
                                                                {elseif $field.field_type == 'CUSTOMATTR'}
                                                                    <div class="form-group">
                                                                        <label class="control-label col-lg-3">
                                                                            {$field.field_name}
                                                                        </label>

                                                                        <div class="col-lg-9">
                                                                            {assign var=optionsArray value=","|explode:$field.field_options}

                                                                            {if $field.value}
                                                                                {assign var=valueArray value=","|explode:$field.value}
                                                                            {else}
                                                                                {assign var=valueArray value=""}
                                                                            {/if}

                                                                            {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '<')}
                                                                                {foreach $optionsArray as $option}
                                                                                    <div class="checkbox">
                                                                                        <label for="{$option}"><input type="checkbox" name="item[{$smarty.foreach.products.iteration}][customattr][{$field.field_id}]" value="{$option}">{$option}</label>
                                                                                    </div>
                                                                                {/foreach}
                                                                            {else}
                                                                                {foreach $optionsArray as $key => $option}
                                                                                    {if $option}
                                                                                        <div class="checkbox">
                                                                                            {strip}
                                                                                                <label for="{$option}">
                                                                                                    <input type="checkbox" name="item[{$smarty.foreach.products.iteration}][customattr][{$key}][{$field.field_id}]"
                                                                                                            {if is_array($valueArray)}
                                                                                                                {foreach $valueArray as $value}
                                                                                                                    {if $value === $option}
                                                                                                                        checked="checked"
                                                                                                                        {break}
                                                                                                                    {/if}
                                                                                                                {/foreach}
                                                                                                            {/if}
                                                                                                           value="{$option}" />
                                                                                                    {$option}
                                                                                                </label>
                                                                                            {/strip}
                                                                                        </div>
                                                                                    {/if}
                                                                                {/foreach}
                                                                            {/if}
                                                                        </div>
                                                                    </div>
                                                                {/if}
                                                            {/foreach}
                                                        {else}
                                                            Wybierz kategorię sellbox aby zobaczyć pola dodatkowe.
                                                        {/if}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </td>
                                    <td style="vertical-align: top;padding-top:10px;">
                                        <p><label>ZDJĘCIA</label></p>

                                        <div style="float: left; width: 100%" class="panel">
                                            <div class="row">
                                                {foreach $product.images as $key => $item}
                                                    {if $key < 9}
                                                        <div class="col-md-3 image {if $key==0}main_image{/if}">
                                                            <input type="checkbox" name="item[{$smarty.foreach.products.iteration}][image][{$key}]" value="{$item}" checked="checked" style="position: absolute; margin: 5px 0px 0px 5px;" />
                                                            <img src="{$item}" class="img-responsive" />
                                                        </div>
                                                    {/if}
                                                {/foreach}
                                            </div>
                                        </div>
                                        <p><label>OPCJE PREMIOWANIA</label></p>
                                        <p>Wkrótce...</p>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                            <input type="hidden" name="item[{$smarty.foreach.products.iteration}][product_url]" value="{$product.url}">
                        {/foreach}
                </fieldset>
            </div>
        </div>
    </form>

    <script type="text/javascript">
        var sellbox = new sellbox();
        sellbox.itemForm();
        sellbox.addItemCategoryForm();
    </script>
{else}
    <a href="">Wróć na stronę kategorii</a>
{/if}
