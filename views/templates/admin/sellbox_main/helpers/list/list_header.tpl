{extends file="helpers/list/list_header.tpl"}
{block name=leadin}
    <form id="sellbox_advanced_filters" class="defaultForm form-horizontal bootstrap clearfix" action="{$currentIndex}&token={$token}" method="post">
        <div class="panel col-lg-12 clearfix">
            <fieldset>
                <div class="panel-heading">{l s='Filtrowanie kategorii' mod='sellbox'}</div>

                <div class="row">
                    <div class="col-lg-12">
                        <div id="container_category_tree">
                            {$category_tree}
                        </div>
                    </div>

                    <div class="col-lg-12 xfilter-submit">
                        <a href="{$currentIndex}&token={$token}&reset_xFilter=1" class="button btn btn-default">{l s='Resetuj' mod='sellbox'}</a>
                        <button type="submit" name="submit_xFilter" class="button btn btn-success">{l s='Filtruj' mod='sellbox'}</button>
                    </div>
                </div>
            </fieldset>
        </div>

        <script type="text/javascript">
            var sellbox = new sellbox();
            sellbox.productActions();
        </script>
    </form>
{/block}

{block name="startForm"}
    <form method="post" action="{$currentIndex}&token={$token}" class="form-horizontal clearfix" id="allegro_perform_list">
{/block}
