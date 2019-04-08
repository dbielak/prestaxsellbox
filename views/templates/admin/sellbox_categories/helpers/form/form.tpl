{extends file="helpers/form/form.tpl"}

{block name="field"}
	{if $input.name == 'id_sellbox_category'}
        {$parent_id = 0}
        {$selected_cat_id = ''}
        {$selected_cat_name = ''}
        {foreach $input.path as $value}
            <select name="select-{$input.name}" class="sellbox-category-select required">
                <option value="0">{l s='-- Wybierz --' mod='sellbox'}</option>
                {foreach $input.categories as $category}
                    {if $parent_id == $category.cat_parent_id}
                        <option value="{$category.cat_id}" {if $value.cat_id == $category.cat_id}selected="selected"{/if}>{$category.cat_name}</option>
                    {/if}
                {/foreach}
            </select>

            {$selected_cat_id = $value.cat_id}

            {if $parent_id == 0}
                {$selected_cat_name = $selected_cat_name|cat:$value.cat_name}
            {else}
                {$selected_cat_name = $selected_cat_name|cat:' > '|cat:$value.cat_name}
            {/if}

            {$parent_id = $value.cat_id}
        {/foreach}

        <input type="hidden" name="{$input.name}" value="{$selected_cat_id}" />
        <input type="hidden" name="sellbox_cat_name" value="{$selected_cat_name}" />
	{else}
		{$smarty.block.parent}
	{/if}
{/block}

{block name="input"}
    {if $input.type == 'DROPDOWN'}
        {assign var=optionsArray value=","|explode:$input.options}

        <select name="attr[{$input.id}]" class="sellbox-category-select required">
            <option value="">{l s='-- Wybierz --' mod='sellbox'}</option>
            {foreach $optionsArray as $option}
                <option value="{$option}"{if $input.value === $option} selected="selected"{/if}>{$option}</option>
            {/foreach}
        </select>
    {elseif $input.type == 'TEXT'}
        {$smarty.block.parent}
        <input type="text" class="sellbox-category-select required" name="attr[{$input.id}]" value="{$input.value}">
    {elseif $input.type == 'CUSTOMATTR'}
        {assign var=optionsArray value=","|explode:$input.options}

        {if $input.value}
            {assign var=valueArray value=","|explode:$input.value}
        {else}
            {assign var=valueArray value=""}
        {/if}

        {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '<')}
            {foreach $optionsArray as $option}
                <input type="checkbox" name="attr[custom]{$option}" id="{$option}" option="{$option}" />
                <label for="{$option}" class="t"><strong>{$option}</strong></label><br />
            {/foreach}
        {else}
            {foreach $optionsArray as $option}
                {if $option}
                    <div class="checkbox">
                        {strip}
                            <label for="{$option}">
                                <input type="checkbox" name="attr[custom][{$option}]"
                                       {if is_array($valueArray)}
                                           {foreach $valueArray as $value}
                                               {if $value === $option}
                                                   checked="checked"
                                                   {break}
                                               {/if}
                                           {/foreach}
                                       {/if}
                                       id="{$option}" value="{$option}" />
                                {$option}
                            </label>
                        {/strip}
                    </div>
                {/if}
            {/foreach}
        {/if}
    {else}
        {$smarty.block.parent}
    {/if}
{/block}

{block name="script"}
    var Sellbox = new sellbox();
    Sellbox.categoryForm();
{/block}
