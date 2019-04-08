{extends file="helpers/form/form.tpl"}
{block name="input"}
    <div class="row">
        <div class="col-lg-9">
            {if $input.type == 'text'}
                <input type="{$input.type}" class="{$input.class}" name="{$input.name}" value="{$input.value}" />
            {/if}
        </div>
    </div>
{/block}