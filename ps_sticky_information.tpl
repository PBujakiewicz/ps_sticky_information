<!-- Block ps_sticky_information -->
<div id="ps_sticky_information_div" style="
    position: -webkit-sticky;
    position: sticky;
    top: 0;
    background-color:   {if isset($ps_sticky_information_background_color) && $ps_sticky_information_background_color}
                            {$ps_sticky_information_background_color}
                        {else}
                             black
                        {/if};
    padding: 10px;
    font-size: 16px;
    text-align: center;
    color:  {if isset($ps_sticky_information_color) && $ps_sticky_information_color}
                {$ps_sticky_information_color}
            {else}
                white
            {/if};
    z-index: 8000;"
>
    <p>
        {if isset($ps_sticky_information_txt) && $ps_sticky_information_txt}
            {$ps_sticky_information_txt}
        {else}
            ps_sticky_information_txt is empty!
        {/if} 
    </p>
</div>
<!-- /Block ps_sticky_information -->
