{extends file="layout.tpl"}
{block name='body:id'}mondialrelay{/block}
{block name="title"}{if $pages.seo.title}{$pages.seo.title}{else}{$pages.title}{/if}{/block}
{block name="description"}{if $pages.seo.description}{$pages.seo.description}{elseif !empty($pages.resume)}{$pages.resume}{elseif !empty($pages.content)}{$pages.content|strip_tags|truncate:100:'...'}{/if}{/block}
{block name="styleSheet"}
    {$css_files = [
    "/skin/{$theme}/css/form{if $setting.mode.value !== 'dev'}.min{/if}.css"
    ]}
    <link rel="preload" href="https://unpkg.com/leaflet/dist/leaflet.css" as="style" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
{/block}
{block name='article'}
    {strip}
        {switch $mollie.status_h}
        {case 'paid' break}
        {$msg = {#status_accept_msg#}}
        {$type = 'success'}
        {capture name="icon"}check{/capture}
        {case 'failed' break}
        {$msg = {#status_decline_msg#}}
        {$type = 'warning'}
        {capture name="icon"}error_outline{/capture}
        {case 'canceled' break}
        {$msg = {#status_cancel_msg#}}
        {$type = 'warning'}
        {capture name="icon"}close{/capture}
        {case 'expired' break}
        {$msg = {#status_expired_msg#}}
        {$type = 'warning'}
        {capture name="icon"}error_outline{/capture}
        {/switch}
    {/strip}
    <div class="container">
        {*<p class="col-sm-12 alert alert-{$type} fade in">
            <i class="material-icons ico ico-{$smarty.capture.icon}"></i> {$msg}
        </p>*}
        {*<pre>{$mondialrelay|print_r}</pre>*}
        <!-- HTML Element in which the Parcelshop Picker Widget is loaded -->
        <div id="Zone_Widget"></div>

        <!-- La balise ayant pour id "TargetDisplay_Widget" a été paramétrée pour reçevoir
          l'ID du Point Relais sélectionné -->
        Point Relais Selectionné : <input type="text" id="TargetDisplay_Widget" /><br/>

        <!--Balise HTML avec id "TargetDisplayInfoPR_Widget" paramétrée pour recevoir
        l'adresse du Point Relais sélectionné -->
        InfosPR : <span id="TargetDisplayInfoPR_Widget"></span>
        <!-- Balise HTML avec id "Target_Widget", paramétrée pour reçevoir l'ID
        du Point Relais sélectionné -->
        <form id="contact-form" class="validate_form nice-form" method="post" action="{$url}/{$lang}/mondialrelay/">
            <input type="hidden" id="Target_Widget" />
            <input type="hidden" name="mrData[rel_id]" id="cb_ID" />
            <input type="hidden" name="mrData[rel_id]" id="cb_Nom" />
            <input type="hidden" name="mrData[rel_id]" id="cb_Adresse" />
            <input type="hidden" name="mrData[rel_id]" id="cb_CP" />
            <input type="hidden" name="mrData[rel_id]" id="cb_Ville" />
            <input type="hidden" name="mrData[rel_id]" id="cb_Pays" />
            <button type="submit" class="btn btn-box btn-main-invert">{#confirm_relay_point#|ucfirst}</button>
        </form>

    </div>
{/block}
{block name="scripts"}
    {$jquery = true}
    {$js_files = [
    'group' => [
    'form'
    ],
    'normal' => [
    ],
    'defer' => [
    "/skin/{$theme}/js/{if $setting.mode.value === 'dev'}src/{/if}form{if !$setting.mode.value === 'dev'}.min{/if}.js",
    "/skin/{$theme}/js/vendor/localization/messages_{$lang}.js",
    "https://unpkg.com/leaflet/dist/leaflet.js",
    "https://widget.mondialrelay.com/parcelshop-picker/jquery.plugin.mondialrelay.parcelshoppicker.min.js",
    "/skin/{$theme}/js/{if $setting.mode.value === 'dev'}src/{/if}mondialrelay{if !$setting.mode.value === 'dev'}.min{/if}.js"
    ]
    ]}
    {if {$lang} !== "en"}{$js_files['defer'][] = "/libjs/vendor/localization/messages_{$lang}.js"}{/if}
{/block}
{block name="foot"}
    <script>
        var merchant = "{$mondialrelay.merchant}";
        $(function() {
            if (typeof mondialrelay == "undefined") {
                console.log("mondialrelay is not defined");
            } else {
                mondialrelay.run(merchant);
            }
        });
    </script>
{/block}