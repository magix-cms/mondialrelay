{extends file="layout.tpl"}
{block name='head:title'}Mondial Relay{/block}
{block name='body:id'}mondialrelay{/block}
{block name='article:header'}
    <h1 class="h2">Mondial Relay</h1>
{/block}
{block name='article:content'}
    {if {employee_access type="view" class_name=$cClass} eq 1}
        <div class="panels row">
            <section class="panel col-ph-12">
                {if $debug}
                    {$debug}
                {/if}
                <header class="panel-header">
                    <h2 class="panel-heading h5">{#mondialrelay_management#}</h2>
                </header>
                <div class="panel-body panel-body-form">
                    <div class="mc-message-container clearfix">
                        <div class="mc-message"></div>
                    </div>
                    <div class="row">
                        <form id="mondialrelay_config" action="{$smarty.server.SCRIPT_NAME}?controller={$smarty.get.controller}&amp;action=edit" method="post" class="validate_form edit_form col-xs-12 col-md-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-10">
                                    <div class="form-group">
                                        <label for="merchant">Enseigne :&nbsp;</label>
                                        <input type="text" name="mrConfig[merchant]" id="merchant" class="form-control" placeholder="{#ph_merchant#}" value="{$mondialrelay.merchant}" />
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-10">
                                    <div class="form-group">
                                        <label for="privatekey">privateKey :</label>
                                        <div class="input-group">
                                            <div class="input-group-addon"><span class="fa fa-key"></span></div>
                                            <input type="text" class="form-control" id="privatekey" name="mrConfig[privatekey]" placeholder="{#ph_privatekey#}" value="{$mondialrelay.privatekey}" size="50" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="submit">
                                <button class="btn btn-main-theme" type="submit" name="action" value="edit">{#save#|ucfirst}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>
        </div>
    {else}
        {include file="section/brick/viewperms.tpl"}
    {/if}
{/block}