{extends file="helpers/form/form.tpl"}

<div class="inside">
    {block name="input"}
        {if $input.type == 'DROPDOWN'}
            {assign var=optionsArray value=","|explode:$input.options}

            <select name="item[{$input.iteration}][attr][{$input.id}]" class="sellbox-category-select required">
                <option value="">{l s='-- Wybierz --' mod='sellbox'}</option>
                {foreach $optionsArray as $option}
                    <option value="{$option}"{if $input.value === $option} selected="selected"{/if}>{$option}</option>
                {/foreach}
            </select>
        {elseif $input.type == 'TEXT'}
            {$smarty.block.parent}
            <input type="text" class="sellbox-category-select required" name="item[{$input.iteration}][attr][{$input.id}]" value="{$input.value}">
        {elseif $input.type == 'CUSTOMATTR'}
            {assign var=optionsArray value=","|explode:$input.options}

            {if $input.value}
                {assign var=valueArray value=","|explode:$input.value}
            {else}
                {assign var=valueArray value=""}
            {/if}

            {if version_compare($smarty.const._PS_VERSION_, '1.6.0.0', '<')}
                {foreach $optionsArray as $option}
                    <input type="checkbox" name="item[{$input.iteration}][attr][custom]{$option}" id="{$option}" option="{$option}" />
                    <label for="{$option}" class="t"><strong>{$option}</strong></label><br />
                {/foreach}
            {else}
                {foreach $optionsArray as $option}
                    {if $option}
                        <div class="checkbox">
                            {strip}
                                <label for="{$option}">
                                    <input type="checkbox" name="item[{$input.iteration}][attr][custom][{$option}]"
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
</div>